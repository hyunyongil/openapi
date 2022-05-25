<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
    private $admin;

    public function __construct()
    {
        parent::__construct();
        $this->admin = $this->session->userdata('admin');
    }

    /**
     * index view
     */
	public function index()
	{
	    //当前用户授权的项目
	    if ($this->admin['privilege'] != 1) {
            $_adminProject = $this->db->where('admin_id', $this->admin['id'])->get('admin_project')->result_array();
            $project_id = [];
            foreach ($_adminProject as $v) {
                $project_id[] = $v['project_id'];
            }

            if (count($project_id)) {
                $this->db->where_in('id', $project_id);
            } else {
                $this->db->where_in('id', 0);
            }
        }
	    $projects = $this->db->order_by('id', 'DESC')->get('project')->result_array();

	    //当前操作的项目，如果不存在，则选择项目列表中的第一项作为当前操作项目
	    $curProject = $this->session->userdata('project');
	    if (!$curProject && count($projects)) {
            $curProject = $projects[0];
            $this->session->set_userdata(['project'=>$curProject]);
        }

	    //获取项目模块以及api列表作为左侧菜单
        $this->load->model('api_model');
	    $apiList = $this->api_model->get($curProject['id']);

		$this->load->view('index', [
		    'admin'=>$this->admin,
            'projects'=>$projects,
            'curProject'=>$curProject,
            'apiList'=>$apiList,
        ]);
	}

    /**
     * main view
     */
    public function mainView()
    {
        $sql = 'SELECT VERSION() AS version';
        $mysql = $this->db->query($sql)->row_array();

        $this->load->view('main', [
            'admin'=>$this->admin,
            'mysql'=>$mysql,
        ]);
    }

    /**
     * icon list view
     */
    public function icon()
    {
        $this->load->view('unicode');
    }

    /**
     * 设置当前浏览项目
     * @return array|mixed
     */
    public function set_pj()
    {
        if (!IS_POST) methodProtect();

        $data = array();

        $req = $this->input->post();
        $id = $req['id'];

        if ($this->admin['privilege'] != 1) {
            $count = $this->db->select('COUNT(*) AS total')->where(['admin_id'=>$this->admin['id'], 'project_id'=>$id])->get('admin_project')->row_array();
            if (!$count['total']) {
                $data['state'] = 1;
                $data['msg'] = '권한이 없습니다.';
                return responseJson($data);
            }
        }

        $this->load->model('project_model');
        $project = $this->project_model->find($id);

        $this->session->set_userdata(['project'=>$project]);

        $data['state'] = 0;
        $data['msg'] = 'SUCCESS';
        return responseJson($data);
    }
}