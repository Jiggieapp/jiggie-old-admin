
<div class="">

    <form class="form-horizontal form-bordered" role="form" id="loginForm" method="post" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Edit Admin User Details</h4>
            </div>
            <div class="panel-body" >
                <div class="col-sm-5 ">
                	 <?php $image = $admin_user->profile_image_url ?base_url() . 'uploads/admin_users/' .$admin_user->profile_image_url: $this->config->item('assets_url').'images/default.png' ?>
                            <img   alt="image"  src="<?php echo base_url()?>timthumb.php?src=<?php echo base64_encode($image) ; ?>&w=200&q=100&h=150" />
                    
                </div>
                <div class="col-sm-7 profile-details">

                </div>               
            </div>
            <div class="panel-body">
                <?php echo showMessage() ?>

                <div class="form-group" id="email_div">
                    <label class="control-label col-sm-3">Email<span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <span class="form-control"><?php echo $admin_user->email; ?></span>
                    </div>

                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">User Type <span class="asterisk">*</span></label>
                    <div class="controls col-sm-4">
                    	<input type="hidden" value="2" name="user_type_id" id="user_type_id"/>
                    	Admin
                        <!--select name="user_type_id" class="form-control" required>
                            <option value="">Select</option>
                            <?php foreach ($user_types as $user_type) { ?>
                                <option value="<?php echo $user_type["user_type_id"]; ?>" <?php echo $user_type["user_type_id"] == $admin_user->user_type_id ? "selected" : ""; ?>><?php echo $user_type["user_type_name"]; ?></option>
                            <?php } ?>
                        </select-->
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">First Name<span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <input name="first_name" type="text" class="form-control" placeholder="First Name" value="<?php echo $admin_user->first_name; ?>" required/>

                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Last Name<span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <input name="last_name" type="text" class="form-control" placeholder="Last Name" value="<?php echo $admin_user->last_name; ?>" required />
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
                    <label class="control-label col-sm-12"></label>
                    <div class="controls col-sm-12 ">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a class="btn btn-default" href="<?php echo base_url() . 'admin/adminuser'; ?>">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>    