<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */
 
/*
 * @package base
 * @copyright Copyright (c) 2010, shopex. inc
 * @author edwin.lzh@gmail.com
 * @license 
 */
class base_kvstore_mongodb extends base_kvstore_abstract implements base_interface_kvstore_base 
{
    static private $_mongodb = null;

    function __construct($prefix) 
    {
        if(!isset(self::$_mongodb)){
            $hosts = (array)config::get('kvstore.base_kvstore_mongodb.hosts', 'mongodb://localhost:27017');
            $options = config::get('kvstore.base_kvstore_mongodb.options', array("connect" => TRUE));
            $hosts = implode(',', $hosts);

            $m = new MongoClient($hosts,$options);
            $db = $m->ecos; //todo 需要改成config配置
            self::$_mongodb = $db->selectCollection(base_kvstore::kvprefix());
        }
        $this->prefix = $prefix;
    }//End Function

    public function fetch($key, &$value, $timeout_version=null) 
    {
        MongoCursor::$slaveOkay = true;
        $store = self::$_mongodb->findOne(array('key'=>$this->encodeSpecialKey($this->create_key($key)) ));
        if(!is_null($store) && $timeout_version < $store['dateline']){
            if($store['ttl'] > 0 && ($store['dateline']+$store['ttl']) < time()){
                return false;
            }
            $value = $this->decodeSpecialKey($store['value']);
            return true;
        }
        return false;
    }//End Function

    public function store($key, $value, $ttl=0) 
    {
        $store['value'] = $this->encodeSpecialKey($value);
        $store['dateline'] = time();
        $store['ttl'] = $ttl;
        $store['key'] = $this->encodeSpecialKey($this->create_key($key));
        $store['prefix'] = $this->prefix;
        $res = self::$_mongodb->update(array('key'=>$store['key']), $store, array("upsert" => true));
        return $res;
    }//End Function

    public function delete($key) 
    {
        return self::$_mongodb->remove(array('key'=>$this->encodeSpecialKey($this->create_key($key)) ));
    }//End Function

    public function recovery($record) 
    {
        $key = $record['key'];
        $store['key'] = $this->encodeSpecialKey($this->create_key($key));
        $store['value'] = $this->encodeSpecialKey($record['value']);
        $store['dateline'] = $record['dateline'];
        $store['ttl'] = $record['ttl'];
        $res = self::$_mongodb->update(array('key'=>$store['key']), $store, array("upsert" => true));
        return $res;
    }//End Function



// -------mongo的键名不支持 ".","\\","\$",所以以下方法为替换操作----------------start
    private function encodeKey($key)
    {
        $newkey1 = str_ireplace("\\", "\\\\", $key);
        $newkey2 = str_ireplace("\$", "\\u0024", $newkey1);
        $newkey3 = str_ireplace(".", "\\u002e", $newkey2);
        return $newkey3;
    }

    private function encodeSpecialKey($data)
    {
        if(is_array($data))
        {
            foreach($data as $key=>$v)
            {
                $newkey = $this->encodeSpecialKey($key);
                unset($data[$key]);
                $data[$newkey] = $v;
            }
        }
        else
        {
            if(strlen($data))
            {
                $data = $this->encodeKey($data);
            }
            else
            {
                $data = $data;
            }
        }
        return $data;
    }

    private function decodeKey($key)
    {
        $newkey1 = str_ireplace("\\u002e", ".", $key);
        $newkey2 = str_ireplace("\\u0024", "\$", $newkey1);
        $newkey3 = str_ireplace("\\\\", "\\", $newkey2);
        return $newkey3;
    }

    private function decodeSpecialKey($data)
    {
        if(is_array($data))
        {
            foreach($data as $key=>$v)
            {
                $newkey = $this->decodeSpecialKey($key);
                unset($data[$key]);
                $data[$newkey] = $v;
            }
        }
        else
        {
            if(strlen($data))
            {
                $data = $this->decodeKey($data);
            }
            else
            {
                $data = $data;
            }
        }
        return $data;
    }
// -------mongo的键名不支持 ".","\\","\$",所以以上方法为替换操作----------------end

}//End Class
