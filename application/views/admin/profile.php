    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="panel panel-default panel-profile-details">
                <div class="panel-body">
                    <div class="col-sm-5 text-center">
                     <?php if($admin_details->profile_image_url!=''): ?>
                        <img alt="image" class="img-circle img-profile" src="<?php echo base_url(); ?><?php echo 'uploads/admin_users/'.$admin_details->profile_image_url; ?>">
                     <?php else: ?>
                        <img alt="image" class="img-circle img-profile" src="">
                     <?php endif; ?>   
                    </div>
                    <div class="col-sm-7 profile-details">
                        <h3><?php echo $admin_details->first_name." ".$admin_details->last_name; ?></h3>

                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-sm-5">
                        <dl>
                            <dt>Email</dt>
                            <dd><?php echo $admin_details->email; ?></dd>
                        </dl>
                    </div>                   
                </div>            
            </div>
        </div>
    </div>
