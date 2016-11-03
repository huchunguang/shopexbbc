<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class systrade_data_trade_total {

    /**
     * 生成订单总计详细
     * @params object 控制器
     * @params object cart objects
     * @params array sdf array
     */
    public function trade_total_method($params)
    {
        $total_fee = $params['total_fee'];
        $items_weight = $params['total_weight'];
        $dlyTmplId = $params['template_id'];
        $shopId = $params['shop_id'];
        $region_id = $params['region_id'];
        $usedCartPromotionWeight = $params['usedCartPromotionWeight'];
        $discount_fee = $params['discount_fee'];

        // 检查传入的配送方式是否属于当前店铺
        if($dlyTmplId && $dlyTmplId!='-1')
        {
            $tmpParams = array(
                'shop_id' => $params['shop_id'],
                'status' => 'on',
                'fields' => 'template_id',
            );
            $dtytmpls = app::get('systrade')->rpcCall('logistics.dlytmpl.get.list',$tmpParams);
            $validTemplateIds = array_column($dtytmpls['data'], 'template_id');
            if(!in_array($dlyTmplId, $validTemplateIds))
            {
                throw new \LogicException(app::get('systrade')->_('配送方式选择有误，商家没有此配送方式！'));
            }
        }

        if($dlyTmplId && $region_id)
        {
            $params = array(
                'template_id' => $dlyTmplId,
                'weight' => $items_weight,
                'areaIds' => $region_id,
            );
            // 免运费促销 优惠前的运费
            $beforePromotion_post_fee = app::get('systrade')->rpcCall('logistics.fare.count',$params);
            if($usedCartPromotionWeight>0)
            {
                // 免运费促销 优惠后的运费
                $minusWeight = ecmath::number_minus(array($items_weight, $usedCartPromotionWeight));
                if($minusWeight>0)
                {
                    $params['weight'] = $minusWeight;
                    $post_fee = app::get('systrade')->rpcCall('logistics.fare.count',$params)[$dlyTmplId];
                }
                else
                {
                    $post_fee = 0;
                }
            }
            else
            {
                // 没有免运费的运费
                $post_fee = $beforePromotion_post_fee[$dlyTmplId];
            }

            if($post_fee<0)
            {
                $post_fee = 0;
            }
        }
        // $objMath = kernel::single('ectools_math');
        $payment = ecmath::number_plus(array($total_fee, $post_fee));
        $payment = ecmath::number_minus(array($payment, $discount_fee));
        if($payment < 0)
        {
            $payment = 0.01; //不可以有0元订单，最小值为0.01；后续改造
        }

        //计算商品总额所获积分
        $totalFee = $payment-$post_fee;
        $subtotal_obtain_point = app::get('systrade')->rpcCall('user.pointcount',array('money'=>$totalFee));

        $payment_detail = array(
            'total_fee'=>$total_fee,
            'post_fee'=>$post_fee,
            'payment'=>$payment,
            'discount_fee' => $discount_fee,
            'consume_point_fee' => $subtotal_consume_point,
            'obtain_point_fee' => $subtotal_obtain_point,
        );

        return $payment_detail;
    }
}


