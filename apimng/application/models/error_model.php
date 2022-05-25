<?php
class error_model extends CI_Model{
    /**
     * 根据id获取一行数据
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->db->where('id', $id)->limit(1)->get('error')->row_array();
    }

    /**
     * 获取数据列表以及分页数据
     * @param $req
     * @return array
     */
    public function pagination($req, $perpage=20)
    {
        $datas = array();

        $where = array();

        $project = $this->session->userdata('project');
        $where['error.project_id'] = $project['id'];

        if (array_key_exists('api_id', $req) && $req['api_id'] != -1) {
            $where['error.api_id'] = $req['api_id'];
        }

        $count = $this->db->select('COUNT(*) AS total')->where($where)->get('error')->row_array();

        $this->load->library('page', ['total'=>$count['total'], 'countPerPage'=>$perpage]);
        $datas['paging'] = $this->page->fpage();

        $datas['data'] = $this->db->select('error.*, api.api_name')->join('api', 'error.api_id=api.id', 'left')->where($where)->order_by('error.sort', 'DESC')->order_by('error.id', 'ASC')->limit($perpage, $this->page->getOffset())->get('error')->result_array();

        return $datas;
    }

    /**
     * 插入一条数据
     * @param $data
     * @return int
     */
    public function insert($data)
    {
        $project = $this->session->userdata('project');
        $data['project_id'] = $project['id'];

        $res = $this->db->insert('error', $data);
        if ($res) {
            $error_id = $this->db->insert_id('error');
            setLog('error', 'insert', $error_id, 'Add an error<'.$data['code'].' '.$data['message'].'> successfully.');

            return $error_id;
        } else {
            return 0;
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
        $res = $this->db->where('id', $id)->update('error', $data);
        setLog('error', 'update', $id, 'Update an error<'.$data['code'].' '.$data['message'].'> successfully.');

        return $res;
    }

    /**
     * 删除一条数据
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $field = $this->find($id);

        $res = $this->db->delete('error', ['id'=>$id]);
        if ($res) {
            setLog('error', 'delete', $id, 'Delete an error<'.$field['code'].' '.$field['message'].'> successfully.');
        }

        return $res;
    }
}
