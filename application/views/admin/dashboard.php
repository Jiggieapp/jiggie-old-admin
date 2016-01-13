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

      <div class="col-md-12" id="user_stat_number">
        <header>
            <h3 class="thin">Users</h3>
        </header>
        <div class="row">
          <div class="col-md-6">
            <h4>Internal</h4>
            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-body ">
                  <h5 class="page-header no-margin semi-bold">Today</h5>
                  <h2 id="user_stat_today" class="semi-bold text-primary-dark no-margin-bottom counter">0</h2>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-body">
                  <h5 class="page-header no-margin semi-bold">Yesterday</h5>
                  <h2 id="user_stat_yesterday" class="semi-bold text-primary-dark no-margin-bottom counter">0</h2>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-body">
                  <h5 class="page-header no-margin semi-bold">Last 30 days</h5>
                  <h2 id="user_stat_30day" class="semi-bold text-primary-dark no-margin-bottom">0</h2>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6" >
            <h4>Mix Panel</h4>
            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-body">
                  <h5 class="page-header no-margin semi-bold">Today</h5>
                  <h2 id="user_mp_today" class="semi-bold text-primary-dark no-margin-bottom text-center">0</h2>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-body">
                  <h5 class="page-header no-margin semi-bold">Yesterday</h5>
                  <h2 id="user_mp_yesterday" class="semi-bold text-primary-dark no-margin-bottom">0</h2>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-body">
                  <h5 class="page-header no-margin semi-bold">Last 30 days</h5>
                  <h2 id="user_mp_30day" class="semi-bold text-primary-dark no-margin-bottom">0</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
        	<div class="col-md-6 npxx">
        		<div class="panel panel-default">
                    <div class="panel-body">
                        <h5 class="page-header no-margin semi-bold">Total Internal</h5>
                        <h2 id="total_user_stat" class="semi-bold text-primary-dark no-margin-bottom counter">0</h2>
                    </div>
                </div>
        	</div>
        	
        	<div class="col-md-6 npxx">
        		<div class="panel panel-default">
                    <div class="panel-body">
                        <h5 class="page-header no-margin semi-bold">Total Mixpanel</h5>
                        <h2 id="total_mp_stat" class="semi-bold text-primary-dark no-margin-bottom counter">0</h2>
                    </div>
                </div>
        	</div>
        </div>        
    </div>
</div>
<div class="row ph_db">
    <div class="col-md-12" id="user_feed_number">
        <header>
            <h3 class="thin">Social</h3>
        </header>
        <div class="row">
        	<div class="col-md-2">
        		<div class="panel panel-default ">
                    <div class="panel-body panel-body-success">
                        <h5 class="page-header no-margin semi-bold">Accepted Feed</h5>
                        <h2 id="feed_accepted" class="semi-bold text-primary-dark no-margin-bottom">0</h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-success">
                        <h5 class="page-header no-margin semi-bold">Passed Feed</h5>
                        <h2 id="feed_passed" class="semi-bold text-primary-dark no-margin-bottom">0</h2>
                    </div>
                </div>
        	</div>
        	<!--div class="col-md-2">
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
        	</div-->
        </div>
    </div>
</div>
<div class="row ph_db">
    <div class="col-md-12" id="user_chat_number">
        <header>
            <h3 class="thin">Chats</h3>
        </header>
        <div class="row">
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Today</h5>
                        <h2 id="chat_stat_today" class="semi-bold text-primary-dark no-margin-bottom counter">0</h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Yesterday</h5>
                        <h2 id="chat_stat_yesterday" class="semi-bold text-primary-dark no-margin-bottom">0</h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Last 7 days</h5>
                        <h2 id="chat_stat_7day" class="semi-bold text-primary-dark no-margin-bottom">0</h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Previous 7 days</h5>
                        <h2 id="chat_stat_14day" class="semi-bold text-primary-dark no-margin-bottom text-center">0</h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Last 30 days</h5>
                        <h2 id="chat_stat_30day" class="semi-bold text-primary-dark no-margin-bottom">0</h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Previous 30 days</h5>
                        <h2 id="chat_stat_60day" class="semi-bold text-primary-dark no-margin-bottom">0</h2>
                    </div>
                </div>
        	</div>
        	
        </div>
        <div class="row">
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Total</h5>
                        <h2 id="total_chat_stat" class="semi-bold text-primary-dark no-margin-bottom">0</h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">New Chat</h5>
                        <h2 id="total_new_chat" class="semi-bold text-primary-dark no-margin-bottom">0</h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Updated Chats</h5>
                        <h2 id="total_updated_chat" class="semi-bold text-primary-dark no-margin-bottom">0</h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning">
                        <h5 class="page-header no-margin semi-bold">Unique Chatter</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning nplr">
                        <h5 class="page-header no-margin semi-bold">Unique Guest Chatter</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"></h2>
                    </div>
                </div>
        	</div>
        	<div class="col-md-2">
        		<div class="panel panel-default">
                    <div class="panel-body panel-body-warning nplr">
                        <h5 class="page-header no-margin semi-bold">Unique Host Chatter</h5>
                        <h2 class="semi-bold text-primary-dark no-margin-bottom"></h2>
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