<div class="row">
    
</div>
<div class="row">
    <div class="col-md-12">
        
        <div class="panel">            
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-8">
                    	<div class="col-xs-12 col-sm-3 npxx" id="data-ele1">
	                        <select name="data-set1" id="data-set1"  class="form-control input-sm select2-offscreen"  tabindex="-1" title="">
	                            <option value=""></option>
	                            <?php  foreach($this->config->item('metrics') as $key=> $val ){
	                            	$sel =('total_users'==$key)?'selected':'';
	                            	echo '<option value="'.$key.'" data-id="'.$key.'" '.$sel.' data-name="'.$val.'">'.$val.'</option>';								
								 } ?>							                           
							</select> 
	                        
	                    </div>
	                    <label class="control-label pull-left">Vs</label>
	                    <div class="col-xs-12 col-sm-3 text-center mbsm npxx" id="data-ele2">
	                        <select name="data-set2" id="data-set2"  class="form-control input-sm select2-offscreen"  tabindex="-1" title="">
	                            <option value=""></option>
	                             <?php  foreach($this->config->item('metrics') as $key=> $val ){ 
	                            	echo '<option value="'.$key.'" data-id="'.$key.'" data-name="'.$val.'">'.$val.'</option>';								
								 } ?>                    
							</select> 
	                    </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 text-right">
                        <div data-toggle="buttons" class="btn-group" id="goptions">
                            <a id="Byday" class="btn btn-info active"><input type="radio" name="options">Day</a>
                            <a id="ByWeek" class="btn btn-info"><input type="radio" name="options">Week</a>
                            <a id="ByMonth" class="btn btn-info"><input type="radio" name="options">Month</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body panel-body-primary padding-sm-vertical">
                <div id="ph_overviewgrpah" class="morris-hover-dark" style="height: 243px"></div>
            </div>
        </div>
    </div>
  
</div>

<div class="row">
    <div class="col-sm-6 col-lg-4">
        <div class="panel panel-metric">
            <div class="panel-body">
                <div class="metric-box">
                	<header>
                        <h3 class="thin">Total Users</h3>
                    </header>
                    <div class="value">
                        2,154
                    </div>                   
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="panel panel-metric">
            <div class="panel-body">
                <div class="metric-box">
                	<header>
                        <h3 class="thin">New Users</h3>
                    </header>
                    <div class="value">
                        154
                    </div>                   
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-lg-4">
        <div class="panel panel-metric">
            <div class="panel-body">
                <div class="metric-box">
                	<header>
                        <h3 class="thin">Total Chat</h3>
                    </header>
                    <div class="value">
                        500
                    </div>                   
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="panel panel-metric">
            <div class="panel-body">
                <div class="metric-box">
                	<header>
                        <h3 class="thin">New Chat</h3>
                    </header>
                    <div class="value">
                        300
                    </div>                   
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="panel panel-metric">
            <div class="panel-body">
                <div class="metric-box">
                	<header>
                        <h3 class="thin">Updated Chats</h3>
                    </header>
                    <div class="value">
                        200
                    </div>                   
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-lg-4">
        <div class="panel panel-metric">
            <div class="panel-body">
                <div class="metric-box">
                	<header>
                        <h3 class="thin">Unique Guest Chats</h3>
                    </header>
                    <div class="value">
                        154
                    </div>                   
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="panel panel-metric">
            <div class="panel-body">
                <div class="metric-box">
                	<header>
                        <h3 class="thin">Unique Host Chats</h3>
                    </header>
                    <div class="value">
                        54
                    </div>                   
                </div>
            </div>
        </div>
    </div>
</div>