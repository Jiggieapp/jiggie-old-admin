<div class="container-fluid-md">
    <form class="form-horizontal form-bordered" role="form" id="VenueForm" method="post" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create Venue</h4>
            </div>
            <div class="panel-body">
                <?php echo showMessage() ?>
                <p class='f11'>All <span class="asterisk">*</span> marked fields are mandatory</p>
                <div class="form-group" id="venue_name_div">
                    <label class="control-label col-sm-3">Name <span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <input name="name" id="venue_name_create" type="text" class="form-control" placeholder="Name" value="<?php echo set_value('name'); ?>"  required/></div>
                </div>
               
                <div class="form-group">
                    <label class="control-label col-sm-3">Grade <span class="asterisk">*</span></label>

                    <div class="controls col-sm-2">
                        <select name="grade" class="form-control" required>
                            <option value="">Select Grade</option>
                            <?php for($i = 1; $i<=100; $i++) { ?>
                                <option value="<?php echo $i;?>" <?php echo $this->input->post("grade") == $i ? "selected" : ""; ?>><?php echo $i;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Address</label>
                    <div class="controls col-sm-6">
                    <textarea name="address" class="form-control autogrow" placeholder="Address" rows="4" style="height: 105px;"><?php echo $this->input->post('address'); ?></textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Neighborhood</label>

                    <div class="controls col-sm-4">
                        <input name="neighborhood" type="text" class="form-control" placeholder="Neighborhood" value="<?php echo $this->input->post('neighborhood'); ?>" />

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Cross street</label>

                    <div class="controls col-sm-4">
                        <input name="cross_street" type="text" class="form-control" placeholder="Cross street" value="<?php echo $this->input->post('cross_street'); ?>" />

                    </div>
                 </div>
                
                 <div class="form-group">
                    <label class="control-label col-sm-3">City <span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <!--<input name="city" type="text" class="form-control" placeholder="City" value="<?php //echo $this->input->post('city'); ?>" />-->
                        <select name="city" class="form-control" required>
                            <option value="">Select City</option>
                            <?php foreach($cities as $city) { ?>
                                <option value="<?php echo $city["venue_city_id"];?>" <?php echo $this->input->post("city") == $city["venue_city_id"] ? "selected" : ""; ?>><?php echo $city["venue_city_name"];?></option>
                            <?php } ?>
                        </select>

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">State</label>

                    <div class="controls col-sm-4">
                        <input name="state" type="text" class="form-control" placeholder="State" value="<?php echo $this->input->post('state'); ?>" />

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Zip</label>

                    <div class="controls col-sm-4">
                        <input name="zip" type="text" class="form-control" placeholder="Zip" value="<?php echo $this->input->post('zip'); ?>" />

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Country</label>

                    <div class="controls col-sm-4">
                        <input name="country" type="text" class="form-control" placeholder="Country" value="<?php echo $this->input->post('country'); ?>" />

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Phone</label>

                    <div class="controls col-sm-4">
                        <input name="phone" type="text" class="form-control" placeholder="Phone" value="<?php echo $this->input->post('phone'); ?>" />

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Latitude</label>

                    <div class="controls col-sm-4">
                        <input name="lat" type="text" class="form-control" placeholder="Latitude" value="<?php echo $this->input->post('lat'); ?>" />

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Longitude</label>

                    <div class="controls col-sm-4">
                        <input name="lng" type="text" class="form-control" placeholder="Longitude" value="<?php echo $this->input->post('lng'); ?>" />

                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Url</label>

                    <div class="controls col-sm-4">
                        <input name="url" type="text" class="form-control" placeholder="Url" value="<?php echo $this->input->post('url'); ?>" />

                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Description</label>
                    <div class="controls col-sm-6">
                    <textarea name="description" class="form-control autogrow" placeholder="Description" rows="4" style="height: 105px;"><?php echo $this->input->post('description'); ?></textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Venue status</label>
                    <div class="col-md-3 col-sm-6">
                      <input type="checkbox" name="venue_status" id="vstatus" data-off-color="success" data-off-text="INACTIVE"  data-on-text="ACTIVE" data-size="small" data-on-color="primary" value ="1" checked="true" class="boot-switch"/>
                     
                    
                    </div>
                </div>
                
                <!--input type="hidden" name="profile_image" id="profile_image" value="" />
                <input type="hidden" name="uploaded_image" id="uploaded_image" value="0" />
                <div id="file-uploader-demo1">		
                    <noscript>			
                            <p>Please enable JavaScript to use file uploader.</p>
                            <!-- or put a simple form for upload here -->
                    <!--/noscript>
                    
                </div>
                <div class="qq-upload-extra-drop-area">Drop files here too</div-->
                <div class="form-group">
                    <label class="control-label col-sm-3">Venue images
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
                </div> 
                <div class="form-group">
                    <label class="control-label col-sm-3"></label>
                    <div class="controls col-sm-4"><button type="submit" class="btn btn-primary">Create Venue</button>
                    <a class="btn btn-default" href="<?php echo base_url().'admin/venue'; ?>">Cancel</a>
                    </div>
                </div>
                
            </div>
        </div>
        
    </form>
</div>    
