<?php

/**
 * 获取应用的http路由
 * @param string $path
 * @return string
 */
function _url_($path='')
{
    $CI = &get_instance();

    return trim($CI->config->item('base_url'), '/') . '/' . trim($path, '/');
}

/**
 * 整理request对象获取客户端的数据
 * @param $data
 * @return array
 */
function datas($data)
{
    $return = array();

    foreach ($data as $v) {
        if ($v['value'] === null) $v['value'] = '';
        $return[$v['name']] = $v['value'];
    }

    return $return;
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
 * 数组转为可输出的json
 * @param array $data
 * @return mixed
 */
function responseJson(Array $data)
{
    $CI = &get_instance();

    return $CI->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
}

/**
 * 请求方法保护，不让本该post请求的方法用get来访问
 */
function methodProtect()
{
    $CI = &get_instance();
    $CI->load->helper('url');
    redirect(_url_('/'));
    exit;
}

/**
 * 转义html标签
 * @param $str
 * @return string
 */
function e($str)
{
    return htmlspecialchars($str);
}

/**
 * 权限映射
 * @param $privilege
 * @return string
 */
function privileges($privilege)
{
    $priData = array(1=>'모든권한', 2=>'개발자', 3=>'게스트');
    return $priData[(int)$privilege];
}

/**
 * api post 参数数据转为父子关系的数组（以 id,parent_id 来标记父子关系）
 * @param $api_id
 * @param $input
 * @param $max_ids
 * @return array[]
 */
function transformData($api_id, $input, $max_ids)
{
    $datas = [
        'req'=>[],
        'res'=>[],
    ];

    foreach ($input as $k=>$v) {
        if (strpos($k, 'req_parent') !== false) {
            $keys = explode('@', $k);

            $data = array();
            $data['id'] = $keys[1] + $max_ids['max_req_id'];
            $data['parent_id'] = $v ? $v + $max_ids['max_req_id'] : $v;
            $data['api_id'] = $api_id;
            $data['req_key'] = $input['req_key@' . $keys[1]];
            $data['req_type'] = $input['req_type@' . $keys[1]];
            $data['req_mode'] = $input['req_mode@' . $keys[1]];
            $data['req_description'] = $input['req_description@' . $keys[1]];
            $data['req_sort'] = $input['req_sort@' . $keys[1]];

            $datas['req'][$keys[1]] = $data;
        }


        if (strpos($k, 'res_parent') !== false) {
            $keys = explode('@', $k);

            $data = array();
            $data['id'] = $keys[1] + $max_ids['max_res_id'];
            $data['parent_id'] = $v ? $v + $max_ids['max_res_id'] : $v;
            $data['api_id'] = $api_id;
            $data['res_key'] = $input['res_key@' . $keys[1]];
            $data['res_type'] = $input['res_type@' . $keys[1]];
            $data['res_description'] = $input['res_description@' . $keys[1]];
            $data['res_sort'] = $input['res_sort@' . $keys[1]];

            $datas['res'][$keys[1]] = $data;
        }
    }

    return $datas;
}

/**
 * 记录系统操作日志
 * @param $keyword
 * @param $method
 * @param $id
 * @param $msg
 */
function setLog($keyword, $method, $id, $msg)
{
    $CI = &get_instance();
    $admin = $CI->session->userdata('admin');

    $CI->db->insert('log', [
        'keyword'=>$keyword,
        'method'=>$method,
        'relation_id'=>$id,
        'message'=>$msg,
        'admin_id'=>$admin['id'],
        'addtime'=>time(),
    ]);
}