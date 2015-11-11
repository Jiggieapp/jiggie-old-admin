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
        $qry_condn = "created_at >= '$date 00:00:00' and created_at<='$date 23:59:00'";       
        $chatTotal['chats_today'] = $this->getChatCntByDate( $qry_condn);
        
        $ydate = date("Y-m-d",strtotime("-1 days"));
        $qry_condn = "created_at = '$ydate 00:00:00' and created_at<='$ydate 23:59:00'";        
        $chatTotal['chats_yesterday'] = $this->getChatCntByDate( $qry_condn);
        
        $qry_condn = "created_at >= DATE_SUB( NOW( ) , INTERVAL 1 WEEK)";      
        $chatTotal['chats_week'] = $this->getChatCntByDate( $qry_condn);
        
        $prev_date = date("Y-m-d H:i:s",strtotime("-7 days"));
        $qry_condn = "created_at >= DATE_SUB( '$prev_date' , INTERVAL 1 WEEK) and created_at <= '$prev_date'"; 
        $chatTotal['chats_prev_week'] = $this->getChatCntByDate( $qry_condn);
        
        $qry_condn = "created_at >= DATE_SUB( NOW( ) , INTERVAL 1 MONTH)";        
        $chatTotal['chats_month'] = $this->getChatCntByDate( $qry_condn);  
        
        $prev_month_date = date("Y-m-d H:i:s",strtotime("-30 days"));
        $qry_condn = "created_at >= DATE_SUB( '$prev_month_date' , INTERVAL 1 MONTH) and created_at <= '$prev_month_date'"; 
        $chatTotal['chats_prev_month'] = $this->getChatCntByDate( $qry_condn); 
         
        $chatTotal['chats_total'] = $this->getChatCount();
        return $chatTotal;
    }
    
    public function getChatCntByDate( $qry_condn){
        $result = 0;
        
        $query	= $this->db->query("Select count(channel_id) as chat_cnt from hosting_channel where $qry_condn");       
        foreach ($query->result_array() as $count){
            $result = $count['chat_cnt'];
        }
        
	return $result;
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
        $file = strtotime(date("Y-m-d h:i:s")) . ".csv";

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