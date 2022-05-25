<?php
class admin_model extends CI_Model{
    /**
     * 获取登录即将的用户信息
     * @param $username
     * @return mixed
     */
    public function getLoginUser($username)
    {
        return $this->db->where('username', $username)->get('admin')->row_array();
    }

    /**
     * 根据id获取一行数据
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->db->where('id', $id)->limit(1)->get('admin')->row_array();
    }

    /**
     * 获取数据列表以及分页数据
     * @return array
     */
    public function pagination($perpage=10)
    {
        $datas = array();

        $count = $this->db->select('COUNT(*) AS total')->get('admin')->row_array();

        $this->load->library('page', ['total'=>$count['total'], 'countPerPage'=>$perpage]);
        $datas['paging'] = $this->page->fpage();

        $admin = $this->session->userdata('admin');
        if ($admin['id'] != '1') {
            $this->db->where('id >', 1);
        }

        $datas['data'] = $this->db->order_by('id', 'ASC')->limit($perpage, $this->page->getOffset())->get('admin')->result_array();

        return $datas;
    }

    /**
     * 插入一条数据
     * @param $data
     * @return int
     */
    public function insert($data)
    {
        $insert = array();
        $insert['username'] = $data['username'];
        $insert['password'] = sha1($data['password']);
        $insert['is_use'] = $data['is_use'];
        $insert['privilege'] = $data['privilege'];
        $insert['addtime'] = time();

        $res = $this->db->insert('admin', $insert);
        if ($res) {
            $admin_id = $this->db->insert_id('admin');
            setLog('admin', 'insert', $admin_id, 'Add an admin<'.$insert['username'].'> successfully.');

            return $admin_id;
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
        $update = array();
        $update['username'] = $data['username'];
        if ($data['password']) {
            $update['password'] = sha1($data['password']);
        }
        $update['is_use'] = $id != 1 ? $data['is_use'] : '1';
        $update['privilege'] = $data['privilege'];

        $res = $this->db->where('id', $id)->update('admin', $update);
        setLog('admin', 'update', $id, 'Update an admin<'.$update['username'].'> successfully.');

        return $res;
    }

    /**
     * 删除管理员数据
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $field = $this->find($id);

        $res = $this->db->delete('admin', ['id'=>$id]);
        if ($res) {
            $this->db->delete('admin_project', ['admin_id'=>$id]);
            setLog('admin', 'delete', $id, 'Delete an admin<'.$field['username'].'> successfully.');
        }

        return $res;
    }
}
