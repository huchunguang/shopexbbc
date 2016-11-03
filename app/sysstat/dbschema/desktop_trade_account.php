<?php
return  array(
    'columns'=>array(
        'desktop_statcount_id'=>array(
            //'type'=>'bigint unsigned',
            'type' => 'bigint',
            'unsigned' => true,
            //'pkey'=>true,
            'autoincrement' => true,
            'required' => true,
            'label' => 'id',
            'comment' => app::get('sysstat')->_('运营商统计id 自赠'),
            'order' => 1,
        ),
        'imput_fee'=>array(
            'type' => 'money',
            'default' => 0,
            'comment' => app::get('sysstat')->_('具体收入'),
        ),
        'new_trade'=>array(
            //'type'=>'varchar(50)',
            'type' => 'number',
            'default' => 0,
            'label' => app::get('sysstat')->_('新增订单数'),
            'comment' => app::get('sysstat')->_('新增订单数'),
            'order' => 2,
        ),
        'new_fee'=>array(
            'type' => 'money',
            'default' => 0,
            'comment' => app::get('sysstat')->_('新增订单额'),
        ),
        'refunds_num'=>array(
            //'type'=>'varchar(50)',
            'type' => 'number',
            'default' => 0,
            'label' => app::get('sysstat')->_('已退款的订单数量'),
            'comment' => app::get('sysstat')->_('已退款的订单数量'),
            'order' => 7,
        ),
        'refunds_fee'=>array(
            'type' => 'money',
            'default' => 0,
            'comment' => app::get('sysstat')->_('已退款的订单额'),
        ),
        'complete_trade'=>array(
            //'type'=>'varchar(50)',
            'type' => 'number',
            'default' => 0,
            'label' => app::get('sysstat')->_('已完成的订单数量'),
            'comment' => app::get('sysstat')->_('已完成的订单数量'),
            'order' => 8,
        ),
        'complete_fee'=>array(
            'type' => 'money',
            'default' => 0,
            'comment' => app::get('sysstat')->_('已完成订单额'),
        ),
        'createtime'=>array(
            'type'=>'time',
            'comment' => app::get('sysstat')->_('创建时间'),
        ),
    ),
    'primary' => 'desktop_statcount_id',
    'index' => array(
        'ind_createtime' => ['columns' => ['createtime']],
    ),
    'comment' => app::get('sysstat')->_('运营商交易统计表'),
);
