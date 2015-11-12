<div class="">
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
                        <input name="name" id="venue_name_create" type="text" class="form-control" placeholder="Name" value="<?php  ?>"  required/></div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Grade <span class="asterisk">*</span></label>

                    <div class="controls col-sm-2">
                        <select name="grade" class="form-control" required>
                            <option value="">Select Grade</option>
                            <?php for($i = 1; $i<=100; $i++) { ?>
                                <option value="<?php echo $i;?>" <?php echo set_post_value('grade',$venue->grade) == $i ? "selected" : ""; ?>><?php echo $i;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Address</label>
                    <div class="controls col-sm-6">
                    <textarea name="address" class="form-control autogrow" placeholder="Address" rows="4" style="height: 105px;"><?php echo $venue->address; ?></textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Neighborhood</label>

                    <div class="controls col-sm-4">
                        <input name="neighborhood" type="text" class="form-control" placeholder="Neighborhood" value="<?php echo $venue->neighborhood; ?>" />

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Cross street</label>

                    <div class="controls col-sm-4">
                        <input name="cross_street" type="text" class="form-control" placeholder="Cross street" value="<?php echo $venue->cross_street; ?>" />

                    </div>
                 </div>
                
                 <div class="form-group">
                    <label class="control-label col-sm-3">City <span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <!--<input name="city" type="text" class="form-control" placeholder="City" value="<?php //echo $this->input->post('city'); ?>" />-->
                        <select name="city" class="form-control" required>
                            <option value="">Select City</option>
                            <?php foreach($cities as $city) { ?>
                                <option value="<?php echo $city["venue_city_id"];?>" <?php echo $city["venue_city_id"] == $venue->venue_city_id ? "selected" : "";  ?>><?php echo $city["venue_city_name"];?></option>
                            <?php } ?>
                        </select>

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">State</label>

                    <div class="controls col-sm-4">
                        <input name="state" type="text" class="form-control" placeholder="State" value="<?php echo $venue->state; ?>" />

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Zip</label>

                    <div class="controls col-sm-4">
                        <input name="zip" type="text" class="form-control" placeholder="Zip" value="<?php echo $venue->zip; ?>" />

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Country</label>

                    <div class="controls col-sm-4">
                        <input name="country" type="text" class="form-control" placeholder="Country" value="<?php echo $venue->country; ?>" />

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Phone</label>

                    <div class="controls col-sm-4">
                        <input name="phone" type="text" class="form-control" placeholder="Phone" value="<?php echo $venue->phone?$venue->phone:'' ?>" />

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Latitude</label>

                    <div class="controls col-sm-4">
                        <input name="lat" type="text" class="form-control" placeholder="Latitude" value="<?php echo $venue->lat; ?>" />

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Longitude</label>

                    <div class="controls col-sm-4">
                        <input name="lng" type="text" class="form-control" placeholder="Longitude" value="<?php echo $venue->lng; ?>" />

                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Url</label>

                    <div class="controls col-sm-4">
                        <input name="url" type="text" class="form-control" placeholder="Url" value="<?php echo $venue->url; ?>" />

                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Description</label>
                    <div class="controls col-sm-6">
                    <textarea name="description" class="form-control autogrow" placeholder="Description" rows="4" style="height: 105px;"><?php echo $venue->description; ?></textarea>
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
                    <label class="control-label col-sm-3">Profile images
                    <p class="f11">Max allowed size:2MB <br/> Allowed file types :png,jpg</p>
                    </label>

                    <div class="controls col-sm-4">
                          <input type="file" name="image1" class="" id="file1" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                          <input type="file" name="image2" class="" id="file2" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                          <input type="file" name="image3" class="" id="file3" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                          <input type="file" name="image4" class="" id="file4" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                          <input type="file" name="image5" class="file1" id="file5" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div>    
                
                
                <div class="form-group">
                    <label class="control-label col-sm-3"></label>
                    <div class="controls col-sm-4"><button type="submit" class="btn btn-primary">Duplicate Venue</button>
                    <a class="btn btn-default" href="<?php echo base_url().'/admin/venue'; ?>">Cancel</a>
                    </div>
                </div>
                
            </div>
        </div>
        <input type="hidden" value="" name="email_verified" id="email_verified" />
    </form>
</div>    
<script>
	var venue_img_1 = "<?php echo $venue->img_1 ?>";
	var venue_img_2 = "<?php echo $venue->img_2 ?>";
	var venue_img_3 = "<?php echo $venue->img_3 ?>";
	var venue_img_4 = "<?php echo $venue->img_4 ?>";
	var venue_img_5 = "<?php echo $venue->img_5;?>";
</script>