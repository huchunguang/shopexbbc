<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */
class sysstat_analysis_index extends ectools_analysis_abstract implements ectools_analysis_interface
{
	protected $_title = '经营概况';
	public $logs_options = array(
		'1' => array(
						'name' => '订单成交量',
						'flag' => array(),
 					),
		'2' => array(
						'name' => '订单成交额',
						'flag' => array(),
					),
	);
	public $graph_options = array(
		'iframe_height' => 300,
	);
	public $finder_options = array(
		'hidden' => true,
	);
	public function ext_detail(&$detail){

		$detail = array();
		$filter = $this->_params;
		//echo '<pre>';print_r($filter);
		$filter['time_from'] = isset($filter['time_from'])?strtotime($filter['time_from']):'';
		$filter['time_to'] = isset($filter['time_to'])?(strtotime($filter['time_to'])+86400):'';

		$saleObj = app::get('sysstat')->model('analysis_sale');
		$payMoney = $saleObj->get_pay_money($filter);
		/*$refundMoney = $saleObj->get_refund_money($filter);
		$earn = $payMoney-$refundMoney;*/
		$earn = $payMoney;

		$detail[app::get('sysstat')->_('收入')]['value']= $earn?$earn:0;
		//$detail[app::get('sysstat')->_('收入')]['memo']= app::get('sysstat')->_('“收款额”减去“退款额”');
		$detail[app::get('sysstat')->_('收入')]['memo']= app::get('sysstat')->_('“收款额”');
		$detail[app::get('sysstat')->_('收入')]['icon'] = 'coins.gif';

		$shopsaleObj = app::get('sysstat')->model('analysis_shopsale');

		$filterOrder = array(
			'key'=>'created_time',
			'time_start'=>$filter['time_from'],
			'time_end'=>$filter['time_to']
		);
		$filterShip = array(
			'key'=>'consign_time',
			'time_start' => $filter['time_from'],
			'time_end' => $filter['time_to'],
			'status' => "'WAIT_BUYER_CONFIRM_GOODS'",
		);

		$filterPay = array(
			'key'=>'pay_time',
			'time_start' => $filter['time_from'],
			'time_end' => $filter['time_to'],
			//'status|in' => array('WAIT_SELLER_SEND_GOODS','WAIT_BUYER_CONFIRM_GOODS','TRADE_FINISHED'),
		);

		$order = $shopsaleObj->get_order($filterOrder); //全部
		$orderAll = $order['saleTimes'];
		$orderPayment = $order['salePrice'];

		$orderShip = $shopsaleObj->get_order($filterShip); //已发货
		$orderShip = $orderShip['saleTimes'];

		$orderPay = $shopsaleObj->get_order($filterPay); //已支付
		$orderPay = $orderPay['saleTimes'];

		$detail[app::get('sysstat')->_('新增订单')]['value']= $orderAll?$orderAll:0;
		$detail[app::get('sysstat')->_('新增订单')]['memo']= app::get('sysstat')->_('新增加的订单数量');
		$detail[app::get('sysstat')->_('新增订单')]['icon'] = 'application_add.gif';

		$detail[app::get('sysstat')->_('新增订单金额')]['value']= $orderPayment?$orderPayment:0;
		$detail[app::get('sysstat')->_('新增订单金额')]['memo']= app::get('sysstat')->_('新增订单金额');
		$detail[app::get('sysstat')->_('新增订单金额')]['icon'] = 'application_add.gif';

		$detail[app::get('sysstat')->_('付款订单')]['value']= $orderPay?$orderPay:0;
		$detail[app::get('sysstat')->_('付款订单')]['memo']= app::get('sysstat')->_('付款的订单数量');
		$detail[app::get('sysstat')->_('付款订单')]['icon'] = 'application_key.gif';
		$detail[app::get('sysstat')->_('已发货订单')]['value']= $orderShip?$orderShip:0;
		$detail[app::get('sysstat')->_('已发货订单')]['memo']= app::get('sysstat')->_('以发货的订单数量');
		$detail[app::get('sysstat')->_('已发货订单')]['icon'] = 'application_go.gif';

		$memObj = app::get('sysstat')->model('analysis_member');

		$filterMem = array(
			'regtime' => 'true',
			'regtime_from' => date('Y-m-d',$filter['time_from']),
			'regtime_to' => date('Y-m-d',$filter['time_to']),
			'_regtime_search' => 'between',
			'_DTIME_' => array(
				'H'=>array('regtime_from'=>'00','regtime_to'=>'00'),
				'M'=>array('regtime_from'=>'00','regtime_to'=>'00')
			),
		);

		$memberNewadd = $memObj->getCount($filterMem);

		$memberNum = $memObj->getCount(array());

		$detail[app::get('sysstat')->_('新增会员')]['value']= $memberNewadd;
		$detail[app::get('sysstat')->_('新增会员')]['memo']= app::get('sysstat')->_('新增加的会员数量');
		$detail[app::get('sysstat')->_('新增会员')]['icon'] = 'folder_user.gif';
		$detail[app::get('sysstat')->_('会员总数')]['value']= $memberNum;
		$detail[app::get('sysstat')->_('会员总数')]['memo']= app::get('sysstat')->_('网店会员总数');
		$detail[app::get('sysstat')->_('会员总数')]['icon'] = 'group_add.gif';

	}
	public function rank()
	{
		$filter = $this->_params;

		$filter['time_from'] = isset($filter['time_from'])?$filter['time_from']:'';
		$filter['time_to'] = isset($filter['time_to'])?$filter['time_to']:'';

		$productObj = app::get('sysstat')->model('analysis_productsale');
		$numProducts = $productObj->getlist('*', $filter, $offset=0, 5, 'saleTimes desc');
		$priceProducts = $productObj->getlist('*', $filter, $offset=0, 5, 'salePrice desc');

		$pagedata['numProducts'] = $numProducts;
		$pagedata['priceProducts'] = $priceProducts;
		$imageDefault = kernel::single('image_data_image')->getImageSetting('item');
		$pagedata['defaultImage'] = $imageDefault['S']['default_image'];
		$html = view::make('sysstat/admin/analysis/productlist.html', $pagedata)->render();

		$this->pagedata['rank_html'] = $html;
	}

}
