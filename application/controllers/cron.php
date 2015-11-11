<?php
class Cron extends CI_Controller {
	public function setRecurrning()
	{
		if($this->input->is_cli_request()){
			$query	= $this->db->query(	"SELECT * FROM hostings 
										WHERE 
											(DATE(time) BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND DATE_ADD(CURDATE(), INTERVAL 1 DAY))
											AND 
												is_recurring =1 
										");       
	        $result = $query->result_array();
			foreach($result as $hosting){   
				$qryCheck	= 	$this->db->query("SELECT id FROM hostings where  parent_id = '" . $hosting['id'] . "'");       
	        	$resCheck 	= 	$qryCheck->row_array();
				if($hosting['hosting_status']){	
					if(empty($resCheck))
					{
						$this->db->set("parent_id"		,	$hosting['id']);
		        		$this->db->set("venue_id"		,	$hosting['venue_id']) ;
						$this->db->set("user_id"		,	$hosting['user_id']) ;
						$this->db->set("theme"			,	$hosting['theme']);
						$this->db->set("description"	,	$hosting['description']) ;
						$this->db->set("date"			,	$hosting['date']) ;
	                	$this->db->set('time'			, 	'DATE_ADD("'.$hosting['time'].'",INTERVAL 7 DAY)', FALSE);                         
	                    $this->db->set("is_recurring"	,	$hosting['is_recurring']);
	                    $this->db->set("is_promoter"	,	$hosting['is_promoter']);
	                    $this->db->set("rank"			,	$hosting['rank']);
						$this->db->set("created_at"		,	'NOW()', FALSE);					
	                    $this->db->set("hosting_status"	,	$hosting['hosting_status']);
	                    $this->db->set("user_image_url"	,	$hosting['user_image_url']) ;                                        
	                    $this->db->insert('hostings');
					}
				}else{
					if($resCheck){
						$this->db->set('hosting_status', '0');
						$this->db->where('id', $resCheck['id']);
						$this->db->update('hostings'); 
					}
				}
	        }
		}
	}
}
?>