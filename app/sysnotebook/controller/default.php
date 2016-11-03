<?php
use Doctrine\DBAL\Schema\View;
header("content-type:text/html;charset=utf8");
/**
 * Ecos Platform
 *
 * @author    huchunguang
 * @copyright  Copyright (c) 2005-2014 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license    http://ecos.shopex.cn/ ShopEx License
 */

class sysnotebook_ctl_default extends topc_controller{
	public function index()
	{
		$pagedata['items']=app::get('notebook')->model('item')->getlist('*');
	
		return view::make('notebook/default.html',$pagedata);
	}
	public function addnew()
	{
		$this->begin(array('ctl'=>'default','act'=>'index'));
		$data=array
		(
			'item_subject'=>$_POST['subject'],
			'item_content'=>$_POST['content'],
			'item_email'  =>$_POST['email'],
			'item_posttime'=>time(),	
		);
	$result=$this->app->model('item')->insert($data);
	$this->end($result);
	}
	
	
}