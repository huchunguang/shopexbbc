<?php

/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

return array(
    'columns' => array(
        'activity_id' => array(
            'type' => 'number',
            'required' => true,
            'autoincrement' => true,
            'in_list' => false,
            'label' => app::get('syspromotion')->_('活动id'),
            'comment' => app::get('syspromotion')->_('活动id'),
        ),
        'activity_name' => array(
            'type' => 'string',
            'length' => 100,
            'required' => true,
            'default' => '',
            'editable' => true,
            'in_list' => true,
            'default_in_list' => true,
            'filterdefault' => true,
            'is_title' => true,
            'order' => 5,
            'label' => app::get('syspromotion')->_('活动名称'),
            'comment' => app::get('syspromotion')->_('活动名称'),
        ),
        'activity_tag' => array(
            'type' => 'string',
            'lenght' => '15',
            'required' => true,
            'default_in_list' => true,
            'in_list' => true,
            'order' => 6,
            'label' => app::get('syspromotion')->_('活动标签'),
            'comment' => app::get('syspromotion')->_('活动标签'),
        ),
        'activity_desc' => array(
            'type' => 'text',
            'required' => false,
            'default' => '',
            'label' => app::get('syspromotion')->_('活动描述'),
            'comment' => app::get('syspromotion')->_('活动描述'),
        ),
        'apply_begin_time' => array(
            'type' => 'time',
            'default' => 0,
            'required' => true,
            'in_list' => true,
            'default_in_list' => true,
            'filterdefault' => true,
            'order' => 7,
            'label' => app::get('syspromotion')->_('申请活动开始时间'),
            'comment' => app::get('syspromotion')->_('申请活动开始时间'),
        ),
        'apply_end_time' => array(
            'type' =>'time',
            'default' => 0,
            'required' => true,
            'in_list' => true,
            'default_in_list' => true,
            'filterdefault' => true,
            'order' => 8,
            'label' => app::get('syspromotion')->_('申请活动结束时间'),
            'comment' => app::get('syspromotion')->_('申请活动结束时间'),
        ),
        'release_time' => array(
            'type' => 'time',
            'default' => 0,
            'required' => true,
            'in_list' => true,
            'default_in_list' => true,
            'filterdefault' => true,
            'order' => 9,
            'label' => app::get('syspromotion')->_('发布时间'),
            'comment' => app::get('syspromotion')->_('发布时间'),
        ),
        'start_time' => array(
            'type' => 'time',
            'default' => 0,
            'required' => true,
            'in_list' => true,
            'default_in_list' => true,
            'filterdefault' => true,
            'order' => 10,
            'label' => app::get('syspromotion')->_('活动生效开始时间'),
            'comment' => app::get('syspromotion')->_('活动生效开始时间'),
        ),
        'end_time' => array(
            'type' =>'time',
            'default' => 0,
            'required' => true,
            'in_list' => true,
            'default_in_list' => true,
            'filterdefault' => true,
            'order' => 11,
            'label' => app::get('syspromotion')->_('活动生效结束时间'),
            'comment' => app::get('syspromotion')->_('活动生效结束时间'),
        ),
        'buy_limit' => array(
            'type' =>'number',
            'default' => 0,
            'required' => true,
            'default_in_list' => false,
            'in_list' => true,
            'order' => 12,
            'label' => app::get('syspromotion')->_('用户限购数量'),
            'comment' => app::get('syspromotion')->_('用户限购数量'),
        ),
        'enroll_limit' => array(
            'type' =>'number',
            'default' => 0,
            'required' => true,
            'default_in_list' => false,
            'in_list' => true,
            'order' => 13,
            'label' => app::get('syspromotion')->_('店铺报名限制数量'),
            'comment' => app::get('syspromotion')->_('店铺报名限制数量'),
        ),
        'limit_cat' => array(
            'type' =>'serialize',
            'default' => 0,
            'required' => true,
            'comment' => app::get('syspromotion')->_('报名类目限制'),
            'width' => 100,
        ),
        'shoptype' => array(
            'type' =>'string',
            'comment' => app::get('syspromotion')->_('店铺类型限制'),
            'width' => 100,
        ),
        'discount_min' => array(
            'type' =>'number',
            'required' => true,
            'label' => app::get('syspromotion')->_('最小折扣'),
            'comment' => app::get('syspromotion')->_('最小折扣'),
            'width' => 100,
        ),
        'discount_max' => array(
            'type' =>'number',
            'required' => true,
            'label' => app::get('syspromotion')->_('最大折扣'),
            'comment' => app::get('syspromotion')->_('最大折扣'),
            'width' => 100,
        ),
        'mainpush' => array(
            'type' => 'bool',
            'default' => 0,
            'in_list' => true,
            'default_in_list' => true,
            'order' => 4,
            'label' => app::get('syspromotion')->_('是否主推活动'),
            'comment' => app::get('syspromotion')->_('是否主推活动'),
        ),
        'slide_images' => array(
            'type' => 'string',
            'length' => 500,
            'comment' => app::get('syspromotion')->_('轮播广告图'),
        ),
        'enabled' => array(
            'type' => 'bool',
            'default' => 0,
            'filterdefault' => true,
            'default_in_list' => false,
            'in_list' => false,
            'label' => app::get('syspromotion')->_('是否启用'),
            'comment' => app::get('syspromotion')->_('是否启用'),
        ),
        'created_time' => array(
            'type' => 'time',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '100',
            'order' => 15,
            'label' => app::get('syspromotion')->_('创建时间'),
            'comment' => app::get('syspromotion')->_('创建时间'),
        ),
    ),
    'primary' => 'activity_id',
    'index' => array(
        'ind_created_time' => ['columns' => ['created_time']],
    ),
    'comment' => app::get('syspromotion')->_('活动规则表'),
);
