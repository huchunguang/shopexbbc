<?php
class topshop_ctl_trade_flow extends topshop_controller{

    /**
     * 产生订单发货页面
     * @params string order id
     * @return string html
     */
    public function godelivery()
    {
        $tid = input::get('tid');
        if(!$tid)
        {
            header('Content-Type:application/json; charset=utf-8');
            echo '{error:"'.app::get('topshop')->_("订单号传递出错.").'",_:null}';exit;
        }
        $params['tid'] = $tid;
        $params['fields'] = "tid,receiver_name,receiver_mobile,receiver_state,receiver_district,receiver_address,need_invoice,ziti_addr,invoice_type,invoice_name,invoice_main,orders.tid,orders.oid,dlytmpl_id";
        $tradeInfo = app::get('topshop')->rpcCall('trade.get',$params,'seller');

        $oids = implode(',',array_column($tradeInfo['orders'],'oid'));
        $delivery = $this->createDelivery(array('tid'=>$tid,'oids'=>$oids));
        $pagedata['delivery'] = $delivery;
        $pagedata['tradeInfo'] = $tradeInfo;

        //获取用户的物流模板
        if($tradeInfo['dlytmpl_id'] == 0 && $tradeInfo['ziti_addr'])
        {
            $pagedata['ziti'] = 'true';
        }
        else
        {
            $dtytmpl = app::get('topshop')->rpcCall('logistics.dlytmpl.get',array('shop_id'=>$this->shopId,'template_id'=>$tradeInfo['dlytmpl_id']));
            $pagedata['dtyList'] = $dtytmpl;
            $dlycorpListParams['corp_id'] = $dtytmpl['corp_id'];
        }

        $dlycorp = app::get('topshop')->rpcCall('logistics.dlycorp.get.list',$dlycorpListParams);
        $pagedata['dlycorp'] = $dlycorp['data'];

        return view::make('topshop/trade/godelivery.html', $pagedata);
    }

    public function createDelivery($postdata=null)
    {
        if(!$postdata)
        {
            $postdata = input::get('trade');
        }
        $postdata['seller_id'] = $this->sellerId;
        $postdata['shop_id'] = $this->shopId;
        $postdata['op_name'] = $this->sellerName;
        $deliveryId = app::get('topshop')->rpcCall('delivery.create',$postdata);
        $pagedata['delivery_id'] = $deliveryId;
        $pagedata['tid'] = $postdata['tid'];
        return $pagedata;
    }

    /**
     * 发货订单处理
     * @params null
     * @return null
     */
    public function dodelivery()
    {
        $sdf = input::get();

        //当订单为自提订单并且没有物流配送，可以填写字体备注
        if( isset($sdf['isZiti']) && $sdf['isZiti'] == "true" )
        {
            if(!trim($sdf['logi_no']) && !trim($sdf['ziti_memo']))
            {
                return $this->splash('error',null, '订单为自提订单，运单号和备注至少选择一项必填', true);
            }
            if( mb_strlen(trim($sdf['ziti_memo']),'utf8') > 200)
            {
                return $this->splash('error',null, '自提备注过长', true);
            }
            $sdf['ziti_memo'] = trim($sdf['ziti_memo']) ? trim($sdf['ziti_memo']) : "";
        }
        else
        {
            if(empty($sdf['logi_no']))
            {
                return $this->splash('error',null, '发货单号不能为空', true);
            }
        }

        if(isset($sdf['logi_no']) && trim($sdf['logi_no']) && strlen(trim($sdf['logi_no'])) < 6)
        {
            return $this->splash('error',null, '运单号过短，请认真核对后填写(大于6)正确的编号', true);
        }

        if(strlen(trim($sdf['logi_no'])) > 20 )
        {
            return $this->splash('error',null, '运单号过长，请认真核对后填写(小于20)正确的编号', true);
        }
        $sdf['logi_no'] = trim($sdf['logi_no']) ? trim($sdf['logi_no']) : "0";
        $sdf['seller_id'] = $this->sellerId;
        $sdf['shop_id'] = $this->shopId;
        try
        {
            app::get('topshop')->rpcCall('trade.delivery',$sdf,'seller');
        }
        catch (Exception $e)
        {
            return $this->splash('error',null, $e->getMessage(), true);
        }
        return $this->splash('success',null, '发货成功', true);
    }
}

