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

class Venue_model extends CI_Model {
    public function __construct()
    {
            parent::__construct();
    }
   
   function getAllVenues($arr_condition, $arr_sort, $return_type, $num = 0, $offset = 0, $arr_search=array()){		 
		if(!empty($arr_condition)){
			$this->db->where($arr_condition);
		}
                
                $this->db->where("(deleted IS NULL OR `deleted` != '1')", NULL, FALSE);  
                $arr_search["where"] = trim($arr_search["where"]);
                if(!empty($arr_search["where"])) {
                    $this->db->where("
                        (name  LIKE '%".$arr_search["where"]."%' OR
                        address  LIKE '%".$arr_search["where"]."%' OR
                        neighborhood  LIKE '%".$arr_search["where"]."%' OR
                        description  LIKE '%".$arr_search["where"]."%' OR
                        grade  LIKE '%".$arr_search["where"]."%'  OR  
                        cross_street  LIKE '%".$arr_search["where"]."%' OR
                        phone  LIKE '%".$arr_search["where"]."%' OR
                        lat  LIKE '%".$arr_search["where"]."%' OR
                        lng  LIKE '%".$arr_search["where"]."%'  OR
                        url  LIKE '%".$arr_search["where"]."%' OR
                        city  LIKE '%".$arr_search["where"]."%' OR
                        zip  LIKE '%".$arr_search["where"]."%'     
                        )", NULL, FALSE);  
                }
                
		if('count' == $return_type){
			$this->db->select ("COUNT(id) AS count", FALSE);
			$query	= $this->db->get('venues');
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
		$query	=	$this->db->get('venues');
		return $query->result();
	}	
    
   public function updateVenue($field, $data, $venue) {
        if($field == "cross") $field = "cross_street";
        if($field == "venue_description") $field = "description";
        $update_field = array($field => $data);
        return $this->db->update("venues", $update_field, array("id"=>$venue));
   }     
   
   public function delete_venue($venue_id) {
        return $this->db->update("venues",array("deleted"=>1,"venue_status"=>10), array("id"=>$venue_id));
   }
   
   public function save_venue($post_data, $venue_image) 
   {
        $data = $post_data;
        
        $venue_image_concat = $venue_image;
        $venue_images = explode(",", $venue_image_concat);
        $i = 0;
        if(!empty($venue_images)) {
            foreach($venue_images as $venue_image) 
            {
                $append_index = array("_1","_2","_3","_4","_5");
                $data["img".$append_index[$i]] = $venue_image;
                $i++;
            }
        }
        
        $data["created_at"] = date("Y-m-d h:i:s");
        $this->db->set($data);
        $this->db->insert('venues'); 
    }
    public function profile_img($data, $user) {
        return $this->db->update("venues", $data, array("id"=>$user));
    }
    
    public function export($start_date, $end_date, $where="") {
        $delimiter = ",";
        $newline = "\r\n";
        $this->load->dbutil();
        $this->load->helper('file');
        if(!empty($where)) {
             $where_search = " AND (name  LIKE '%".$where."%' OR address  LIKE '%".$where."%' OR neighborhood  LIKE '%".$where."%' OR description  LIKE '%".$where."%' )";  
        } else {
            $where_search = "";
        }
        $where_search .= " AND venue_status != 10";
        $query = $this->db->query("SELECT * FROM venues WHERE created_at >= '$start_date' AND created_at <= '$end_date' $where_search");
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);

        $file_path = $this->config->item("site_basepath") . "uploads/csv/";
        $file = strtotime(date("Y-m-d h:i:s")) . ".csv";

        if (write_file($file_path . $file, $data)) {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . $file);
            header('Pragma: no-cache');
            readfile("$file_path" . "$file");
        } else {
            
        }
    }
    
    public function getVenueHosting($venue_id) {
        $this->db->select("venue_id");
        $this->db->from("hostings");
        $this->db->where(array("venue_id" => $venue_id));
        $query = $this->db->get();
        return $query->num_rows();
    }
    
}