<?php

class AdminLogin {
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function loginCheck()
    {
        $this->CI->load->helper('url');
        if (strpos(current_url(), 'login') === false) {//当前访问路由中包含login字符，则不再执行hook，要不然在登录界面无限重定向
            $this->CI->load->library('session');
            $admin = $this->CI->session->userdata('admin');

            if (!$admin) {
                redirect(_url_('/login'));
            }
        }
    }
}