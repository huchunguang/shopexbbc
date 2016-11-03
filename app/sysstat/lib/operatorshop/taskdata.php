<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2014-2015 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

/**
 * 实现商家报表返回数据
 * @auther shopex ecstore dev dev@shopex.cn
 * @version 0.1
 * @package sysstat.lib.analysis
 */
class sysstat_operatorshop_taskdata 
{
    public function exec($params)
    {
        // 得到规定时间内的新添加的会员和会员总数,商家数，商家总数，店铺数，店铺总数 保存
        $sysstatMdlUser  = app::get("sysstat")->model("desktop_stat_user");
        
        $memberInfo = kernel::single('sysstat_operatorshop_task')->getMemeberInfo($params);
        
        $statuId = $sysstatMdlUser->getRow('statu_id',array('createtime'=>$memberInfo['createtime']));
        if(!is_null($statuId))
        {
            $memberInfo['statu_id'] = $statuId['statu_id'];
        }
        $sysstatMdlUser->save($memberInfo); //保存
        //echo '<pre>';print_r($memberInfo);exit();
        // 得到规定时间内的会员下单排行榜数量 保存
        $memberOrderInfo = kernel::single('sysstat_operatorshop_task')->getMemeberOrderInfo($params);

        $sysstatMdlUserOrder  = app::get("sysstat")->model("desktop_stat_userorder");
        foreach ($memberOrderInfo as $key => $value)
        {
            $desktopStatOId = $sysstatMdlUserOrder->getRow('statu_oid',array('createtime'=>$value['createtime'],'user_id'=>$value['user_id']));
            if(!is_null($desktopStatOId))
            {
                $value['statu_oid'] = $desktopStatOId['statu_oid'];
            }
            $sysstatMdlUserOrder->save($value);
        }
        //echo '<pre>';print_r($memberOrderInfo);exit();

        // 得到规定时间内的新添加的订单数、额，以完成的订单数、额,以退款订单数，额  保存
        $tradeInfo = kernel::single('sysstat_operatorshop_task')->getTradeInfo($params);
        $sysstatMdlTradeStat  = app::get("sysstat")->model("desktop_trade_statics");

        foreach ($tradeInfo as $key => $value)
        {
            $desktopStatId = $sysstatMdlTradeStat->getRow('desktop_stat_id',array('createtime'=>$value['createtime'],'stats_trade_from'=>$key));
            
            if(!is_null($desktopStatId))
            {
                $value['desktop_stat_id'] = $desktopStatId['desktop_stat_id'];
            }
            $sysstatMdlTradeStat->save($value);
        }
        
        //echo '<pre>';print_r($params);exit();

    }

}
