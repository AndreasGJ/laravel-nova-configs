<?php
namespace Gwd\LaravelNovaConfigs\Helpers;

use Illuminate\Database\Eloquent\Model;

class Configs extends Model
{
    public $timestamps = false;
    protected $table = 'laravel_nova_configs';

    protected $casts = [
        'value' => 'array'
    ];

    /**
     * Get the value of the corresponding key
     *
     * @param $key
     * @return mixed
     */
    public static function get($key)
    {
        $line =  self::getElementByKey($key);
        if(is_null($line)){
            return null;
        }
        return $line->value;
    }
    
    /**
     * Get all settings
     *
     * @param $filter
     * @return mixed
     */
    public static function list($filter = [])
    {
        return self::where(function($query) use ($filter){
            foreach($filter as $k){
                $query->orWhere('key', 'not like', $k.'%');
            }
        })->pluck('value', 'key')->toArray();
    }

    /**
     * Set the new value of the key
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public static function set($key, $value)
    {
        $line = self::getElementByKey($key);
        if(is_null($line)){
            $line = new self();
            $line->key = $key;
        }
        $line->value = $value;
        $line->save();
        return true;
    }

    /**
     * Get the record by key
     *
     * @param $key
     * @return mixed
     */
    public static function getElementByKey($key)
    {
        return self::where('key', $key)->first();
    }

    /**
     * Update By ID
     *
     * @param $id
     * @param $key
     * @param $value
     * @return bool
     */
    public static function setById($id, $key, $value)
    {
        $config = self::find($id);
        if(is_null($config)){
            return false;
        }
        $config->key = $key;
        $config->value = $value;
        $config->save();
        return true;
    }
}