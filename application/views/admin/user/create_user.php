<div class="">
    <form class="form-horizontal form-bordered" role="form" id="loginForm" method="post" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create User</h4>
            </div>
            <div class="panel-body">
                <?php echo showMessage() ?>
                <p class='f11'>All <span class="asterisk">*</span> marked fields are mandatory</p>
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
		      		  <input class="boot-switch" type="radio" checked name="gender" data-size="small"   data-off-text="M"  data-on-text="M" data-label-text="<i class='fa fa-male'></i>" data-on-color="info" value="male" >
                      <input class="boot-switch" type="radio" name="gender" data-size="small"   data-off-text="F"  data-on-text="F" data-label-text="<i class='fa fa-female'></i>" data-on-color="info" value="female">
                    </div>                    
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Birthday</label>
                    <div class="controls col-sm-4">
                        <input name="birthday"  class="datepicker form-control" type="text" class="form-control" placeholder="Birthday" value="<?php echo $this->input->post('birthday'); ?>" />
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
                    	 <input type="checkbox"  name="is_promoter" data-off-text="NO"  data-on-text="YES" id="is_promoter" data-off-color="success" data-size="small" data-on-color="primary"   />
                     
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
                    <div class="controls col-sm-4"><button type="submit" class="btn btn-primary">Submit</button>
                    <a class="btn btn-default" href="<?php echo base_url().'admin/user'; ?>">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" value="" name="email_verified" id="email_verified"/>
    </form>
</div>    

