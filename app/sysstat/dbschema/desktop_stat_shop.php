<?php
return  array(
    'columns'=>array(
        'desktop_statshop_id'=>array(
            'type' => 'bigint',
            'unsigned' => true,
            'autoincrement' => true,
            'required' => true,
            'label' => 'id',
            'comment' => app::get('sysstat')->_('店铺销售排行id 自赠'),
            'order' => 1,
        ),
        'shopname'=>array(
            'type' => 'number',
            'default' => 0,
            'label' => app::get('sysstat')->_('店铺名称'),
            'comment' => app::get('sysstat')->_('店铺名称'),
            'order' => 2,
        ),
        'accountfee'=>array(
            'type' => 'number',
            'default' => 0,
            'label' => app::get('sysstat')->_('销售额'),
            'comment' => app::get('sysstat')->_('销售额'),
            'order' => 5,
        ),
        'accountnum'=>array(
            'type' => 'number',
            'default' => 0,
            'label' => app::get('sysstat')->_('销售量'),
            'comment' => app::get('sysstat')->_('销售量'),
            'order' => 3,
        ),
        'createtime'=>array(
            'type'=>'time',
            'comment' => app::get('sysstat')->_('创建时间'),
        ),
    ),
    'primary' => 'desktop_statshop_id',
    'index' => array(
        'ind_createtime' => ['columns' => ['createtime']],
    ),
    'comment' => app::get('sysstat')->_('店铺销售排行统计表'),
);
