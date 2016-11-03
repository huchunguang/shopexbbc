<?php
header("content-type:text/html;charset=utf8");
/**
 * Ecos Platform
 *
 * @author    huchunguang
 * @copyright  Copyright (c) 2005-2014 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license    http://ecos.shopex.cn/ ShopEx License
 */
$setting['author']='huchunguang@shopex.cn';
$setting['version']='v1.0';
$setting['name']='文章列表';
$setting['stime']='2015-12-19';
$setting['catalog']='辅助信息';
$setting['usual']=1;
$setting['description']='文章列表';
$setting['userinfo']='胡春光';
$setting['tag']='auto';
$setting['template']=array(
		'default.html'=>app::get('topm')->_('默认'),
);
$syscontentlibarticle=kernel::single('syscontent_article_node');
$nodeList=$syscontentlibarticle->nodeListWidget();
$setting['selectmaps']=$nodeList;
