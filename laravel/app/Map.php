<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Map
{
    public function district($level)
    {
        $subdistrict = [
            'province' => 1,
            'city' => 2,
            'county' => 3,
        ];

        $params = [
            'key' => config('amap.key_web'),
            'subdistrict' => $subdistrict[$level],
        ];

        $key_redis = 'district:' . $level;
        if ($cache = Redis::get($key_redis)) {
            return json_decode($cache, true);
        }

        $url = 'http://restapi.amap.com/v3/config/district';
        $result = json_decode(app('curl')->to($url)->withData($params)->get(), true) ?? [];

        $districts = [];
        if (!empty($result['status'])) {
            if (!empty($result['districts'])) {
                $districts = current($result['districts'])['districts'] ?? [];
                foreach ($districts as $district) {
                }
            }
        }

//        Redis::set($key_redis, json_encode($districts));

        return $districts;
    }
}