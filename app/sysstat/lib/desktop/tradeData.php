<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class sysstat_desktop_tradeData
{

    public $trade_num = array(
        'new_trade'=>'新增订单数',
        'refunds_num'=>'已退款的订单数量',
        'complete_trade'=>'已完成订单数量'
    );
    public $trade_money = array(
        'new_fee'=>'新增订单额',
        'refunds_fee'=>'已退款的订单额',
        'complete_fee'=>'已完成订单额'
    );


    //获取公共数据
     /**
     * data  页面传过来的数据
     * @return array
     */
    public function getCommonData($data)
    {
        if(strtotime($data['time_start'])>strtotime($data['time_end']))
        {
            $msg = "开始时间必须小于结束时间";
            return $this->splash('error',null,$msg);
        }
        if($data['timeType'])
        {
            $timeRange = $this->_getTimeRangeByType($data['timeType']);
            $timeStart = strtotime($timeRange['time_start']);
            $timeEnd = strtotime($timeRange['time_end']);
            //echo '<pre>';print_r($timeRange);exit();
        }
        else
        {
            $timeStart = strtotime($data['time_start']);
            $timeEnd = strtotime($data['time_end']);
        }
        //获取时间段
        $categories = $this->_getCategories($timeStart,$timeEnd);
        $pagedata['timeRange'] = json_encode($categories);

        //获取交易数据
        $dataType = $data['dataType']?$data['dataType']:'num';
        $tradeFrom = $data['tradeFrom']?$data['tradeFrom']:'all';
        $tradeInfo = $this->_getTradeData($dataType,$timeStart,$timeEnd,$tradeFrom);
        $tradeData = $this->_getSeriesData($tradeInfo,$dataType);
       //echo '<pre>';print_r($tradeData);exit();
        $pagedata['tradeData'] = json_encode($tradeData);

        if($dataType=='num')
        {
            $pagedata['typeData'] = json_encode("数量");
        }
        else
        {
            $pagedata['typeData'] = json_encode("金额");
        }
        $pagedata['time_start'] = date('Y/m/d',$timeStart);
        $pagedata['time_end'] = date('Y/m/d',$timeEnd);
        return $pagedata;
    }


    /**
     * @brief  获取时间数组[2015-03-03,2015-01-05] array
     * $timeStart 查询的开始时间 2015-03-01
     * $timeEnd 查询的结束时间2015-03-03
     * @return array
     */
    private function _getCategories($timeStart,$timeEnd)
    {
        $rangeTime = $timeEnd-$timeStart;
        $rangeDay = ceil($rangeTime/86400);

        for ($i=0; $i <$rangeDay ; $i++)
        { 
            $timedata[] = date('Y-m-d',$timeStart+86400*$i);
        }
        return $timedata;
    }

    /**
     * @brief  重新组织交易数据给报表
     * $tradeInfo 已经查询出来的交易数据 array
     * $dataType 数据类型  是件数num,还是钱money,string
     * @return array
     */
    private function _getSeriesData($tradeInfo,$dataType)
    {
        if($dataType=='num')
        {
            $lineText = $this->trade_num;
        }
        if($dataType=='money')
        {
            $lineText = $this->trade_money;
        }
 
        foreach ($lineText as $key => $value)
        {
            $data[$key]['name']=$value;
            foreach ($tradeInfo as $k => $v)
            {
                $data[$key]['data'][] = (double)$tradeInfo[$k][$key]?(double)$tradeInfo[$k][$key]:0;
            }
        }

        foreach ($data as $key => $value)
        {
            $tradeData[] = $value;
        }

        return $tradeData;
    }

    /**
     * @brief  获取交易数据
     * $dataType 数据类型  是件数num,还是钱money,string
     * $timeStart 查询的开始时间 2015-03-01
     * $timeEnd 查询的结束时间2015-03-03
     * $tradeFrom 来自哪个终端(all,pc,wap) string
     * @return array
     */
    private function _getTradeData($dataType,$timeStart,$timeEnd,$tradeFrom)
    {
        $mdlDesktopTradeStat = app::get('sysstat')->model('desktop_trade_statics');

        $filter = array(
            'createtime|bthan'=>$timeStart,
            'createtime|lthan'=>$timeEnd+1,
            'stats_trade_from'=>$tradeFrom
        );

        if($dataType=='num')
        {
            $fileds = 'new_trade,refunds_num,complete_trade,createtime';
            $tradeData = $mdlDesktopTradeStat->getList($fileds,$filter,0,-1,'createtime asc');
        }
        else
        {
            $fileds = 'new_fee,refunds_fee,complete_fee,createtime';
            $tradeData = $mdlDesktopTradeStat->getList($fileds,$filter,0,-1,'createtime asc');
        }

        //echo '<pre>';print_r($tradeData);exit();
        //补充数据——交易数据报表 没有天数的数据
        $tradeAddData = $this->dataAdd($tradeData,$dataType,$timeStart,$timeEnd);
        $tradeData = $tradeAddData;
        
        return $tradeData;
    }


    /**
     * @brief  补充数据——交易数据报表
     * $tradeData 已经查询出来的交易数据 array
     * $dataType 数据类型  是件数num,还是钱money,string
     * $timeStart 查询的开始时间 2015-03-01
     * $timeEnd 查询的结束时间2015-03-03
     * @return
     */
    public function dataAdd($tradeData,$dataType,$timeStart,$timeEnd)
    {
        //把时间作为键
        foreach ($tradeData as $key => $value)
        {
            foreach ($value as $k => $v)
            {
                $tradeInfo[date('Y-m-d',$value['createtime'])][$k] = $v;
            }
        }
        //获取时间段数组
        $categories = $this->_getCategories($timeStart,$timeEnd);

        //给没有数据的天数添加默认数据
        foreach ($categories as $key => $value)
        {
            if(!$tradeInfo[$value]&&$dataType=='num')
            {
                $tradeInfo[$value]['new_trade'] = 0;
                $tradeInfo[$value]['refunds_num'] = 0;
                $tradeInfo[$value]['complete_trade'] = 0;
                $tradeInfo[$value]['createtime'] = strtotime($value);
            }
            if(!$tradeInfo[$value]&&$dataType=='money')
            {
                $tradeInfo[$value]['new_fee'] = 0;
                $tradeInfo[$value]['refunds_fee'] = 0;
                $tradeInfo[$value]['complete_fee'] = 0;
                $tradeInfo[$value]['createtime'] = strtotime($value);
            }
        }
        //排序
        $createtime = array();
        foreach ($tradeInfo as $trade)
        {
            $createtime[] = $trade['createtime'];
        }
        array_multisort($createtime, SORT_ASC, $tradeInfo);

        return $tradeInfo;
    }


     /**
     * @brief  根据时间类型获取时间范围 
     * $timeType string yesterday,before,week,month
     * @return array 时间范围
     */
    private function _getTimeRangeByType($timeType)
    {
        switch ($timeType) {
            case 'yesterday':
                return array(
                    'time_start'=>date('Y-m-d 00:00:00', strtotime('-1 day')),
                    'time_end'=>date('Y-m-d 23:59:59', strtotime('-1 day'))
                );
                break;
            case 'beforeday':
                return array(
                    'time_start'=>date('Y-m-d 00:00:00', strtotime('-2 day')),
                    'time_end'=>date('Y-m-d 23:59:59', strtotime('-2 day'))
                );
                break;
            case 'week':
                return array(
                    'time_start'=>date('Y-m-d 00:00:00', strtotime('-7 day')),
                    'time_end'=>date('Y-m-d 23:59:59', strtotime('-1 day'))
                );
                break;
            case 'month':
                return array(
                    'time_start'=>date('Y-m-d 00:00:00', strtotime('-30 day')),
                    'time_end'=>date('Y-m-d 23:59:59', strtotime('-1 day'))
                );
                break;
        }
    }

}
