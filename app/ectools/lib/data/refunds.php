<?php
class ectools_data_refunds{
    public function create($params)
    {
        if($params['money'])
        {
            $params['cur_money'] = $params['money'];
        }
        $params['status'] = "succ";
        $params['refund_id'] =time();
        $params['created_time'] =time();
        $params['finish_time'] =time();
        $params['confirm_time'] =time();
        $objMdlRefunds = app::get('ectools')->model('refunds');
        $result = $objMdlRefunds->save($params);
        if(!$result)
        {
            throw new \LogicException("创建件退款单失败");
            return false;
        }
        return $result;
    }
}
