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
			'post_id'=>array
				(
					'type'=>'table:post',
					'autoincrement'=>true,
					
							
						
				),
				'title'=>array
				(
					'type'=>'string',
					'required'=>true,	
				),
				'content'=>array
				(
					'type'=>'string',
					'required'=>true,	
				),
				'status'=>array
				(
					'type'=>array
						(
							'wait'=>'等待',
							'shopping'=>'购买',
							'post'=>'已发送',	
						),
						'default'=>'wait',
						'required'=>true,
						'comment'=>app::get('notebookHu')->_('货物状态'),
				),
				
		),
		'comment'=>'测试的数据表',
		'primary'=>'post_id',
		'index'=>array
		(	
				'post_unique'=>
			array
				(
						'columns'=>array
						(
							'content'	
						),
						'prefix'=>'UNIQUE'
						
						
					)	
		),
		
		
		'engine'=>'innodb'
		
);