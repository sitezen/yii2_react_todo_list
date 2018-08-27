<?php

namespace app\behaviors;
/**
 * Created by PhpStorm.
 * User: stnik
 * Date: 21.08.2018
 * Time: 12:04
 */


use yii;
use yii\base\Behavior;



class MemTable extends Behavior
{

    const KEY_PREF = 'MEMTAB';

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function events()
    {
        return [
            yii\db\ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    protected function buildKeyPref()
    {
        $schema = $this->owner->getTableSchema();
        return self::KEY_PREF . '-' . $schema->name . '-';
    }

    public function afterFind($event)
    {
        $cache_key = $this->buildKeyPref();
        foreach ($this->owner->getTableSchema()->primaryKey as $key => $val){
            $cache_key .= $val . '-' . $this->owner->$val;
        }

        $cols = [];
        foreach ($schema->columns as $key=>$col){
            $colname = $col->name;
            $cols[$colname] = $this->owner->$colname;
        }

        Yii::$app->cache->set($cache_key, $cols);

    }

    /**
     * @param $key array for example: ['id' => 59]
     * @return array
     */
    public function getRow($key){
        $cache_key = $this->buildKeyPref();
        foreach ($key as $id => $val){
            $cache_key .= $id . '-' . $val;
        }
        return Yii::$app->cache->get($cache_key);
    }

}