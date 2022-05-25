<?php

class Privilege {
    private $CI;
    private $developerGuardUrl = [
        'admin',
        'project',
        'icon'
    ];
    private $guestGuardUrl = [
        'admin',
        'project',
        'api/create_view',
        'api/create',
        'api/edit_view',
        'api/edit',
        'api/del',
        'error',
        'icon'
    ];

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function guard()
    {
        $this->CI->load->helper('url');

        $auth = true;
        $admin = $this->CI->session->userdata('admin');

        switch ($admin['privilege']) {
            case '2': $auth = $this->developerGuard(); break;
            case '3': $auth = $this->guestGuard(); break;
        }

        if (!$auth) {
            redirect(_url_('/'));
        }
    }

    private function developerGuard()
    {
        $auth = true;
        foreach ($this->developerGuardUrl as $v) {
            if (strpos(current_url(), $v) !== false) {
                $auth = false;
                break;
            }
        }

        return $auth;
    }

    private function guestGuard()
    {
        $auth = true;
        foreach ($this->guestGuardUrl as $v) {
            if (strpos(current_url(), $v) !== false) {
                $auth = false;
                break;
            }
        }

        return $auth;
    }
}
