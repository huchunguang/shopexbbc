<?php

header("content-type:text/html;charset=utf8");
/**
 * Ecos Platform
 *
 * @author    huchunguang
 * @copyright  Copyright (c) 2005-2014 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license    http://ecos.shopex.cn/ ShopEx License
 */
class topc_ctl_booktop extends topc_controller
{
	
	public function index()
	{
		echo 'this is a test';
		exit();
		$pagedata['items']=app::get('sysnotebook')->model('item')->getlist('*');
		$name 			  =route::currentRouteName();
        return view::make('topc/bookview.html',$pagedata);
				
	}
	public function addnew()
	{
		$data=array
			(
				'item_subject'=>$_POST['subject'],
				'item_centent'=>$_POST['content'],
				'item_email'  =>$_POST['email'],
				'item_posttime'=>$_POST['posttime'],
			
			);
		print_r($data);
// 		exit();
		$result=app::get('sysnotebook')->model('item')->insert($data);
// 		exit();
		return redirect::action('topc_ctl_booktop@index');
		
	}
	
}