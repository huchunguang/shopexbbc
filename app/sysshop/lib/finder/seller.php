<?php
class sysshop_finder_seller{

	public $column_edit = '操作';
	public $column_edit_order = 2;



	public function column_edit(&$colList, $list)
	{

        foreach($list as $k=>$row)
        {
            $url = '?app=sysshop&ctl=admin_seller&act=sysshopUpdatePwd&finder_id='.$_GET['_finder']['finder_id'].'&p[0]='.$row['seller_id'];
            $target = 'dialog::  {title:\''.app::get('sysshop')->_('商家密码修改').'\', width:500, height:400}';
            $title = app::get('sysshop')->_('商家密码修改');

            $colList[$k] = '<a href="' . $url . '" target="' . $target . '">' . $title . '</a>';
        }
	}

    public $column_shopname = "所属店铺";
    public $column_shopname_order = 31;
    public $column_shopname_width = 120;
    public function column_shopname(&$colList, $list)
    {
        $shopIds = array_column($list, 'shop_id');
        if( !$shopIds ) return $colList;
        $objMdlRelShop = app::get('sysshop')->model('shop');
        $shopData = $objMdlRelShop->getList('shop_name,shop_id',array('shop_id'=>$shopIds));
        $shopData = array_bind_key($shopData,'shop_id');

        foreach($list as $k=>$row)
        {
            if($row['shop_id'])
            {
                $colList[$k] = $shopData[$row['shop_id']]['shop_name'];
            }
        }
    }
}
