<?php
header('content-type:text/html;charset=utf8');
/**
 * Ecos Platform
 *
 * @author    huchunguang
 * @copyright  Copyright (c) 2005-2014 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license    http://ecos.shopex.cn/ ShopEx License
 */
return array
(
		
	'columns'=>array(
			
			'item_id'=>array(
							'type'			=>'number',
// 							'required'		=>true,
// 							'autoincrement'=>true,
// 					'pkey'	 =>'item_id'
			
							
									
	                   		),
			'item_subject' =>array('type'=>'string'),
			'item_centent' =>array('type'=>'text'),
			'item_posttime'=>array('type'=>'time'),
			'item_email'   =>array('type'=>'email'),
 				 	),
		
);