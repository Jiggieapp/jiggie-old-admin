<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Handles admin functions.
 *
 * @package		CodeIgniter
 * @subpackage	Models
 * @category	Models
 * @author
 */
// ------------------------------------------------------------------------

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	
	public function getAllUsers($arr_condition, $arr_sort, $return_type, $num = 0, $offset = 0, $arr_search=array()){
		if(!empty($arr_condition)){			 
			$this->db->where($arr_condition);
		}
                if(!empty($arr_search["where"])) {
                    $this->db->where("(first_name  LIKE '%".$arr_search["where"]."%' OR 
                        last_name  LIKE '%".$arr_search["where"]."%' OR 
                        email  LIKE '%".$arr_search["where"]."%' OR 
                        CONCAT_WS(' ', first_name, last_name)  LIKE '%".$arr_search["where"]."%' OR
                        location  LIKE '%".$arr_search["where"]."%' OR 
                        about  LIKE '%".$arr_search["where"]."%' OR
                        gender  LIKE '".$arr_search["where"]."' OR
                        TIMESTAMPDIFF(YEAR, birthday, CURDATE()) =  '".$arr_search["where"]."' OR 
                        phone  LIKE '%".$arr_search["where"]."%' OR 
                        fb_id  LIKE '%".$arr_search["where"]."%' OR
                        invited_by  LIKE '%".$arr_search["where"]."%' OR
                        tagline  LIKE '%".$arr_search["where"]."%'    
                        )", NULL, FALSE); 
                }
		if('count' == $return_type){
			$this->db->select ("COUNT(id) AS count", FALSE);
			$query	= $this->db->get('users');
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
		 $this->db->select("id ,CONCAT_WS(' ', first_name, last_name) as name,  profile_image_url ,  gender , 
                                   TIMESTAMPDIFF(YEAR, birthday, CURDATE()) AS age,
                                   location ,  invited_by , created_at , IF(verified = 1,  'Yes' ,  'No' ) as verified, 
                                   (CASE WHEN user_status ='0' THEN 'New'
                                   WHEN user_status ='1' THEN 'Active'
                                   ELSE 'Suspended' END) as user_status,  cnt_hosting ,  cnt_chat,  cnt_invite",false);
		$query	=	$this->db->get('users');
		return $query->result();
	}

    public function updateUser($field, $data, $user, $data1="", $field1="") {

        $field_array = array("fbid" => "fb_id",
            "email" => "email",
            "nickname" => "nick_name",
            "gender" => "gender",
            "tagline" => "tagline",
            "about" => "about",
            "location" => "location",
            "phone" => "phone",
            "locationP" => "location",
            "fname" => "first_name",
            "lname" => "last_name",
            "updated_at" => date("Y-m-d h:i:s")
        );
        $field = $field_array[$field];
        /* if($field == "first_name") {
          $field1 = $field_array[$field1];
          $update_field = array($field => $data, $field1=> $data1);
          } else { */
        $update_field = array($field => $data);
        //}

        return $this->db->update("users", $update_field, array("id" => $user));
    }

    public function updateUserGender($data, $user) {
        return $this->db->update("users", array("gender" => $data, "updated_at" => date("Y-m-d h:i:s")), array("id" => $user));
    }

    public function updateBirthday($birthday, $user) {
        $birthday = date("Y-m-d", strtotime($birthday));
        return $this->db->update("users", array("birthday" => $birthday, "updated_at" => date("Y-m-d h:i:s")), array("id" => $user));
    }

    public function verifyhost($verify, $user) {
        return $this->db->update("users", array("verified" => $verify, "updated_at" => date("Y-m-d h:i:s")), array("id" => $user));
    }

    public function suspend_user($verify, $user) {
        return $this->db->update("users", array("user_status" => $verify, "updated_at" => date("Y-m-d h:i:s")), array("id" => $user));
    }

    public function delete_user($user_id) {
        //return $this->db->delete("users", array("id"=>$user_id));
        return $this->db->update("users", array("user_status" => "10"), array("id"=>$user_id));
    }

    public function handle_upload($field_name = "user_file", $config = array()) {
        $this->load->library('upload', $config);
        $this->upload->do_upload('qqfile');
        $file_data = $this->upload->data();
        return $file_data;	
    }

    public function profile_img($data, $user) {
        return $this->db->update("users", $data, array("id" => $user));
    }

    public function save_user($post_data, $profile_image, $password) {
        $data = $post_data;
        $profile_image_concat = $profile_image;
        $profile_images = explode(",", $profile_image_concat);
        $i = 0;
        if (!empty($profile_images)) {
            foreach ($profile_images as $profile_image) {
                $append_index = array("", "_2", "_3", "_4", "_5");
                $data["profile_image_url" . $append_index[$i]] = $profile_image;
                $i++;
            }
        }

        $data["created_at"] = date("Y-m-d h:i:s");
        $this->db->set("password", "LEFT(MD5(CONCAT(MD5('$password'), 'cloud')), 50)", false);
        $this->db->set($data);
        $this->db->insert('users');
    }
    
    public function update_password($password, $email) {
        $this->db->set("crypted_password", "LEFT(MD5(CONCAT(MD5('$password'), 'cloud')), 50)", false);
        $this->db->where('email', $email);
        $this->db->update('admin_users');
    }

    public function export($start_date, $end_date, $where="") {
        $delimiter = ",";
        $newline = "\r\n";
        $this->load->dbutil();
        $this->load->helper('file');
        if(!empty($where)) {
            $where_search = " AND (first_name  LIKE '%".$where."%' OR 
                last_name  LIKE '%".$where."%' OR 
                email  LIKE '%".$where."%' OR 
                CONCAT_WS(' ', first_name, last_name)  LIKE '%".$where."%')";
        } else {
            $where_search = "";
        }
        $where_search .= " AND user_status != 10";
        $query = $this->db->query($SQL="SELECT * FROM users WHERE created_at >= '$start_date' AND created_at <= '$end_date' $where_search");
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
    
    public function getUserCounts(){
        $userTotal = array();
        $date = date("Y-m-d");
        $qry_condn = "created_at >= '$date 00:00:00' and created_at<='$date 23:59:00'";        
        $userTotal['user_today'] = $this->getUserCntByDate( $qry_condn);
        
        $ydate = date("Y-m-d",strtotime("-1 days"));
        $qry_condn = "created_at >= '$ydate 00:00:00' and created_at<='$ydate 23:59:00'";        
        $userTotal['user_yesterday'] = $this->getUserCntByDate( $qry_condn);
        
        $qry_condn = "created_at >= DATE_SUB( NOW( ) , INTERVAL 1 WEEK)";      
        $userTotal['user_week'] = $this->getUserCntByDate( $qry_condn);
        
        $prev_date = date("Y-m-d H:i:s",strtotime("-7 days"));
        $qry_condn = "created_at >= DATE_SUB( '$prev_date' , INTERVAL 1 WEEK) and created_at <= '$prev_date'"; 
        $userTotal['user_prev_week'] = $this->getUserCntByDate( $qry_condn);
        
        $qry_condn = "created_at >= DATE_SUB( NOW( ) , INTERVAL 1 MONTH)";        
        $userTotal['user_month'] = $this->getUserCntByDate( $qry_condn);  
        
        $prev_month_date = date("Y-m-d H:i:s",strtotime("-30 days"));
        $qry_condn = "created_at >= DATE_SUB( '$prev_month_date' , INTERVAL 1 MONTH) and created_at <= '$prev_month_date'"; 
        $userTotal['user_prev_month'] = $this->getUserCntByDate( $qry_condn); 
         
        $userTotal['user_total'] = $this->getUserCount();
        return $userTotal;
    }
    
    public function getUserCntByDate( $qry_condn){
        $result = 0;
        $query	= $this->db->query("Select count(id) as user_cnt from users where $qry_condn");       
        foreach ($query->result_array() as $count){
            $result = $count['user_cnt'];
        }
        
	return $result;
    }
    
    public function getUserCount(){
        $query	= $this->db->query("Select count(id) as user_cnt from users");
        foreach ($query->result_array() as $count){
            $result = $count['user_cnt'];
        }
        
	return $result;
    }
    
    public function getUserHosting($id) {
        $this->db->select("id");
        $this->db->where("user_id", $id);
        $query = $this->db->from("hostings");
        return $this->db->count_all_results();
    }
    
    public function getUserChat($id) {
        $this->db->select("id");
        $this->db->where("user_id", $id);
        $query = $this->db->from("chat_participants");
        return $this->db->count_all_results();
    }
   
}

