<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifications extends CI_Controller {
    
    var $gen_contents	=	array();
    
    public function __construct()
    {
        parent::__construct();
        $this->merror['error']	= '';
        $this->msuccess['msg']	= '';
		 $this->gen_contents['current_controller'] = $this->router->fetch_class();
        $this->load->model(array('master_model','admin/permission_model'));
        $this->gen_contents['title']	=	'';
		presetpastdaterange();
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
        
        $this->access_userid = $this->session->userdata("ADMIN_USERID");
        $this->access_usertypeid = $this->session->userdata("USER_TYPE_ID");
        $this->access_permissions = $this->permission_model->get_all_permission();
    }
    
   
	public function index()
        {
            if(!$this->master_model->checkAccess('view', BLOCKS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            } else {
                $this->dashboard();
            }
        }
	 public function dashboard($init=''){
	 	
    	//$this->ajax_list($init);
        $this->gen_contents['p_title']= 'Notifications Dashboard';
        $this->gen_contents['ci_view']= 'admin/block/listing';
        $this->gen_contents['add_link']= '';
        $this->gen_contents['export_link']= '';
        //$this->gen_contents['current_controller'] = "block";
        $breadCrumbs = array( );
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        $this->template->write_view('content', 'admin/notifications',$this->gen_contents);
        $this->template->render();
    }
	
}