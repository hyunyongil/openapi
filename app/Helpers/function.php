<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;

/**
 * 路由可能是http，也可能是https，这里设置，全局使用
 * @param $path
 * @return \Illuminate\Contracts\Routing\UrlGenerator|\Illuminate\Foundation\Application|string
 */
function _url_($path)
{
    return url($path);
    //return secure_url($path);
}

/**
 * 判断数组中某个key对应的值是否有效，有效则true，否则为false
 * @param $key
 * @param $array
 * @return bool
 */
function array_member($key, $array)
{
    if (array_key_exists($key, $array) && $array[$key] !== null && $array[$key] !== '') {
        return true;
    } else {
        return false;
    }
}

/**
 * 判断数组中是否有某个key，如果有则返回该key对应的值，否则返回空字符串或0
 * @param $key
 * @param $array
 * @param bool $is_num
 * @return string
 */
function array_val($key, $array, $is_num=false)
{
    if ($is_num)
        return array_key_exists($key, $array) ? $array[$key] : '0';
    else
        return array_key_exists($key, $array) ? $array[$key] : '';
}

/**
 * 获取api的配置信息
 * @param $cfg
 * @return mixed
 */
function cfg($cfg)
{
    return Config::get('api.'.$cfg);
}

/**
 * token 解码后返回 dmm_id
 * @return string
 */
function dmm_id()
{
    $token = Input::get('token');
    $payload = JWT::decode($token, new Key(cfg('api_key'), cfg('jwt_alg')));

    return $payload->sub;
}