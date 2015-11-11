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

class Chat_model extends CI_Model {
    public function __construct()
    {
            parent::__construct();
    }
    
    public function getAllChats($arr_condition, $arr_sort, $return_type, $num = 0, $offset = 0, $arr_search=array()){
      
	    if(!empty($arr_condition)){			 
			
            }
            if(!empty($arr_search["where"])) {            	
                    $this->db->where("(U1.first_name  LIKE '%".$arr_search["where"]."%' OR 
                        U1.last_name  LIKE '%".$arr_search["where"]."%' OR 
                        U2.first_name  LIKE '%".$arr_search["where"]."%' OR 
                        U2.last_name  LIKE '%".$arr_search["where"]."%' OR
                        CONCAT_WS(' ', U1.first_name, U1.last_name)  LIKE '%".$arr_search["where"]."%' OR
                        CONCAT_WS(' ', U2.first_name, U2.last_name)  LIKE '%".$arr_search["where"]."%') OR 
                        m.message  LIKE '%".$arr_search["where"]."%' OR
                        ct.topic  LIKE '%".$arr_search["where"]."%'
                        ", NULL, FALSE); 
                }
            if(!empty($arr_search["chat_filter"])) {            	 
				$cus_filter = explode(":", urldecode($arr_search["chat_filter"]));
				if($cus_filter[0]=='user')       	
					
                    $this->db->where("ct.host_id = ".$cus_filter[1]." OR ct.guest_id = ".$cus_filter[1], NULL, FALSE); 
                    
				elseif ($cus_filter[0]=='hosting') {
					$this->db->where("ct.hosting_id = ".$cus_filter[1], NULL, FALSE); 
				}
            }else{
            	$this->db->where($arr_condition);
            }
            $this->db->join('conversations m','ct.id = m.topic_id', 'LEFT', false);
            $this->db->join('users U1','U1.id = ct.host_id', 'LEFT', false);
            $this->db->join('users U2','U2.id = ct.guest_id', 'LEFT', false);
            $this->db->group_by("ct.id");
            if('count' == $return_type){
                    $this->db->select ("COUNT(ct.id) AS count", FALSE);
                    $query	= $this->db->get('conversation_topics ct');
                    $result	= $query->num_rows();
                    return $result;
            }
            
            if(is_array($arr_sort) && count($arr_sort) > 0){
                    //$sort_value	= ('ASC' == $arr_sort['value']) ? 'ASC': 'DESC';
                    //if($arr_sort['name'] == 'created_date'){
                    //	$this->db->order_by($arr_sort['name'], $sort_value);
                    //}
                    $this->db->order_by($arr_sort['name'], $arr_sort['value']);
            }else {
                    $this->db->order_by('ct.created_at', 'DESC');
            }

            if($num > 0){
                    $this->db->limit($num, $offset);
            }
             $this->db->select("ct.id,ct.host_id,ct.chat_count,ct.venue,DATE_FORMAT(ct.hosting_time,'%m-%d-%Y %T') as chathosting_time, DATE_FORMAT(ct.created_at,'%m-%d-%Y %T') as chatcreated_at,  DATE_FORMAT(ct.updated_at,'%m-%d-%Y %T') as chatupdated_at,CONCAT_WS(' ', U1.first_name, U1.last_name) as host,
                                CONCAT_WS(' ', U2.first_name, U2.last_name) as guest",false);
             //$this->db->select("ct.id,ct.host_id,ct.chat_count,(SELECT message FROM conversations WHERE topic_id = ct.id ORDER by created_at DESC LIMIT 1) as message,ct.venue,ct.hosting_time,ct.created_at,ct.updated_at,CONCAT_WS(' ', U1.first_name, U1.last_name) as host,
             //CONCAT_WS(' ', U2.first_name, U2.last_name) as guest",false);
            $query	=	$this->db->get('conversation_topics ct');
			// Need to change this sql 
            $result = $query->result_array();
			foreach ($result as &$data ){
	            $query1 = $this->db->query("SELECT message FROM conversations WHERE topic_id='".$data['id']."' ORDER by created_at DESC LIMIT 1");
				$set= $query1->result_array();
				$data['message']  = $set[0]['message'];
	        }
			
			return $result;
    }
    
    public function getChatCounts($start,$end){
        $chatTotal = array();
        $date = date("Y-m-d");
        $qry_condn = "created_at >= '$date 00:00:00' and created_at<='$date 23:59:00'";        
        $chatTotal['chats_today'] = $this->getChatCntByDate( $qry_condn);
        
        $ydate = date("Y-m-d",strtotime("-1 days"));
        $qry_condn = "created_at >= '$ydate 00:00:00' and created_at<='$ydate 23:59:00'";        
        $chatTotal['chats_yesterday'] = $this->getChatCntByDate( $qry_condn);
        
        $sevendate = date("Y-m-d",strtotime("-7 days"));
        $qry_condn = "created_at >= '$sevendate 00:00:00' and created_at<='$ydate 23:59:00'";      
        $chatTotal['chats_week'] = $this->getChatCntByDate( $qry_condn);
        
        $eight_date = date("Y-m-d",strtotime("-8 days"));
		$forteen_date = date("Y-m-d",strtotime("-14 days"));
         
        $qry_condn = "created_at >= '$forteen_date 00:00:00' and created_at<='$eight_date 23:59:00'"; 
        $chatTotal['chats_prev_week'] = $this->getChatCntByDate( $qry_condn);
        
        $thirty_date = date("Y-m-d",strtotime("-30 days"));		
        $qry_condn = "created_at >= '$thirty_date 00:00:00' and created_at <='$ydate 23:59:00'";         
        $chatTotal['chats_month'] = $this->getChatCntByDate( $qry_condn);  
        
        $thirtyone_date = date("Y-m-d",strtotime("-31 days"));
		$sixty_date = date("Y-m-d",strtotime("-60 days"));
        $qry_condn = "created_at >= '$sixty_date 00:00:00' and created_at <='$thirtyone_date 23:59:00'";          
        $chatTotal['chats_prev_month'] = $this->getChatCntByDate( $qry_condn); 	                 
        $chatTotal['chats_total'] = $this->getChatCntByDate(1);
        
		$qry_condn = "created_at >= '$start' and created_at<='$end'";   
        $chatTotal['chats_new'] = $this->getChatCntByDate( $qry_condn);
		$chatTotal['updated_chat'] = $this->getUpdatedChatCntByDate($start,$end);
		$chatTotal['unique_chatter'] = $this->getUniqueChatter($start,$end);
		$chatTotal['guest_chat'] = $this->getUniqueGuestChatter($start,$end);
		$chatTotal['host_chat'] = $this->getUniqueHostChatter($start,$end); 
    
         
        //$chatTotal['chats_total'] = $this->getChatCntByDate('');
        return $chatTotal;
    }
    
    public function getChatCntByDate( $qry_condn=''){
    	$result = 0;	
    	$query	= $this->db->query("SELECT count(t.id) as chat_cnt FROM conversation_topics t WHERE host_id IN 
		 	(SELECT to_id FROM conversations WHERE t.id=topic_id AND date(t.created_at)= date(created_at) AND t.hosting_id = hosting_id) 
		 AND host_id IN 
		 	(SELECT from_id FROM conversations WHERE t.id=topic_id AND date(t.created_at)= date(created_at) AND t.hosting_id = hosting_id) 
		 	AND $qry_condn
		 	 ");
       
        //$query	= $this->db->query("Select count(id) as chat_cnt from conversation_topics where $qry_condn");       
        foreach ($query->result_array() as $count){
            $result = $count['chat_cnt'];
        }
		return $result;
    }
	public function getUpdatedChatCntByDate($start,$end){
    	$result = 0;	
    	$query	= $this->db->query("SELECT  count(t.id) as chat_cnt FROM conversation_topics t  WHERE
		   host_id IN 
			 	(SELECT to_id FROM conversations WHERE t.id=topic_id AND date(t.updated_at)= date(created_at) AND t.hosting_id = hosting_id) 
			AND host_id IN 
			 	(SELECT from_id FROM conversations WHERE t.id= topic_id AND date(t.updated_at)= date(created_at) AND t.hosting_id = hosting_id)		 
		 AND t.updated_at BETWEEN '$start' AND '$end' AND   ABS(TIMESTAMPDIFF(DAY,updated_at,created_at))>=1  
		 ");
       
        foreach ($query->result_array() as $count){
            $result = $count['chat_cnt'];
        }
		return $result;
    }
    public function getUniqueChatter( $start,$end){
    	$result = 0;	
    	$query	= $this->db->query("SELECT   sum(ct) as chat_cnt FROM((
		 SELECT count( DISTINCT(t.host_id)) as ct FROM conversations m
		 INNER JOIN conversation_topics  AS t ON t.host_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id
		 WHERE `m`.`created_at` BETWEEN '$start' AND '$end' )
		 UNION 
		 (SELECT  count( DISTINCT(t.guest_id)) as ct FROM conversation_topics  t
		 INNER JOIN conversations AS m ON t.guest_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id 
		 WHERE `m`.`created_at` BETWEEN '$start' AND '$end' )) AS baseview");
       
        foreach ($query->result_array() as $count){
            $result = $count['chat_cnt'];
        }
		return $result;
    }
    public function getUniqueGuestChatter( $start,$end){
    	$result = 0;	
    	$query	= $this->db->query("SELECT count( DISTINCT(guest_id)) as chat_cnt FROM conversations  m
		 INNER JOIN conversation_topics AS t ON t.guest_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id 
		 WHERE `m`.`created_at` BETWEEN '$start' AND '$end'");
       
        //$query	= $this->db->query("Select count(id) as chat_cnt from conversation_topics where $qry_condn");       
        foreach ($query->result_array() as $count){
            $result = $count['chat_cnt'];
        }
		return $result;
    }
    public function getUniqueHostChatter( $start,$end){
    	$result = 0;	
    	$query	= $this->db->query("SELECT count( DISTINCT(t.host_id)) as chat_cnt FROM conversations m
		 INNER JOIN conversation_topics AS t ON t.host_id = m.from_id AND t.id = m.topic_id AND t.hosting_id = m.hosting_id
		 WHERE `m`.`created_at` BETWEEN '$start' AND '$end' ");
       
        //$query	= $this->db->query("Select count(id) as chat_cnt from conversation_topics where $qry_condn");       
        
        foreach ($query->result_array() as $count){
            $result = $count['chat_cnt'];
        }
		return $result;
    }
    
    public function getChatCountsAPI(){
        $chatTotal = array();
        $date = date("Y-m-d");
		$tstart = date(DATE_ISO8601, strtotime($date.'00:00:00'));
		$tend  = date(DATE_ISO8601, strtotime($date.'23:59:00'));		
        $qry_condn = "&start_date=$tstart&end_date=$tend";       
        $chatTotal['chats_today'] = $this->getChatCntByDate( $qry_condn);		
		
        
        $ydate = date("Y-m-d",strtotime("-1 days"));
		$ystart = date(DATE_ISO8601, strtotime($ydate.'00:00:00'));
		$yend  = date(DATE_ISO8601, strtotime($ydate.'23:59:00'));
        $qry_condn = "&start_date=$ystart&end_date=$yend";        
        $chatTotal['chats_yesterday'] = $this->getChatCntByDate( $qry_condn);
        
        $last7 = date('Y-m-d', strtotime('-7 days'));
        $wstart = date(DATE_ISO8601, strtotime($last7.'00:00:00'));		
        $qry_condn = "&start_date=$wstart&end_date=$yend";            
        $chatTotal['chats_week'] = $this->getChatCntByDate( $qry_condn);
        
        $p7date = date("Y-m-d",strtotime("-8 days"));
		$p7dateend = date("Y-m-d",strtotime("-14 days"));
		$p7start = date(DATE_ISO8601, strtotime($p7date.'00:00:00'));
		$p7end  = date(DATE_ISO8601, strtotime($p7dateend.'23:59:00'));
        $qry_condn = "&start_date=$p7start&end_date=$p7end";        
        $chatTotal['chats_prev_week'] = $this->getChatCntByDate( $qry_condn);		
        
        $lmdate = date("Y-m-d",strtotime("-30 days"));		
		$lmstart = date(DATE_ISO8601, strtotime($lmdate.'00:00:00'));		
        $qry_condn = "&start_date=$lmstart&end_date=$yend";        
        $chatTotal['chats_month'] = $this->getChatCntByDate( $qry_condn);
        
		$pmdate = date("Y-m-d",strtotime("-31 days"));
		$pmdateend = date("Y-m-d",strtotime("-60 days"));
		$pmstart = date(DATE_ISO8601, strtotime($pmdate.'00:00:00'));
		$pmend  = date(DATE_ISO8601, strtotime($pmdateend.'23:59:00'));
        $qry_condn = "&start_date=$pmstart&end_date=$pmend";        
        $chatTotal['chats_prev_month'] = $this->getChatCntByDate( $qry_condn);         
        $chatTotal['chats_total'] = $this->getChatCntByDate('');
		$chatTotal['updated_chat'] = $this->getChatCntByDate('');
		$chatTotal['unique_chatter'] = $this->getChatCntByDate('');
		$chatTotal['guest_chat'] = $this->getChatCntByDate('');
		$chatTotal['host_chat'] = $this->getChatCntByDate('');
		
        return $chatTotal;
		
    }
    
	public function getconversation($id){
		$this->db->select("c.message,DATE_FORMAT(c.created_at,'%m-%d-%Y %T') as created_at,c.from_id,CONCAT_WS(' ', U1.first_name, U1.last_name) as fromname",FALSE);
        $this->db->where("topic_id", $id);
		$this->db->join('users U1','U1.id = c.from_id', 'LEFT', false);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get("conversations c");
        return $query->result();
		
	}
	public function getLastMessage($id){
		$this->db->select("message",FALSE);
        $this->db->where("topic_id", $id);	 
        $this->db->order_by('created_at', 'DESC');
		 $this->db->limit(1, 0);
        $query = $this->db->get("conversations c");
        return $query->result_array();
	}
	
    public function getChatCntByDateAPI( $qry_condn=''){
        $result = 0;
        $url = 'https://partyhostapp.herokuapp.com/searchadminconversations?key=9dayZ9ydHhda98hd98n8hoO!NsdbffZjnLJ'.$qry_condn;
        $json = file_get_contents($url);
		$array = json_decode($json, true);        
		return  $array['total_count'];
    }
    public function getChatCount(){
        $query	= $this->db->query("Select count(channel_id) as chat_cnt from hosting_channel");
        foreach ($query->result_array() as $count){
            $result = $count['chat_cnt'];
        }
        
	return $result;
    }
    
    public function export($arr_condition, $arr_sort,$arr_search=array()) { 
        $delimiter = ",";
        $newline = "\r\n";
        $this->load->dbutil();
        $this->load->helper('file');
        //Search Factor
        if(!empty($arr_condition)){			 
			$this->db->where($arr_condition);
            }
            if(!empty($arr_search["where"])) {            	
                    $this->db->where("(U1.first_name  LIKE '%".$arr_search["where"]."%' OR 
                        U1.last_name  LIKE '%".$arr_search["where"]."%' OR 
                        U2.first_name  LIKE '%".$arr_search["where"]."%' OR 
                        U2.last_name  LIKE '%".$arr_search["where"]."%' OR
                        CONCAT_WS(' ', U1.first_name, U1.last_name)  LIKE '%".$arr_search["where"]."%' OR
                        CONCAT_WS(' ', U2.first_name, U2.last_name)  LIKE '%".$arr_search["where"]."%') OR 
                        m.message  LIKE '%".$arr_search["where"]."%' OR
                        ct.topic  LIKE '%".$arr_search["where"]."%'
                        ", NULL, FALSE); 
                }
            
            $this->db->join('conversations m','ct.id = m.topic_id', 'LEFT', false);
            $this->db->join('users U1','U1.id = ct.host_id', 'LEFT', false);
            $this->db->join('users U2','U2.id = ct.guest_id', 'LEFT', false);
            $this->db->group_by("ct.id");
            
            
            if(is_array($arr_sort) && count($arr_sort) > 0){
                    //$sort_value	= ('ASC' == $arr_sort['value']) ? 'ASC': 'DESC';
                    //if($arr_sort['name'] == 'created_date'){
                    //	$this->db->order_by($arr_sort['name'], $sort_value);
                    //}
                    $this->db->order_by($arr_sort['name'], $arr_sort['value']);
            }else {
                    $this->db->order_by('created_at', 'DESC');
            }

            
        $this->db->select("ct.id,ct.host_id,ct.chat_count,(SELECT message FROM conversations WHERE topic_id = ct.id ORDER by created_at DESC LIMIT 1) as message,ct.venue,ct.hosting_time,ct.created_at,ct.updated_at,CONCAT_WS(' ', U1.first_name, U1.last_name) as host,
                                CONCAT_WS(' ', U2.first_name, U2.last_name) as guest",false);
        $query	=	$this->db->get('conversation_topics ct');
        
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);

        $file_path = $this->config->item("site_basepath") . "uploads/csv/";
        
		$file = "Chat-".date("Y-m-d").".csv";
        if (write_file($file_path . $file, $data)) {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . $file);
            header('Pragma: no-cache');
            readfile("$file_path" . "$file");
        } else {
            
        }
    }
    
    public function getChatMessageCount($chat_id) {
        $query	= $this->db->query("Select count(channel_id) as chat_cnt from chat_messages where channel_id = '$chat_id'");
        foreach ($query->result_array() as $count){
            $result = $count['chat_cnt'];
        }
        
	return $result;
    }
}   