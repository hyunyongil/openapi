<?php
class project_model extends CI_Model{
    /**
     * 根据id获取一行数据
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->db->where('id', $id)->limit(1)->get('project')->row_array();
    }

    /**
     * 获取数据列表
     * @return array
     */
    public function get()
    {
        return $this->db->order_by('id', 'DESC')->get('project')->result_array();
    }

    /**
     * 插入一条数据
     * @param $data
     * @return int
     */
    public function insert($data)
    {
        $data['addtime'] = time();

        $res = $this->db->insert('project', $data);

        if ($res) {
            $project_id = $this->db->insert_id('project');
            setLog('project', 'insert', $project_id, 'Add a project<'.$data['pj_name'].'> successfully.');

            return $project_id;
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
        $res = $this->db->where('id', $id)->update('project', $data);
        setLog('project', 'update', $id, 'Update a project<'.$data['pj_name'].'> successfully.');

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

        $res = $this->db->delete('project', ['id'=>$id]);
        if ($res) {
            $this->db->delete('admin_project', ['project_id'=>$id]);
            $this->db->delete('module', ['project_id'=>$id]);
            setLog('project', 'delete', $id, 'Delete a project<'.$field['pj_name'].'> successfully.');
        }

        return $res;
    }
}
