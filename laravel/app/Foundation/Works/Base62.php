<?php

namespace App\Foundation\Works;

class Base62
{

    private static $string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private static $encodeBlockSize = 7;
    private static $decodeBlockSize = 4;

    /**
     * 将mid转换成62进制字符串
     *
     * @param    string $mid
     * @return    string
     */
    public static function encode($mid)
    {
        $str = "";
        $midlen = strlen($mid);
        $segments = ceil($midlen / self::$encodeBlockSize);
        $start = $midlen;
        for ($i = 1; $i < $segments; $i += 1) {
            $start -= self::$encodeBlockSize;
            $seg = substr($mid, $start, self::$encodeBlockSize);
            $seg = self::encodeSegment($seg);
            $str = str_pad($seg, self::$decodeBlockSize, '0', STR_PAD_LEFT) . $str;
        }
        $str = self::encodeSegment(substr($mid, 0, $start)) . $str;
        return $str;
    }

    /**
     * 将62进制字符串转成10进制mid
     *
     * @param    string $str
     * @return    string
     */
    public static function decode($str, $compat = false, $for_mid = true)
    {
        $mid = "";
        $strlen = strlen($str);
        $segments = ceil($strlen / self::$decodeBlockSize);
        $start = $strlen;
        for ($i = 1; $i < $segments; $i += 1) {
            $start -= self::$decodeBlockSize;
            $seg = substr($str, $start, self::$decodeBlockSize);
            $seg = self::decodeSegment($seg);
            $mid = str_pad($seg, self::$encodeBlockSize, '0', STR_PAD_LEFT) . $mid;
        }
        $mid = self::decodeSegment(substr($str, 0, $start)) . $mid;
        //判断v3版本mid
        if ($for_mid && $mid > 4294967295) {
            if (PHP_INT_SIZE < 8) {
                $cmd = "expr $mid / 4294967296";
                $time = `$cmd`;
            } else {
                $mid += 0;
                $time = $mid >> 32; //64位中前32位是时间，精确到秒级
            }
            if ($time > 1230739200 && $time < 1577808000) return $mid;
        }
        //end
        if ($compat && !in_array(substr($mid, 0, 3), array('109', '110', '201', '211', '221', '231', '241'))) {
            $mid = self::decodeSegment(substr($str, 0, 4)) . self::decodeSegment(substr($str, 4));
        }
        if ($for_mid) {
            if (substr($mid, 0, 1) == '1' && substr($mid, 7, 1) == '0') {
                $mid = substr($mid, 0, 7) . substr($mid, 8);
            }
        }
        return $mid;
    }

    /**
     * 将10进制转换成62进制
     *
     * @param    string $str 10进制字符串
     * @return    string
     */
    public static function encodeSegment($str)
    {
        $out = '';
        while ($str > 0) {
            $idx = $str % 62;
            $out = substr(self::$string, $idx, 1) . $out;
            $str = floor($str / 62);
        }
        return $out;
    }

    /**
     * 将62进制转换成10进制
     *
     * @param    string $str 62进制字符串
     * @return    string
     */
    public static function decodeSegment($str)
    {
        $out = 0;
        $base = 1;
        for ($t = strlen($str) - 1; $t >= 0; $t -= 1) {
            $out = $out + $base * strpos(self::$string, substr($str, $t, 1));
            $base *= 62;
        }
        return $out . "";
    }
}