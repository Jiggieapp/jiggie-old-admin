<div class="container-fluid-md">
    <form class="form-horizontal form-bordered" role="form" id="loginForm" method="post" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create Admin User</h4>
            </div>
            <div class="panel-body">
                <?php echo showMessage() ?>
                
                <div class="form-group" id="email_div">
                    <label class="control-label col-sm-3">Email<span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <input aria-required="true" name="email" id="admin_email" type="text" class="form-control" placeholder="Email" value="<?php echo set_value('email'); ?>"  required/></div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">User Type</label>
                    <div class="controls col-sm-4">
                        <select name="user_type_id" class="form-control" required>
                            <option value="">Select</option>
                            <?php foreach($user_types as $user_type) { ?>
                                <option value="<?php echo $user_type["user_type_id"];?>" <?php echo $user_type["user_type_id"] == $this->input->post("user_type_id")  ? "selected" : "";?>><?php echo $user_type["user_type_name"];?></option>
                            <?php } ?>
                        </select>
                    </div>
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
                    <label class="control-label col-sm-3">Image Upload</label>

                    <div class="controls col-sm-4">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                          <div>
                            <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span>
                                <input type="file" name="image" /></span>
                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                          </div>
                        </div>
                    </div>
                </div>
                

               
                
                <div class="form-group">
                    <label class="control-label col-sm-3"></label>
                    <div class="controls col-sm-4">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a class="btn btn-default" href="<?php echo base_url().'/admin/adminuser'; ?>">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" value="" name="email_verified" id="email_verified"/>
    </form>
</div>    