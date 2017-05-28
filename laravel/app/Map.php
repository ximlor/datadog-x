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
                $provinces = current($result['districts'])['districts'] ?? [];
                $districts = [];
                foreach ($provinces as $province) {
                    $cities = $province['districts'];
                    if (empty($cities)) {
                        continue;
                    }

                    $district = [
                        'value' => $province['center'],
                        'label' => $province['name'],
                        'children' => [],
                    ];
                    foreach ($cities as $city) {
                        $district['children'][] = [
                            'value' => $city['center'],
                            'label' => $city['name'],
                        ];
                    }
                    $districts[] = $district;
                }
            }
        }

        Redis::set($key_redis, json_encode($districts));
        return $districts;
    }

    function place()
    {
        $params = [
            'key' => config('amap.key_web'),
            'types' => '高等院校',
            'city' => '哈尔滨',
            'citylimit' => true,
        ];

        $key_redis = urlencode('place:' . $params['city'] . ':' . $params['types']);
        if ($cache = Redis::get($key_redis)) {
            return json_decode($cache, true);
        }

        $url = 'http://restapi.amap.com/v3/place/text';
        $result = json_decode(app('curl')->to($url)->withData($params)->get(), true) ?? [];

        Redis::set($key_redis, json_encode($result));
        Redis::expire($key_redis, 60);
        return $result;
    }
}