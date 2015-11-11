
<div class="container-fluid-md">

    <form class="form-horizontal form-bordered" role="form" id="loginForm" method="post" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Edit Admin User Details</h4>
            </div>
            <div class="panel-body" >
                <div class="col-sm-5 ">
                    <img  width="200" height="200" alt="image" class="img-circle img-profile" src="<?php echo base_url() . 'uploads/admin_users/' . $admin_user->profile_image_url; ?>" />
                </div>
                <div class="col-sm-7 profile-details">

                </div>               
            </div>
            <div class="panel-body">
                <?php echo showMessage() ?>

                <div class="form-group" id="email_div">
                    <label class="control-label col-sm-3">Email<span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <input aria-required="true" name="email" id="admin_email_edit" type="text" class="form-control" placeholder="Email" value="<?php echo $admin_user->email; ?>"  readonly/></div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">User Type</label>
                    <div class="controls col-sm-4">
                        <select name="user_type_id" class="form-control" required>
                            <option value="">Select</option>
                            <?php foreach ($user_types as $user_type) { ?>
                                <option value="<?php echo $user_type["user_type_id"]; ?>" <?php echo $user_type["user_type_id"] == $admin_user->user_type_id ? "selected" : ""; ?>><?php echo $user_type["user_type_name"]; ?></option>
                            <?php } ?>
                        </select>
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
                        <a class="btn btn-default" href="<?php echo base_url() . '/admin/adminuser'; ?>">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>    