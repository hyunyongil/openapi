<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends CI_Controller {
    /**
     * 数据列表视图
     */
	public function index()
	{
        $this->load->model('project_model');
        $datas = $this->project_model->get();

		$this->load->view('project/index', [
		    'datas'=>$datas,
        ]);
	}

    /**
     * 新增数据表单视图
     */
	public function create_view()
    {
        $this->load->view('project/create');
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

        if (!$input['pj_name']) {
            $data['state'] = 1;
            $data['msg'] = '프로젝트명을 입력하세요';
            return $data;
        }

        $this->load->model('project_model');
        $res = $this->project_model->insert($input);

        if ($res) {
            $data['state'] = 0;
            $data['msg'] = 'SUCCESS';
        } else {
            $data['state'] = 4;
            $data['msg'] = '데이터 추가 오류';
        }

        return $data;
    }

    /**
     * 更新数据表单视图
     */
    public function edit_view()
    {
        $req = $this->input->get();

        $this->load->model('project_model');
        $field = $this->project_model->find($req['id']);

        $this->load->view('project/edit', [
            'field'=>$field,
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

        if (!$input['pj_name']) {
            $data['state'] = 1;
            $data['msg'] = '프로젝트명을 입력하세요';
            return $data;
        }

        $this->load->model('project_model');
        $res = $this->project_model->update($id, $input);

        if ($res) {
            $data['state'] = 0;
            $data['msg'] = 'SUCCESS';
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

        $count = $this->db->select('COUNT(*) AS total')->where('project_id', $id)->get('api')->row_array();
        if ($count['total']) {
            $data['state'] = 2;
            $data['msg'] = 'api 전부 삭제한후 프로젝트를 삭제해 주세요';
            return responseJson($data);
        }

        $this->load->model('project_model');
        $res = $this->project_model->delete($id);

        if ($res) {
            $data['state'] = 0;
            $data['msg'] = 'SUCCESS';
        } else {
            $data['state'] = 1;
            $data['msg'] = '데이터 삭제 오류';
        }

        return responseJson($data);
    }

    /**
     * 模块列表
     */
    public function module_list()
    {
        $req = $this->input->get();
        $id = $req['id'];

        $datas = $this->db->where('project_id', $id)->get('module')->result_array();

        $this->load->view('project/module', [
            'datas'=>$datas,
        ]);
    }

    /**
     * 删除一行数据
     * @return mixed
     */
    public function module_del()
    {
        if (!IS_POST) methodProtect();

        $data = array();

        $req = $this->input->post();
        $id = $req['id'];

        $count = $this->db->select('COUNT(*) AS total')->where('module_id', $id)->get('api')->row_array();
        if ($count['total']) {
            $data['state'] = 2;
            $data['msg'] = 'api 전부 삭제한후 모듈을 삭제해 주세요';
            return responseJson($data);
        }

        $res = $this->db->delete('module', ['id'=>$id]);

        if ($res) {
            $data['state'] = 0;
            $data['msg'] = 'SUCCESS';
        } else {
            $data['state'] = 1;
            $data['msg'] = '데이터 삭제 오류';
        }

        return responseJson($data);
    }
}