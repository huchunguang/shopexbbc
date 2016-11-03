<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class systrade_task{

    public function post_update($dbver)
    {
        if($dbver['dbver'] < 0.2)
        {
            $db = app::get('systrade')->database();
            $tradeList = $db->executeQuery('SELECT tid,pay_time FROM systrade_trade')->fetchAll();;
            foreach ($tradeList as $key => $value)
            {
                $db->executeUpdate('UPDATE systrade_order SET pay_time = ? WHERE tid = ?', [$value['pay_time'], $value['tid']]);
            }
        }
    }

}

