<div class="">
    <form class="form-horizontal form-bordered" role="form" id="adminuser_det" method="post" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create Admin User</h4>
            </div>
            <div class="panel-body">
                <?php echo showMessage() ?>
                <p class='f11'>All <span class="asterisk">*</span> marked fields are mandatory</p>
                <div class="form-group" id="email_div">
                    <label class="control-label col-sm-3">Email <span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <input aria-required="true" name="email" id="admin_email" type="text" autocomplete="off" class="form-control" placeholder="Email" value="<?php echo set_value('email'); ?>"  required/></div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">User Type</label>
                    <div class="controls col-sm-4">
                    	<input type="hidden" value="2" name="user_type_id" id="user_type_id"/>
                    	<!--Admin-->
                        <select name="user_type_id" class="form-control" required>
                            
                            <?php foreach($user_types as $user_type) { ?>
                                <option value="<?php echo $user_type["user_type_id"];?>" <?php echo $user_type["user_type_id"] == $this->input->post("user_type_id")  ? "selected" : "";?>><?php echo $user_type["user_type_name"];?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Password <span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <input name="password" id="password" type="password" autocomplete="off" class="form-control" placeholder="Password" value=""  required/></div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">First Name <span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <input name="first_name" type="text" class="form-control" placeholder="First Name" value="<?php echo $this->input->post('first_name'); ?>" required/>

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Last Name <span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <input name="last_name" type="text" class="form-control" placeholder="Last Name" value="<?php echo $this->input->post('last_name'); ?>" required />
                    </div>
                </div>
                <div class="form-group">
                   <label class="control-label col-sm-3">
                    	<p>Profile Image</p>
                    	<p class="f11">Max allowed size:2MB <br/> Allowed file types :png,jpg</p>
                    </label>
					
                    <div class="controls col-sm-4">
                         <input type="file" name="image" class="file1" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div>
                

               
                
                <div class="form-group">
                    <label class="control-label col-sm-3"></label>
                    <div class="controls col-sm-4">
                        <button type="submit" class="btn btn-primary">Create</button>
                        <a class="btn btn-default" href="<?php echo base_url().'/admin/adminuser'; ?>">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" value="" name="email_verified" id="email_verified"/>
    </form>
</div>    