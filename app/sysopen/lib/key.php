<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 * @author guocheng
 */

class sysopen_key extends system_prism_init_base
{

    public function suspend($shop_id, $mark='')
    {
        $keyBindModel = app::get('sysopen')->model('keys');
        $key['shop_id'] = $shop_id;
        $key['contact_type'] = 'notallowopen';
        $key['mark'] = $mark;
        return $keyBindModel->save($key);
    }

    //重新开启商家的开放权限
    public function open($shop_id)
    {
        $keyBindModel = app::get('sysopen')->model('keys');
        $data = $keyBindModel->getRow('*', ['shop_id'=>$shop_id]);
        $key = $data['key'];
        if(!$key) return false;
        return $keyBindModel->update(['contact_type'=>'openstandard'],['key'=>$key]);
    }

    //用来生成申请状态的
    public function apply($shop_id)
    {
        $keyBindModel = app::get('sysopen')->model('keys');
        $key['shop_id'] = $shop_id;
        $key['contact_type'] = 'applyforopen';
        return $keyBindModel->save($key);
    }


    //申请key通过的时候调用这个方法，就可以添加好了
    public function create($shop_id, $type, $mark='')
    {
        $this->__checkCreate($shop_id, $type);
        $keySecret = kernel::single('sysopen_prism')->create($type);

        $key = $keySecret['key'];
        $secret = $keySecret['secret'];

        $keyBindModel = app::get('sysopen')->model('keys');

        $keySdf = array(
            'key' => $key,
            'secret' => $secret,
            'shop_id' => $shop_id,
            'contact_type' => $type,
        );
        if($mark != '')
        {
            $keySdf['mark'] = $mark;
        }

        $keyBindModel->save($keySdf);

        return true;
    }

    private function __checkCreate($shop_id, $type)
    {
        $keyBindModel = app::get('sysopen')->model('keys');
        $shopKey = $keyBindModel->getRow(['shop_id'=>$shop_id]);
        if($shopKey['key'] != null)
            throw new LogicException('The shop has a key, please check the key.');
        else
            return true;
    }


}

