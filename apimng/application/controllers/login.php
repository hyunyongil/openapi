<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
        $this->load->view('login');
	}

	public function loginAction()
    {
        if (!IS_POST) methodProtect();

        $req = $this->input->post();
        $input = datas($req['data']);
        $res = $this->_loginAction($input);

        return responseJson($res);
    }

	private function _loginAction($input)
    {
        $data = array();

        if (!$input['username']) {
            $data['state'] = 1;
            $data['msg'] = '사용자 아이디를 입력하세요';
            return $data;
        }

        if (!$input['password']) {
            $data['state'] = 2;
            $data['msg'] = '비밀번호를 입력하세요';
            return $data;
        }

        if (!$input['code']) {
            $data['state'] = 3;
            $data['msg'] = '인증코드를 입력하세요';
            return $data;
        }

        $this->load->library('code');
        $code =$this->code->get();

        if (strtoupper($input['code']) != $code) {
            $data['state'] = 4;
            $data['msg'] = '인증코드오류';
            return $data;
        }

        $this->load->model('admin_model');
        $admin = $this->admin_model->getLoginUser($input['username']);

        if (!$admin) {
            $data['state'] = 5;
            $data['msg'] = '아이디 혹은 비밀번호 오류';
        } else {
            if (!$admin['is_use'] || sha1($input['password']) != $admin['password']) {
                $data['state'] = 5;
                $data['msg'] = '아이디 혹은 비밀번호 오류';
            } else {
                $this->session->set_userdata(['admin'=>$admin]);

                $data['state'] = 0;
                $data['msg'] = 'SUCCESS';
            }
        }

        return $data;
    }

    public function logout()
    {
        $this->session->unset_userdata('admin');
        $this->session->unset_userdata('project');

        $this->load->helper('url');
        redirect(_url_('login'));
    }

	public function code()
    {
        $this->load->library('code');
        $this->code->make();
    }
}