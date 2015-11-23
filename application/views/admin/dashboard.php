<div class="row ph_db">	
    <div class="col-md-12">
        
        <div class="panel">            
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-8">
                    	<div class="col-xs-12 col-sm-5 npxx" id="data-ele1"><?php //echo $this->session->userdata('metric1').$this->session->userdata('metric2')?>
	                        <select name="data-set1" id="data-set1"  class="form-control input-sm select2-offscreen"  tabindex="-1" title="">
	                            <option value=""></option>
	                            <?php  foreach($this->config->item('metrics') as $key=> $val ){
	                            	$sel =($this->session->userdata('metric1')==$key)?'selected':'';
	                            	echo '<option value="'.$key.'" data-id="'.$key.'" '.$sel.' data-name="'.$val.'">'.$val.'</option>';								
								 } ?>							                           
							</select> 
	                        
	                    </div>
	                    <label class="control-label pull-left">Vs</label>
	                    <div class="col-xs-12 col-sm-5  mbsm npxx" id="data-ele2">
	                        <select name="data-set2" id="data-set2"  class="form-control input-sm select2-offscreen"  tabindex="-1" title="">
	                            <option value=""></option>
	                             <?php  foreach($this->config->item('metrics') as $key=> $val ){
	                             	$sel = ( $key== $this->session->userdata('metric2'))?'selected':''; 
	                            	echo '<option value="'.$key.'" data-id="'.$key.'"'.$sel.' data-name="'.$val.'">'.$val.'</option>';								
								 } ?>                    
							</select> 
	                    </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 text-right">
                    	<?php $p = $this->session->userdata('period');
                    		$day = (!$p || $p =='ByDay' )? 'active':'';
							$week = (  $p =='ByWeek')? 'active':'';
							$month = (  $p =='ByMonth')? 'active':'';
                    	?>
                        <div data-toggle="buttons" class="btn-group" id="goptions">
                            <a id="ByDay" class="btn btn-info <?php echo $day?>"><input type="radio" name="options">Day</a>
                            <a id="ByWeek" class="btn btn-info <?php echo $week?>"><input type="radio" name="options">Week</a>
                            <a id="ByMonth" class="btn btn-info <?php echo $month?>"><input type="radio" name="options">Month</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body panel-body-primary padding-sm-vertical">
                <div id="ph_overviewgrpah" class="morris-hover-dark" style="height: 243px"></div>
            </div>
        </div>
    </div>  

    <div class="col-md-12">
        <header>
            <h3 class="thin">New Users</h3>
        </header>
        <div class="row">
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body ">
                        <h5 class="page-header no-margin semi-bold">Today</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $user_count['user_today']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body">
                        <h5 class="page-header no-margin semi-bold">Yesterday</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $user_count['user_yesterday']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body">
                        <h5 class="page-header no-margin semi-bold">Last 7 days</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $user_count['user_week']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body">
                        <h5 class="page-header no-margin semi-bold">Previous 7 days</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom text-center"><?php echo $user_count['user_prev_week']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body">
                        <h5 class="page-header no-margin semi-bold">Last 30 days</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $user_count['user_month']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body">
                        <h5 class="page-header no-margin semi-bold">Previous 30 days</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $user_count['user_prev_month']; ?></h2>
                    </div>
                </div>
        	</div>
        	
        </div>
        <div class="row">
        	<div class="col-md-6 npxx">
        		<div class="panel panel-default">
                    <div class="panel-body">
                        <h5 class="page-header no-margin semi-bold">Total</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $user_count['user_total']; ?></h2>
                    </div>
                </div>
        	</div>
        	
        	<div class="col-md-6 npxx">
        		<div class="panel panel-default">
                    <div class="panel-body">
                        <h5 class="page-header no-margin semi-bold">New users</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $user_count['user_new']; ?></h2>
                    </div>
                </div>
        	</div>
        </div>        
    </div>
</div>
<div class="row ph_db">
    <div class="col-md-12">
        <header>
            <h3 class="thin">New Hostings</h3>
        </header>
        <div class="row">
        	<div class="col-md-2">
        		<div class="panel panel-default ">
                    <div class="panel-body panel-body-success">
                        <h5 class="page-header no-margin semi-bold">Today</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $hosting_count['hosting_today']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-success">
                        <h5 class="page-header no-margin semi-bold">Yesterday</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $hosting_count['hosting_yesterday']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-success">
                        <h5 class="page-header no-margin semi-bold">Last 7 days</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $hosting_count['hosting_week']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-success">
                        <h5 class="page-header no-margin semi-bold">Previous 7 days</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom "><?php echo $hosting_count['hosting_prev_week']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-success">
                        <h5 class="page-header no-margin semi-bold">Last 30 days</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $hosting_count['hosting_month']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-success">
                        <h5 class="page-header no-margin semi-bold">Previous 30 days</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $hosting_count['hosting_prev_month']; ?></h2>
                    </div>
                </div>
        	</div>
        	
        </div>
        <div class="row">
        	<div class="col-md-12">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-success">
                        <h5 class="page-header no-margin semi-bold">Total</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $hosting_count['hosting_total']; ?></h2>
                    </div>
                </div>
        	</div>
        </div>       
    </div>
</div>
<div class="row ph_db">
    <div class="col-md-12">
        <header>
            <h3 class="thin">New Chats</h3>
        </header>
        <div class="row">
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Today</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $chats_count['chats_today']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Yesterday</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $chats_count['chats_yesterday']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Last 7 days</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $chats_count['chats_week']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Previous 7 days</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom text-center"><?php echo $chats_count['chats_prev_week']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Last 30 days</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $chats_count['chats_month']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Previous 30 days</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $chats_count['chats_prev_month']; ?></h2>
                    </div>
                </div>
        	</div>
        	
        </div>
        <div class="row">
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Total</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $chats_count['chats_total']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">New Chat</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $chats_count['chats_new']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Updated Chats</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $chats_count['updated_chat']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Unique Chatter</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $chats_count['unique_chatter']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning nplr">
                        <h5 class="page-header no-margin semi-bold">Unique Guest Chatter</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $chats_count['guest_chat']; ?></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning nplr">
                        <h5 class="page-header no-margin semi-bold">Unique Host Chatter</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"><?php echo $chats_count['host_chat']; ?></h2>
                    </div>
                </div>
        	</div>
        </div>
        <div class="row">
        	<div class="col-md-12">
        		<div class="f11">*chat = At least one message was sent by both sides.</div>
        		<div class="f11">*chatter =  Unique users that have actually sent a message in any given period.</div>
        	</div>
        </div>        
    </div>
</div>