<?php
class UserInfo_model extends CI_Model
{


    public function getAllUserInfoData($draw = '', $condition = array(), $limit = array(), $order = array())
    {
        $limit = (array_reverse($limit));
        // print_r($limit);
        $this->db->select('ui.*');
        $this->db->from('user_info ui');
        $this->db->where('ui.is_deleted', 0);
        if (!empty($condition['userId']['value'])) {
            $user_id = $condition['userId']['value'];
            $this->db->where('ui.userId', $user_id);
        }
        if (!empty($limit)) {
            $this->db->limit($limit[0], $limit[1]);
        }
        if (!empty($order) && isset($order['order_by']) && isset($order['order_type'])) {
            $this->db->order_by($order['order_by'], $order['order_type']);
        }


        $query_data = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        $this->db->select('COUNT(*) As count');
        $this->db->from('user_info ui');
        $this->db->where('ui.is_deleted', 0);
        if (!empty($condition['userId']['value'])) {
            $user_id = $condition['userId']['value'];
            $this->db->where('ui.userId', $user_id);
        }
        $total_record_query = $this->db->get()->row_array();
        $totalCount = ($total_record_query['count']);
        $noOfRecordsToDisplay = count($query_data);
        $data = array();
        if (count($query_data)) {
            $count = 1;
            // print_r($rows);
            foreach ($query_data as $row) {

                $data[] = array(
                    '',
                    $row['userId'],
                    $row['title'],
                    $row['body'],
                    $row['id'],

                );
            }
        }
        $directory_info = array(
            'draw' => (int) $draw ? (int) $draw : (int) 0,
            "iTotalDisplayRecords" => $totalCount,
            "iTotalRecords" => $noOfRecordsToDisplay,
            'data' => $data,
        );
        return ($directory_info);
    }
    public function getUserDataById($id)
    {

        $sql = "SELECT ui.* FROM user_info ui  where ui.id='" . $id . "' ";
        $query = $this->db->query($sql);
        return ($query->row_array());
    }


    public function getSearchFormData()
    {
        $this->db->select('ui.*');
        $this->db->from('user_info ui');
        $this->db->where('ui.is_deleted', 0);
        // $this->db->order_by('ui.id', 'DESC');
        $ans = $this->db->get()->result_array();
        return $ans;
    }

    public function getAllUserId()
    {
        $this->db->select('ui.userId');
        $this->db->from('user_info ui');
        $this->db->where('ui.is_deleted', 0);
        $this->db->group_by('ui.userId');

        // $this->db->order_by('ui.id', 'DESC');
        $ans = $this->db->get()->result_array();
        return $ans;
    }
    public function getUserDataByIdList($ids_list = [])
    {
        $this->db->select('ui.*');
        $this->db->from('user_info ui');
        $this->db->where('ui.is_deleted', 0);
        $this->db->where_in('ui.id', $ids_list);
        $this->db->order_by('ui.id', 'asc');
        $ans = $this->db->get()->result_array();
        return ($ans);
    }
}
