<div class="">
    <form class="form-horizontal form-bordered" role="form" id="event-form" method="post">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create Event!</h4>
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
                    	<input class="typeahead form-control" name="title" id="title" type="text" placeholder="Event Title" value="<?php echo $this->input->post('title'); ?>" required>
                        
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Select Venue <span class="asterisk">*</span></label>
                    <div class="controls col-sm-4">
                        <select name="venue_sel" id="venue_sel" class="form-control" required>                           
                            <?php foreach ($venues as $venue) {
                            	$location = $venue->address.', '.$venue->city.', '.$venue->state.', '.$venue->zip;                        	
                            	?>                            	
                                <option data-location="<?php echo $location?>" value="<?php echo $venue->_id; ?>" <?php echo $this->input->post("venue_sel") == $venue->_id ? "selected" : ""; ?>><?php echo $venue->name; ?></option>
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
                          <option value="ticket">Ticket</option>
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
                        <input name="location" id="location" type="text" class="form-control" disabled="disabled" placeholder="Location" value="<?php echo $this->input->post('location'); ?>" />

                    </div>
                </div>
				<div class="form-group">
                    <label class="control-label col-sm-3">Event Type</label>

                    <div class="controls col-sm-4">
                        <select id="event_type" class="form-control" name="event_type">							
							<option <?php echo $this->input->post("event_type") == "special" ? "selected" : ""; ?> value="special">Special</option>
							<option <?php echo $this->input->post("event_type") == "weekly" ? "selected" : ""; ?> value="weekly">Weekly</option>
						</select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" ><span id="startdatelabel">Start series date</span> <span class="asterisk">*</span></label>
                    <div class="controls col-sm-4">
                        <input readonly="readonly" style="background-color: #fff; cursor: auto" name="start_date" id="start_date"   class="event_range_start form-control" type="text" class="form-control" placeholder="Start Date" value="<?php $this->input->post('start_date'); ?>" required/>

                    </div>
                </div>
                 <div  id="endrangeselection">
                 	<div class="form-group">
                 		<label class="control-label col-sm-3" >End series date<span class="asterisk">*</span></label>
	                    <div class="controls col-sm-4">
	                        
						    <input readonly="readonly" style="background-color: #fff; cursor: auto" name="end_date" id="end_date"   class="event_range form-control"  type="text" class="form-control" placeholder="End Date" value="<?php echo $this->input->post('end_date'); ?>" /> 
	                    </div>
                 	</div>
                 	<div class="form-group">
                 		<label class="control-label col-sm-3" ></label>
                 		<div class=" controls col-sm-4 checkbox" > <label><input name="forever" id="forever" type="checkbox" value="1">Forever</label></div>
                 	</div>
                    
                </div>
                 <div class="form-group">
                    <label class="control-label col-sm-3">Start Time <span class="asterisk">*</span></label>
                    <div class="col-sm-3 col-md-6 col-lg-4">
                       <?php $default = ($this->input->post('starttime')?$this->input->post('starttime'):'')?>
                        <div class="input-group">
                            <input readonly="readonly" style="background-color: #fff; cursor: auto" type="text" class="form-control"  data-minute-step="30"  data-rel="eventtimepicker" name="starttime" id="starttime"     value="<?php echo $default; ?>" placeholder="00:00:AM" required>
                            
                        </div>
                        
                    </div>
                </div>
                 <div class="form-group">
                    <label class="control-label col-sm-3">Event Duration <span class="asterisk">*</span></label>
                    <div class="col-sm-3 col-md-6 col-lg-4">
                       <?php $default = ($this->input->post('endtime')?$this->input->post('endtime'):'')?>
                        <div class="input-group">
                        	<select class="form-control" name="event_time" id="event_time" >
                        		 <?php for($i = 2; $i<=48; $i++) { ?>
	                                <option value="<?php echo $i/2;?>" <?php echo $this->input->post("event_time") == $i/2 ? "selected" : ""; ?>><?php echo $i/2;?></option>
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
                <div class="form-group">
                    <label class="control-label col-sm-3"> Description
                    	<p class="f11">(If left blank, event description will inherit venue description)</p>
                    	</label>
                    <div class="controls col-sm-6">
                        <textarea name="description" class="form-control autogrow" placeholder="Description" rows="4" style="height: 105px;"><?php echo $this->input->post('description'); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3"> Instruction to Customers
                      <p class="f11">(Please provide some instructions to customers after they are booking a ticket / table)</p>
                      </label>
                    <div class="controls col-sm-6">
                        <textarea name="instruction" class="form-control autogrow" placeholder="Instructions" rows="4" style="height: 105px;"><?php echo $this->input->post('instruction'); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Event Featured</label>
                    <div class="col-md-3 col-sm-6">
                      <!--input type="checkbox" name="featured_event" id="featured_event" data-off-color="success" data-off-text="NO"  data-on-text="YES" data-size="small" data-on-color="primary" value ="0"   class="boot-switch"/-->
                       <select name="featured_event" id="featured_event" class="form-control" required> 
                      	    <option <?php echo $this->input->post("featured_event") == "admin" ? "selected" : ""; ?> value="admin">Admin</option>
                      		<option <?php echo $this->input->post("featured_event") == "user" ? "selected" : ""; ?>value="user">User</option>                      		
                      		<option <?php echo $this->input->post("featured_event") ==  "featured"? "selected" : ""; ?>value="featured">Featured</option>                      	 
                       </select> 
                    
                    </div>
                </div>
                 <div class="form-group">
                    <label class="control-label col-sm-3">Rank <span class="asterisk">*</span></label>

                    <div class="controls col-sm-2">
                        <select name="rank" class="form-control" required>                            
                            <?php for($i = 1; $i<=10; $i++) { ?>
                                <option value="<?php echo $i;?>" <?php echo $this->input->post("rank") == $i ? "selected" : ""; ?>><?php echo $i;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Event status</label>
                    <div class="col-md-3 col-sm-6">
                      <!--input type="checkbox" name="event_status" id="estatus" data-off-color="success" data-off-text="INACTIVE"  data-on-text="ACTIVE" data-size="small" data-on-color="primary" value ="1" checked="true" class="boot-switch"/-->
                      <select name="event_status" class="form-control" required> 
                      	    <option <?php echo $this->input->post("event_status") == "draft" ? "selected" : ""; ?>  value="draft">Draft</option>
                      		<option <?php echo $this->input->post("event_status") == "published" ? "selected" : ""; ?>  value="published">Published</option>                      		
                      		<option <?php echo $this->input->post("event_status") == "passed" ? "selected" : ""; ?>  value="passed">Passed</option>
                      		<option <?php echo $this->input->post("event_status") == "inactive" ? "selected" : ""; ?>  value="inactive">Inactive</option>
                      		<option <?php echo $this->input->post("event_status") == "soldout" ? "selected" : ""; ?>  value="soldout">Sold Out</option> 
                       </select>                    
                    </div>
                </div>
				<div class="form-group">
                    <label class="control-label col-sm-3">Event Tags</label>
                    <div class="col-md-3 col-sm-6">
                      <!--input type="checkbox" name="event_status" id="estatus" data-off-color="success" data-off-text="INACTIVE"  data-on-text="ACTIVE" data-size="small" data-on-color="primary" value ="1" checked="true" class="boot-switch"/-->
                      <select id="event_tags" name="event_tags[]" class="form-control" multiple="multiple">
						    

              <optgroup label="Tag List">
                <option value="Art & Culture">Art & Culture</option>
                <option value="Fashion">Fashion</option>
                <option value="Food & Drink">Food & Drink</option>
                <option value="Family">Family</option>
                <option value="Music">Music</option>
                <option value="Nightlife">Nightlife</option>
              </optgroup>

              <!--
              <optgroup label="Music Type">
						    <option value="ART & CULTURE">ART & CULTURE</option>
						    <option value="FASHION">FASHION</option>
						    <option value="FOOD & DRINK">FOOD & DRINK</option>
						    <option value="FAMILY">FAMILY</option>
                <option value="MUSIC">MUSIC</option>
                <option value="NIGHTLIFE">NIGHTLIFE</option>
						  </optgroup>
              -->
              <!--
						  <optgroup label="Venue Type">
						    <option value="nightclub">Nightclub</option>
						    <option value="lounge">Lounge</option>
						    <option value="hotellounge">Hotel Lounge</option>
						    <option value="rooftop">Rooftop</option>
						    <option value="restaurant">Restaurant</option> 
						  </optgroup>
						  <optgroup label="Vibe">
						    <option value="redcarpet">Red Carpet</option>
						    <option value="upscalechic">Upscale Chic</option>
						    <option value="casual">Casual</option> 
						  </optgroup>
              -->
						</select>                   
                    </div>
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
                </div---> 

               

                

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
