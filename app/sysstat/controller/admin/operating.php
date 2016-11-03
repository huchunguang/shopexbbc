<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class  sysstat_ctl_admin_operating extends desktop_controller
{
    public function index()
    {
        //kernel::single('sysstat_tasks_operatorday')->exec();
        return $this->finder('sysstat_mdl_desktop_stat_userorder',array(
            'use_buildin_delete' => false,
            'title' => app::get('sysshop')->_('会员下单排行列表'),
           /* 'actions'=>array(
                array(
                    'label'=>app::get('sysshop')->_('添加自营用户'),
                    'href'=>'?app=sysshop&ctl=admin_seller&act=addSelfUser',
                    'target'=>'dialog::{title:\''.app::get('sysshop')->_('添加自营用户').'\',  width:500,height:320}',
                ),
            ),*/
        ));
    }

}
