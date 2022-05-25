<?php
class api_model extends CI_Model{
    /**
     * 根据id获取一行数据
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->db->select('api.*, module.mo_name')->join('module', 'api.module_id=module.id', 'left')->where('api.id', $id)->limit(1)->get('api')->row_array();
    }

    /**
     * 获取数据列表
     * @param $project_id
     * @return array
     */
    public function get($project_id)
    {
        $list = $this->db->select('api.id, api.api_name, module.mo_name')->join('module', 'api.module_id=module.id', 'left')->where('api.project_id', $project_id)->order_by('module.id', 'ASC')->order_by('api.sort', 'DESC')->order_by('api.id', 'ASC')->order_by('api.version', 'DESC')->group_by('api.group_code')->get('api')->result_array();
        $_list = array();

        foreach ($list as $v) {
            $moName = $v['mo_name'] ?: 'default';
            $_list[$moName][] = $v;
        }

        return $_list;
    }

    /**
     * 获取不同版本的相同api
     * @param $group_code
     * @return mixed
     */
    public function getGroup($group_code)
    {
        return $this->db->select('id, version')->where('group_code', $group_code)->order_by('version', 'ASC')->get('api')->result_array();
    }

    /**
     * 获取 api 的请求参数数据（根据父子关系重新排序）
     * @param $api_id
     * @return array
     */
    public function getReqParam($api_id)
    {
        $reqParam = $this->db->where('api_id', $api_id)->order_by('req_sort', 'DESC')->order_by('id', 'ASC')->get('request_param')->result_array();
        return $this->_resort($reqParam);
    }

    /**
     * 获取 api 的请求参数数据（根据父子关系重新排序）
     * @param $api_id
     * @return array
     */
    public function getResParam($api_id)
    {
        $resParam = $this->db->where('api_id', $api_id)->order_by('res_sort', 'DESC')->order_by('id', 'ASC')->get('response_param')->result_array();
        return $this->_resort($resParam);
    }

    /**
     * 参数数据根据父子等级关系重新排序
     * @param $data
     * @param int $parent_id
     * @param int $level
     * @param bool $isClear
     * @return array
     */
    private function _resort($data, $parent_id=0, $level=0, $isClear=true)
    {
        static $params = array();

        if($isClear){
            $params = array();
        }

        foreach($data as $k=>$v){
            if($v['parent_id'] == $parent_id){
                $v['level'] = $level;
                $params[] = $v;
                unset($data[$k]);

                $this->_resort($data, $v['id'], $level+1, false);
            }
        }

        return $params;
    }

    /**
     * 获取请求参数表中最大id值
     * @return int
     */
    public function getReqMaxId()
    {
        $row = $this->db->order_by('id', 'DESC')->limit(1)->get('request_param')->row_array();

        return $row ? $row['id'] : 0;
    }

    /**
     * 获取响应参数表中最大id值
     * @return int
     */
    public function getResMaxId()
    {
        $row = $this->db->order_by('id', 'DESC')->limit(1)->get('response_param')->row_array();

        return $row ? $row['id'] : 0;
    }

    /**
     * 插入一条数据
     * @param $data
     * @return int
     */
    public function insert($data)
    {
        $insert = array();

        //project_id
        $project = $this->session->userdata('project');
        $insert['project_id'] = $project['id'];

        //module_id
        $moduleName = $data['module_name'];
        $module = $this->db->where(['project_id'=>$project['id'], 'mo_name'=>$moduleName])->get('module')->row_array();

        if ($module) {
            $module_id = $module['id'];
        } else {
            $this->db->insert('module', ['project_id'=>$project['id'], 'mo_name'=>$moduleName]);
            $module_id = $this->db->insert_id('module');
        }
        $insert['module_id'] = $module_id;

        //判断是否有相同的api存在
        $exists = $this->db->where(['project_id'=>$project['id'], 'module_id'=>$module_id, 'version'=>$data['version'], 'api_name'=>$data['api_name']])->get('api')->row_array();
        if ($exists) {
            return false;
        }

        //group_code
        $versionApi = $this->db->where(['project_id'=>$project['id'], 'module_id'=>$module_id, 'version !='=>$data['version'], 'api_name'=>$data['api_name']])->get('api')->row_array();
        if ($versionApi) {
            $insert['group_code'] = $versionApi['group_code'];
        } else {
            $insert['group_code'] = uniqid();
        }

        $admin = $this->session->userdata('admin');
        $insert['version'] = $data['version'];
        $insert['status'] = $data['status'];
        $insert['api_name'] = $data['api_name'];
        $insert['api_desc'] = $data['api_desc'];
        $insert['api_path'] = $data['api_path'];
        $insert['api_method'] = $data['api_method'];
        $insert['with_token'] = $data['with_token'];
        $insert['request_demo'] = $data['request_demo'];
        $insert['response_demo'] = $data['response_demo'];
        $insert['sort'] = $data['sort'];
        $insert['create_admin'] = $admin['id'];
        $insert['create_time'] = time();
        $insert['last_edit_admin'] = $admin['id'];
        $insert['last_edit_time'] = time();
        $insert['memo'] = $data['memo'];

        $res = $this->db->insert('api', $insert);

        if ($res) {
            $api_id = $this->db->insert_id('api');
            setLog('api', 'insert', $api_id, 'Add an api<'.$insert['api_name'].'> successfully.');

            return $api_id;
        } else {
            return 0;
        }
    }

    /**
     * 添加请求参数和响应参数
     * @param $api_id
     * @param $data
     */
    public function addParam($api_id, $data)
    {
        $this->db->delete('request_param', ['api_id'=>$api_id]);
        $this->db->delete('response_param', ['api_id'=>$api_id]);

        $req_id_map = array();
        $res_id_map = array();

        //记录api请求参数数据
        foreach ($data['req'] as $v) {
            $_id = $v['id'];
            unset($v['id']);

            if ($v['parent_id'] && array_key_exists($v['parent_id'], $req_id_map)) {
                $v['parent_id'] = $req_id_map[$v['parent_id']];
            }
            $v['org_id'] = (string)$_id;

            $this->db->insert('request_param', $v);
            $insert_id = $this->db->insert_id('request_param');

            $req_id_map[$_id] = $insert_id;
        }

        //记录api响应参数数据
        foreach ($data['res'] as $v) {
            $_id = $v['id'];
            unset($v['id']);

            if ($v['parent_id'] && array_key_exists($v['parent_id'], $res_id_map)) {
                $v['parent_id'] = $res_id_map[$v['parent_id']];
            }
            $v['org_id'] = (string)$_id;

            $this->db->insert('response_param', $v);
            $insert_id = $this->db->insert_id('response_param');

            $res_id_map[$_id] = $insert_id;
        }
    }

    /**
     * 更新数据
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $update = array();

        //project_id
        $project = $this->session->userdata('project');

        //module_id
        $moduleName = $data['module_name'];
        $module = $this->db->where(['project_id'=>$project['id'], 'mo_name'=>$moduleName])->get('module')->row_array();

        if ($module) {
            $module_id = $module['id'];
        } else {
            $this->db->insert('module', ['project_id'=>$project['id'], 'mo_name'=>$moduleName]);
            $module_id = $this->db->insert_id('module');
        }
        $update['module_id'] = $module_id;

        //判断是否有相同的api存在
        $exists = $this->db->where(['project_id'=>$project['id'], 'module_id'=>$module_id, 'version'=>$data['version'], 'api_name'=>$data['api_name'], 'id !='=>$id])->get('api')->row_array();
        if ($exists) {
            return false;
        }

        //group_code
        $versionApi = $this->db->where(['project_id'=>$project['id'], 'module_id'=>$module_id, 'version !='=>$data['version'], 'api_name'=>$data['api_name'], 'id !='=>$id])->get('api')->row_array();
        if ($versionApi) {
            $update['group_code'] = $versionApi['group_code'];
        } else {
            $update['group_code'] = uniqid();
        }

        $admin = $this->session->userdata('admin');
        $update['version'] = $data['version'];
        $update['status'] = $data['status'];
        $update['api_name'] = $data['api_name'];
        $update['api_desc'] = $data['api_desc'];
        $update['api_path'] = $data['api_path'];
        $update['api_method'] = $data['api_method'];
        $update['with_token'] = $data['with_token'];
        $update['request_demo'] = $data['request_demo'];
        $update['response_demo'] = $data['response_demo'];
        $update['sort'] = $data['sort'];
        $update['create_admin'] = $admin['id'];
        $update['create_time'] = time();
        $update['last_edit_admin'] = $admin['id'];
        $update['last_edit_time'] = time();
        $update['memo'] = $data['memo'];

        $res = $this->db->where('id', $id)->update('api', $update);
        setLog('api', 'update', $id, 'Update an api<'.$update['api_name'].'> successfully.');

        return $res;
    }

    /**
     * 删除一条api
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $api = $this->db->select('module_id, api_name')->where(['id'=>$id])->get('api')->row_array();

        $res = $this->db->delete('api', ['id'=>$id]);
        if ($res) {
            //删除请求响应参数
            $this->db->delete('request_param', ['api_id'=>$id]);
            $this->db->delete('response_param', ['api_id'=>$id]);

            //模块中没有api，则删除该模块
            $exists = $this->db->select('COUNT(*) AS total')->where(['module_id'=>$api['module_id']])->get('api')->row_array();
            if (!$exists['total']) {
                $this->db->delete('module', ['id'=>$api['module_id']]);
            }

            //删除 api 对应的 error
            $this->db->delete('error', ['api_id'=>$id]);

            setLog('api', 'delete', $id, 'Delete an api<'.$api['api_name'].'> successfully.');

            $returnVal = true;
        } else {
            $returnVal = false;
        }

        return $returnVal;
    }
}
