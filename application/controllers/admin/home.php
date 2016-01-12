<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    var $email = "";

    /**
     * Admin password
     *
     * @var string
     */
    var $password = "";
    var $cpassword = "";
    var $gen_contents = array();

    public function __construct() {

        parent::__construct();
         $this->gen_contents['current_controller'] = $this->router->fetch_class();
        $this->merror['error'] = '';
        $this->msuccess['msg'] = '';
        $this->load->model(array('admin/admin_model','admin/user_model',
            'admin/hosting_model','admin/chat_model','master_model'));
        $this->gen_contents['title'] = '';
		
         
    }

    public function index() {
    	presetpastdaterange();
        $this->mcontents = array();
				($this->authentication->check_logged_in("admin")) ? redirect('admin/home/dashboard') : '';
        if (!empty($_POST)) {

	        $this->load->library('form_validation');
	        $this->_init_adminlogin_validation_rules(); //server side validation of input values
	        if ($this->form_validation->run() == FALSE) {// form validation
            sf('error_message', 'Invalid Username or Password');
            redirect("admin");
          } else {
            $this->_init_adminlogin_details();
            $login_details['email'] = $this->email;
            $login_details['password'] = $this->password;
						$login_status =  $this->authentication->process_admin_login($login_details);
            if ($login_status == 'success') {
                redirect("admin/home/dashboard");
            } else if ($login_status == 'deleted') {
                sf('error_message', 'Your account has been deleted. Please contact Party Host Admin');
                redirect("admin");
            } else {
                sf('error_message', 'The email or password you entered is incorrect.');
                redirect("admin");
            }
          }
        }

        // define form attributes
        $data = array(
            'id' => 'login_form',
            'method' => 'post'
        );

        $this->gen_contents['attributes'] = $data;
        $this->gen_contents['title'] = $this->gen_contents['title'] . " - Login";


        $this->load->view('admin/login');
    }
    
        
    public function profile(){
        
        
        //if(isset($this->session->userdata('ADMIN_USERID'))){
        $this->gen_contents['admin_details'] = $this->master_model->getAdminDet($this->session->userdata('ADMIN_USERID'));
        $this->template->write_view('content', 'admin/profile', $this->gen_contents);
        $this->template->render();
        //}    
        //else{
           // redirect("admin/home");
        //}
    }

    /**
     * validating the form elemnets
     */
    function _init_adminlogin_validation_rules() {
        $this->form_validation->set_rules('email', 'username', 'required|max_length[50]');
        $this->form_validation->set_rules('password', 'password', 'required|max_length[20]');
    }

    function _init_adminlogin_details() {
        $this->email = $this->common_model->safe_html($this->input->post("email"));
        $this->password = $this->common_model->safe_html($this->input->post("password"));
    }

    public function dashboard() {
// 		redirect('admin/user');

        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
				$start_date = $this->session->userdata('startDate') . " 00:00:00";
        $end_date = $this->session->userdata('endDate') . " 23:59:59";
				$this->config->set_item('site_title', 'Party Host - Administrator DashBoard');
        $this->gen_contents['user_count'] = $this->user_model->getUserCounts($start_date,$end_date);
        $this->gen_contents['hosting_count'] = $this->hosting_model->getHostingCounts($start_date,$end_date);
        $this->gen_contents['chats_count'] = $this->chat_model->getChatCounts($start_date,$end_date);
		
        $this->template->write_view('content', 'admin/dashboard', $this->gen_contents);
        $this->template->render();
    }

	public function ajax_mixpanel_users()
	{
		if (empty($this->input->get('event', TRUE))){
			$err = array(
				'code' => 500,
				'message' => 'event data needed'
			);

			echo json_encode($err);
			exit();
		}

		$apiK = '71fb579d648860ee5e6df09e3243b1e4';
		$apiS = 'a25cf43fe8892a4f06686eb29cc89c99';

		$now = strtotime(date("Y-m-d H:i:s"));
		$expire = $now + 86400;

		$from = $this->input->get('from', TRUE);
		$fromDate = date('Y-m-d', $from > 0 ? strtotime('-' . $from . ' days', strtotime(date('Y-m-d'))) : strtotime(date('Y-m-d')));
		$toDate = date('Y-m-d');

		$where = '';

		$mpToday = array(
				'api_key' => $apiK,
				'event' => 'Sign Up',
				'from_date' => $fromDate,
				'to_date' => $toDate,
				'where' => $where,
				'expire' => $expire,
		);

		ksort($mpToday);

		$mixpanel_params = '';
		$hash_params = "";
		foreach ($mpToday as $k => $v) {
			if (!empty($mixpanel_params)) $mixpanel_params .= '&';
			$mixpanel_params .= $k . '=' . urlencode($v);
			$hash_params .= $k . '=' . $v;
		}

		$sig = md5($hash_params . $apiS);

		$endpoint = 'http://mixpanel.com/api/2.0/segmentation?' . $mixpanel_params . '&sig=' . $sig;

		$response = file_get_contents($endpoint);
		$data = json_decode($response, true);

		$msg = '';
		$total = 0;
		if (isset($data['data']['values'])){
			$values = $data['data']['values'];
			foreach ($values[$this->input->get('event', TRUE)] as $count)
				$total += $count;

			$msg = array(
				'code' => 200,
				'event' => $this->input->get('event', TRUE),
				'from_date' => $fromDate,
				'to_date' => $toDate,
				'total_count' => $total
			);

			echo json_encode($msg);
		}

		exit();
	}

	protected function getData(){

	}
	
	public function overview() {		
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';  
		$this->config->set_item('site_title', 'Party Host - Chat Overview');       
        $this->template->write_view('content', 'admin/overview', $this->gen_contents);
        $this->template->render();
		
    }
	public function parserMonth($data1='',$data2='',$start_date,$end_date){
		$period = new DatePeriod(
		     new DateTime($start_date),
		     new DateInterval('P1M'),
		     new DateTime($end_date)
		);	
		$ct=0;	
		foreach($period as $dt){			
			  $graph[$ct]['date']= $dt->format("M-y");
			  if(array_key_exists($dt->format("Ym"), $data1))
  		    	 $graph[$ct]['a']= $data1[$dt->format("Ym")];
			  else
				   $graph[$ct]['a']=0;			    
			  if(array_key_exists($dt->format("Ym"), $data2))
  		    	 $graph[$ct]['b']= $data2[$dt->format("Ym")];
			  else
				   $graph[$ct]['b']=0;			   
			 $ct++;			
		}
		return $graph;
		 
	}
	public function parserWeek($data1='',$data2='',$start_date,$end_date){
		//echo 	$start_date.$end_date;
		$weeks = array();
;
		$period = new DatePeriod(
		     new DateTime($start_date),
		     new DateInterval('P1D'),
		     new DateTime($end_date)
		);	
		$ct=0;	
		foreach($period as $dt){
  			$week_array[$dt->format('o-W')] = $dt->format('oW');
		}      
		foreach($week_array as $key =>$dt){
			  $ym = explode('-', $key) ;
			  
			  $interval = new DateInterval('P6D');			  
			  if(array_key_exists($dt, $data1))
  		    	 $graph[$ct]['a']= $data1[$dt];
			  else
				   $graph[$ct]['a']=0;			    
			  if(array_key_exists($dt, $data2))
  		    	 $graph[$ct]['b']= $data2[$dt];
			  else
				   $graph[$ct]['b']=0;
			  //$d1 = $dt->format("n/j");
			  //$d2 = $dt->add($interval)	;
			   $dto = new DateTime();
			 // $dto->setISODate($dt)->format('Y-m-d');
			  $graph[$ct]['date']=  $dto->setISODate($ym[0] ,$ym[1])->format('n/j').' - '.$ret['week_end'] = $dto->modify('+6 days')->format('n/j');
			  //$graph[$ct]['date']= $dto->setISODate($ym[0] ,$ym[1])->format('n/j').' - '.$d2->format("n/j");			   
			 $ct++;			
		}
		
		return $graph;
		 
	}
	public function parserDay($data1='',$data2='',$start_date,$end_date){
		$period = new DatePeriod(
		     new DateTime($start_date),
		     new DateInterval('P1D'),
		     new DateTime($end_date)
		);	
		$ct=0;	
		foreach($period as $dt){
			
			  $graph[$ct]['date']= $dt->format("n/j/y");
			  if(array_key_exists($dt->format("Y-m-d"), $data1))
  		    	 $graph[$ct]['a']= $data1[$dt->format("Y-m-d")];
			  else
				   $graph[$ct]['a']=0;
			  if(array_key_exists($dt->format("Y-m-d"), $data2))
  		    	 $graph[$ct]['b']= $data2[$dt->format("Y-m-d")];
			  else
				   $graph[$ct]['b']=0;
			 $ct++;
			
			 
		}		
		return $graph;
	}
	
	public function getgraph(){
		 
		$act_dt = new DateTime($this->session->userdata('startDate'));
		$grp_dt = new DateTime('2014-06-01');

	    $start_date = ($act_dt < $grp_dt)  ?'2014-06-01 00:00:00':$this->session->userdata('startDate'). " 00:00:00";
		 
		//$start_date = $this->session->userdata('startDate') . " 00:00:00";
        $end_date = $this->session->userdata('endDate') . " 23:59:59";
		$set1 = $this->input->post('set1');
		$set2 =  $this->input->post('set2');		  
		$period = $this->input->post('period');
		$span = array('ByDay','ByWeek','ByMonth');
		$graph= array();
		$ses_array['metric1']=$set1;
		$ses_array['metric2']=$set2;
		if (in_array($period, $span)) {
		    $range_sel=$period;
			$ses_array['period']=$period;
			
		}else{
			$range_sel= 'ByDay';
		}
		$this->session->set_userdata($ses_array);
		switch ($set1) {
		     case "returning_users":
		        $fnc_name1 ='getReturningUsers';
		        break;
		    case "new_users":
		        $fnc_name1 ='getUsers';
		        break;			
			case "active_hostings":
		        $fnc_name1 ='getActiveHosting';
		        break;
			case "nonrec_hostings":
		        $fnc_name1 ='getNewNonRecurringHosting';
		        break;
			case "rec_hostings":
		        $fnc_name1 ='getNewRecurringHosting';
		        break;
		    case "total_chat":
		        $fnc_name1 ='getTotalChat';
		        break;
		    case "new_chat":
		        $fnc_name1 ='getNewChat';
		        break;
			 case "updated_chat":
		        $fnc_name1 ='getUpdatedChat';
		        break;
		    case "guest_chat":
		        $fnc_name1 ='getGuest';
		        break;
			case "host_chat":
		        $fnc_name1 ='getHost';
		        break;
			case "unique_chatter":
		        $fnc_name1 ='getUniqueChatter';
		        break;
		    default:
		         $fnc_name1 ='getUsers';
		}
		switch ($set2) {
		    case "returning_users":
		        $fnc_name2 ='getReturningUsers';
		        break;
		    case "new_users":
		        $fnc_name2 ='getUsers';
		        break;			
			case "active_hostings":
		        $fnc_name2 ='getActiveHosting';
		        break;
			case "nonrec_hostings":
		        $fnc_name2 ='getNewNonRecurringHosting';
		        break;
			case "rec_hostings":
		        $fnc_name2 ='getNewRecurringHosting';
		        break;
		    case "total_chat":
		        $fnc_name2 ='getTotalChat';
		        break;
		    case "new_chat":
		        $fnc_name2 ='getNewChat';
		        break;
			 case "updated_chat":
		        $fnc_name2 ='getUpdatedChat';
		        break;
		    case "guest_chat":
		        $fnc_name2 ='getGuest';
		        break;
			case "host_chat":
		        $fnc_name2 ='getHost';
		        break;
			case "unique_chatter":
		        $fnc_name2 ='getUniqueChatter';
		        break;
		    default:
		         $fnc_name2 ='getUsers';
		}	
		$data1 =array(); $data2 =array();					
		$cal_fnc1= $fnc_name1.$range_sel;
		$cal_fnc2= $fnc_name2.$range_sel;
		if($set1)
			$data1 =$this->user_model->$cal_fnc1($start_date,$end_date);
		if($set2)
			$data2= $this->user_model->$cal_fnc2($start_date,$end_date);
		
		if($range_sel == 'ByDay'){
			$garaph_data= $this->parserDay($data1,$data2,$start_date,$end_date);
		}else if ($range_sel=='ByWeek'){
			$garaph_data= $this->parserWeek($data1,$data2,$start_date,$end_date);
		}else if ($range_sel=='ByMonth'){
			$garaph_data= $this->parserMonth($data1,$data2,$start_date,$end_date);
		}
		else{
			$garaph_data= $this->parserDay($data1,$data2,$start_date,$end_date);
		}
		 echo json_encode($garaph_data);
		exit;
	}

    function logout() {
        $this->authentication->admin_logout();
        redirect('admin');
    }

    function dateRange() {

        $data = array(
            'selected'=> $this->input->post('selected'),
            'startDate' => date("Y-m-d",strtotime($this->input->post('startDate'))),
            'endDate' => date("Y-m-d",strtotime($this->input->post('endDate')))			 
        );

        $this->session->set_userdata($data);

        echo $this->session->userdata('endDate');
        exit;
    }
    
	function check_email_validate(){
		$email = $this->input->post('fp_mail');       
        $email_exists = $this->common_model->getDataExists(array("email"), "admin_users", array("email" => $email));
        if (empty($email_exists)) {
            echo "false";
        } else {
            echo "true";
        }

        exit;
	}
    function forget_password() {
    	$email = $this->input->post('fp_mail');       
        $email_exists = $this->common_model->getDataExists(array("email"), "admin_users", array("email" => $email));
        if (empty($email_exists)) {
            echo "false";
        } else {
            $to_email =$email;			
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
     
			$password =substr(str_shuffle($chars),6,6);
	        $from = $this->config->item('smtp_from_name');
	        $subject = "Reset password";
	        $body_content = "Dear Admin,<br /><br />";
	        $body_content .= "Your Partyhost password has been changed.<br />";
	        $body_content .= "New password :".$password."<br /><br />";
	        $body_content .= "Partyhost";
	        if($this->common_model->send_mail($to_email, $from, $subject, $body_content)) {
	        
	            $this->user_model->update_password($reset['password'],$email_exists->email);
				echo "true";
	        } else {
	            echo "false";
	        }
        }
        exit;
    }
    
    function reset_password() {
        $this->mcontents = array();

        if (!empty($_POST)) {

            $this->load->library('form_validation');
            $this->_init_adminreset_validation_rules(); //server side validation of input values
            if ($this->form_validation->run() == FALSE) {// form validation
                sf('error_message', 'Invalid Username or Password');
                redirect("admin");
            } else {
                $this->_init_adminreset_details();
                $reset['password'] = $this->password;
                $reset['cpassword'] = $this->cpassword;
                $email_exists = $this->common_model->getDataExists(array("email"), "admin_users", array("email" => $this->email));
                if(empty($email_exists)) {
                    sf('error_message', 'Email not exists');
                    redirect("admin/home");
                }
                else if ($reset['password'] == $reset['cpassword']) {
                    $this->user_model->update_password($reset['password'],$email_exists->email);
                    sf('success_message', 'Password reset successfull');
                    redirect("admin/home");
                } else {
                    sf('error_message', 'Password mismatching');
                    redirect("admin");
                }
            }
        }

        // define form attributes
        $data = array(
            'id' => 'login_form',
            'method' => 'post'
        );

        $this->gen_contents['attributes'] = $data;
        $this->gen_contents['title'] = $this->gen_contents['title'] . " - Reset Password";


        $this->load->view('admin/reset_password');
    }

	
    
    function _init_adminreset_validation_rules() {
        $this->form_validation->set_rules('password', 'password', 'required|max_length[50]');
        $this->form_validation->set_rules('cpassword', 'cpassword', 'required|max_length[20]');
    }

    function _init_adminreset_details() {
        $this->email = $this->common_model->safe_html($this->input->post("email"));
        $this->password = $this->common_model->safe_html($this->input->post("password"));
        $this->cpassword = $this->common_model->safe_html($this->input->post("cpassword"));
    }


}
