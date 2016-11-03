<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class sysstat_desktop_widgets_tradeData implements desktop_interface_widget
{

    var $order = 1;
    function __construct($app)
    {
        $this->app = app::get('sysstat');
    }

    function get_title(){

        return app::get('sysstat')->_("交易数据");

    }

    function get_html()
    {

        $params = array(
                'time_start'=>date('Y-m-d 00:00:00', strtotime('-7 day')),
                'time_end'=>date('Y-m-d 23:59:59', strtotime('-1 day'))
            );
        $pagedata['time_start'] = $params['time_start'];
        $pagedata['time_end'] = $params['time_end'];

        return view::make('sysstat/desktop/widgets/tradeaccount.html', $pagedata)->render();
    }
    public function get_className()
    {
        return " valigntop";
    }
    public function get_width()
    {
        return "l-1";
    }

}
