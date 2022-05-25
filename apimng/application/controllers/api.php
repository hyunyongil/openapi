<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {
    /**
     * api详细页面
     */
    public function show()
    {
        $req = $this->input->get();
        $api_id = $req['api_id'];

        $this->load->model('api_model');
        $apiData = $this->api_model->find($api_id);

        //相同api的不同版本
        $apiGroup = $this->api_model->getGroup($apiData['group_code']);

        //创建人和最后操作人
        $creator = $this->db->select('username')->where('id', $apiData['create_admin'])->limit(1)->get('admin')->row_array();
        $lastEditor = $this->db->select('username')->where('id', $apiData['last_edit_admin'])->limit(1)->get('admin')->row_array();

        //当前操作的项目（用于获取Host数据）
        $project = $this->session->userdata('project');

        //api的参数数据
        $reqParam = $this->api_model->getReqParam($api_id);
        $resParam = $this->api_model->getResParam($api_id);

        //api的错误列表
        $errors = $this->db->where('api_id', $api_id)->order_by('sort', 'DESC')->order_by('id', 'ASC')->get('error')->result_array();

        //当前管理员（用于判断该不该给“更新”和“删除”的按钮）
        $admin = $this->session->userdata('admin');

        $this->load->view('api/show', [
            'apiData'=>$apiData,
            'apiGroup'=>$apiGroup,
            'creator'=>$creator,
            'lastEditor'=>$lastEditor,
            'project'=>$project,
            'reqParam'=>$reqParam,
            'resParam'=>$resParam,
            'errors'=>$errors,
            'admin'=>$admin,
        ]);
    }

    /**
     * 新增数据表单视图
     */
	public function create_view()
    {
        $project = $this->session->userdata('project');
        $modules = $this->db->where('project_id', $project['id'])->get('module')->result_array();

        $this->load->view('api/create', [
            'modules'=>$modules,
        ]);
    }

    /**
     * 新增数据方法
     * @return mixed
     */
    public function create()
    {
        if (!IS_POST) methodProtect();

        $req = $this->input->post();
        $input = datas($req['data']);
        $res = $this->_create($input);

        return responseJson($res);
    }

    /**
     * 新增数据具体逻辑
     * @param $input
     * @return array
     */
    private function _create($input)
    {
        $data = array();

        if (!$input['api_name']) {
            $data['state'] = 1;
            $data['msg'] = 'API명을 입력하세요';
            return $data;
        }

        if (!$input['api_path']) {
            $data['state'] = 2;
            $data['msg'] = 'API경로를 입력하세요';
            return $data;
        }

        if (!$input['module_name']) {
            $data['state'] = 3;
            $data['msg'] = 'API모듈명 입력하세요';
            return $data;
        }

        $this->load->model('api_model');
        $api_id = $this->api_model->insert($input);

        if ($api_id) {
            $max_ids = array();
            $max_ids['max_req_id'] = $this->api_model->getReqMaxId();
            $max_ids['max_res_id'] = $this->api_model->getResMaxId();
            $params = transformData($api_id, $input, $max_ids);

            $this->api_model->addParam($api_id, $params);

            $data['state'] = 0;
            $data['msg'] = 'SUCCESS';
            $data['api_id'] = $api_id;
        } else {
            $data['state'] = 4;
            $data['msg'] = '데이터 추가 오류';
        }

        return $data;
    }

    /**
     * 根据模块获取api列表，用于新建api中复制数据
     * @return mixed
     */
    public function get_module_api()
    {
        $datas = array();

        $req = $this->input->get();
        $module_id = $req['module_id'];
        $project = $this->session->userdata('project');

        $api_list = $this->db->select('id, api_name')->where(['project_id'=>$project['id'], 'module_id'=>$module_id])->get('api')->result_array();

        $datas['state'] = 0;
        $datas['msg'] = 'SUCCESS';
        $datas['api_list'] = $api_list;

        return responseJson($datas);
    }

    /**
     * 根据id获取api的数据以及它的请求响应参数
     * @return array
     */
    public function get_copy_api()
    {
        $datas = array();

        $req = $this->input->get();
        $api_id = $req['api_id'];

        $this->load->model('api_model');
        $api = $this->api_model->find($api_id);
        $req_param = $this->api_model->getReqParam($api_id);
        $res_param = $this->api_model->getResParam($api_id);

        $datas['state'] = 0;
        $datas['msg'] = 'SUCCESS';
        $datas['api'] = $api;
        $datas['req_param'] = $req_param;
        $datas['res_param'] = $res_param;

        return responseJson($datas);
    }

    /**
     * 更新数据表单视图
     */
    public function edit_view()
    {
        $req = $this->input->get();
        $api_id = $req['api_id'];

        $this->load->model('api_model');
        $field = $this->api_model->find($api_id);
        $modules = $this->db->where('project_id', $field['project_id'])->get('module')->result_array();
        $req_param = $this->api_model->getReqParam($api_id);
        $res_param = $this->api_model->getResParam($api_id);

        $max_req_id = $this->db->insert_id('request_param');
        $max_res_id = $this->db->insert_id('response_param');

        $this->load->view('api/edit', [
            'field'=>$field,
            'modules'=>$modules,
            'req_param'=>$req_param,
            'res_param'=>$res_param,
            'max_req_id'=>$max_req_id,
            'max_res_id'=>$max_res_id,
        ]);
    }

    /**
     * 更新数据方法
     * @return mixed
     */
    public function edit()
    {
        if (!IS_POST) methodProtect();

        $req = $this->input->post();
        $id = $req['id'];
        $input = datas($req['data']);
        $res = $this->_edit($id, $input);

        return responseJson($res);
    }

    /**
     * 更新数据具体逻辑
     * @param $id
     * @param $input
     * @return array
     */
    private function _edit($id, $input)
    {
        $data = array();

        if (!$input['api_name']) {
            $data['state'] = 1;
            $data['msg'] = 'API명을 입력하세요';
            return $data;
        }

        if (!$input['api_path']) {
            $data['state'] = 2;
            $data['msg'] = 'API경로를 입력하세요';
            return $data;
        }

        if (!$input['module_name']) {
            $data['state'] = 3;
            $data['msg'] = 'API모듈명 입력하세요';
            return $data;
        }

        $this->load->model('api_model');
        $res = $this->api_model->update($id, $input);

        if ($res) {
            $max_ids = array();
            $max_ids['max_req_id'] = $this->api_model->getReqMaxId();
            $max_ids['max_res_id'] = $this->api_model->getResMaxId();
            $params = transformData($id, $input, $max_ids);

            $this->api_model->addParam($id, $params);

            $data['state'] = 0;
            $data['msg'] = 'SUCCESS';
            $data['api_id'] = $id;
        } else {
            $data['state'] = 4;
            $data['msg'] = '데이터 업데이트 오류';
        }

        return $data;
    }

    /**
     * 删除一行数据
     * @return mixed
     */
    public function del()
    {
        if (!IS_POST) methodProtect();

        $data = array();

        $req = $this->input->post();
        $id = $req['id'];

        $this->load->model('api_model');
        $res = $this->api_model->delete($id);

        if ($res) {
            $data['state'] = 0;
            $data['msg'] = 'SUCCESS';
        } else {
            $data['state'] = 2;
            $data['msg'] = '데이터 삭제 오류';
        }

        return responseJson($data);
    }
}