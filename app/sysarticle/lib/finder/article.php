<?php
header("content-type:text/html;charset=utf8");
/**
 * Ecos Platform
 *
 * @author    huchunguang
 * @copyright  Copyright (c) 2005-2014 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license    http://ecos.shopex.cn/ ShopEx License
 */
class sysarticle_finder_article extends desktop_controller
{
	var $column_edit='编辑';
	var $column_edit_order=COLUMN_IN_HEAD;
	function column_edit($article)
	{	
// 		echo 'this is a test';
		return "<a href='' style=color:red>编辑shiqing</a>";
	}

}