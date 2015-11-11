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

class Hosting_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

	public function getAllHostings($arr_condition, $arr_sort, $return_type, $num = 0, $offset = 0, $arr_search=array()){
		if(!empty($arr_condition)){			 
			$this->db->where($arr_condition);
		}
                //Search Factor
                if(!empty($arr_search["search_name"])) {
                    $this->db->where("(first_name  LIKE '%".$arr_search["search_name"]."%' OR 
                        last_name  LIKE '%".$arr_search["search_name"]."%' OR
                        email  LIKE '%".$arr_search["search_name"]."%'  OR
                        CONCAT_WS(' ', first_name, last_name)  LIKE '%".$arr_search["search_name"]."%' OR
                        h.description  LIKE '%".$arr_search["search_name"]."%' OR
                        theme  LIKE '%".$arr_search["search_name"]."%' OR    
                        h.rank  LIKE '%".$arr_search["search_name"]."%'    
                        )", NULL, FALSE); 
                }
                if(!empty($arr_search["search_venue"])) {
                    $this->db->where("venue_id", $arr_search["search_venue"]  );
                }
                if(!empty($arr_search["search_promoter"]) ) {
                    if($arr_search["search_promoter"] == 2) $arr_search["search_promoter"] = 0;
                    $this->db->where("h.is_promoter", $arr_search["search_promoter"]);
                } 
                
                if(!empty($arr_search["search_recurring"]) ) {
                    if($arr_search["search_recurring"] == 2) $arr_search["search_recurring"] = 0;
                    $this->db->where("h.is_recurring", $arr_search["search_recurring"]);
                }
                
                if(!empty($arr_search["search_verified"]) ) {
                    if($arr_search["search_verified"] == 2) $arr_search["search_verified"] = 0;
                    $this->db->where("h.hosting_status", $arr_search["search_verified"]);
                }
                
		$this->db->join('users AS u','u.id = h.user_id', 'left', false);
		$this->db->join('venues AS v','v.id = h.venue_id', 'left', false);
		if('count' == $return_type){
			$this->db->select ("COUNT(h.id) AS count", FALSE);
			$query	= $this->db->get('hostings as h');
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
		 $this->db->select('h.id as hid, u.email,CONCAT_WS(" ", u.first_name, u.last_name) as uname, v.name, h.description as description, h.time, h.created_at as created_at,IF(h.is_recurring = 1, "Yes", "No") as is_recurring,IF(h.hosting_status = 1, "Yes", "No") as hosting_status,h.user_id',false);
		$query	=	$this->db->get('hostings as h');
		return $query->result();
	}
	
    public function search($search) 
    {
        $this->db->select("fb_id, email, first_name as name",false);
        //$this->db->where("user_status != 10 AND (fb_id LIKE '%$search%' OR email LIKE '%$search%' OR first_name LIKE '%$search%' OR last_name LIKE '%$search%')");
        $this->db->where("user_status != 10 AND (email LIKE '%$search%')");
        /*$this->db->like("fb_id", $search);
        $this->db->or_like("email", $search);
        $this->db->or_like("first_name", $search);
        $this->db->or_like("last_name", $search);*/
        $query = $this->db->get("users");
        return $query->result_array();
    }
    
    public function search_user($search) 
    {
        $this->db->select("id",false);
        $this->db->or_where("fb_id", $search);
        $this->db->or_where("email", $search);
        $this->db->or_where("first_name", $search);
        $this->db->or_where("last_name", $search);
        $query = $this->db->get("users");
        $data = $query->row();
        if(!empty($data)) {
            return $data->id;
        } 
    }
    
    public function save_hosting($post_data) 
    {
        $data = $post_data;
                
        $data["created_at"] = date("Y-m-d h:i:s");
        $this->db->set($data);
        return $this->db->insert('hostings'); 
    }
    
    public function getBookedVenue($venue_id, $booked_time) 
    {
        $this->db->select("HOUR(TIMEDIFF('".$booked_time."',time)) AS hours_different", FALSE);
        $this->db->where("venue_id", $venue_id);
        $this->db->where("hosting_status !=", 10);
        $query = $this->db->get("hostings");
        $data = $query->result_array();
        if(!empty($data)) {
            foreach($data as $hours) {
                if($hours['hours_different']<=6)
                    return $hours['hours_different'];
            }
            return -1;
        }
        else {
            return -1;
        }
    }
    
    public function getBookedVenueEdit($venue_id, $booked_time, $hosting_id) 
    {
        $this->db->select("HOUR(TIMEDIFF('".$booked_time."',time)) AS hours_different", FALSE);
        $this->db->where("venue_id", $venue_id);
        $this->db->where("id !=", $hosting_id);
        $query = $this->db->get("hostings");
        $data = $query->result_array();
        if(!empty($data)) {
            foreach($data as $hours) {
                if($hours['hours_different']<=6)
                    return $hours['hours_different'];
            }
            
            return -1;
        }
        else { 
            return -1;
        }
    }
    
    public function getHostings($hosting_id) {
        $query = $this->db->query("SELECT `venue_id`, `user_id`,  `theme`, h.`description`, `date`, `time`, `is_recurring`, 
                        h.`is_promoter`, h.`rank`, h.`updated_ip`, `user_image_url`, `hosting_status` FROM hostings h
                         JOIN venues v ON (h.venue_id = v.id)
                         JOIN users u ON (user_id = u.id)
                         WHERE h.id = '$hosting_id' 
                         ");
        return $query->row();
        
    }
    
    public function updateHosting($field, $data, $hosting) { 

        $field_array = array("user"             => "user_image_url",
                             "userselect"       => "user_id", 
                             "venue"            => "venue_id",
                             "recurring"        => "is_recurring", 
                             "promoter"         => "is_promoter",
                             "hstatus"          => "hosting_status",
                             "host_date"        => "date",
                             "host_description" => "description"
                            );
        if(array_key_exists($field, $field_array)) {
            $field = $field_array[$field];
        } 
        $new_time_updated = "";
        $validate_venue = "";
        $flag= "";
        //Check for venue Availabilty
        if($field == "date" || $field == "time" || $field == "venue_id") {   
            $getVenue = $this->common_model->getDataExists(array("venue_id","time"),"hostings",array("id"=>$hosting));//echo $this->db->last_query();
            if($field == "date")  {
                //$new_time_updated = date("Y-m-d",strtotime($data))." ".date("H:i:s",strtotime($getVenue->time));
                $new_time_updated = $data;
                $field  = "time";
                $data = $new_time_updated;
                $flag = "D";
            }
            else if($field == "time")  {
                //$new_time_updated = date("Y-m-d",strtotime($getVenue->time))." ".$data;
                $new_time_updated = $data;
                $field  = "time";
                $data = $new_time_updated;
                $flag = "T";
            } elseif($field == "venue_id") {
                $new_time_updated = $getVenue->time;
                $getVenue->venue_id = $data;
                
            }
            
            //$validate_venue = $this->getBookedVenueEdit($getVenue->venue_id, $new_time_updated, $hosting);
                       
            /*if($validate_venue >= 0){  // echo "111";
                return "D";
            }*/
        }
        
        $update_field = array($field => $data);
        if( $this->db->update("hostings", $update_field, array("id"=>$hosting))) { //echo $this->db->last_query();;
            if($field == "venue_id") {
                $venue =  $this->common_model->getDataExists(array("name"),"venues",array("id"=>$data));
                return $venue->name;
            }
            else if($field == "user_id") {
                $user =  $this->common_model->getDataExists(array("CONCAT_WS(' ', first_name, last_name) as name"),"users",array("id"=>$data));
                return $user->name;
            } else if($flag == "T") {
                return date("H:i:s", strtotime($data));
            } else if($flag == "D") {
                return date("m/d/Y", strtotime($data));
            } else if($field == "is_recurring" || $field == "is_promoter" || $field == "hosting_status") {
                return $data == 1 ? "Yes ":"No ";
            }
            else {
                return $data;
            }
               
        }
    }
    
    public function delete_hosting($hosting_id) {
        //return $this->db->delete("hostings", array("id"=>$hosting_id));
         return $this->db->update("hostings", array("hosting_status" => "10"), array("id"=>$hosting_id));
    }
    
    public function getChatCount($hosting_id) {
        $query = $this->db->query("SELECT count(*) as num_chats FROM hosting_channel WHERE hosting_id = '$hosting_id'
                                   GROUP BY  hosting_id");
        return $query->row();
    }
    
    public function getChatsByHosting($hosting_id) {
        $result = array();
        $getChannelquery = $this->db->query("SELECT channel_id FROM `hosting_channel` WHERE hosting_id = '$hosting_id'");
        
        foreach($getChannelquery->result_array() as $channel){
            $channelId = $channel['channel_id'];
            $query = $this->db->query("SELECT a.channel_id , a.user_id AS user1, b.user_id AS user2,a.is_host AS host1,b.is_host 
                    AS host2,CONCAT_WS(' ', u1.first_name, u1.last_name) as name1,
                    CONCAT_WS(' ', u2.first_name, u2.last_name) as name2,
                    a.created_at FROM `chat_participants` as a LEFT JOIN users as u1 ON a.user_id=u1.id,
                    `chat_participants` as b LEFT JOIN users as u2 ON b.user_id=u2.id where a.channel_id = b.channel_id
                    AND a.user_id !=b.user_id and a.channel_id ='$channelId' group by channel_id");
            foreach ($query->result_array() as $res){
                $result[$channelId]['channel_id'] = $res['channel_id'];
                $result[$channelId]['user1'] = $res['user1'];
                $result[$channelId]['user2'] = $res['user2'];
                $result[$channelId]['host1'] = $res['host1'];
                $result[$channelId]['host2'] = $res['host2'];
                $result[$channelId]['name1'] = $res['name1'];
                $result[$channelId]['name2'] = $res['name2'];
                $result[$channelId]['created_date'] = $res['created_at'];
            }
        }
        
	return $result;
    }
        
    public function getHostingCounts(){
        $hostingTotal = array();
        $date = date("Y-m-d");
        $qry_condn = "created_at >= '$date 00:00:00' and created_at<='$date 23:59:00'";       
        $hostingTotal['hosting_today'] = $this->getHostCntByDate( $qry_condn);
        
        $ydate = date("Y-m-d",strtotime("-1 days"));
        $qry_condn = "created_at >= '$ydate 00:00:00' and created_at <='$ydate 23:59:00'";        
        $hostingTotal['hosting_yesterday'] = $this->getHostCntByDate( $qry_condn);
        
        $qry_condn = "created_at >= DATE_SUB( NOW( ) , INTERVAL 1 WEEK)";      
        $hostingTotal['hosting_week'] = $this->getHostCntByDate( $qry_condn);
        
        $prev_date = date("Y-m-d H:i:s",strtotime("-7 days"));
        $qry_condn = "created_at >= DATE_SUB( '$prev_date' , INTERVAL 1 WEEK) and created_at <= '$prev_date'"; 
        $hostingTotal['hosting_prev_week'] = $this->getHostCntByDate( $qry_condn);
        
        $qry_condn = "created_at >= DATE_SUB( NOW( ) , INTERVAL 1 MONTH)";        
        $hostingTotal['hosting_month'] = $this->getHostCntByDate( $qry_condn);  
        
        $prev_month_date = date("Y-m-d H:i:s",strtotime("-30 days"));
        $qry_condn = "created_at >= DATE_SUB( '$prev_month_date' , INTERVAL 1 MONTH) and created_at <= '$prev_month_date'"; 
        $hostingTotal['hosting_prev_month'] = $this->getHostCntByDate( $qry_condn); 
         
        $hostingTotal['hosting_total'] = $this->getHostCount();
        return $hostingTotal;
    }
    
    public function getHostCntByDate( $qry_condn){
        $result = 0;
        $query	= $this->db->query("Select count(id) as host_cnt from hostings where $qry_condn and is_recurring!=1 and hosting_status=1");       
        foreach ($query->result_array() as $count){
            $result = $count['host_cnt'];
        }
        
	return $result;
    }
    
    public function getHostCount(){
        $query	= $this->db->query("Select count(id) as host_cnt from hostings where is_recurring!=1");
        foreach ($query->result_array() as $count){
            $result = $count['host_cnt'];
        }
        
	return $result;
    }
    
    public function export($start_date, $end_date, $where="", $hosting_venue, $hosting_promoter, $hosting_recurring) { 
        $delimiter = ",";
        $newline = "\r\n";
        $this->load->dbutil();
        $this->load->helper('file');
        //Search Factor
        if(!empty($where)) {
            $where_search =  "  AND (first_name  LIKE '%".$where."%' OR 
                        last_name  LIKE '%".$where."%' 
                        OR email  LIKE '%".$where."%'  OR
                        CONCAT_WS(' ', first_name, last_name)  LIKE '%".$where."%')";
        } else {
            $where_search = "";
        }
        
                
        if(!empty($hosting_venue)) {
            $where_search .= " AND venue_id = $hosting_venue";
        }
        if(!empty($hosting_promoter) ) {
            if($hosting_promoter == 2) $hosting_promoter = 0;
                $where_search .= " AND is_promoter = $hosting_promoter";
        } 

        if(!empty($hosting_recurring) ) {
            if($hosting_recurring == 2) $hosting_recurring = 0;
                $where_search .= " AND is_recurring = $hosting_recurring";
        }
        
        $where_search .= " AND hosting_status != 10";
        
        $query = $this->db->query($SQL='SELECT h.id as hid, u.email,CONCAT_WS(" ", u.first_name, u.last_name) as uname, v.name, h.description as description, h.time, h.created_at as created_at,IF(h.is_recurring = 1, "Yes", "No") as is_recurring,IF(h.hosting_status = 1, "Yes", "No") as hosting_status FROM hostings h 
                LEFT JOIN users u ON (u.id = h.user_id)
                LEFT JOIN venues v ON (v.id = h.venue_id)
                WHERE h.created_at >= "'.$start_date.'" AND h.created_at <= "'.$end_date.'"'. $where_search);
       // echo $SQL;
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
}    