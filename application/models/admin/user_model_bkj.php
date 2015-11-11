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
                    $this->db->where("(u.first_name  LIKE '%".$arr_search["where"]."%' OR 
                        u.last_name  LIKE '%".$arr_search["where"]."%' OR 
                        u.email  LIKE '%".$arr_search["where"]."%' OR 
                        CONCAT_WS(' ', u.first_name, u.last_name)  LIKE '%".$arr_search["where"]."%' OR
                        u.location  LIKE '%".$arr_search["where"]."%' OR 
                        u.about  LIKE '%".$arr_search["where"]."%' OR
                        u.gender  LIKE '".$arr_search["where"]."' OR
                        TIMESTAMPDIFF(YEAR, u.birthday, CURDATE()) =  '".$arr_search["where"]."' OR 
                        u.phone  LIKE '%".$arr_search["where"]."%' OR 
                        u.fb_id  LIKE '%".$arr_search["where"]."%' OR
                        u.invited_by  LIKE '%".$arr_search["where"]."%' OR
                        u.tagline  LIKE '%".$arr_search["where"]."%'    
                        )", NULL, FALSE); 
                }
		if('count' == $return_type){
			$this->db->select ("COUNT(id) AS count", FALSE);
			$query	= $this->db->get('users u');
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
			$this->db->order_by('u.name', 'ASC');
		}
		
		if($num > 0){
			$this->db->limit($num, $offset);
		}
		 $this->db->join('users U1','U1.id = u.invited_by', 'LEFT', false);
		 $this->db->select("u.id ,CONCAT_WS(' ', u.first_name, u.last_name) as name, CONCAT_WS(' ', U1.first_name, U1.last_name) as inviteuser, u.profile_image_url , u.profile_image_url_2, u.profile_image_url_3, u.profile_image_url_4, u.profile_image_url_5, u.gender , IF(TIMESTAMPDIFF(YEAR, u.birthday, CURDATE()),  TIMESTAMPDIFF(YEAR, u.birthday, CURDATE()) ,  0 ) as age,
                                    
                                   u.location ,  u.invited_by , u.created_at , IF(u.verified = 1,  'Yes' ,  'No' ) as verified, 
                                   (CASE WHEN u.user_status ='0' THEN 'New'
                                   WHEN u.user_status ='1' THEN 'Active'
                                   ELSE 'Suspended' END) as user_status,  u.cnt_hosting ,  u.cnt_chat,  u.cnt_invite,
                                   (SELECT COUNT(hs.id) AS other FROM hostings AS hs where hs.user_id = u.id  and hs.hosting_status !=10 AND hs.created_at < NOW() ) AS num_host,
                                   (SELECT COUNT(t.id) AS other FROM conversation_topics AS t where t.guest_id = u.id OR   t.host_id= u.id) AS num_chat,
                                   ", FALSE);
		$query	=	$this->db->get('users as u');
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
            "updated_at" => date("Y-m-d H:i:s")
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
        return $this->db->update("users", array("gender" => $data, "updated_at" => date("Y-m-d H:i:s")), array("id" => $user));
    }

    public function updateBirthday($birthday, $user) {
        $birthday = date("Y-m-d", strtotime($birthday));
        return $this->db->update("users", array("birthday" => $birthday, "updated_at" => date("Y-m-d H:i:s")), array("id" => $user));
    }

    public function verifyhost($verify, $user) {
        return $this->db->update("users", array("verified" => $verify, "updated_at" => date("Y-m-d H:i:s")), array("id" => $user));
    }

    public function suspend_user($verify, $user) {
        return $this->db->update("users", array("user_status" => $verify, "updated_at" => date("Y-m-d H:i:s")), array("id" => $user));
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

        $data["created_at"] = date("Y-m-d H:i:s");
        $data["login_last_dt"] = date("Y-m-d H:i:s");
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
		$this->load->helper('common');
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
		$file = "User-".date("m-d-Y").".csv";
        if (write_file($file_path . $file, $data)) {
           set_time_limit(0);
			output_file($file_path.$file, $file, 'application/csv');
        } else {
            
        }
    }
    
    public function getUserCounts($start,$end){
        $userTotal = array();
        $date = date("Y-m-d");
        $qry_condn = "created_at >= '$date 00:00:00' and created_at<='$date 23:59:00'";        
        $userTotal['user_today'] = $this->getUserCntByDate( $qry_condn);
        
        $ydate = date("Y-m-d",strtotime("-1 days"));
        $qry_condn = "created_at >= '$ydate 00:00:00' and created_at<='$ydate 23:59:00'";        
        $userTotal['user_yesterday'] = $this->getUserCntByDate( $qry_condn);
        
        $sevendate = date("Y-m-d",strtotime("-7 days"));
        $qry_condn = "created_at >= '$sevendate 00:00:00' and created_at<='$ydate 23:59:00'";      
        $userTotal['user_week'] = $this->getUserCntByDate( $qry_condn);
        
        $eight_date = date("Y-m-d",strtotime("-8 days"));
		$forteen_date = date("Y-m-d",strtotime("-14 days"));         
        $qry_condn = "created_at >= '$forteen_date 00:00:00' and created_at<='$eight_date 23:59:00'";  
        $userTotal['user_prev_week'] = $this->getUserCntByDate( $qry_condn);
        
        $thirty_date = date("Y-m-d",strtotime("-30 days"));		
        $qry_condn = "created_at >= '$thirty_date 00:00:00' and created_at <='$ydate 23:59:00'";         
        $userTotal['user_month'] = $this->getUserCntByDate( $qry_condn);  
        
        $thirtyone_date = date("Y-m-d",strtotime("-31 days"));
		$sixty_date = date("Y-m-d",strtotime("-60 days"));
        $qry_condn = "created_at >= '$sixty_date 00:00:00' and created_at <='$thirtyone_date 23:59:00'";
        $userTotal['user_prev_month'] = $this->getUserCntByDate( $qry_condn); 
        $qry_condn = "created_at >= '$start' and created_at<='$end'";        
        $userTotal['user_new'] = $this->getUserCntByDate( $qry_condn);        
        $userTotal['user_total'] = $this->getUserCount();
        return $userTotal;
    }
    
    public function getUserCntByDate( $qry_condn){
        $result = 0;
        $query	= $this->db->query("Select count(id) as user_cnt from users where $qry_condn AND user_status != 10");       
        foreach ($query->result_array() as $count){
            $result = $count['user_cnt'];
        }
		return $result;
    }
    
    public function getUserCount(){
        $query	= $this->db->query("Select count(id) as user_cnt from users WHERE user_status != 10");
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
	
	public function getReturningUsersByDay($start,$end){
		 $query	= $this->db->query("SELECT date(login_last_dt) as day,count(id) as ct FROM `users` WHERE login_last_dt BETWEEN '$start' AND '$end' 
		 AND user_status  =1  AND  date(created_at) < date(login_last_dt) GROUP BY date(login_last_dt) ORDER BY date(login_last_dt) ASC ");
         $result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['day']] =$count['ct'];
         }	 
		 return $result;
	}
	
	public function getReturningUsersByWeek($start,$end){
		 $query	= $this->db->query("SELECT DATE(`users`.`login_last_dt`) AS `date`, COUNT(`users`.`id`) AS `count`, 
		 	 YEARWEEK(`users`.`login_last_dt`,1) as week
		 	FROM `users` WHERE `users`.`login_last_dt` 
		 	BETWEEN  '$start' AND '$end' AND user_status  =1 AND date(created_at) < date(login_last_dt) GROUP BY week ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['week']] =$count['count'];
         }	 
		 return $result;
	}
	public function getReturningUsersByMonth($start,$end){
		 $query	= $this->db->query("SELECT  DATE(`users`.`login_last_dt`) AS `date`,COUNT(`users`.`id`) AS `count`, 
		 	EXTRACT(YEAR_MONTH FROM `users`.`login_last_dt`) as month
		 	FROM `users` WHERE `users`.`login_last_dt` 
		 	BETWEEN  '$start' AND '$end' AND user_status  =1 AND date(created_at) < date(login_last_dt)  GROUP BY month ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['month']] =$count['count'];
         }	 
		 return $result;
	}
	public function getUsersByDay($start,$end){
		 $query	= $this->db->query("SELECT date(created_at) as day,count(id) as ct FROM `users` WHERE created_at BETWEEN '$start' AND '$end' AND user_status  =1 GROUP BY date(created_at) ORDER BY date(created_at) ASC ");
         $result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['day']] =$count['ct'];
         }	 
		 return $result;
	}
	public function getUsersByWeek($start,$end){
		 $query	= $this->db->query("SELECT DATE(`users`.`created_at`) AS `date`, COUNT(`users`.`id`) AS `count`, 
		 	 YEARWEEK(`users`.`created_at`,1) as week
		 	FROM `users` WHERE `users`.`created_at` 
		 	BETWEEN  '$start' AND '$end' AND user_status  =1  GROUP BY week ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['week']] =$count['count'];
         }	 
		 return $result;
	}
	public function getUsersByMonth($start,$end){
		 $query	= $this->db->query("SELECT  DATE(`users`.`created_at`) AS `date`,COUNT(`users`.`id`) AS `count`, 
		 	EXTRACT(YEAR_MONTH FROM `users`.`created_at`) as month
		 	FROM `users` WHERE `users`.`created_at` 
		 	BETWEEN  '$start' AND '$end' AND user_status  =1  GROUP BY month ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['month']] =$count['count'];
         }	 
		 return $result;
	}
	public function getNewRecurringHostingByDay($start,$end){
		 $query	= $this->db->query("SELECT date(h.created_at) as day,count(h.id) as ct FROM  hostings h LEFT JOIN `users` AS u ON `u`.`id` = `h`.`user_id` LEFT JOIN `venues` AS v ON `v`.`id` = `h`.`venue_id`
		 WHERE h.created_at BETWEEN  '$start' AND '$end' AND h.hosting_status  !=10 AND h.is_recurring=1 AND h.parent_id=0  AND `u`.`user_status` = 1 AND `v`.`venue_status` = 1   GROUP BY date(h.created_at) 
		 ORDER BY date(h.created_at) ASC ");
         $result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['day']] =$count['ct'];
         }	 
		 return $result;
	}
    public function getNewRecurringHostingByWeek($start,$end){
		 $query	= $this->db->query("SELECT DATE(h.created_at) AS `date`, COUNT(h.id) AS `count`, 
		 	 YEARWEEK(`h`.`created_at`,1) as week 
		 	FROM hostings h LEFT JOIN `users` AS u ON `u`.`id` = `h`.`user_id` LEFT JOIN `venues` AS v ON `v`.`id` = `h`.`venue_id` WHERE `h`.`created_at` 
		 	BETWEEN  '$start' AND '$end' AND h.hosting_status  !=10 AND h.is_recurring=1 AND h.parent_id=0  AND `u`.`user_status` = 1 AND `v`.`venue_status` = 1 GROUP BY week ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['week']] =$count['count'];
         }	 
		 return $result;
	}
	public function getNewRecurringHostingByMonth($start,$end){
		 $query	= $this->db->query("SELECT  DATE(h.`created_at`) AS `date`,COUNT(h.`id`) AS `count`, 
		 	EXTRACT(YEAR_MONTH FROM `h`.`created_at`) as month
		 	FROM hostings h LEFT JOIN `users` AS u ON `u`.`id` = `h`.`user_id` LEFT JOIN `venues` AS v ON `v`.`id` = `h`.`venue_id` WHERE `h`.`created_at` 
		 	BETWEEN  '$start' AND '$end' AND h.hosting_status !=10 AND h.is_recurring=1 AND h.parent_id=0  AND `u`.`user_status` = 1 AND `v`.`venue_status` = 1 GROUP BY month ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['month']] =$count['count'];
         }	 
		 return $result;
	}
		public function getNewNonRecurringHostingByDay($start,$end){
		 $query	= $this->db->query("SELECT date(h.created_at) as day,count(h.id) as ct FROM hostings h LEFT JOIN `users` AS u ON `u`.`id` = `h`.`user_id` LEFT JOIN `venues` AS v ON `v`.`id` = `h`.`venue_id`
		 WHERE h.created_at BETWEEN  '$start' AND '$end' AND h.hosting_status  !=10 AND h.is_recurring=0   AND `u`.`user_status` = 1 AND `v`.`venue_status` = 1  GROUP BY date(h.created_at) 
		 ORDER BY date(h.created_at) ASC ");
         $result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['day']] =$count['ct'];
         }	 
		 return $result;
	}
    public function getNewNonRecurringHostingByWeek($start,$end){
		 $query	= $this->db->query("SELECT DATE( h.created_at ) AS `date`, COUNT(h.id) AS `count`, 
		 	 YEARWEEK(`h`.`created_at`,1) as week 
		 	FROM  hostings h LEFT JOIN `users` AS u ON `u`.`id` = `h`.`user_id` LEFT JOIN `venues` AS v ON `v`.`id` = `h`.`venue_id` WHERE `h`.`created_at` 
		 	BETWEEN  '$start' AND '$end' AND h.hosting_status  !=10 AND h.is_recurring=0  AND `u`.`user_status` = 1 AND `v`.`venue_status` = 1 GROUP BY week ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['week']] =$count['count'];
         }	 
		 return $result;
	}
	public function getNewNonRecurringHostingByMonth($start,$end){
		 $query	= $this->db->query("SELECT  DATE(h.created_at ) AS `date`,COUNT(h.id ) AS `count`, 
		 	EXTRACT(YEAR_MONTH FROM `h`.`created_at`) as month
		 	FROM hostings h LEFT JOIN `users` AS u ON `u`.`id` = `h`.`user_id` LEFT JOIN `venues` AS v ON `v`.`id` = `h`.`venue_id` WHERE `h`.`created_at` 
		 	BETWEEN  '$start' AND '$end' AND h.hosting_status  !=10 AND  h.is_recurring=0  AND `u`.`user_status` = 1 AND `v`.`venue_status` = 1 GROUP BY month ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['month']] =$count['count'];
         }	 
		 return $result;
	}
	public function getActiveHostingByDay($start,$end){
		 $query	= $this->db->query("SELECT date(h.created_at) as day,count(h.id) as ct FROM  hostings h LEFT JOIN `users` AS u ON `u`.`id` = `h`.`user_id` LEFT JOIN `venues` AS v ON `v`.`id` = `h`.`venue_id`
		 WHERE h.created_at BETWEEN '$start' AND '$end'  AND h.hosting_status !=10  AND `u`.`user_status` = 1 AND `v`.`venue_status` = 1 GROUP BY date(h.created_at) 
		 ORDER BY date(h.created_at) ASC ");
         $result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['day']] =$count['ct'];
         }	 
		 return $result;
	}
    public function getActiveHostingByWeek($start,$end){
		 $query	= $this->db->query("SELECT DATE(h.created_at ) AS `date`, COUNT(h.id ) AS `count`, 
		 	 YEARWEEK(`h`.`created_at`,1) as week   
		 	FROM hostings h LEFT JOIN `users` AS u ON `u`.`id` = `h`.`user_id` LEFT JOIN `venues` AS v ON `v`.`id` = `h`.`venue_id` WHERE `h`.`created_at` 
		 	BETWEEN '$start' AND '$end'  AND h.hosting_status  !=10  AND `u`.`user_status` = 1 AND `v`.`venue_status` = 1  GROUP BY week ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['week']] =$count['count'];
         }	 
		 return $result;
	}
	public function getActiveHostingByMonth($start,$end){
		 $query	= $this->db->query("SELECT  DATE(h.created_at ) AS `date`,COUNT(h.id ) AS `count`, 
		 	EXTRACT(YEAR_MONTH FROM `h`.`created_at`) as month
		 	FROM  hostings h LEFT JOIN `users` AS u ON `u`.`id` = `h`.`user_id` LEFT JOIN `venues` AS v ON `v`.`id` = `h`.`venue_id` WHERE `h`.`created_at` 
		 	BETWEEN '$start' AND '$end'  AND h.hosting_status  !=10  AND `u`.`user_status` = 1 AND `v`.`venue_status` = 1  GROUP BY month ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['month']] =$count['count'];
         }	 
		 return $result;
	}	
	public function getNewChatByDay($start,$end){
		 $query	= $this->db->query("
		 SELECT date(t.created_at) as day,count(t.id) as ct FROM conversation_topics t WHERE host_id IN 
		 	(SELECT to_id FROM conversations WHERE t.id=topic_id AND date(t.created_at)= date(created_at) AND t.hosting_id = hosting_id) 
		 AND host_id IN 
		 	(SELECT from_id FROM conversations WHERE t.id=topic_id AND date(t.created_at)= date(created_at) AND t.hosting_id = hosting_id) 
		 	AND t.created_at BETWEEN '$start' AND '$end'
		 	GROUP BY day
		    ORDER BY date(t.created_at) ASC ");		
         $result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['day']] =$count['ct'];
         }	 
		 return $result;
	}
	public function getNewChatByWeek($start,$end){
		 $query	= $this->db->query("SELECT DATE(`t`.`created_at`) AS `date`, COUNT(`t`.`id`) AS `count`, 
		 	 YEARWEEK(`t`.`created_at`,1) as week
		 	FROM conversation_topics t WHERE 
		 	host_id IN 
			 	(SELECT to_id FROM conversations WHERE t.id=topic_id AND date(t.created_at)= date(created_at) AND t.hosting_id = hosting_id) 
			AND host_id IN 
			 	(SELECT from_id FROM conversations WHERE t.id=topic_id AND date(t.created_at)= date(created_at) AND t.hosting_id = hosting_id)
		 	AND `t`.`created_at` 
		 	BETWEEN '$start' AND '$end'  GROUP BY week ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['week']] =$count['count'];
         }	 
		 return $result;
	}
	public function getNewChatByMonth($start,$end){
		 $query	= $this->db->query("SELECT  DATE(`t`.`created_at`) AS `date`,COUNT(`t`.`id`) AS `count`, 
		 	EXTRACT(YEAR_MONTH FROM `t`.`created_at`) as month
		 	FROM conversation_topics t  WHERE 
		 	host_id IN 
			 	(SELECT to_id FROM conversations WHERE t.id=topic_id AND date(t.created_at)= date(created_at) AND t.hosting_id = hosting_id) 
			AND host_id IN 
			 	(SELECT from_id FROM conversations WHERE t.id=topic_id AND date(t.created_at)= date(created_at) AND t.hosting_id = hosting_id)
		 	AND `t`.`created_at` 
		 	BETWEEN '$start' AND '$end'   GROUP BY month ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['month']] =$count['count'];
         }	 
		 return $result;
	}
	public function getUpdatedChatByDay($start,$end){
		 $query	= $this->db->query("SELECT date(t.updated_at) as day,count(t.id) as ct FROM conversation_topics t  WHERE
		   host_id IN 
			 	(SELECT to_id FROM conversations WHERE t.id=topic_id AND date(t.updated_at)= date(created_at) AND t.hosting_id = hosting_id) 
			AND host_id IN 
			 	(SELECT from_id FROM conversations WHERE t.id=topic_id AND date(t.updated_at)= date(created_at) AND t.hosting_id = hosting_id)		 
		 AND t.updated_at BETWEEN '$start' AND '$end' AND  ABS(TIMESTAMPDIFF(DAY,updated_at,created_at))>=1  GROUP BY date(updated_at) 
		 ORDER BY date(updated_at) ASC ");
         $result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['day']] =$count['ct'];
         }	 
		 return $result;
	}
	public function getUpdatedChatByWeek($start,$end){
		 $query	= $this->db->query("SELECT DATE(`t`.`updated_at`) AS `date`, COUNT(`t`.`id`) AS `count`, 
		 	 YEARWEEK(`t`.`updated_at`,1) as week
		 	FROM  conversation_topics t WHERE 
		 	host_id IN 
			 	(SELECT to_id FROM conversations WHERE t.id=topic_id AND date(t.updated_at)= date(created_at) AND t.hosting_id = hosting_id) 
			AND host_id IN 
			 	(SELECT from_id FROM conversations WHERE t.id=topic_id AND date(t.updated_at)= date(created_at) AND t.hosting_id = hosting_id)		 
		 	AND `t`.`updated_at` 	BETWEEN '$start' AND '$end'  AND ABS(TIMESTAMPDIFF(DAY,updated_at,created_at))>=1 GROUP BY week ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['week']] =$count['count'];
         }	 
		 return $result;
	}
	public function getUpdatedChatByMonth($start,$end){
		 $query	= $this->db->query("SELECT  DATE(`t`.`updated_at`) AS `date`,COUNT(`t`.`id`) AS `count`, 
		 	EXTRACT(YEAR_MONTH FROM `t`.`updated_at`) as month
		 	FROM conversation_topics t  WHERE 
		 	host_id IN 
			 	(SELECT to_id FROM conversations WHERE t.id=topic_id AND date(t.updated_at)= date(created_at) AND t.hosting_id = hosting_id) 
			AND host_id IN 
			 	(SELECT from_id FROM conversations WHERE t.id=topic_id AND date(t.updated_at)= date(created_at) AND t.hosting_id = hosting_id)		 
		 	AND `t`.`updated_at` 
		 	BETWEEN '$start' AND '$end' AND  ABS(TIMESTAMPDIFF(DAY,updated_at,created_at))>=1  GROUP BY month ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['month']] =$count['count'];
         }	 
		 return $result;
	}
	public function getTotalChatByDay($start,$end){
		$query	= $this->db->query("SELECT day , sum(ct) as ct FROM((SELECT date(t.created_at) as day,count(t.id) as ct FROM conversation_topics t WHERE host_id IN 
		 	(SELECT to_id FROM conversations WHERE t.id=topic_id AND date(t.created_at)= date(created_at) AND t.hosting_id = hosting_id) 
		 	AND host_id IN 
		 	(SELECT from_id FROM conversations WHERE t.id=topic_id AND date(t.created_at)= date(created_at) AND t.hosting_id = hosting_id) 
		 	AND t.created_at BETWEEN '$start' AND '$end'
		 	GROUP BY day
		    ORDER BY date(t.created_at) ASC)
		 UNION 
		 (SELECT date(updated_at) as day,count(id) as ct FROM conversation_topics t  WHERE
		   host_id IN 
			 	(SELECT to_id FROM conversations WHERE t.id=topic_id AND date(t.updated_at)= date(created_at) AND t.hosting_id = hosting_id) 
			AND host_id IN 
			 	(SELECT from_id FROM conversations WHERE t.id=topic_id AND date(t.updated_at)= date(created_at) AND t.hosting_id = hosting_id)		 
		 AND t.updated_at BETWEEN '$start' AND '$end' AND  ABS(TIMESTAMPDIFF(DAY,updated_at,created_at))>=1  GROUP BY date(updated_at) 
		 ORDER BY date(updated_at) ASC)) AS baseview GROUP BY day");
         $result =array();         
         foreach ($query->result_array() as $count){
         	$result[$count['day']] =$count['ct'];
         }	 
		 return $result;
		
	}
	public function getTotalChatByWeek($start,$end){
		 $query	= $this->db->query("
		 	SELECT week , sum(count) as count FROM((SELECT DATE(t.created_at) AS `date`, COUNT(t.id) AS `count`, 
		 	 YEARWEEK(`t`.`created_at`,1) as week
		 	FROM conversation_topics t WHERE 
		 	host_id IN 
			 	(SELECT to_id FROM conversations WHERE t.id=topic_id AND date(t.created_at)= date(created_at) AND t.hosting_id = hosting_id) 
			AND host_id IN 
			 	(SELECT from_id FROM conversations WHERE t.id=topic_id AND date(t.created_at)= date(created_at) AND t.hosting_id = hosting_id)
		 	AND `t`.`created_at` 
		 	BETWEEN '$start' AND '$end'  GROUP BY week ORDER BY `date`)
		 	UNION
			(SELECT DATE(`t`.`updated_at`) AS `date`, COUNT(`t`.`id`) AS `count`, 
		 	 YEARWEEK(`t`.`updated_at`,1) as week
		 	FROM  conversation_topics t WHERE 
		 	host_id IN 
			 	(SELECT to_id FROM conversations WHERE t.id=topic_id AND date(t.updated_at)= date(created_at) AND t.hosting_id = hosting_id) 
			AND host_id IN 
			 	(SELECT from_id FROM conversations WHERE t.id=topic_id AND date(t.updated_at)= date(created_at) AND t.hosting_id = hosting_id)		 
		 	AND `t`.`updated_at` 	BETWEEN '$start' AND '$end'  AND ABS(TIMESTAMPDIFF(DAY,updated_at,created_at))>=1 GROUP BY week ORDER BY `date`)) AS baseview GROUP BY week
		 	
		 	");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['week']] =$count['count'];
         }	 
		 return $result;	
		
	}
	public function getTotalChatByMonth($start,$end){
		$query	= $this->db->query("
			SELECT month , sum(count) as count FROM((SELECT  DATE(`t`.`created_at`) AS `date`,COUNT(`t`.`id`) AS `count`, 
		 	EXTRACT(YEAR_MONTH FROM `t`.`created_at`) as month
		 	FROM conversation_topics t  WHERE 
		 	host_id IN 
			 	(SELECT to_id FROM conversations WHERE t.id=topic_id AND date(t.created_at)= date(created_at) AND t.hosting_id = hosting_id) 
			AND host_id IN 
			 	(SELECT from_id FROM conversations WHERE t.id=topic_id AND date(t.created_at)= date(created_at) AND t.hosting_id = hosting_id)
		 	AND `t`.`created_at` 
		 	BETWEEN '$start' AND '$end'   GROUP BY month ORDER BY `date`)
		 	UNION
			(SELECT  DATE(`t`.`updated_at`) AS `date`,COUNT(`t`.`id`) AS `count`, 
		 	EXTRACT(YEAR_MONTH FROM `t`.`updated_at`) as month
		 	FROM conversation_topics t  WHERE 
		 	host_id IN 
			 	(SELECT to_id FROM conversations WHERE t.id=topic_id AND date(t.updated_at)= date(created_at) AND t.hosting_id = hosting_id) 
			AND host_id IN 
			 	(SELECT from_id FROM conversations WHERE t.id=topic_id AND date(t.updated_at)= date(created_at) AND t.hosting_id = hosting_id)		 
		 	AND `t`.`updated_at` 
		 	BETWEEN '$start' AND '$end' AND  ABS(TIMESTAMPDIFF(DAY,updated_at,created_at))>=1  GROUP BY month ORDER BY `date`))AS baseview GROUP BY month
		 	");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['month']] =$count['count'];
         }	 
		 return $result;
	}
	public function getHostByDay($start,$end){
		 $query	= $this->db->query("SELECT date(m.created_at) as day,count( DISTINCT(t.host_id)) as ct FROM conversations m
		 INNER JOIN conversation_topics AS t ON t.host_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id
		 WHERE `m`.`created_at` BETWEEN '$start' AND '$end' GROUP BY date(m.created_at) 
		 ORDER BY date(m.created_at) ASC ");
         $result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['day']] =$count['ct'];
         }	 
		 return $result;
	}
	public function getHostByWeek($start,$end){
		 $query	= $this->db->query("SELECT DATE(`m`.`created_at`) AS `date`, COUNT( DISTINCT(t.host_id)) AS `count`, 
		 	YEARWEEK(`m`.`created_at`,1) as week
		 	FROM conversations m
		 	INNER JOIN conversation_topics AS t ON t.host_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id
		 	WHERE `m`.`created_at` 
		 	BETWEEN '$start' AND '$end'  GROUP BY week ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['week']] =$count['count'];
         }	 
		 return $result;
	}
	public function getHostByMonth($start,$end){
		 $query	= $this->db->query("SELECT DATE(`m`.`created_at`) AS `date`, COUNT( DISTINCT(t.host_id)) AS `count`, 
		 	EXTRACT(YEAR_MONTH FROM `m`.`created_at`) as month
		 	FROM conversations m  
		 	INNER JOIN conversation_topics AS t ON t.host_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id 
		 	WHERE `m`.`created_at`  
		 	BETWEEN '$start' AND '$end'   GROUP BY month ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['month']] =$count['count'];
         }	 
		 return $result;
	}
	public function getGuestByDay($start,$end){
		 $query	= $this->db->query("SELECT date(m.created_at) as day,count( DISTINCT(guest_id)) as ct FROM conversations  m
		 INNER JOIN conversation_topics AS t ON t.guest_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id
		 WHERE `m`.`created_at` BETWEEN '$start' AND '$end'  GROUP BY date(m.created_at) 
		 ORDER BY date(m.created_at) ASC ");
         $result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['day']] =$count['ct'];
         }	 
		 return $result;
	}
	public function getGuestByWeek($start,$end){
		 $query	= $this->db->query("SELECT DATE(`m`.`created_at`) AS `date`, COUNT( DISTINCT(t.guest_id)) AS `count`, 
		 	YEARWEEK(`m`.`created_at`,1) as week
		 	FROM conversations m
		 	INNER JOIN conversation_topics AS t ON t.guest_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id
		 	WHERE `m`.`created_at` 
		 	BETWEEN '$start' AND '$end'  GROUP BY week ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['week']] =$count['count'];
         }	 
		 return $result;
	}
	public function getGuestByMonth($start,$end){
		 $query	= $this->db->query("SELECT  DATE(`m`.`created_at`) AS `date`,COUNT( DISTINCT(t.guest_id)) AS `count`, 
		 	EXTRACT(YEAR_MONTH FROM `m`.`created_at`) as month
		 	FROM conversations m  
		 	INNER JOIN conversation_topics AS t ON t.guest_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id
		 	WHERE `m`.`created_at`  
		 	BETWEEN '$start' AND '$end'   GROUP BY month ORDER BY `date`");
			$result =array();
         foreach ($query->result_array() as $count){
         	$result[$count['month']] =$count['count'];
         }	 
		 return $result;
	}
	
	public function getUniqueChatterByDay($start,$end){
		$query	= $this->db->query("SELECT day , sum(ct) as ct FROM((
		 SELECT date(m.created_at) as day,count( DISTINCT(t.host_id)) as ct FROM conversations m
		 INNER JOIN conversation_topics AS t ON t.host_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id
		 WHERE `m`.`created_at` BETWEEN '$start' AND '$end' GROUP BY date(m.created_at) 
		 ORDER BY date(m.created_at) ASC)
		 UNION 
		 (SELECT date(m.created_at) as day,count( DISTINCT(guest_id)) as ct FROM conversations  m
		 INNER JOIN conversation_topics AS t ON t.guest_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id
		 WHERE `m`.`created_at` BETWEEN '$start' AND '$end'  GROUP BY date(m.created_at) 
		 ORDER BY date(m.created_at) ASC)) AS baseview GROUP BY day");
         $result =array();         
         foreach ($query->result_array() as $count){
         	$result[$count['day']] =$count['ct'];
         }	 
		 return $result;
		
	}
	public function getUniqueChatterByWeek($start,$end){
		$query	= $this->db->query("SELECT    sum(count) as count,week FROM((SELECT DATE(`m`.`created_at`) AS `date`, COUNT( DISTINCT(t.host_id)) AS `count`, 
		 	YEARWEEK(`m`.`created_at`,1) as week
		 	FROM conversations m
		 	INNER JOIN conversation_topics AS t ON t.host_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id
		 	WHERE `m`.`created_at` 
		 	BETWEEN '$start' AND '$end'  GROUP BY week ORDER BY `day`)
		 UNION 
		 (SELECT DATE(`m`.`created_at`) AS `date`, COUNT( DISTINCT(t.guest_id)) AS `count`, 
		 	YEARWEEK(`m`.`created_at`,1) as week
		 	FROM conversations m
		 	INNER JOIN conversation_topics AS t ON t.guest_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id
		 	WHERE `m`.`created_at` 
		 	BETWEEN '$start' AND '$end'  GROUP BY week ORDER BY `week`)) AS baseview GROUP BY week");
         $result =array();         
         foreach ($query->result_array() as $count){
         	$result[$count['week']] =$count['count'];
         }	 
		 return $result;
		
	}
	public function getUniqueChatterByMonth($start,$end){
		$query	= $this->db->query("SELECT   sum(count) as count,month FROM((SELECT DATE(`m`.`created_at`) AS `date`, COUNT( DISTINCT(t.host_id)) AS `count`, 
		 	EXTRACT(YEAR_MONTH FROM `m`.`created_at`) as month
		 	FROM conversations m  
		 	INNER JOIN conversation_topics AS t ON t.host_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id 
		 	WHERE `m`.`created_at`  
		 	BETWEEN '$start' AND '$end'   GROUP BY month ORDER BY `month`)
		 UNION 
		 (SELECT  DATE(`m`.`created_at`) AS `date`,COUNT( DISTINCT(t.guest_id)) AS `count`, 
		 	EXTRACT(YEAR_MONTH FROM `m`.`created_at`) as month
		 	FROM conversations m  
		 	INNER JOIN conversation_topics AS t ON t.guest_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id
		 	WHERE `m`.`created_at`  
		 	BETWEEN '$start' AND '$end'   GROUP BY month ORDER BY `month`)) AS baseview GROUP BY month");
         $result =array();         
         foreach ($query->result_array() as $count){
         	$result[$count['month']] =$count['count'];
         }	 
		 return $result;
		
	}
}

