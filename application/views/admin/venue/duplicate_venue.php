<div class="container-fluid-md">
    <form class="form-horizontal form-bordered" role="form" id="loginForm" method="post" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create Venue</h4>
            </div>
            <div class="panel-body">
                <?php echo showMessage() ?>
                <div class="form-group" id="venue_name_div">
                    <label class="control-label col-sm-3">Name</label>

                    <div class="controls col-sm-4">
                        <input name="name" id="venue_name_create" type="text" class="form-control" placeholder="Name" value="<?php  ?>"  required/></div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Grade</label>

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
                    <label class="control-label col-sm-3">City</label>

                    <div class="controls col-sm-4">
                        <!--<input name="city" type="text" class="form-control" placeholder="City" value="<?php //echo $this->input->post('city'); ?>" />-->
                        <select name="city" class="form-control" required>
                            <option value="">Select City</option>
                            <?php foreach($cities as $city) { ?>
                                <option value="<?php echo $city["venue_city_id"];?>" <?php echo $city["venue_city_id"] == $venue->city ? "selected" : "";  ?>><?php echo $city["venue_city_name"];?></option>
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
                        <input name="phone" type="text" class="form-control" placeholder="Phone" value="<?php echo $venue->phone; ?>" />

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
                    <label class="control-label col-sm-3">Venue images</label>

                    <div class="controls col-sm-4">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                          <div>
                            <span class="btn btn-default btn-file"><span class="fileinput-new">Upload image</span>
                                <span class="fileinput-exists">Change</span>
                                <input type="file" name="image1" /></span>
                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                          <div>
                            <span class="btn btn-default btn-file"><span class="fileinput-new">Upload image</span>
                                <span class="fileinput-exists">Change</span>
                                <input type="file" name="image2" /></span>
                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                          <div>
                            <span class="btn btn-default btn-file"><span class="fileinput-new">Upload image</span>
                                <span class="fileinput-exists">Change</span>
                                <input type="file" name="image3" /></span>
                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                          <div>
                            <span class="btn btn-default btn-file"><span class="fileinput-new">Upload image</span>
                                <span class="fileinput-exists">Change</span>
                                <input type="file" name="image4" /></span>
                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                          <div>
                            <span class="btn btn-default btn-file"><span class="fileinput-new">Upload image</span>
                                <span class="fileinput-exists">Change</span>
                                <input type="file" name="image5" /></span>
                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                          </div>
                        </div>
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
function createUploader(){            
    var uploader = new qq.FileUploader({
        element: document.getElementById('file-uploader-demo1'),
        action: "<?php echo base_url();?>admin/venue/upload",
        debug: true,
        extraDropzones: [qq.getByClass(document, 'qq-upload-extra-drop-area')[0]],
        onComplete : function(id,fileName,responseJSON){
            var img = $('<img width="100" height="100">'); 
            img.attr('src', base_url+"uploads/venues/"+fileName);
            img.appendTo('ul.qq-upload-list  li:last-child');
            var cur_val = $('#profile_image').val();
            if(cur_val)
              $('#profile_image').val(cur_val + "," + base_url+"uploads/venues/"+fileName);
            else
              $('#profile_image').val(base_url+"uploads/venues/"+fileName);

            var uploaded_image = $('#uploaded_image').val();
            var update_uploaded_image = parseInt(uploaded_image)+1;
            $('#uploaded_image').val(update_uploaded_image)
            if(update_uploaded_image == 5) $(".qq-upload-button").hide();
        }
    });   

}

// in your app create uploader as soon as the DOM is ready
// don't wait for the window to load  
window.onload = createUploader;   
</script>