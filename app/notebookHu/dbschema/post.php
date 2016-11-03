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
						'type'=>'integer',
						'required'=>true,
						'autoincrement'=>true,
						
				),
		),
		'comment'=>'测试的文章表',
		'primary'=>'post_id',
		'engine'=>'innodb'	
);