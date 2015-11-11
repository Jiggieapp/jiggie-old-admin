<div class="">
    <form class="form-horizontal form-bordered" role="form" id="hosting-form" method="post">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create Hosting</h4>
            </div>
            <div class="panel-body">
                <?php echo showMessage(); //echo $error;?>

                <?php if (isset($error)) { ?>
                    <div class="alert alert-block  alert-danger fade in">
                        <button class="close" type="button" data-dismiss="alert">×</button><?php echo $error; ?></div>
                <?php } ?>
				<p class='f11'>All <span class="asterisk">*</span> marked fields are mandatory</p>
				<div class="form-group">
                    <label class="control-label col-sm-3">Event Title </label>
					<?php $eventid = $events->event_type =='special' ? $events->_id:$events->event_id?>
                    <div class="controls col-sm-4">
                    	<?php echo $events->title?> 
                    	<input type="hidden" name="w_event_id" value="<?php echo $events->event_id ?>" />
                    	<input type="hidden" name="s_event_id" value="<?php echo $events->_id ?>" />  
                    	<input type="hidden" name="event_type" value="<?php echo $events->event_type ?>" />                       
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Event Start Time</label>

                    <div class="controls col-sm-4" id="eventstarttime" data-eventstarttime="<?php echo $events->start_datetime_str?>" >
                    	<?php echo $events->start_datetime_str?>                         
                    </div>
                </div>
                 <div class="form-group">
                    <label class="control-label col-sm-3" id="eventendtime" data-eventendtime="<?php echo $events->end_datetime_str?>" >Event End Time</label>

                    <div class="controls col-sm-4">
                    	<?php echo $events->end_datetime_str?>                         
                    </div>
                </div>
                <div class="form-group" id="hosting_name_div">
                    <label class="control-label col-sm-3">User Email<span class="asterisk">*</span></label>

                    <div class="controls col-sm-4" id ="hosting_name_div_err">
                    	<input class="typeahead form-control" name="uemail" id="uemail" type="text" value="<?php echo $this->input->post('uemail'); ?>" placeholder="User Email" required>
                        <input type="hidden" name="user_fb_id" id="user_fb_id" value="<?php echo $this->input->post('user_fb_id'); ?>" /> 
                    </div>
                </div>

                <!--div class="form-group">
                    <label class="control-label col-sm-3">Select Venue <span class="asterisk">*</span></label>
                    <div class="controls col-sm-4">
                        <select name="venue" id="venue" class="form-control" required>                           
                            <?php foreach ($venues as $venue) {
                            	$location = $venue->address.', '.$venue->city.', '.$venue->state.', '.$venue->zip;                        	
                            	?>                            	
                                <option data-location="<?php echo $location?>" value="<?php echo $venue->_id; ?>" <?php echo $this->input->post("venue_sel") == $venue->_id ? "selected" : ""; ?>><?php echo $venue->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Theme</label>

                    <div class="controls col-sm-4">
                        <input name="theme" type="text" class="form-control" placeholder="Theme" value="<?php echo $this->input->post('theme'); ?>" />

                    </div>
                </div-->

                
				<?php if($events->event_type =='special'): ?>
                <div class="form-group">
                    <label class="control-label col-sm-3">Date <span class="asterisk">*</span></label>
                    <div class="controls col-sm-4">
                    	 <?php $default_s_date = ($this->input->post('start_date')?$this->input->post('start_date'):date("M d, Y", strtotime($events->start_datetime_str)))?>
						 <input readonly="readonly" style="background-color: #fff; cursor: auto" name="start_date" id="start_date"   class="event_range_start form-control" type="text" class="form-control" placeholder="Start Date" value="<?php echo $default_s_date ?>" required/>
                        

                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Start Time <span class="asterisk">*</span></label>
                    <div class="col-sm-3 col-md-6 col-lg-4">
                       <?php $default = ($this->input->post('starttime')?$this->input->post('starttime'):date("h:i, A", strtotime($events->start_datetime_str)))?>
                        <div class="input-group">
                            <input readonly="readonly" style="background-color: #fff; cursor: auto" type="text" class="form-control"  data-minute-step="30"  data-rel="eventtimepicker" name="starttime" id="starttime"     value="<?php echo $default; ?>" placeholder="00:00:AM" required>
                            
                        </div>
                        
                    </div>
                </div>
				<div class="form-group">
                    <label class="control-label col-sm-3"> Hosting time <span class="asterisk">*</span></label>
                    <div class="col-sm-3 col-md-6 col-lg-4">
                       <?php $default = ($this->input->post('endtime')?$this->input->post('endtime'):'')?>
                        <div class="input-group">
                        	<select class="form-control" name="event_time" id="event_time" >
                        		 <?php for($i = 1; $i<=24; $i++) { ?>
	                                <option value="<?php echo $i;?>" <?php echo $this->input->post("event_time") == $i ? "selected" : ""; ?>><?php echo $i;?></option>
	                            <?php } ?>
                        	</select> 
                        </div>                        
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3"> End Time <span class="asterisk">*</span></label>
                    <div class="col-sm-3 col-md-6 col-lg-4">
                       <?php $default = ($this->input->post('endtime')?$this->input->post('endtime'):'')?>
                        <div class="input-group">
                        	
                            <input type="text" class="form-control"  name="endtime" id="endtime" disabled="disabled"  value="<?php $this->input->post('end_time'); ?>" >
                            <input type="hidden" class="form-control"  name="end_time" id="end_time"  value="<?php $this->input->post('end_time'); ?>" >
                        </div>
                        
                    </div>
                </div>
                <?php endif;?>
                <div class="form-group">
                    <label class="control-label col-sm-3"> Description <span class="asterisk">*</span></label>
                    <div class="controls col-sm-6">
                        <textarea name="description" class="form-control autogrow" placeholder="Description" rows="4" style="height: 105px;" required><?php echo $this->input->post('description'); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Recurring</label>
                    <div class="controls col-sm-6">
                    	 <?php $checked = $events->event_type =='weekly' ? 'checked':''?>
                         <input type="checkbox" name="is_recurring" readonly id="is_recurring" data-off-color="success" data-off-text="NO"  data-on-text="YES" data-size="small" data-on-color="primary" value ="1" <?php echo $checked?>   class="boot-switch"/>
                         <span id="p_recurring"></span>
                    </div>
                </div>
				<?php if($events->event_type =='special'): ?>
                <div class="form-group">                    
                    <label class="control-label col-sm-3">Verified Table</label>
                    <div class="controls col-sm-6">
                         <input type="checkbox" name="verified_table" id="verified_table" data-off-color="success" data-off-text="NO"  data-on-text="YES" data-size="small" data-on-color="primary" value ="1" <?php echo $this->input->post("verified_table") == 1 ? "checked" : ""; ?> class="boot-switch"/>
                         <span id="p_promoter"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Rank</label>

                    <div class="controls col-sm-2">                        
                          <select name="rank" id="rank_h" aria-controls="table-basic" class="form-control input-sm select2-offscreen" tabindex="-1" title="" required>   
                            <?php $cnt =0;
                            while ($cnt <=100 ) { ?>
                                <option value="<?php echo $cnt; ?>" <?php echo $this->input->post("rank") == $cnt ? "selected" : ""; ?>><?php echo $cnt; ?></option>
                            <?php $cnt ++;} ?>
                        </select>
                    </div>
                    <div class="col-sm-4">Range 1-100</div>
                </div>
				<?php endif;?>
                <div class="form-group">

                    <label class="control-label col-sm-3">Hosting Status</label>
                    <div class="controls col-sm-6">
                        <input type="checkbox" name="hstatus" id="hstatus" data-off-color="success" data-off-text="INACTIVE"  data-on-text="ACTIVE" data-size="small" data-on-color="primary" value ="1" <?php echo $this->input->post("hstatus") == 1 ? "checked" : ""; ?> class="boot-switch" <?php if(!isset($_POST["hstatus"])) echo "checked";?>/>
                         <span id="p_hstatus"></span>
                    </div>
                </div>

                <!--<div class="form-group">
                    <label class="control-label col-sm-3">User Image URL</label>

                    <div class="controls col-sm-4">
                        <input name="user_image_url" type="text" class="form-control" placeholder="User Image URL" value="<?php echo $this->input->post('user_image_url'); ?>" />

                    </div>
                </div>-->

                <div class="form-group">
                    <label class="control-label col-sm-3"></label>
                    <div class="controls col-sm-4">
                        <button type="submit" class="btn btn-primary">Create Hosting</button>
                        <a class="btn btn-default" href="<?php echo base_url().'/admin/hostings'; ?>">Cancel</a>
                    </div>
                </div>

            </div>
        </div>
        <input type="hidden" value="" name="email_verified" id="email_verified"/>
    </form>
</div> 
