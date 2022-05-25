<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    /**
     * 数据列表视图
     */
	public function index()
	{
        $this->load->model('admin_model');
        $datas = $this->admin_model->pagination(10);

		$this->load->view('admin/index', [
		    'datas'=>$datas,
        ]);
	}

    /**
     * 新增数据表单视图
     */
	public function create_view()
    {
        $this->load->model('project_model');
        $projects = $this->project_model->get();

        $this->load->view('admin/create', [
            'projects'=>$projects,
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

        $exists = $this->db->select('COUNT(*) AS cnt')->where('username', $input['username'])->get('admin')->row_array();
        if ($exists['cnt']) {
            $data['state'] = 3;
            $data['msg'] = '이미 존재한 아이디 입니다';
            return $data;
        }

        $this->load->model('admin_model');
        $res = $this->admin_model->insert($input);

        if ($res) {
            //添加授权的项目
            $adminProject = array();
            $index = 0;
            foreach ($input as $k=>$v) {
                if (!strstr($k, 'project')) continue;
                $adminProject[$index]['admin_id'] = $res;
                $adminProject[$index]['project_id'] = $v;
                $index++;
            }

            if (count($adminProject)) {
                $this->db->insert_batch('admin_project', $adminProject);
            }

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

        $this->load->model('admin_model');
        $field = $this->admin_model->find($req['id']);

        $this->load->model('project_model');
        $projects = $this->project_model->get();

        $_adminProjects = $this->db->where('admin_id', $req['id'])->get('admin_project')->result_array();
        $adminProjects = array();
        foreach ($_adminProjects as $v) {
            $adminProjects[] = $v['project_id'];
        }

        $this->load->view('admin/edit', [
            'field'=>$field,
            'projects'=>$projects,
            'adminProjects'=>$adminProjects,
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

        if (!$input['username']) {
            $data['state'] = 1;
            $data['msg'] = '사용자 아이디를 입력하세요';
            return $data;
        }

        $exists = $this->db->select('COUNT(*) AS cnt')->where(['id !='=>$id, 'username'=>$input['username']])->get('admin')->row_array();
        if ($exists['cnt']) {
            $data['state'] = 2;
            $data['msg'] = '이미 존재한 아이디 입니다';
            return $data;
        }

        $this->load->model('admin_model');
        $res = $this->admin_model->update($id, $input);

        if ($res) {
            //先删除已授权的项目
            $this->db->delete('admin_project', ['admin_id'=>$id]);

            //重新添加授权的项目
            $adminProject = array();
            $index = 0;
            foreach ($input as $k=>$v) {
                if (!strstr($k, 'project')) continue;
                $adminProject[$index]['admin_id'] = $id;
                $adminProject[$index]['project_id'] = $v;
                $index++;
            }

            if (count($adminProject)) {
                $this->db->insert_batch('admin_project', $adminProject);
            }

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

        if ($id == 1) {
            $data['state'] = 1;
            $data['msg'] = '최고관리자 삭제불가';
            return responseJson($data);
        }

        $this->load->model('admin_model');
        $res = $this->admin_model->delete($id);

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