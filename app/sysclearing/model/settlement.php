<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class sysclearing_mdl_settlement extends dbeav_model {


    public function _filter($filter)
    {
        if( is_array($filter) &&  $filter['shop_name'] )
        {
            $objMdlShop = app::get('sysshop')->model('shop');
            $adata = $objMdlShop->getList('shop_id',array('shop_name|has'=>$filter['shop_name']));
            if($adata)
            {
                foreach($adata as $key=>$value)
                {
                    $shop[$key] = $value['shop_id'];
                }
                $filter['shop_id'] = $shop;
            }
            else
            {
                $filter['shop_id'] = "-1";
            }
            unset($filter['shop_name']);
        }

        if($filter['timearea'])
        {
            $timeArray = explode('-', $filter['timearea']);
            $filter['settlement_time|than']  = strtotime($timeArray[0]);
            $filter['settlement_time|lthan'] = strtotime($timeArray[1]);
            unset($filter['timearea']);
        }
        if($filter['settlement_status']=='')
        {
            unset($filter['settlement_status']);
        }
        return parent::_filter($filter);
    }

    public function searchOptions(){
        $columns = array();
        foreach($this->_columns() as $k=>$v)
        {
            if(isset($v['searchtype']) && $v['searchtype'])
            {
                $columns[$k] = $v['label'];
            }
        }

        $columns = array_merge($columns, array(
            'shop_name'=>app::get('systrade')->_('所属商家'),
        ));

        return $columns;
    }

}

