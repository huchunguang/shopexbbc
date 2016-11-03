<?php
return  array(
    'columns'=>array(
        'desktop_item_stat_id'=>array(
            'type' => 'bigint',
            'unsigned' => true,
            'autoincrement' => true,
            'required' => true,
            'label' => 'id',
            'comment' => app::get('sysstat')->_('商家商品数据统计id 自赠'),
            'order' => 1,
        ),
        'shop_id' => array(
            'type' => 'table:shop@sysshop',
            'required' => true,
            'comment' => app::get('sysstat')->_('所属商家'),
        ),
        'shop_name' => array(
            'type' => 'string',
            'required' => true,
            'comment' => app::get('sysstat')->_('所属商家'),
        ),
        'item_id' => array(
            'type' => 'table:item@sysitem',
            'required' => true,
            'comment' => app::get('sysstat')->_('商品id'),
        ),
        'title' => array(
            'type' => 'string',
            'length' => 60,
            'required' => true,
            'default' => '',
            'comment' => app::get('sysstat')->_('商品标题'),
        ),
        'pic_path' => array(
            'type' => 'string',
            'comment' => app::get('sysstat')->_('商品图片绝对路径'),
        ),
        'amountnum' => array(
            'type' => 'number',
            'comment' => app::get('sysstat')->_('销售数量'),
        ),
        'amountprice' => array(
            'type' => 'money',
            'comment' => app::get('sysstat')->_('销售总价'),
        ),
        'createtime'=>array(
            'type' => 'time',
            'comment' => app::get('sysstat')->_('创建时间'),
        ),
    ),
    'primary' => 'desktop_item_stat_id',
    'index' => array(
        'ind_createtime' => ['columns' => ['createtime']],
        'ind_item_id' => ['columns' => ['item_id']],
        'ind_shop_id' => ['columns' => ['shop_id']],
    ),
    'comment' => app::get('sysstat')->_('运营商商品统计表'),
);
