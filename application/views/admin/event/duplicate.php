<div class="">
	<?php //var_dump($events)?>
    <form class="form-horizontal form-bordered" role="form" id="event-form" method="post">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create Event</h4>
            </div>
            <div class="panel-body" id="section_create_event">
                <?php //echo showMessage(); //echo $error;?>

                <?php if (isset($error)) { ?>
                    <div class="alert alert-block  alert-danger fade in">
                        <button class="close" type="button" data-dismiss="alert">×</button><?php echo $error; ?></div>
                <?php } ?>
				<p class='f11'>All <span class="asterisk">*</span> marked fields are mandatory</p>
                <div class="form-group" id="hosting_name_div">
                    <label class="control-label col-sm-3">Title<span class="asterisk">*</span></label>

                    <div class="controls col-sm-4" id ="hosting_name_div_err">
                    	<input class="typeahead form-control" name="title" id="title" type="text" placeholder="Event Title" value="<?php echo set_post_value('title', $events->title) ; ?>" required>
                        
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Select Venue <span class="asterisk">*</span></label>
                    <div class="controls col-sm-4">
                        <select name="venue_sel" id="venue_sel" class="form-control" required>                           
                            <?php foreach ($venues as $venue) {
                            	$location = $venue->address.', '.$venue->city.', '.$venue->state.', '.$venue->zip;                        	
                            	?>                            	
                                <option data-location="<?php echo $location?>" value="<?php echo $venue->_id; ?>" <?php echo set_post_value('venue_sel', $events->venue->_id) == $venue->_id ? "selected" : ""; ?>><?php echo $venue->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>



                <div class="form-group">
                    <label class="control-label col-sm-3">Select Fulfillment Type <span class="asterisk">*</span></label>
                    <div class="controls col-sm-4">
                        <select name="fullfillment_type" id="fullfillment_type" class="form-control">
                          <option value="none">None</option>
                          <option value="phone_number">Phone Number</option>
                          <option value="link">Link</option>
                          <option value="reservation">Reservation</option>
                          <option value="purchase">Purchase</option>
                        </select>

                    </div>
                </div>


                <div class="form-group" id="hosting_name_div">
                    <label class="control-label col-sm-3">Fulfillment Value</label>

                    <div class="controls col-sm-4" id ="hosting_name_div_err">
                      <input class="typeahead form-control" name="fullfillment_value" id="fullfillment_value" type="text" placeholder="Fullfillment Value" value="<?php echo $this->input->post('fullfillment_value'); ?>">
                    </div>
                </div>





                <div class="form-group">
                    <label class="control-label col-sm-3">Location</label>

                    <div class="controls col-sm-4">
                        <input name="location" id="location" type="text" class="form-control" disabled="disabled" placeholder="Location" value="<?php echo set_post_value('location', $events->location)  ?>" />

                    </div>
                </div>
				<div class="form-group">
                    <label class="control-label col-sm-3">Event Type</label>

                    <div class="controls col-sm-4">
                        <select id="event_type" class="form-control" name="event_type">							
							<option <?php echo set_post_value('event_type', $events->event_type) == "special" ? "selected" : ""; ?> value="special">Special</option>
							<option <?php echo set_post_value('event_type', $events->event_type) == "weekly" ? "selected" : ""; ?> value="weekly">Weekly</option>
						</select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" ><span id="startdatelabel">Start series date</span> <span class="asterisk">*</span></label>
                    <div class="controls col-sm-4">
                        <input readonly="readonly" style="background-color: #fff; cursor: auto" name="start_date" id="start_date"   class="event_range_start form-control" type="text" class="form-control" placeholder="Start Date" value="<?php echo set_post_value('start_date', date('M d, Y',strtotime($events->start_datetime_str))) ?>" required/>

                    </div>
                </div>
                <?php $end = date('M d, Y', strtotime(@$events->end_series_datetime));
						  $today   = date('m/d/y h:i a'); 
						  $today_dt = strtotime($today);
						  $expire_dt = strtotime($end);
						  if ($expire_dt > $today_dt) {
						  	$edte =$end;
							 $foerver ='';
						  }else{
						  	$foerver ='checked';
							$edte='';
						  }						  
					?>	   
                 <div  id="endrangeselection">
                 	<div class="form-group">
                 		<label class="control-label col-sm-3" >End series date<span class="asterisk">*</span></label>
	                    <div class="controls col-sm-4">
	                        
						    <input readonly="readonly" style="background-color: #fff; cursor: auto" name="end_date" id="end_date"   class="event_range form-control"  type="text" class="form-control" placeholder="End Date" value="<?php echo set_post_value('end_date', $edte ) ?>" /> 
	                    </div>
                 	</div>
                 	<div class="form-group">
                 		<label class="control-label col-sm-3" ></label>
                 		<div class=" controls col-sm-4 checkbox" > <label><input name="forever" id="forever" type="checkbox"<?php echo $foerver;?> value="1">Forever</label></div>
                 	</div>
                    
                </div>
                 <div class="form-group">
                    <label class="control-label col-sm-3">Start Time <span class="asterisk">*</span></label>
                    <div class="col-sm-3 col-md-6 col-lg-4">
                       <?php $default = ($this->input->post('starttime')?$this->input->post('starttime'):'')?>
                        <div class="input-group">
                            <input readonly="readonly" style="background-color: #fff; cursor: auto" type="text" class="form-control"  data-minute-step="30"  data-rel="eventtimepicker" name="starttime" id="starttime"     value="<?php echo  set_post_value('starttime', date('g:i A',strtotime($events->start_datetime_str))); ?>" placeholder="00:00:AM" required>
                            
                        </div>
                        
                    </div>
                </div>
                 <div class="form-group">
                    <label class="control-label col-sm-3"> Event Duration <span class="asterisk">*</span></label>
                    <div class="col-sm-3 col-md-6 col-lg-4">
                       <?php $default = ($this->input->post('endtime')?$this->input->post('endtime'):'')?>
                        <div class="input-group">
                        	<?php
                        	$date1=  new DateTime($events->end_datetime_str);
							$date2= new DateTime($events->start_datetime_str);
							$diff=  (int)$date1->diff($date2)->format("%h");
                        	?>
                        	<select class="form-control" name="event_time" id="event_time" >
                        		
                        		 <?php for($i = 1; $i<=24; $i++) { ?>
	                                <option value="<?php echo $i;?>" <?php echo set_post_value('event_time', $diff) == $i ? "selected" : ""; ?>><?php echo $i;?></option>
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
                        	
                            <input type="text" class="form-control"  name="endtime" id="endtime" disabled="disabled"  value="<?php echo set_post_value('endtime', date('M d, Y',strtotime($events->end_datetime_str))) ?>" >
                            <input type="hidden" class="form-control"  name="end_time" id="end_time"  value="<?php $this->input->post('end_time'); ?>" >
                        </div>
                        
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3"> Description
                    	<p class="f11">(If left blank, event description will inherit venue description)</p>
                    	</label>
                    <div class="controls col-sm-6">
                        <textarea name="description" class="form-control autogrow" placeholder="Description" rows="4" style="height: 105px;"><?php echo set_post_value('description', $events->description)  ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Event source</label>
                    <div class="col-md-3 col-sm-6">
                      <!--input type="checkbox" name="featured_event" id="featured_event" data-off-color="success" data-off-text="NO"  data-on-text="YES" data-size="small" data-on-color="primary" value ="0"   class="boot-switch"/-->
                       <select name="featured_event" id="featured_event" class="form-control" required> 
                      	    <option <?php echo set_post_value('featured_event', $events->source) == "admin" ? "selected" : ""; ?> value="admin">Admin</option>
                      		<option <?php echo set_post_value('featured_event', $events->source) == "user" ? "selected" : ""; ?> value="user">User</option>                      		
                      		<option <?php echo set_post_value('featured_event', $events->source) ==  "featured"? "selected" : ""; ?> value="featured">Featured</option>                      	 
                       </select> 
                    
                    </div>
                </div>
                 <div class="form-group">
                    <label class="control-label col-sm-3">Rank <span class="asterisk">*</span></label>

                    <div class="controls col-sm-2">
                        <select name="rank" class="form-control" required>                            
                            <?php for($i = 1; $i<=10; $i++) { ?>
                                <option value="<?php echo $i;?>" <?php echo set_post_value('rank', $events->rank)== $i ? "selected" : ""; ?>><?php echo $i;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Event status</label>
                    <div class="col-md-3 col-sm-6">
                      <!--input type="checkbox" name="event_status" id="estatus" data-off-color="success" data-off-text="INACTIVE"  data-on-text="ACTIVE" data-size="small" data-on-color="primary" value ="1" checked="true" class="boot-switch"/-->
                      <select name="event_status" class="form-control" required> 
                      	    <option <?php echo set_post_value('event_status', $events->status) == "draft" ? "selected" : ""; ?>  value="draft">Draft</option>
                      		<option <?php echo set_post_value('event_status', $events->status) == "published" ? "selected" : ""; ?>  value="published">Published</option>                      		
                      		<option <?php echo set_post_value('event_status', $events->status) == "passed" ? "selected" : ""; ?>  value="passed">Passed</option>
                      		<option <?php echo set_post_value('event_status', $events->status) == "inactive" ? "selected" : ""; ?>  value="inactive">Inactive</option>
                      		<option <?php echo set_post_value('event_status', $events->status) == "soldout" ? "selected" : ""; ?>  value="soldout">Sold Out</option> 
                       </select>                    
                    </div>
                </div>

                <div class="form-group" id="hosting_name_div">
                    <label class="control-label col-sm-3">Photos</label>

                    <div class="controls col-sm-4" id ="hosting_name_div_err">
                      <input class="typeahead form-control" name="fullfillment_value" id="fullfillment_value" type="text" placeholder="Fullfillment Value" value="<?php echo set_post_value('photos', $events->photos[0]) ; ?>">
                    </div>
                </div>





        <div class="form-group">
                    <label class="control-label col-sm-3">Event Tags</label>
                    <div class="col-md-3 col-sm-6">                      
                      <!--input type="checkbox" name="event_status" id="estatus" data-off-color="success" data-off-text="INACTIVE"  data-on-text="ACTIVE" data-size="small" data-on-color="primary" value ="1" checked="true" class="boot-switch"/-->
                      <select id="event_tags" name="event_tags[]" class="form-control" multiple="multiple">
              <optgroup label="Event Tags">

                <option <?php echo in_array('Art & Culture', $events->tags) ? "selected" : ""; ?> value="Art & Culture">Art & Culture</option>
                <option <?php echo in_array('Fashion', $events->tags) ? "selected" : ""; ?> value="Fashion">Fashion</option>
                <option <?php echo in_array('Food & Drink', $events->tags) ? "selected" : ""; ?> value="Food & Drink">Food & Drink</option>
               <option  <?php echo in_array('Family', $events->tags) ? "selected" : ""; ?> value="Family">Family</option>
                <option <?php echo in_array('Music', $events->tags) ? "selected" : ""; ?> value="Music">Music</option>
                <option <?php echo in_array('Nightlife', $events->tags) ? "selected" : ""; ?> value="Nightlife">Nightlife</option>
              </optgroup>
            </select>                   
                    </div>
                </div>
                



                  <div class="us-listing table-responsive"> 
                  <?php 
                   if(count($events->photos) > 0){  
                   for($i=0; $i< count($events->photos); $i++) { ?>
                            <div class="item col-xs-12 col-sm-6 col-md-3 img-box-live "  id="update_dupcnt_imageform<?php echo $i; ?>">
                              <div class="thumbnail">                    
                                      <img   alt="image" class="img-responsive" src="<?php echo set_post_value('photos', $events->photos[$i]) ; ?>" />
                                   
                                  
                                  <div class="caption">
                                        <div class="file-actions">
                                  <div class="file-footer-buttons">
                                      <button type="button" tag="<?php echo $i; ?>" class="event-file-remove event-dupimage-remove btn btn-xs btn-default" data-type="special" title="Remove image" data-url="<?php echo set_post_value('photos', $events->photos[$i]) ; ?>"><i class="glyphicon glyphicon-trash text-danger"></i></button>
                                  </div>                     
                                  <div class="clearfix"></div>
                              </div>                      
                                  </div>                    
                              </div>
                          
                        
                      </div>   
                         <?php } 
                     } else { ?>
                          <div   alert alert-warning>No Photos.</div> 
                     <?php
                     }
                     ?>   
                 
                </div>

                <div id="hidden_pics_total" style="display:none;">
                  <input type="hidden" name="pic_total" value="<?php echo count($events->photos); ?>" />
                </div>

                <div id="hidden_pics" style="display:none;">
                  <?php 
                   if(count($events->photos) > 0){  
                      for($j=0; $j< count($events->photos); $j++) 
                      { ?>
                        <input id="hidden_photo_<?php echo $j; ?>" type="hidden" name="photo_<?php echo $j; ?>" value="<?php echo $events->photos[$j] ?>" />
                   <?php } 
                    } ?>
                </div>

                <!--div class="form-group">
                    <label class="control-label col-sm-3">Event images
                    	<p class="f11">Max allowed size:2MB <br/> Allowed file types :png,jpg</p>
                    </label>

                   	<div class="controls col-sm-4">
                          <input type="file" name="image1" class="file1" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                          <input type="file" name="image2" class="file1" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                          <input type="file" name="image3" class="file1" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                          <input type="file" name="image4" class="file1" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                          <input type="file" name="image5" class="file1" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div--> 

               
                  <div class="form-group" style="clear:both;">
                  </div>
                

                <div class="form-group">
                    <label class="control-label col-sm-3"></label>
                    <div class="controls col-sm-4">
                        <button type="submit" class="btn btn-primary">Create Event</button>
                        <a class="btn btn-default" href="<?php echo base_url().'admin/events'; ?>">Cancel</a>
                    </div>
                </div>

            </div>
        </div>
        <input type="hidden" value="" name="email_verified" id="email_verified"/>
    </form>
    
</div> 
