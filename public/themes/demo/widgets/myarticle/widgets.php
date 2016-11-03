<?php 
header('content-type:text/html;charset=utf8');
$setting['author']='胡春光';
$setting['version']='V1.0';
$setting['name']='我的文章';
$setting['catalog']='我的文章';
$setting['description']='展示我的文章';
$setting['userinfo']='';
$setting['usual']=1;
$setting['tag']='auto';
$setting['template']=array
							(
								'default.html'=>app::get('sysarticle')->_('默认的首页'),	
							);











?>