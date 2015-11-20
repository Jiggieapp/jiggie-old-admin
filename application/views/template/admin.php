<?php 
 header("Content-Type: text/html; charset=UTF-8"); 
 header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
 header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
 header('P3P: CP="ALL ADM DEV PSAi COM OUR OTRo STP IND ONL"');
?>
<!doctype html>
<!--[if IE 8]>         <html class="ie8"> <![endif]-->
<!--[if IE 9]>         <html class="ie9"> <![endif]-->
<!--[if gt IE 9]><!--> <html> <!--<![endif]-->
    <head>
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Jiggie Admin </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <!--<link rel="shortcut icon" href="/favicon.ico">-->
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="stylesheet" href="<?php echo $this->config->item('assets_css_url')?>/bootstrap.css">
        <link rel="stylesheet" href="<?php echo $this->config->item('assets_css_url')?>/iriy-admin.css">
        <!--link rel="stylesheet" href="demo/css/demo.css"-->
        <link rel="stylesheet" href="<?php echo $this->config->item('assets_css_url')?>font-awesome/css/font-awesome.css">
 		<link rel="stylesheet" href="<?php echo $this->config->item('assets_url')?>css/plugins/jquery-select2.css" />





 		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.min.css">
		<link rel="stylesheet" href="<?php echo $this->config->item('assets_url')?>plugins/bootstrap-daterangepicker/daterangepicker-bs3.css">
        <link rel="stylesheet" href="<?php echo $this->config->item('assets_css_url')?>plugins/bootstrap-switch.css" rel="stylesheet">  		
  		<link rel="stylesheet" href="<?php echo $this->config->item('assets_url')?>plugins/morris/morris.css">
  		<link rel="stylesheet" href="<?php echo $this->config->item('assets_css_url')?>plugins/fullcalendar.min.css">
        <!--[if lt IE 9]>
        <script src="assets/libs/html5shiv/html5shiv.min.js"></script>
        <script src="assets/libs/respond/respond.min.js"></script>
        <![endif]-->
 		<link href="<?php echo $this->config->item('assets_css_url')?>fileinput.min.css" media="all" rel="stylesheet"  >

        <link rel="stylesheet" href="<?php echo $this->config->item('assets_css_url')?>/bootstrap-editable.css">
        <link rel="stylesheet" href="<?php echo $this->config->item('assets_url')?>js/bootstrap-datepicker.css" />
		<script type="text/javascript">
		    var base_url 		= "<?php echo base_url(); ?>";
            var start_date              = "<?php echo date("m/d/Y",strtotime($this->session->userdata('startDate'))); ?>";
            var end_date                = "<?php echo date("m/d/Y",strtotime($this->session->userdata('endDate'))); ?>";
            var start_date_JS           = "<?php echo date("Y-m-d",strtotime($this->session->userdata('startDate'))); ?>";
            var end_date_JS             = "<?php echo date("Y-m-d",strtotime($this->session->userdata('endDate'))); ?>";
            var int_sel					= "<?php echo $this->session->userdata('selected');?>";
            var controller_JS			= "<?php echo $this->router->fetch_class()?>";
		</script>
    </head>
    <?php //class="mod-tutorrank course-1 notloggedin dir-ltr lang-en_utf8 fixedwidthcolumn" id="mod-tutorrank-index" ?>
    <body >
        <header>
           <?php $this->load->view('admin/common/header'); ?>
        </header>
        <div class="page-wrapper">
        	
            <aside class="sidebar sidebar-default">
               <?php $this->load->view('admin/common/left_menu'); ?>
            </aside>

            <div class="page-content">
	            	<div class="page-subheading page-subheading-md page-subheading-xxs">                        
                        <ol class="breadcrumb" >
                            <li><a href="<?php echo base_url().'admin/home'; ?>"><?php echo 'Home'; ?></a></li>
                            <li><a href="<?php echo base_url().'admin/home/dashboard'; ?>"><?php echo 'Dashboard'; ?></a></li>
                            <?php if(isset($breadcrumbs)): ?>
                                <?php foreach($breadcrumbs as $key=>$crumb): ?>
                                    <li><a href="<?php echo base_url().$key; ?>"><?php echo $crumb; ?></a></li>	
                                <?php endforeach; ?> 
                            <?php endif; ?>
                        </ol>                        
                        <?php  //if( $this->router->fetch_method()!='dashboard'): 
                        	$date_picker_class = (strtolower($current_controller)=='events')?'events': 'all'
                        	?>
                      	<span id="reportrange" class="pull-right pull-left-xs <?php echo $date_picker_class ?>" style="">
							<input id="e1" name="e1">
                        </span>
                        
                        <?php  //endif; ?>
                    </div>	
                    <div class="container-fluid-md">				   
                       <?php echo $content ?>
                    </div>
            </div>
        </div>
        <script src="<?php echo $this->config->item('assets_url')?>js/timeshift.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>libs/jquery/jquery.min.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>js/bs3/js/bootstrap.min.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>plugins/bootstrap-daterangepicker/moment.min.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>plugins/bootstrap-daterangepicker/moment-timezone.min.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>js/ejs_production.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>js/signals.min.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>js/jquery.bootpag.min.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>js/hasher.min.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>plugins/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>        
        <script src="<?php echo $this->config->item('assets_url')?>plugins/jquery-navgoco/jquery.navgoco.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>js/jquery.form.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>plugins/jquery-select2/select2.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>libs/jquery-ui/jquery-ui.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>plugins/daterangepicker/jquery.ph.daterangepicker.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>plugins/jquery-validation/jquery.validate.min.js"></script>       
        <script src="<?php echo $this->config->item('assets_js_url')?>typeahead.js" type="text/javascript"></script>
        <script src="<?php echo $this->config->item('assets_js_url')?>fileinput.min.js" type="text/javascript"></script>
        <!--script src="<?php echo $this->config->item('assets_js_url')?>jquery.masonry.min.js" type="text/javascript"></script-->
         <script src="<?php echo $this->config->item('assets_url')?>plugins/raphael/raphael-min.js"></script>
		<script src="<?php echo $this->config->item('assets_url')?>plugins/morris/morris.min.js"></script>		
        <script src="<?php echo $this->config->item('assets_js_url')?>imagesloaded.pkgd.min.js" type="text/javascript"></script>
        <script src="<?php echo $this->config->item('assets_js_url')?>fullcalendar.min.js" type="text/javascript"></script>
        <script src="<?php echo $this->config->item('assets_url')?>js/main.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>js/jquery.datepair.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>js/custom.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>js/bootstrap-datepicker.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>js/jquery.timepicker.js"></script>

        

        <script src="<?php echo $this->config->item('assets_url')?>plugins/jquery-validation/additional-methods.min.js"></script>
        <!--[if lt IE 9]>
        <script src="<?php echo $this->config->item('assets_css_url')?>/plugins/flot/excanvas.min.js"></script>
        <![endif]-->
       
		<script src="<?php echo $this->config->item('assets_url')?>js/jquery.confirm.min.js"></script>
        <script src="<?php echo $this->config->item('assets_url')?>js/bs3/js/bootstrap-editable.js"></script>
        

        <?php  if($this->router->fetch_class()=='hostings' && $this->router->fetch_method()=='details'): ?>
           
                <script type="text/javascript">

                    $(document).ready(function(){ 
                        $('#userselect').editable({
	                        value: <?php echo $user->id; ?>,
	                        source: [
	                            <?php foreach($users_all as $user_data):  ?>
	                                {value: "<?php echo $user_data["id"];?>", text: "<?php echo $user_data["name"];?>"},
	                            <?php endforeach; ?>
	                        ],
	                         url: base_url+'admin/hostings/'+'changeuser'+'/'+$(".panel-body").attr("hosting_id"),
	                         success: function(msg) {		 	
							 	var data = jQuery.parseJSON(msg); 		
							 	
							    if(data.status == 'success'){
							    	console.log(data.url) 	; 
							    	$('#user_image_url').html(data.url);
							    	$('#user_image_url').editable('setValue', data.url)
							    	
							    	$('#host_user_email').html(data.email);
							    }else{
							    	
							    }
							 }
							 

                        });
                         
                        $('#venue').editable({
                        value: <?php echo $venue->id; ?>,
                        source: [
                            <?php foreach($venue_all as $venue_data): ?>
                                {value: "<?php echo $venue_data['id'];?>", text: "<?php echo $venue_data['name'];?>"},
                            <?php endforeach; ?>
                        ],
                         url: base_url+'admin/hostings/'+'save'+'/'+$(".panel-body").attr("hosting_id")
                        });
                         
                        
                    });
                </script>
        <?php endif; ?>
         <?php  if($this->router->fetch_class()=='venue' && $this->router->fetch_method()=='duplicate_venue'): ?>
         	 	<script type="text/javascript">
	                $(document).ready(function(){
	                	
		                	$("#file1").fileinput({
							    initialPreview: venue_img_1 ? "<img src='"+venue_img_1+"' class='file-preview-image'>":false,
							    'showUpload':false,
							    'allowedFileTypes':['image'],allowedFileExtensions:['jpg','png'],
							    maxFileSize:2000							    
							});						 
							$("#file2").fileinput({
							   initialPreview: venue_img_2 ? "<img src='"+venue_img_2+"' class='file-preview-image'>":false,
							    'showUpload':false,
							    'allowedFileTypes':['image'],allowedFileExtensions:['jpg','png'],
							    maxFileSize:2000
							});						 
							$("#file3").fileinput({
							    initialPreview: venue_img_3 ? "<img src='"+venue_img_3+"' class='file-preview-image'>":false,
							    'showUpload':false,
							    'allowedFileTypes':['image'],allowedFileExtensions:['jpg','png'],
							    maxFileSize:2000
							});						
							$("#file4").fileinput({
							    initialPreview: venue_img_4 ? "<img src='"+venue_img_4+"' class='file-preview-image'>":false,
							    'showUpload':false,
							    'allowedFileTypes':['image'],allowedFileExtensions:['jpg','png'],
							    maxFileSize:2000
							});						
							$("#file5").fileinput({
							    initialPreview: venue_img_5 ? "<img src='"+venue_img_5+"' class='file-preview-image'>":false,
							    'showUpload':false,
							    'allowedFileTypes':['image'],allowedFileExtensions:['jpg','png'],
							    maxFileSize:2000
							   
							});					
						
	         	 	});
                </script>
         <?php endif; ?>
    </body>
</html>
