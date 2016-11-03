<?php
header("content-type:text/html;charset=utf8");
/**
 * Ecos Platform
 *
 * @author    huchunguang
 * @copyright  Copyright (c) 2005-2014 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license    http://ecos.shopex.cn/ ShopEx License
 */
return  array
(
		'columns'=>array
		(
			'acid'=>array
				(
					'type'=>'number',
					'required'=>true,
					'autoincrement'=>true,
					'width'=>50,
					'order'=>1,	
				),
			'aid'=>array
				(
					'type'=>'number',
					'requried'=>true,
					'comment'=>app::get('sysarticle')->_('文章ID'),
					'width'=>50,
					'order'=>2,	
				),
			'content'=>array
				(
					'type'=>'text',
					'required'=>true,
					'order'=>3,
					'comment'=>app::get()->_('文章内容'),	
					'editable'=>true,
				),
			'tags'=>array
				(
					'type'=>'string',
					'comment'=>app::get('sysarticle')->_('文章的标签可以作为关键词'),
					'order'=>4,
					'required'=>true,	
				),
			'depict'=>array
				(
					'type'=>'text',
					'comment'=>app::get()->_('文章描述也可以作为摘要'),
					'order'=>5,
					'requried'=>true,			
				),
			
		
					
		),
		'primary'=>'acid',
		'comment'=>app::get('sysarticle')->_('文章内容表'),
);