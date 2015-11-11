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

class Master_model extends CI_Model {
    public function __construct()
    {
         parent::__construct();
    }
    
    public function get_user_types() {
        $query = $this->db->get("user_type");
        return $query->result_array();
    }
    
     public function build_usertype_tree($items) {
        $childs = array();
            foreach ($items as &$item)
                $childs[$item['sub_of']][$item['user_type_id']] = &$item;
            unset($item);
            foreach ($items as &$item)
                if (isset($childs[$item['user_type_id']]))
                    $item['childs'] = $childs[$item['user_type_id']];

            return $childs[0];
    }
    
   public function usertype_depth($arr) {print_r($arr);
       $results = array();
       if ( ! empty ( $arr['childs'] ) ){
           foreach ( $arr['childs'] as $ar ){
               $copy = $ar;
               if (!empty($copy['childs'])){
                   unset($copy['childs']);
                   $results[] = $copy;
                   $childs = $this->usertype_depth($ar['childs']);
                   $results = array_merge($results,$childs);
               } 
               else{
                   $results[] = $ar;
               }
           }
       }
       else{
            if ( ! empty ( $arr ) ){
                 foreach ( $arr as $ar ){
                     $copy = $ar;
                     if (!empty($copy['childs'])){
                          unset($copy['childs']);
                           $results[] = $copy;
                           $childs = $this->usertype_depth($ar['childs']);
                           $results = array_merge($results,$childs);
                     }
                     else{
                           $results[] = $ar;
                       }
                 }
            }
       }
       return $results;
    }

    public function build_usertype_child($arr, $id) {
        $results = array();
        
        
        if (!empty($arr[$id]['childs'])) {
            foreach ($arr[$id]['childs'] as $ar) {
                if ($ar['sub_of'] == $id) {
                    $copy = $ar;
                    if (!empty($copy['childs']))
                        unset($copy['childs']);
                    $results[] = $copy;
                }
               
                if (!empty($ar['childs']) && $childs = $this->build_usertype_child($ar['childs'], $ar['user_type_id'])) {
                    $results = array_merge($results, $childs);
                }
            }
        }
       
    }
    
    public function check_email_bystatus($user_type_id = null) {
        $str = '';
        $myarray = array();
        $myarray["user_status !="] = 10;
        if (isset($user_type_id)) {
            $myarray["user_type_id"] = $user_type_id;
        }
        
        
        $myarray["email"] = $this->input->post('email');
        $query = $this->db->get_where("admin_users", $myarray);
        //print $this->db->last_query(); exit;
        return $query->row_array();
    }
    
    public function insert_user_permission($fields) {
        $this->db->set('created_date', 'NOW()', FALSE);
        $this->db->insert('user_permission', $fields);
        return $this->db->insert_id();
    }
    
    public function create_user($image) {
       
        $password = mysql_real_escape_string($this->input->post('password'));
        $myarray = array();
        $myarray['email'] = $this->input->post('email');
        $myarray['user_type_id'] = $this->input->post('user_type_id');
        $myarray['first_name'] = $this->input->post('first_name') ;
        $myarray['last_name'] = $this->input->post('last_name');
        //$myarray['crypted_password'] = $pass;

        $myarray['profile_image_url'] = $image;
        $myarray['created_at'] =  date("Y-m-d H:i:s");
        $user_type_id = $this->input->post('user_type_id');
               

        $this->db->set("crypted_password", "LEFT(MD5(CONCAT(MD5('$password'), 'cloud')), 50)", false);
        $this->db->insert('admin_users', $myarray);
        
        $establishment_id = $this->db->insert_id();
        if ($establishment_id) {
            $user_type_permission = $this->get_usertype_permission($user_type_id);
            if (!empty($user_type_permission)) {
                foreach ($user_type_permission as $value) {
                    $fields = array();
                    $fields['module_id'] = $value['module_id'];
                    $fields['user_id'] = $establishment_id;
                    $fields['user_type_id'] = $user_type_id;
                    $fields['user_permission'] = $value['permission_value'];
                    $fields['status_id'] = 1;
                    
                    //$fields = array_merge($fields, $this->default_fields_create());
                    $this->insert_user_permission($fields);
                }
            }
            return true;
        }
    }
    public function get_usertype_permission($user_type_id) {
        $this->db->where('user_type_id', $user_type_id);
        $query = $this->db->get("user_type_permission");
        return $query->result_array();
    }
    
    public function getAllUsers($arr_condition, $arr_sort, $return_type, $num = 0, $offset = 0, $arr_search=array()){
		if(!empty($arr_condition)){			 
			$this->db->where($arr_condition);
		}
                if(!empty($arr_search["where"])) {
                    $this->db->where("(first_name  LIKE '%".$arr_search["where"]."%' OR 
                        last_name  LIKE '%".$arr_search["where"]."%' 
                        OR email  LIKE '%".$arr_search["where"]."%' OR 
                        CONCAT_WS(' ', first_name, last_name)  LIKE '%".$arr_search["where"]."%')", NULL, FALSE); 
                }
		if('count' == $return_type){
			$this->db->select ("COUNT(id) AS count", FALSE);
			$query	= $this->db->get('admin_users');
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
			$this->db->order_by('first_name', 'ASC');
		}
		
		if($num > 0){
			$this->db->limit($num, $offset);
		}
		 $this->db->select("id ,CONCAT_WS(' ', first_name, last_name) as name,  profile_image_url ,email, DATE_FORMAT(created_at,'%m-%d-%Y %T') as created_at",false);
		$query	=	$this->db->get('admin_users');
		return $query->result();
   }
   
   /**
     * get admin details
     *
     * @param admin id $id
     * @return unknown
     */
    function getAdminDet($id){
            if(isset($id) && !empty($id)){			
                    $this->db->where('id',$id);
                    $this->db->select('*');
                    $query	=	$this->db->get('admin_users');			
                    if($query->row()){
                            return $query->row();
                    }else{
                            return FALSE;
                    }
            }else{
                    return FALSE;
            }
    }

    public function suspend_admin_user($verify, $user)
    {
        return $this->db->update("admin_users", array("user_status" => $verify), array("id" => $user));
    }
    
    public function update_admin_user($field, $data, $user, $data1="", $field1="")
    {
        $field_array = array(
            "email" => "email",            
            "fname" => "first_name",
            "lname" => "last_name",
        );
        $field = $field_array[$field];
        
        $update_field = array($field => $data);
        
        return $this->db->update("admin_users", $update_field, array("id" => $user));
    }
   
    public function update_adminuser($user_id, $image)
    {
        $myarray = array();
       // $myarray['email'] = $this->input->post('email');
        $myarray['user_type_id'] = $this->input->post('user_type_id');
        $myarray['first_name'] = $this->input->post('first_name') ;
        $myarray['last_name'] = $this->input->post('last_name');
        
        if($image!='')  
            $myarray['profile_image_url'] = $image;
        
        $user_type_id = $this->input->post('user_type_id');
        
        return $this->db->update("admin_users", $myarray, array("id" => $user_id));
    }
    
    function checkAccess($view_type, $module, $userid,$usertypeid, $permissions) {
        if (!$this->authentication->check_access($view_type, $module, $userid,$usertypeid, $permissions)) {
            //show_error("You don't have access to whatever you are trying to view", $status_code = 500);
            $this->template->write_view('content', 'admin/noaccess',$this->gen_contents);
            $this->template->render();
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function delete_user($user_id) {
        $this->db->update("admin_users" , array("user_status" => 10), array("id" => $user_id));
    }
    
     
     public function export($start_date, $end_date, $arr_sort,$arr_search=array()) {
        $delimiter = ",";
        $newline = "\r\n";
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('common');
        if(!empty($arr_search["where"])) { 
            $where_search = " AND (first_name  LIKE '%".$arr_search["where"]."%' OR 
                last_name  LIKE '%".$arr_search["where"]."%' OR 
                email  LIKE '%".$arr_search["where"]."%' OR 
                CONCAT_WS(' ', first_name, last_name)  LIKE '%".$arr_search["where"]."%')";
        } else {
            $where_search = "";
        }
        $where_search .= " AND user_status != 10";
		if(is_array($arr_sort) && count($arr_sort) > 0){
                $order = " ORDER BY ".$arr_sort['name'].' '.$arr_sort['value'];
                 
        }else {
                
				$order = " ORDER BY created_at DESC";
        }
        $query = $this->db->query($SQL="SELECT CONCAT_WS(' ', first_name, last_name) as name, `email`, `last_login`, `profile_image_url`, `created_at` as created_on FROM admin_users WHERE created_at >= '$start_date' AND created_at <= '$end_date' $where_search ".$order);
        //echo $SQL;
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);

        $file_path = $this->config->item("site_basepath") . "uploads/csv/";
        $file = "Adminuser-".date("m-d-Y").".csv";
		
		
		
        if (write_file($file_path . $file, $data)) {
            set_time_limit(0);
			output_file($file_path.$file, $file, 'application/csv');
        } else {
            
        }
    }

}                
