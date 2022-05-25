<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error extends CI_Controller {
    private $modules;

    public function __construct()
    {
        parent::__construct();

        $project = $this->session->userdata('project');
        $this->modules = $this->db->where('project_id', $project['id'])->get('module')->result_array();
    }

    /**
     * 数据列表视图
     */
	public function index()
	{
        $req = $this->input->get() ?: [];

        $this->load->model('error_model');
        $datas = $this->error_model->pagination($req, 20);

		$this->load->view('error/index', [
		    'datas'=>$datas,
		    'modules'=>$this->modules,
		    'req'=>$req,
        ]);
	}

    /**
     * 新增数据表单视图
     */
	public function create_view()
    {
        $this->load->view('error/create', [
            'modules'=>$this->modules,
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
        $this->load->model('error_model');
        $res = $this->error_model->insert($input);

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

        $this->load->model('error_model');
        $field = $this->error_model->find($req['id']);

        $cur_module = $this->db->select('id, api_name, module_id')->where('id', $field['api_id'])->limit(1)->get('api')->row_array();

        $module_api = [];
        if (count($cur_module)) {
            $module_api = $this->db->select('id, api_name')->where('module_id', $cur_module['module_id'])->order_by('sort', 'DESC')->order_by('id', 'ASC')->get('api')->result_array();
        }

        $this->load->view('error/edit', [
            'field'=>$field,
            'modules'=>$this->modules,
            'cur_module'=>$cur_module,
            'module_api'=>$module_api,
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
        $this->load->model('error_model');
        $res = $this->error_model->update($id, $input);

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

        $this->load->model('error_model');
        $res = $this->error_model->delete($id);

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