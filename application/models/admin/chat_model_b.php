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
			$this->db->where($arr_condition);
            }
            if(!empty($arr_search["where"])) {
                    $this->db->where("(U1.first_name  LIKE '%".$arr_search["where"]."%' OR 
                        U1.last_name  LIKE '%".$arr_search["where"]."%' OR 
                        U2.first_name  LIKE '%".$arr_search["where"]."%' OR 
                        U2.last_name  LIKE '%".$arr_search["where"]."%' OR
                        CONCAT_WS(' ', U1.first_name, U1.last_name)  LIKE '%".$arr_search["where"]."%' OR
                        CONCAT_WS(' ', U2.first_name, U2.last_name)  LIKE '%".$arr_search["where"]."%') OR 
                        cm.message  LIKE '%".$arr_search["where"]."%'  
                        ", NULL, FALSE); 
                }
            $this->db->join('chat_participants b','a.channel_id = b.channel_id AND a.user_id !=b.user_id', 'INNER', false);
            $this->db->join('chat_messages cm','a.channel_id = cm.channel_id', 'LEFT', false);
            $this->db->join('users U1','U1.id = a.user_id', 'LEFT', false);
            $this->db->join('users U2','U2.id = b.user_id', 'LEFT', false);
            $this->db->group_by("a.channel_id");
            if('count' == $return_type){
                    $this->db->select ("COUNT(a.channel_id) AS count", FALSE);
                    $query	= $this->db->get('chat_participants a');
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
                    $this->db->order_by('name', 'ASC');
            }

            if($num > 0){
                    $this->db->limit($num, $offset);
            }
             $this->db->select("a.channel_id , a.user_id AS user1, b.user_id AS user2,a.is_host AS host1,b.is_host AS host2,
                                a.created_at,CONCAT_WS(' ', U1.first_name, U1.last_name) as name1,
                                CONCAT_WS(' ', U2.first_name, U2.last_name) as name2",false);
            $query	=	$this->db->get('chat_participants as a');
            return $query->result();
    }
    
    public function getChatCounts(){
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
        return $chatTotal;
    }
    
    public function getChatCntByDate( $qry_condn=''){
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
    
    public function export($start_date, $end_date, $where="") { 
        $delimiter = ",";
        $newline = "\r\n";
        $this->load->dbutil();
        $this->load->helper('file');
        //Search Factor
        if(!empty($where)) {
            $where_search =  "  AND (U1.first_name  LIKE '%".$where."%' OR 
                        U1.last_name  LIKE '%".$where."%' OR 
                        U2.first_name  LIKE '%".$where."%' OR 
                        U2.last_name  LIKE '%".$where."%' OR
                        CONCAT_WS(' ', U1.first_name, U1.last_name)  LIKE '%".$where."%' OR
                        CONCAT_WS(' ', U2.first_name, U2.last_name)  LIKE '%".$where."%')";
        } else {
            $where_search = "";
        }
        
        $query = $this->db->query($SQL='SELECT a.channel_id as channel, 
                                CONCAT_WS(" ", U1.first_name, U1.last_name) as User1,  CONCAT_WS(" ", U2.first_name, U2.last_name) as User2,DATE_FORMAT(a.created_at, "%m/%d/%Y" )as created_at  FROM chat_participants as a
                INNER JOIN chat_participants as b ON (a.channel_id = b.channel_id AND a.user_id !=b.user_id)
                LEFT JOIN users U1 ON (U1.id = a.user_id)
                LEFT JOIN users U2 ON (U2.id = b.user_id)
                WHERE a.created_at >= "'.$start_date.'" AND a.created_at <= "'.$end_date.'"'. $where_search ." GROUP BY a.channel_id ORDER BY channel desc");
        //echo $SQL;
        
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