<?php
header("content-type:text/html;charset=utf8");
/**
 * Ecos Platform
 *
 * @author    huchunguang
 * @copyright  Copyright (c) 2005-2014 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license    http://ecos.shopex.cn/ ShopEx License
 */
return array
(
		'columns'=>array
		(
			'aid'=>array
				(
					'type'=>'number',
					'required'=>true,
					'comment'=>app::get('sysarticle')->_('文章ID'),
					'autoincrement'=>true,	
				),
			'title'=>array
				(
					'type'=>'string',
					'required'=>true,
					'label'=>app::get('sysarticle')->_('文章标题'),
					'in_list'=>true,
					'default_in_list'=>true,
					'searchtype'=>'has',
					'filtertype'=>'normal',
					'filterdefault'=>'true',
					'order'=>1,	
				),
			'platform'=>array
				(	
					'type'=>array
						(
							'pc'=>app::get('sysarticle')->_('电脑端'),
							'wap'=>app::get('sysarticle')->_('移动端'),
								
						),
					'required'=>true,
					'in_list'=>true,
					'default_in_list'=>true,
					'label'=>app::get('sysarticle')->_('发布终端'),
					'order'=>2,
						
				),
			'status'=>array
			(	
					'type'=>'boolean',
					'required'=>true,
					'comment'=>app::get('sysarticle')->_('文章状态,1代表已发布,0代表为草稿'),
					'default'=>0,
			),
			'created'=>array
			(
					'type'=>'time',
					'required'=>true,
					'default'=>0,
					'comment'=>app::get('sysarticle')->_('文章发表时间'),
					'label'=>app::get('sysarticle')->_('发表时间'),
					'order'=>3,
					'in_list'=>true,
					'default_in_list'=>true,
					'in_list'=>true,
					'default_in_list'=>true,
					
					
			),
			'updated'=>array
			(
					'type'=>'time',
					'required'=>true,
					'default'=>0,
					'comment'=>app::get('sysarticle')->_('文章修改时间'),
					'in_list'=>true,
					'label'=>app::get()->_('最近更新时间'),
					'default_in_list'=>true,
					
			),
			'article_logoid'=>array
			(
					'type'=>'string',
					'label'=>'默认图片',
					'width'=>75,
					'default'=>'',
					'hidden'=>true,
					'editable'=>false,
					'in_list'=>false,
					
			),	
			
		),
		'primary'=>'aid',
		'comment'=>app::get('sysarticle')->_('文章主表'),
);