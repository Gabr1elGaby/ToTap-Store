<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * In-memory cache per-request to prevent tens of thousands of duplicate SQL queries
     *
     * @var array
     */
    protected static $runtimeCache = [];

    public static function get($key, $default = null)
    {
        if (array_key_exists($key, static::$runtimeCache)) {
            return static::$runtimeCache[$key];
        }

        $setting = static::where('key', $key)->first();
        $val = $setting ? $setting->value : $default;
        
        static::$runtimeCache[$key] = $val;
        return $val;
    }

    public static function set($key, $value)
    {
        static::$runtimeCache[$key] = $value;
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function clearRuntimeCache()
    {
        static::$runtimeCache = [];
    }
}

