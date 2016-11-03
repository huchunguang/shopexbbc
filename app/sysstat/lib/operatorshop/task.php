<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2014-2015 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

/**
 * 实现商家报表定时任务
 * @auther gongjiapeng
 * @version 0.1
 * 
 */
class sysstat_operatorshop_task 
{

    /**
     * 得到昨日新添加会员以及会员总数,店铺，店铺数，商家，商家数
     * @param null
     * @return null
     */
    public function getMemeberInfo(array $params)
    {
        $userAccountMd = app::get('sysuser')->model('account');
        $sellerAccountMd = app::get('sysshop')->model('account');
        $shopMd = app::get('sysshop')->model('shop');
        $filter = array(
          'createtime|bthan'=>$params['time_start'],
          'createtime|lthan'=>$params['time_end']
        );
        $userAllcount = $userAccountMd->count();
        $userIncreCount = $userAccountMd->count($filter);

        $sellerAccount = $sellerAccountMd->count();
        $sellerNum = $sellerAccountMd->count($filter);

        $shopfilter = array(
          'open_time|bthan'=>$params['time_start'],
          'open_time|lthan'=>$params['time_end'],
          'status' => 'active'
        );
        $shopnum = $shopMd->count($shopfilter);
        $shopaccount = $shopMd->count();

        $rows['newuser'] = $userIncreCount;
        $rows['accountuser'] = $userAllcount;
        $rows['sellernum'] = $sellerNum;
        $rows['selleraccount'] = $sellerAccount;
        $rows['shopnum'] = $shopnum;
        $rows['shopaccount'] = $shopaccount;
        $rows['createtime'] = $params['time_insert'];
        //echo '<pre>';print_r($rows);exit();
        return $rows;
    }

    /**
     * 得到昨日会员下单排行榜
     * @param null
     * @return null
     */
    public function getMemeberOrderInfo(array $params)
    {
        $qb = app::get('systrade')->database()->createQueryBuilder();
        $qb->select('count(*) as userordernum ,sum(payment) as userfee,user_id as user_id')
           ->from('systrade_trade')
           ->where('status="TRADE_FINISHED"')
           ->andWhere('end_time>='.$qb->createPositionalParameter($params['time_start']))
           ->andWhere('end_time<'.$qb->createPositionalParameter($params['time_end']))
           ->groupBy('user_id')
           ->addOrderBy('userfee', 'desc');

        $rows = $qb->execute()->fetchAll();
        $userAccountMd = app::get('sysuser')->model('account');
        foreach ($rows as $key => $value)
        {
            $userName = $userAccountMd->getRow('login_account',array('user_id'=>$value['user_id']));
            $rows[$key]['user_name'] = $userName['login_account'];
            $rows[$key]['createtime'] = $params['time_insert'];
        }
        //echo '<pre>';print_r($userName);exit();
        return $rows;

    }

    /**
     * 得到规定时间内的新添加的订单数、额，以完成的订单数、额,以退款订单数，额
     * @param null
     * @return null
     */
    public function getTradeInfo(array $params) 
    {
        $newTradeQb = app::get('systrade')->database()->createQueryBuilder();
        //新添加的订单数、额，
        $newTradeQb->select('count(*) as new_trade ,sum(payment) as new_fee ,trade_from as stats_trade_from')
           ->from('systrade_trade')
           ->where('created_time>='.$newTradeQb->createPositionalParameter($params['time_start']))
           ->andWhere('created_time<'.$newTradeQb->createPositionalParameter($params['time_end']))
           ->groupBy('trade_from');
        $newTradeInfo = $newTradeQb->execute()->fetchAll();
        foreach ($newTradeInfo as $key => $value)
        {
          $newTrade[$value['stats_trade_from']] = $value;
        }

        //以完成的订单数、额
        $completeTradeQb = app::get('systrade')->database()->createQueryBuilder();
        $completeTradeQb->select('count(*) as complete_trade ,sum(payment) as complete_fee ,trade_from as stats_trade_from')
           ->from('systrade_trade')
           ->where('status="TRADE_FINISHED"')
           ->andWhere('end_time>='.$completeTradeQb->createPositionalParameter($params['time_start']))
           ->andWhere('end_time<'.$completeTradeQb->createPositionalParameter($params['time_end']))
           ->groupBy('trade_from');
        $completeTradeInfo = $completeTradeQb->execute()->fetchAll();
        foreach ($completeTradeInfo as $key => $value)
        {
          $completeTrade[$value['stats_trade_from']] = $value;
        }
        //以退款的订单数、额
        $refundTradeQb = app::get('systrade')->database()->createQueryBuilder();
        $refundTradeQb->select('count(R.refund_id) as refunds_num ,sum(R.cur_money) as refunds_fee ,T.trade_from as stats_trade_from')
           ->from('ectools_refunds', 'R')
           ->leftJoin('R', 'systrade_trade', 'T', 'R.tid=T.tid')
           ->where('R.finish_time>='.$refundTradeQb->createPositionalParameter($params['time_start']))
           ->andWhere('R.finish_time<'.$refundTradeQb->createPositionalParameter($params['time_end']))
           ->andWhere('R.status="succ"')
           ->groupBy('T.trade_from');
        $refundTradeInfo = $refundTradeQb->execute()->fetchAll();

        foreach ($refundTradeInfo as $key => $value)
        {
          $refundTrade[$value['stats_trade_from']] = $value;
        }
        
        //整合数据
        $type = array('pc','wap');
        foreach ($type as $key )
        {
          $data[$key]['new_trade'] = $newTrade[$key]['new_trade']?$newTrade[$key]['new_trade']:0;
          $data[$key]['new_fee'] = $newTrade[$key]['new_fee']?$newTrade[$key]['new_fee']:0;
          $data[$key]['complete_trade'] = $completeTrade[$key]['complete_trade']?$completeTrade[$key]['complete_trade']:0;
          $data[$key]['complete_fee'] = $completeTrade[$key]['complete_fee']?$completeTrade[$key]['complete_fee']:0;
          $data[$key]['refunds_num'] = $refundTrade[$key]['refunds_num']?$refundTrade[$key]['refunds_num']:0;
          $data[$key]['refunds_fee'] = $refundTrade[$key]['refunds_fee']?$refundTrade[$key]['refunds_fee']:0;
          $data[$key]['stats_trade_from'] = $key;
          $data[$key]['createtime'] = $params['time_insert'];
        }
        $data['all']['new_trade'] = $newTrade['pc']['new_trade']+$newTrade['wap']['new_trade'];
        $data['all']['new_fee'] = $newTrade['pc']['new_fee']+$newTrade['wap']['new_fee'];
        $data['all']['complete_trade'] = $completeTrade['pc']['complete_trade']+$completeTrade['wap']['complete_trade'];
        $data['all']['complete_fee'] = $completeTrade['pc']['complete_fee']+$completeTrade['wap']['complete_fee'];
        $data['all']['refunds_num'] = $refundTrade['pc']['refunds_num']+$refundTrade['wap']['refunds_num'];
        $data['all']['refunds_fee'] = $refundTrade['pc']['refunds_fee']+$refundTrade['wap']['refunds_fee'];
        $data['all']['createtime'] = $params['time_insert'];
        $data['all']['stats_trade_from'] = 'all';
        //echo '<pre>';print_r($data);exit();
        return $data;
    }



    /**
     * 得到所有的商家id和已完成订单数量
     * @param null
     * @return null
     */
    public function completeTrade(array $params) 
    {
        $qb = app::get('systrade')->database()->createQueryBuilder();
        $qb->select('count(*) as complete_trade ,shop_id as shop_id ,sum(payment) as complete_fee')
           ->from('systrade_trade')
           ->where('status="TRADE_FINISHED"')
           ->andWhere('end_time>='.$qb->createPositionalParameter($params['time_start']))
           ->andWhere('end_time<'.$qb->createPositionalParameter($params['time_end']))
           ->groupBy('shop_id');

        $rows = $qb->execute()->fetchAll();
        return $rows;
    }


    /**
     * 得到所有的商家id和已取消的订单数量
     * @param null
     * @return null
     */
    public function cancleTrade(array $params) 
    {
        $qb = app::get('systrade')->database()->createQueryBuilder();
        $qb->select('count(*) as cancle_trade ,shop_id as shop_id ,sum(payment) as cancle_fee')
           ->from('systrade_trade')
           ->where($qb->expr()->orX('status="TRADE_CLOSED"', 'status="TRADE_CLOSED_BY_SYSTEM"'))
           ->andWhere('end_time>='.$qb->createPositionalParameter($params['time_start']))
           ->andWhere('end_time<'.$qb->createPositionalParameter($params['time_end']))
           ->groupBy('shop_id');
        $rows = $qb->execute()->fetchAll();
        return $rows;
    }

    /**
     * 得到所有的商家id和热门商品
     * @param null
     * @return null
     */
    public function hotGoods(array $params)
    {

        $qb = app::get('systrade')->database()->createQueryBuilder();
        $qb->select('sum(num) as itemnum ,shop_id as shop_id,bn as bn,title as title,item_id as item_id,pic_path as pic_path,sum(payment) as amountprice')
           ->from('systrade_order')
           ->where('status<>"WAIT_BUYER_PAY"')
           ->andWhere('pay_time>='.$qb->createPositionalParameter($params['time_start']))
           ->andWhere('pay_time<'.$qb->createPositionalParameter($params['time_end']))
           ->groupBy('item_id');

        $rows = $qb->execute()->fetchAll();
        return $rows;

    }



}
