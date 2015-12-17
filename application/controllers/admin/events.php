<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Events extends CI_Controller {
    
    var $gen_contents	=	array();    
    public function __construct()
    {
        parent::__construct();
        $this->merror['error']	= '';
        $this->msuccess['msg']	= '';
        $this->load->model(array('master_model','admin/permission_model'));
        $this->gen_contents['title']	=	'';
				$this->config->set_item('site_title', 'Jiggie  Admin - Events');
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
        presetfuturedaterange();
				$this->gen_contents['current_controller'] = $this->router->fetch_class();
        $this->access_userid = $this->session->userdata("ADMIN_USERID");
        $this->access_usertypeid = $this->session->userdata("USER_TYPE_ID");
        $this->access_permissions = $this->permission_model->get_all_permission();		
		
    }
      
    public function index()
    {
        if(!$this->master_model->checkAccess('view', EVENTS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
            return FALSE;
        } else {
        	
            $this->event_list();
        }
    }
	
	
    public function event_list($init=''){     	     
	      $breadCrumbs = array( 'admin/events/'=>'Events');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        $this->gen_contents['p_title']= 'Events';
        $this->gen_contents['current_controller'] = "events";
				$this->gen_contents['export_link']= base_url().'admin/events/export';
				$this->gen_contents['add_link']= base_url().'admin/events/create_event';
        //$this->template->write_view('content', 'admin/events',$this->gen_contents);
				$this->template->write_view('content', 'admin/listing',$this->gen_contents);
        $this->template->render();		 
    }
	
		public function ajax_list(){
		
		
        $config['base_url'] = base_url().'admin/venue/ajax_list';
				if ('' != $this->input->post('per_page')) {
            $config['per_page'] = $this->input->post('per_page');
            $perPage = '';
        }        
        else {
            $config['per_page'] = 10;
        }        
        	

				if('' != $this->input->post ('offset')){
               $offset	= safeInteger($this->input->post ('offset'));			    
          }
				else {
        	$offset = 1;
        } 
				$this->mcontents['offset'] = $offset;
		 
		 
				$arr_where								= array();
				$arr_sort								= array();
				if ('' != $this->input->post('sort_field')) {
		            $arr_sort['name'] = $this->input->post('sort_field');
		        } else {
		            $arr_sort['name'] = 'start_datetime';
		        }
		        if ('' != $this->input->post('sort_val')) {
		            $arr_sort['value'] = $this->input->post('sort_val');
		        } else {
		            $arr_sort['value'] = 'asc';
		        }

				if ('' != $this->input->post('sort_status')) {
		            $sort_status = $this->input->post('sort_status');
		        } else {
		            $sort_status = '';
		        }

		        $arr_search = array();
		        if ($this->input->post('search_name') != "") {
		          $arr_search["where"] = mysql_real_escape_string($this->input->post('search_name')) ;
					 $search_string ="&search_fields=type,title,description,event_date,&search_value=".urlencode($arr_search["where"]);
		        }else {
		             $search_string  = "";

		        }

				 //T04:00:00.000Z
		         $start_date = $this->input->post('startDate_iso');
		         $end_date =   $this->input->post('endDate_iso');
		        // file_put_contents('C:\Users\User\Desktop\1.txt', $start_date.'======'.$end_date);
				 $url =APIURL."admin/admin/events/list?".TOKEN."&per_page=".$config['per_page']."&offset=".
				 $offset."&sort_field=".$arr_sort['name']."&sort_val=".$arr_sort['value']."&sort_status=".$sort_status."&start_date=$start_date&end_date=$end_date".$search_string;



				//echo $json = file_get_contents($url);exit;


				//  $url =APIURL."admin/events/list/".$start_date."/".$end_date;

				echo $json = file_get_contents($url);exit;
		}

	public function cal_view($start_date='',$end_date=''){
		$sort_status = '' != $this->input->get('sort_status') ? $this->input->get('sort_status') : '';

		$url =APIURL."admin/admin/events/list/calendar/".$start_date."/".$end_date."?".TOKEN."&sort_status=".$sort_status;

		echo $json = file_get_contents($url);
		exit;
	}

	public function event_details($type,$id){
		if($type=='weekly')
			$url =APIURL."admin/admin/event/recurring/details/".$id."?".TOKEN;
		else {
			$url =APIURL."admin/admin/event/details/".$id."?".TOKEN;
		}	
		// echo $url;
		echo $json = file_get_contents($url);        
			exit;
	}
	
	public function allvenues(){
		$url =APIURL."admin/admin/venuelist"."?".TOKEN;	
		 
		echo $json = file_get_contents($url);        
			exit;
	}
   
   public function create_event($date=''){

	   if(!$this->master_model->checkAccess('create', EVENTS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
		   return FALSE;
	   }

	   $startdate='';

	   if(validateDate($date, 'Y-m-d')){
		   $startdate = date('M d, Y',strtotime($date));
	   }

   		try 
        {
            if(!$this->master_model->checkAccess('create', VENUES_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
	            return FALSE;
            }
            
            $this->mcontents = array();


            if(!empty($_POST)) {
	            $post_data["title"] = $this->input->post("title");
							$post_data["event_type"] = $this->input->post("event_type");
	            $post_data["description"] = $this->input->post("description");
							$post_data["source"] = $this->input->post("featured_event");
	            $post_data["description"] = $this->input->post("description");
							$post_data["start_datetime_str"] = $this->input->post("start_date").' '.$this->input->post("starttime");
				      $post_data["end_datetime_str"]   = $this->input->post("end_time");
							$post_data["status"] = $this->input->post("event_status");

							$post_data["fullfillment_type"]         	 = $this->input->post("fullfillment_type");
							$post_data["fullfillment_value"]         	 = $this->input->post("fullfillment_value");

							if(is_array($this->input->post("event_tags")))
								$etags		=implode(",", $this->input->post("event_tags"));
							else
								$etags	=  '';

							$post_data["tags"]         		= $etags;
							$post_data["venue_id"]          = $this->input->post("venue_sel");
							$post_data["rank"]              = $this->input->post("rank");
							if($this->input->post("forever"))
						      $post_data["end_series_datetime"]              = 0;
							else
								$post_data["end_series_datetime"]              = $this->input->post("end_date").' '.$this->input->post("starttime");


							$ch = curl_init(APIURL.'admin/admin/events/add'."?".TOKEN );
							$payload = json_encode( $post_data );

							curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
							curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
							curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
							# Send request.
							$result_set = curl_exec($ch);
							curl_close($ch);
							$result =  json_decode($result_set);
							if($result->success==true){
								sf('success_message', 'Event created successfully');
								if($result->event->event_type=='special'){
									$type= 'event-special';
									$ev = $result->event->_id;
								}else {
									$type= 'event-weekly';
									$ev = $result->event->_id;

								}
								redirect('admin/events/#/'.$type.'='.$ev);
							}else{
								if($result->reason=="event already exists"){
									$this->gen_contents['error'] =$result->reason;
									if($result->event->event_type=='special'){
										$type= 'event-special';
										$ev = $result->event->_id;
									}else {
										$type= 'event-weekly';
										$ev = $result->event->event_id;
									}
									$url = base_url().'admin/events/#/'.$type.'='.$ev;
									$this->gen_contents['error'] = '<a target="_blank" style="color:#fff;text-decoration: underline;"  href="'.$url.'"  >'.$result->event->title.'</a>  already exist';
								}else{
									$this->gen_contents['error'] =$result->reason;
								}
							}
                   
                    
            }

            $venues=  file_get_contents(APIURL.'admin/admin/venuelist'."?".TOKEN);			
						$this->gen_contents['venues'] = json_decode($venues);
						$this->gen_contents['startdate'] = $startdate;
            $breadCrumbs = array( 'admin/events/'=>'Events');
            $this->gen_contents['breadcrumbs'] = $breadCrumbs;
            $this->template->write_view('content', 'admin/event/create_event', $this->gen_contents);
            $this->template->render();
        }
        catch (Exception $e) 
        {
            
        }
   }
	
	public function duplicate($e_type,$event_id=''){
		if($e_type!='weekly' && $e_type!='special'){
    		redirect('admin/events');			
		}

		if(!$event_id)
			redirect('admin/events');

		try
    {
	    if (!$this->master_model->checkAccess('create', EVENTS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions))
      {
        return FALSE;
      }

      $this->mcontents = array();

      if(!empty($_POST)) {

        $post_data["title"]              = $this->input->post("title");
				$post_data["event_type"]         = $this->input->post("event_type");
        $post_data["description"]        = $this->input->post("description");
				$post_data["source"]             = $this->input->post("featured_event");
        $post_data["description"]        = $this->input->post("description");
				$post_data["start_datetime_str"] = $this->input->post("start_date").' '.$this->input->post("starttime");
	      $post_data["end_datetime_str"]   = $this->input->post("end_time");
				$post_data["status"]         	   = $this->input->post("event_status");

				$post_data["fullfillment_type"]         	 = $this->input->post("fullfillment_type");
				$post_data["fullfillment_value"]         	 = $this->input->post("fullfillment_value");


				$pic_total = $this->input->post("pic_total");

				$post_data["pic_total"] = $pic_total;

				$tmpA = array();

				for ($i=0; $i < $pic_total ; $i++)
				{
					if($this->input->post("photo_".$i) != "")
					{
						array_push($tmpA, $this->input->post("photo_".$i));
					}
				}

				for ($j=0; $j <$pic_total ; $j++)
				{
					$post_data["photo_" . $j] = $tmpA[$j];
				}

				//$post_data["pic_total"] = "2";
				//$post_data["photo_0"] = "https://s3-us-west-2.amazonaws.com/cdnpartyhost/1446789007515.jpg";
				//$post_data["photo_1"] = "https://s3-us-west-2.amazonaws.com/cdnpartyhost/1446789007515.jpg";

				if(is_array($this->input->post("event_tags")))
					$etags		=implode(",", $this->input->post("event_tags"));
				else
					$etags	=  '';

				$post_data["tags"]         		= $etags;
				$post_data["venue_id"]          = $this->input->post("venue_sel");
				$post_data["rank"]              = $this->input->post("rank");
				if($this->input->post("forever"))
					$post_data["end_series_datetime"]              = 0;
				else
					$post_data["end_series_datetime"]              = $this->input->post("end_date").' '.$this->input->post("starttime");

				$ch = curl_init(APIURL.'admin/admin/events/add'."?".TOKEN );
				$payload = json_encode( $post_data );

				curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
				curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				# Send request.
				$result_set = curl_exec($ch);
				curl_close($ch);
				$result =  json_decode($result_set);
				//var_dump($result);

				if($result->success==true){
					sf('success_message', 'Event created successfully');
					if($result->event->event_type=='special')
						$type= 'event-special';
					else {
						$type= 'event-weekly';
					}
					redirect('admin/events/#/'.$type.'='.$result->event->_id);
				}else{
					if($result->reason=="event already exists"){
						$this->gen_contents['error'] =$result->reason;

						if($e_type=='special')
							$type= 'event-special';
						else {
							$type= 'event-weekly';
						}
						$url = base_url().'admin/events/#/'.$type.'='.$event_id;
						$this->gen_contents['error'] = '<a target="_blank" style="color:#fff;text-decoration: underline;"  href="'.$url.'"  >'.$result->event->title.'</a>  already exist';
					}else{
						$this->gen_contents['error'] =$result->reason;
					}
				}
      }

      if(empty($event_id))
      {
          sf('error_message', "Events details not found");
          redirect('admin/events');
      }

      $venues=  file_get_contents(APIURL.'admin/admin/venuelist'."?".TOKEN);
			if($e_type=='weekly')
				$url_e =APIURL."admin/admin/event/recurring/details/".$event_id."?".TOKEN;
			else {
				$url_e =APIURL."admin/admin/event/details/".$event_id."?".TOKEN;
			}
			$events=  file_get_contents($url_e);
			$this->gen_contents['venues'] = json_decode($venues);

			$this->gen_contents['events'] = json_decode($events);
      $breadCrumbs = array( 'admin/events/'=>'Events');
      $this->gen_contents['breadcrumbs'] = $breadCrumbs;
      $this->template->write_view('content', 'admin/event/duplicate', $this->gen_contents);
      $this->template->render();
    }
    catch (Exception $e)
    {

    }
   }
	public function delete($event_id){
		if(!$this->master_model->checkAccess('delete', EVENTS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
			return FALSE;
		}

		$url =APIURL."admin/admin/event/update/".$event_id."?".TOKEN;
		$post_data["object"]          = 'active';
		$post_data["value"]           = '0' ;
		$post_data["event_id"]          = $event_id;

		$ch = curl_init($url);
		$payload = json_encode( $post_data );

		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		# Send request.
		$result_set = curl_exec($ch);
		curl_close($ch);
		 
		$result =  json_decode($result_set);
		 
    if($result->success=='true') {
        sf('success_message', 'Event deleted successfully');
        redirect("admin/events/");
    } else {
        sf('error_message', $result->reason);
        redirect("admin/events");
    }
	}
	
	public function weeklydelete($event_id,$force){		 
		if($force=='true') 
		 	$post_data["force"]          = 1;
		else
		 	$post_data["force"]          = 0;

		$url =APIURL."admin/admin/event/recurring/update/".$event_id."?".TOKEN;
		$post_data["object"]          = 'active'; 
		$post_data["value"]           = '0' ; 
		 
		$post_data["event_id"]          = $event_id;
		
		$ch = curl_init($url);					 
		$payload = json_encode( $post_data );	
					  					
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		# Send request.
		$result_set = curl_exec($ch);
		curl_close($ch);
		$result =  json_decode($result_set);
		if($result->success=='true') {
        sf('success_message', 'Event deleted successfully');
        redirect("admin/events/");
    } else {
         sf('error_message', $result->reason);
        redirect("admin/events");
    }
	}

	public function editdatetime($event_id)
	{
		$post_data["start_datetime_str"] = $this->input->post("start_datetime_str");
		$post_data["end_datetime_str"] = $this->input->post("end_datetime_str");
		$post_data["venue_id"] = $this->input->post("venue_id");

		
		$url =APIURL."admin/admin/event/datetime/update/".$event_id."?".TOKEN;

		$ch = curl_init($url);					 
		$payload = json_encode( $post_data );	
					  					
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		# Send request.
		$result_set = curl_exec($ch);
		curl_close($ch);
		$result =  json_decode($result_set);
		//var_dump($result);

		$arrayName = array('success' => false, 'reason'=>'incorrect');
		//echo json_encode($arrayName);

		echo $result_set;

		exit;  
	}
	
	public function edit($event_id){
		 $force = $this->input->post("forceedit");
		 
		 if($force=='true') 
		 	$post_data["force"]          = 1;
		 else
		 	$post_data["force"]          = 0;
	     $url =APIURL."admin/admin/event/recurring/update/".$event_id."?".TOKEN;
		 $post_data["object"]          = $this->input->post('name'); 
		 if($post_data["object"] =='tags'){
		 	if(is_array($this->input->post("value")))
						$etags		=implode(",", $this->input->post("value"));
					else  
						$etags	=  '';
		 	$post_data["value"]         = $etags ;
		 }else{
		 	$post_data["value"]          = $this->input->post('value') ;
		 }
		 
		 
		$post_data["event_id"]          = $event_id;
		  
		$ch = curl_init($url);					 
		$payload = json_encode( $post_data );	
					  					
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		# Send request.
		$result_set = curl_exec($ch);
		curl_close($ch);
		$result =  json_decode($result_set);
		var_dump($result);
		exit;  
          
	}
	
	public function addimage(){
		if(!$this->master_model->checkAccess('update', EVENTS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
			http_response_code(401);
			$response['error'] = "You're not authorized";
			echo json_encode($response);
			exit;
		}

			if (empty($_FILES['photo'])) {
			    echo json_encode(array('error'=>'No files found for upload.')); 		    
			    return;  
			}		
			$event_id =  $this->input->post('event_id');
			$method = 	$this->input->post('method');	
			$force = $this->input->post("forceedit");
		    
			$success = null; 
			$paths= array();	  
			$RealTitleID = $_FILES['photo']['name']; 
			//$url = APIURL.'admin/admin/services/'.$method.'/'.$event_id."?".TOKEN; 
		    $filename = $_FILES['photo']['name'];
		    $filedata = $_FILES['photo']['tmp_name'];
		    $filesize = $_FILES['photo']['size'];
			if($method=='weekly'){
				$url = APIURL.'admin/admin/event/recurring/add/image/'.$event_id."?".TOKEN; 
				if($force=='true') 
				 	$force_data         = 1;
				else
				 	$force_data         = 0;
				$img_arrary = array(
			      'photo' =>
			          '@'            . $_FILES['photo']['tmp_name']
			          . ';filename=' . $_FILES['photo']['name']
			          . ';type='     . $_FILES['photo']['type'],
			      'event_id'=>$event_id,
			      'force'=>$force_data
			    );
			}else{
				$url = APIURL.'admin/admin/event/add/image/'.$event_id."?".TOKEN; 
				$img_arrary = array(
			      'photo' =>
			          '@'            . $_FILES['photo']['tmp_name']
			          . ';filename=' . $_FILES['photo']['name']
			          . ';type='     . $_FILES['photo']['type'],
			      'event_id'=>$event_id
			      
			    );
			}
			
		    if ($filedata != '')
		    {	        	 
				$request = curl_init($url);			 
				curl_setopt($request, CURLOPT_POST, true);
				curl_setopt( $request,  CURLOPT_POSTFIELDS,$img_arrary);			    
				curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($request);			 
				curl_close($request);
				echo $result;
				exit;
			}
	}
	
	function removeimage($event_id=''){
		if(!$this->master_model->checkAccess('update', EVENTS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
			http_response_code(401);
			$response['error'] = "You're not authorized";
			echo json_encode($response);
			exit;
		}

		$force = $this->input->post("forceedit");
		$type = $this->input->post('type');
		$data_to_post = array();
		if($type=='special'){
			$url =APIURL."admin/admin/event/delete/image/".$event_id."?".TOKEN;
		}else{
			
			if($force=='true') 
				 	$data_to_post['force']        = 1;
				else
				 	$data_to_post['force']        = 0;
			$url =APIURL."admin/admin/event/recurring/delete/image/".$event_id."?".TOKEN;
		}

		$data_to_post['url'] = $this->input->post('url');
		$data_to_post['event_id'] = $event_id;		 
		$curl = curl_init();
	
		// Set the options
		curl_setopt($curl,CURLOPT_URL, $url);
		
		// This sets the number of fields to post
		curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
		
		// This is the fields to post in the form of an array.
		curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
		
		//execute the post
		$result = curl_exec($curl);
		
		//close the connection
		curl_close($curl);
		echo 1;
		exit;
	}

	function editspecial($event_id){
		if(!$this->master_model->checkAccess('update', EVENTS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
			http_response_code(401);
			echo "Unauthorized";
			exit;
		}

		$url =APIURL."admin/admin/event/update/".$event_id."?".TOKEN;
		$post_data["object"]          = $this->input->post('name'); 
		if($post_data["object"] =='tags'){
		 	if(is_array($this->input->post("value")))
				$etags		=implode(",", $this->input->post("value"));
			else
				$etags	=  '';
		 	$post_data["value"]         = $etags ;
		}else{
		 	$post_data["value"]          = $this->input->post('value') ;
		}

		$post_data["event_id"]          = $event_id;
		  
		$ch = curl_init($url);					 
		$payload = json_encode( $post_data );	
					  					
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		# Send request.
		$result_set = curl_exec($ch);
		curl_close($ch);
		$result =  json_decode($result_set);
		var_dump($result);
		exit;   
	}

	function getCurlValue($filename, $contentType, $postname)
	{
	    // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
	    // See: https://wiki.php.net/rfc/curl-file-upload
	    if (function_exists('curl_file_create')) {
	        return curl_file_create($filename, $contentType, $postname);
	    }
	 
	    // Use the old style if using an older version of PHP
	    $value = "@{$this->filename};filename=" . $postname;
	    if ($contentType) {
	        $value .= ';type=' . $contentType;
	    }
	 
	    return $value;
	}
	
	 public function export() {
	 	exit;
        try {
        	
			 $url= $_SERVER['QUERY_STRING'];
			 $pars = explode('/',$url);
			 $data = array();
			 if($url){
				foreach ($pars as $values){
				 	$val = explode('=' ,$values);
					$data[$val[0]] =$val[1];
				 }
			 }
		 
        	$starttime = new DateTime($this->session->userdata('startDate')." 00:00:00");
	        $start_date = $starttime->format(DateTime::ISO8601) ;
			
			$endtime = new DateTime($this->session->userdata('endDate')." 23:59:59");
	        $end_date = $endtime->format(DateTime::ISO8601) ;
			
			if(isset($data['sort_field']))
			$arr_sort['name'] = @$data['sort_field'] ; 
			if(isset($data['sort_val']))
				$arr_sort['value'] = @$data['sort_val'] ; 
			if(isset($data['search_name']))
				$arr_search["where"] = mysql_real_escape_string(@$data['search_name']) ;
			else
				$arr_search["where"]='';
	        $url =APIURL."admin/admin/events/list?".TOKEN."&sort_field=".$arr_sort['name']."&sort_val=".$arr_sort['value']."&startDate=$start_date&endDate=$end_date&search_fields=type,title,description,event_date,&search_value=".urlencode($arr_search["where"]);
	        
			
            $json = file_get_contents($url);
			$array = json_decode($json, true);
			
			var_dump($array['events']);exit;
           
			$filename = "Events-".date("Y-m-d").".csv";
			$file = fopen($this->config->item("site_basepath") . "uploads/csv/".$filename,"w");
			
			$firstLineKeys = false;
			$skip = array('_id'=>'','event_id'=>'','messages'=>'','guest_id'=>'','host_id'=>'');
			$p_date = array('last_updated'=>'','topic_date'=>'','created_at'=>'');
			$c_date = array('topic_venue'=>'venue','topic_date'=>'hosting date','created_at'=>'created date');
			foreach ($array['conversations'] as $line)
			{
				if (empty($firstLineKeys))
				{
					$values = array_diff_key ($line , $skip);
					foreach ($values as $key1 => $keyval) {
						if (array_key_exists($key1, $c_date)) {							
						   $n[] = $c_date[$key1];						
						}else{
							$n[] = $key1;	
						}						
					}
					
					$firstLineKeys = array_keys($values);
					
					
					fputcsv($file, $n);
					$firstLineKeys = array_flip($firstLineKeys);
					//var_dump($firstLineKeys);exit;
				}
				$values = array_diff_key ($line , $skip);
				foreach ($values as $key => &$value) {
					if (array_key_exists($key, $p_date)) {
					   $value = date("m/d/Y H:i:s",strtotime($value));						
					}
					if($key=='last_message')
						$value = $value['message'];
				}
				
				fputcsv($file, array_merge($firstLineKeys, $values));
			}
			if (file_exists($this->config->item("site_basepath") . "uploads/csv/".$filename)) {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Pragma: no-cache');
            readfile($this->config->item("site_basepath") . "uploads/csv/".$filename);
	        } else {
	            
	        }
        } catch (Exception $e) {
            
        }
    }
}    