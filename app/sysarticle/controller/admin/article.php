<?php
header("content-type:text/html;charset=utf-8");
/**
 * Ecos Platform
 *
 * @author    huchunguang
 * @copyright  Copyright (c) 2005-2014 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license    http://ecos.shopex.cn/ ShopEx License
 */
class sysarticle_ctl_admin_article extends desktop_controller
{
	public function index()
	{		
	
		$filter=input::get();
		return $this->finder
			('sysarticle_mdl_article',
				array
				(
					'title'=>'文章列表',
					'use_buildin_filter'=>true,
					'actions'=>array
						(	
							array(
								'label'=>'添加文章',
								'href'=>'?app=sysarticle&ctl=admin_article&act=update',
								'target'=>'dialog::{title:\''.app::get('sysarticle')->_('添加文章').'\',width:800,height:500}',
								),		
						),	
				)
			);
		
	}
}