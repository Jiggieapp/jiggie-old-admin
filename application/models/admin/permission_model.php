<?php

class Permission_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    
    public function get_all_module() { 
        $query = $this->db->get("module");
        return $query->result_array();
    }
    
    public function get_module_byids($module_id) { 
        $this->db->where_in('module_id', $module_id);
        $query = $this->db->get("module");
        return $query->result_array();
    }
    
    public function get_single_user_permission($userid,$moduleid,$user_type_id) { 
        $this->db->where('module_id',$moduleid);
        $this->db->where('user_id',$userid);
        $this->db->where('user_type_id',$user_type_id);
        $query = $this->db->get("user_permission");
        return $query->row_array();
    }
    
    public function get_all_usertype() { 
        $query = $this->db->get("user_type");
        return $query->result_array();
    }
    
    public function get_usertype_permission($user_type_ids) { 
        $this->db->where_in('user_type_id', $user_type_ids);
        $query = $this->db->get("user_type_permission");
        return $query->result_array();
    }
    
    public function get_all_permission() { 
        $query = $this->db->get("permission");
        return $query->result_array();
    }
    
    public function get_single_permission($permission_id) {
        $query = $this->db->get_where("premission", array("permission_id" => $permission_id, "status_id" => 1));
        return $query->row_array();
    }

    public function insert_user_permission($fields) {
        $this->db->set('created_date', 'NOW()', FALSE);
        $this->db->insert('user_permission', $fields);
        return $this->db->insert_id();
    }

    public function update_user_permission($fields,$user_permission_id) {
        $this->db->where('user_permission_id', $user_permission_id);
        $this->db->update('user_permission', $fields);
        return TRUE;
    }
    
    public function get_user_permissions($user_id,$user_type_id) {
        $this->db->select('m.module_id,m.module,m.permission_value,m.sub_of,
                    up.user_permission_id,up.user_id,up.user_permission_id,up.user_type_id,up.user_permission')
                ->from('module m')
                ->join('user_permission up', 'up.module_id = m.module_id', 'left')
                ->where('up.user_id', $user_id)
                ->where('up.user_type_id', $user_type_id)
                ->where('up.status_id', 1);
        $query = $this->db->get();
        //print $this->db->last_query();
        return $query->result_array();
    }
    
    public function get_user_module_permissions($user_id,$user_type_id,$module_id) {
        $this->db->select('m.module_id,m.module,m.permission_value,
                    up.user_permission_id,up.user_id,up.user_permission_id,up.user_type_id,up.user_permission')
                ->from('module m')
                ->join('user_permission up', 'up.module_id = m.module_id', 'left')
                ->where('up.user_id', $user_id)
                ->where('up.user_type_id', $user_type_id)
                ->where('m.module_id', $module_id)
                ->where('up.status_id', 1);
        $query = $this->db->get();
        //print $this->db->last_query();
        return $query->row_array();
    }
    
    public function delete_user_permission($user_permission_id,$user_id,$user_type_id){
        if ( ! $user_permission_id || ! $user_id || ! $user_type_id )return false;
        $this->db->where('user_permission_id', $user_permission_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_type_id', $user_type_id);
        $this->db->delete('user_permission'); 
    }
    
     public function default_fields_edit() {
        $this->load->library('session');
        if ($this->uri->segment(1) == FLEETADMIN_DIR) {
            return $myarray = array("updated_by" => $this->session->userdata("ES_ESTABLISHMENTID"),
                "updated_by_type" => $this->session->userdata("ES_USERTYPE"));
        } elseif ($this->uri->segment(1) == ADMIN_DIR) {
            return $myarray = array("updated_by" => $this->session->userdata("AD_USERID"),
                "updated_by_type" => $this->session->userdata("AD_USERTYPE"));
        }
    }
    
    public function default_fields_create() {
        $this->load->library('session');
        /*if ($this->uri->segment(1) == FLEETADMIN_DIR) {
            $created = $this->session->userdata("ES_ESTABLISHMENTID");
            $created_by_type = $this->session->userdata("ES_USERTYPE");
        } elseif ($this->uri->segment(1) == ADMIN_DIR) {
            $created = $this->session->userdata("AD_USERID");
            $created_by_type = $this->session->userdata("AD_USERTYPE");
        }
        
        return $myarray = array("status_id" => 1,
            "created_by" => $created,
            "created_by_type" => $created_by_type
        );*/
        return $myarray = array();
    }
}

?>