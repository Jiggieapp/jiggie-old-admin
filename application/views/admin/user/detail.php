<div class="container-fluid-md user-detail">
    <form class="form-horizontal form-bordered" role="form">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">User Details</h4>

                <div class="panel-options">
                    <!--a href="#" data-rel="collapse"><i class="fa fa-fw fa-minus"></i></a>
                    <a href="#" data-rel="reload"><i class="fa fa-fw fa-refresh"></i></a-->
                    <a href="<?php if(isset($_SERVER['HTTP_REFERER'])){ echo $_SERVER['HTTP_REFERER']; } ?>" data-rel="close">
                        <i class="fa fa-fw fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="panel-body" user_id="<?php echo $user_id; ?>">
                <?php
                if (isset($error)) {
                    echo $error;
                } else {
                    ?>
                    <div class="col-sm-5 ">
                        <a href="<?php echo base_url(); ?>admin/user/image/<?php echo $user_id; ?>">
                            <img  width="200" height="200" alt="image" class="img-circle img-profile" src="<?php echo $user->profile_image_url; ?>" />
                        </a>   
                    </div>
                    <div class="col-sm-7 profile-details">
                        
                         <p id="view_fname" class="form-group">
                           
                            <a id="fname" class="edit_user" ><?php echo $user->first_name; ?></a>
                                                     
                            <a id="lname" class="edit_user"><?php echo $user->last_name; ?></a>
                        </p>
                        <div class="row">
                            <div class="col-xs-3"> 
                                DOB 
                            </div>
                            <div class="col-xs-9"> 
                                <p id="edit_birthday">
                                    <a data-title="Select Date of birth" data-pk="1" data-template="D / MMM / YYYY" data-viewformat="DD/MM/YYYY" data-format="YYYY-MM-DD" data-value="<?php echo date("Y-m-d", strtotime($user->birthday)) ?>" data-type="combodate" id="dob" href="#" class="editable" style="display: inline;">
                                        <?php echo date("d/m/Y", strtotime($user->birthday)) ?>
                                    </a>
                                    
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3"> 
                                Location: 
                            </div>
                            <div class="col-xs-9"> 
                                <p id="view_locationP">                                    
                                    <a id="locationP" class="edit_user"><?php echo $user->location; ?></a>
                                    
                                </p>
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col-xs-3"> 
                                Verified Host
                            </div>
                            <div class="col-xs-9"> 
                                <p>
                                    <span id="wrap_verified_host">
                                    	 <input type="checkbox"  name="verified_host" data-off-text="NO"  data-on-text="YES" id="verified_host" data-off-color="success" data-size="small" data-on-color="primary" value ="1" <?php echo $user->verified == 0 ? "checked" : ""; ?> class="boot-switch"/>
                     
                                        <!--a id="verified_host"onclick="verifyhost('<?php echo $user->verified; ?>');" ><?php echo $user->verified == 0 ? "Yes" : "No"; ?></a-->
                                    </span>
                                </p>
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col-xs-3"> 
                                Suspend user
                            </div>
                            <div class="col-xs-9"> 
                                <p>
                                    <span id="wrap_suspend_user">
                                    	<input type="checkbox"  name="suspend_user" data-off-text="NO"  data-on-text="YES" data-size="small" id="suspend_user" data-off-color="success"  data-on-color="primary" value ="1" <?php echo $user->user_status == 5 ? "checked" : ""; ?> class="boot-switch"/>
                     
                                        <!--a id="suspend_user" onclick="suspend_user('<?php echo $user->user_status;?>');"><?php echo $user->user_status == 5 ? "No":"Yes";?></a-->
                                    </span>
                                </p>
                            </div>    
                        </div>    
                    </div>
                </div>
                <div class="panel-body" >

                    <div class="form-group" id="view_fbid">
                        <label class="control-label col-xs-3  ">Facebook ID</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">
                               
                                <a class="edit_user" id="fbid"><?php echo $user->fb_id; ?></a>
                            </p>
                        </div>
                    </div>

                    <div class="form-group" id="view_email">
                        <label class="control-label col-xs-3  ">Email</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">
                                <a class="edit_user" id="email"><?php echo $user->email; ?></a>
                            </p>
                        </div>
                    </div>

                    <div class="form-group" id="view_nickname">
                        <label class="control-label col-xs-3  ">Nick Name</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static"> 
                               
                                <a class="edit_user" id="nickname"><?php echo $user->nick_name; ?></a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="form-group" id="view_gender">
                        <label class="control-label col-xs-3  ">Gender</label>
                         
                        <div class="controls col-xs-9">
                        	<input class="boot-switch" type="radio" name="gender" data-size="small"   data-off-text="M"  data-on-text="M" data-label-text="<i class='fa fa-male'></i>" data-on-color="info" value="male" <?php echo $user->gender == "male" ? "checked" : ""; ?>>
                     	 	<input class="boot-switch" type="radio" name="gender" data-size="small"   data-off-text="F"  data-on-text="F" data-label-text="<i class='fa fa-female'></i>" data-on-color="info" value="female" <?php echo $user->gender == "female" ? "checked" : ""; ?>>
               	            <span id="p_gender"></span>
                        </div>
                    </div>

                    <div class="form-group" id="view_tagline">
                        <label class="control-label col-xs-3  ">Tagline</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static"> 
                               
                                <a class="edit_user" id="tagline"><?php echo $user->tagline; ?></a>
                            </p>
                        </div>
                    </div>

                    <div class="form-group" id="view_about">
                        <label class="control-label col-xs-3  ">About</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static"> 
                                
                                <a class="edit_user" id="about"><?php echo $user->about; ?></a>
                            </p>
                        </div>
                    </div>

                    <div class="form-group" id ="view_phone"> 
                        <label class="control-label col-xs-3  ">Phone</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">                                 
                                
                                <a class="edit_user" id="phone"><?php echo $user->phone; ?></a>
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-xs-3  ">Invite Code</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static"> <?php echo $user->invite_code; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-3  ">Invited by</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static"> <?php echo $user->invited_by; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-3  ">Joined</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static"> <?php echo date("m/d/Y H:i:s", strtotime($user->created_at)); ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-3  ">Last Login</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static"> <?php if(!empty($user->updated_at)) echo date("m/d/Y H:i:s", strtotime($user->updated_at)); ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-3  ">Hostings</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static"> <?php echo $this->user_model->getUserHosting($user_id);?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-3  ">Chats</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static"><?php echo $this->user_model->getUserChat($user_id);?> </p>
                        </div>
                    </div>
                    

                <div class="form-group">
                    <label class="control-label col-xs-3"></label>
                       <a  href="<?php echo base_url();?>admin/user/delete_user/<?php echo $user_id?>" class="btn btn-danger">Delete user</a>                   
                
                </div>
             </div>
          </div>  
        <?php } ?>
    </form>
</div> 