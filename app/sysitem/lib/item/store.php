<?php

class sysitem_item_store {

    public function updateStore($itemId = null, $skuId, $store)
    {
        //更新sku库存
        $skuStoreModel = app::get('sysitem')->model('sku_store');
        $filter = ['sku_id'=>$skuId];
        $skuStore = $skuStoreModel->getRow('*', $filter);
        $freez = $skuStore['freez'];
        $skuStore['store'] = $freez + $store;
        $skuStoreModel->save($skuStore);

        if(is_null($itemId))
        {
            $itemId = $skuStore['item_id'];
        }

        //更新item库存
        $filter = ['item_id'=>$itemId];
        $skuStores = $skuStoreModel->getList('store,freez', $filter);
        $store = 0;
        $freez = 0;
        foreach($skuStores as $skuStore)
        {
            $freez = $freez + $skuStore['freez'];
            $store = $store + $skuStore['store'];
        }
        $itemStoreModel = app::get('sysitem')->model('item_store');
        $itemStore = ['item_id'=>$itemId, 'store'=>$store, 'freez'=>$freez];
        $itemStoreModel->save($itemStore);

        return true;
    }

    public function updateStoreByBn($itemBn, $skuBn, $shopId, $store)
    {
        $ids = $this->__getIdsByBn($itemBn, $skuBn, $shopId);
        $itemId = $ids['item_id'];
        $skuId = $ids['sku_id'];
        return $this->updateStore($itemId, $skuId, $store);
    }

}

