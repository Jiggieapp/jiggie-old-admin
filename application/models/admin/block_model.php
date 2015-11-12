<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Handles admin functions.
 *
 * @package		CodeIgniter
 * @subpackage	Models
 * @category	Models
 * @author
 */

// ------------------------------------------------------------------------

class Block_model extends CI_Model {
    public function __construct()
    {
            parent::__construct();
    }
    public function getBlockeda() {
        $start_date = $this->session->userdata('startDate')." 00:00:00";
        $end_date = $this->session->userdata('endDate'). " 23:59:59";
        $where = array("");
        
        $query = $this->db->query("SELECT 
                         FROM `block_lists` JOIN users as u on u.id = `requestor_id` JOIN users as u1 on u1.id = blockee_id
                         WHERE (block_lists.created_at >='$start_date' AND block_lists.created_at <='$end_date')");
        return $query->result_array();
    }
	
	public function getBlocked($arr_condition, $arr_sort, $return_type, $num = 0, $offset = 0, $arr_search=array()){
		
		
		if(!empty($arr_condition)){			 
			 $this->db->where($arr_condition);
		}
                if(!empty($arr_search["where"])) {
                    $this->db->where("(u.first_name  LIKE '%".$arr_search["where"]."%' OR 
                        u.last_name  LIKE '%".$arr_search["where"]."%' OR 
                        u1.first_name  LIKE '%".$arr_search["where"]."%' OR 
                        u1.last_name  LIKE '%".$arr_search["where"]."%' OR
                        CONCAT_WS(' ', u.first_name, u.last_name)  LIKE '%".$arr_search["where"]."%' OR
                        CONCAT_WS(' ', u1.first_name, u1.last_name)  LIKE '%".$arr_search["where"]."%')", NULL, FALSE); 
                }
		$this->db->join('users AS u','u.id = b.requestor_id', 'left', false);
		$this->db->join('users AS u1','u1.id = b.blockee_id', 'left', false);
                
		if('count' == $return_type){
			$this->db->select ("COUNT(b.id) AS count", FALSE);
			$query	= $this->db->get('block_lists as b');
			$result	= $query->row();
			return $result->count;
		}
		if(is_array($arr_sort) && count($arr_sort) > 0){
			//$sort_value	= ('ASC' == $arr_sort['value']) ? 'ASC': 'DESC';
			//if($arr_sort['name'] == 'created_date'){
			//	$this->db->order_by($arr_sort['name'], $sort_value);
			//}
			$this->db->order_by($arr_sort['name'], $arr_sort['value']);
		}else {
			$this->db->order_by('name', 'ASC');
		}
		
		if($num > 0){
			$this->db->limit($num, $offset);
		}
		 $this->db->select("b.id,CONCAT_WS(' ', u.first_name, u.last_name) as name1,CONCAT_WS(' ', u1.first_name, u1.last_name) as name2, b.created_at as created,u.id as user_id1, u1.id as user_id2, requestor_id, blockee_id", false);
		 $query	=	$this->db->get('block_lists as b');
		return $query->result();
	}
        
        public function unblock($block_id) {
            $this->db->where('id', $block_id);
            $this->db->delete('block_lists'); 
        }
        
        public function getChannel($user1, $user2) {
            $this->db->select("channel_id,user_id");
            $this->db->from("chat_participants");
            $this->db->where("(user_id = $user1 OR user_id = $user2) AND is_host = 1");
            return $this->db->get()->row();
        }
        
        public function getChatCount($channel_id) {
            $this->db->select("channel_id");
            $this->db->from("chat_messages");
            $this->db->where("(channel_id = $channel_id)");
            return $this->db->get()->num_rows();
        }
        
        public function export($start_date, $end_date, $where="") { 
        $delimiter = ",";
        $newline = "\r\n";
        $this->load->dbutil();
        $this->load->helper('file');
        //Search Factor
        if(!empty($where)) {
            $where_search =  "  AND (u.first_name  LIKE '%".$where."%' OR 
                        u.last_name  LIKE '%".$where."%' OR 
                        u1.first_name  LIKE '%".$where."%' OR 
                        u1.last_name  LIKE '%".$where."%' OR
                        CONCAT_WS(' ', u.first_name, u.last_name)  LIKE '%".$where."%' OR
                        CONCAT_WS(' ', u1.first_name, u1.last_name)  LIKE '%".$where."%')";
        } else {
            $where_search = "";
        }
        
        $query = $this->db->query($SQL='SELECT CONCAT_WS(" ", u.first_name, u.last_name) as name1,CONCAT_WS(" ", u1.first_name, u1.last_name) as name2, DATE_FORMAT(b.created_at, "%m/%d/%Y") as created_at FROM block_lists b 
                LEFT JOIN users AS u ON (u.id = b.requestor_id)
                LEFT JOIN users AS u1 ON (u1.id = b.blockee_id)
                WHERE b.created_at >= "'.$start_date.'" AND b.created_at <= "'.$end_date.'"'. $where_search);
       // echo $SQL;
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);

        $file_path = $this->config->item("site_basepath") . "uploads/csv/";
        
		$file = "Block-".date("Y-m-d").".csv";
        if (write_file($file_path . $file, $data)) {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . $file);
            header('Pragma: no-cache');
            readfile("$file_path" . "$file");
        } else {
            
        }
    }
}    