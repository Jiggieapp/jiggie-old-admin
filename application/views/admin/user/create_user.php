<div class="container-fluid-md">
    <form class="form-horizontal form-bordered" role="form" id="loginForm" method="post" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create User</h4>
            </div>
            <div class="panel-body">
                <?php echo showMessage() ?>
                <div class="form-group" id="email_div">
                    <label class="control-label col-sm-3">Email<span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <input aria-required="true" name="email" id="email" type="text" class="form-control" placeholder="Email" value="<?php echo set_value('email'); ?>"  required/></div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Password<span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <input name="password" id="password" type="password" class="form-control" placeholder="Password" value=""  required/></div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">First Name<span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <input name="first_name" type="text" class="form-control" placeholder="First Name" value="<?php echo $this->input->post('first_name'); ?>" required/>

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Last Name<span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <input name="last_name" type="text" class="form-control" placeholder="Last Name" value="<?php echo $this->input->post('last_name'); ?>" required />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Nick Name</label>

                    <div class="controls col-sm-4">
                        <input name="nick_name" type="text" class="form-control" placeholder="Nick Name" value="<?php echo $this->input->post('nick_name'); ?>" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">About</label>
                    <div class="controls col-sm-6">
                    <textarea name="about" class="form-control autogrow" placeholder="About" rows="4" style="height: 105px;"><?php echo $this->input->post('about'); ?></textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Gender</label>
                    <div class="col-md-3 col-sm-6">                   			
		      <input class="boot-switch" type="radio" name="gender" data-size="small"   data-off-text="M"  data-on-text="M" data-label-text="<i class='fa fa-male'></i>" data-on-color="info" value="male" >
                      <input class="boot-switch" type="radio" name="gender" data-size="small"   data-off-text="F"  data-on-text="F" data-label-text="<i class='fa fa-female'></i>" data-on-color="info" value="female">
                    </div>                    
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Birthday</label>
                    <div class="controls col-sm-4">
                        <input name="birthday" data-rel="datepicker" class="datepicker form-control" type="text" class="form-control" placeholder="Birthday" value="<?php echo $this->input->post('birthday'); ?>" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Location</label>

                    <div class="controls col-sm-4">
                        <input name="location" type="text" class="form-control" placeholder="Location" value="<?php echo $this->input->post('location'); ?>" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Tagline</label>

                    <div class="controls col-sm-4">
                        <input name="tagline" type="text" class="form-control" placeholder="Tagline" value="<?php echo $this->input->post('tagline'); ?>" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Is Promoter</label>
                    <div class="col-md-3 col-sm-6">
                    	<input class="boot-switch" data-off-text="NO"  data-on-text="YES" data-size="small" name="is_promoter" type="checkbox"  checked data-size="large"/>
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
                    <label class="control-label col-sm-3">Profile images</label>

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
                    <div class="controls col-sm-4"><button type="submit" class="btn btn-primary">Submit</button>
                    <a class="btn btn-default" href="<?php echo base_url().'/admin/user'; ?>">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" value="" name="email_verified" id="email_verified"/>
    </form>
</div>    

<script>        
function createUploader(){            
    var uploader = new qq.FileUploader({
        element: document.getElementById('file-uploader-demo1'),
        action: "<?php echo base_url();?>admin/user/upload",
        debug: true,
        extraDropzones: [qq.getByClass(document, 'qq-upload-extra-drop-area')[0]],
        onComplete : function(id,fileName,responseJSON){
            var img = $('<img width="100" height="100">'); 
            img.attr('src', base_url+"uploads/users/"+fileName);
            img.appendTo('ul.qq-upload-list  li:last-child');
            var cur_val = $('#profile_image').val();
            if(cur_val)
              $('#profile_image').val(cur_val + "," + base_url+"uploads/users/"+fileName);
            else
              $('#profile_image').val(base_url+"uploads/users/"+fileName);

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