<div class="user-detail">
    <form class="form-horizontal form-bordered" role="form">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">User Details</h4>

                <div class="panel-options">
                    <!--a href="#" data-rel="collapse"><i class="fa fa-fw fa-minus"></i></a>
                    <a href="#" data-rel="reload"><i class="fa fa-fw fa-refresh"></i></a-->
                    <?php $page =$this->session->userdata('cur_page');
                    $page = $page?$page:0;
                    ?>
                    <a href="<?php echo base_url().'admin/user/users/'.$page?>" data-rel="close">
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
                    <div class="col-sm-6 col-md-5 mbsm" style="overflow-x: hidden">
                        <a href="<?php echo base_url(); ?>admin/user/image/<?php echo $user_id; ?>">                            
                            <?php $image = $user->profile_image_url ?$user->profile_image_url: $this->config->item('assets_url').'images/default.png' ?>
                            <img   alt="image"  src="<?php echo base_url()?>timthumb.php?src=<?php echo base64_encode($image) ; ?>&w=200&q=100&h=150" />
                        </a>   
                    </div>
                    <div class="col-sm-6  col-md-7 profile-details">
                        
                         <p id="view_fname" class="form-group">
                           
                            <a id="fname" class="edit_user1" ><?php echo $user->first_name; ?></a>
                                                     
                            <a id="lname" class="edit_user1"><?php echo $user->last_name; ?></a>
                        </p>
                        <div class="row">
                            <div class="col-xs-3"> 
                                DOB 
                            </div>
                            <div class="col-xs-9"> 
                                <p id="edit_birthday">
                                    <a data-title="Select Date of birth" data-pk="1" data-template="MMM / D / YYYY" data-viewformat="MM/DD/YYYY" data-format="YYYY-MM-DD" data-value="<?php echo date("Y-m-d", strtotime($user->birthday)) ?>" data-type="combodate" id="dob" href="#" class="editable" style="display: inline;">
                                        <?php echo date("m/d/Y", strtotime($user->birthday)) ?>
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
                                    	 <input type="checkbox"  name="verified_host" data-off-text="NO"  data-on-text="YES" id="verified_host" data-off-color="success" data-size="small" data-on-color="primary" value ="1" <?php echo $user->verified == 1 ? "checked" : ""; ?> class="boot-switch"/>
                     
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
                                <a class="edit_user1" id="fbid"><?php echo $user->fb_id; ?></a>
                            </p>
                        </div>
                    </div>

                    <div class="form-group" id="view_email">
                        <label class="control-label col-xs-3  ">Email</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">
                                <a class="edit_user1" id="email"><?php echo $user->email; ?></a>
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
                                
                                <a class="edit_user1" id="phone"><?php echo $user->phone; ?></a>
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
                            <span class="form-control-static"> <?php $name = $this->user_model->getInvitedBy($user->invited_by); 
                            	if ($name) echo anchor("admin/user/user_details/".$user->invited_by ,$name,'target="_blank"');?>
                            </span>
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
                            <span class="form-control-static"> <?php 
                            $num_host = $this->user_model->getUserHosting($user_id);
                            echo $num_host? '<a href="'.base_url().'admin/hostings/hosting/0/'.urlencode('user:'.$user_id).'" target="_blank" >'.$num_host.'</a>':0 ;	
                            	?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-3  ">Chats</label>
                        <div class="controls col-xs-9">
                            <span class="form-control-static"><?php 
                            $num_chat =$this->user_model->getUserChat($user_id);
                            echo $num_chat? '<a href="'.base_url().'admin/chat/chat_list/0/'.urlencode('user:'.$user_id).'" target="_blank" >'.$num_chat.'</a>':0 ;
                            ?> </span>
                        </div>
                    </div>
                   
                <div class="form-group">
                    <label class="control-label col-xs-3"></label>
                    
                    	<a class="confirm btn btn-danger" href="#" data-type="user"  data-url="<?php echo base_url();?>admin/user/delete_user/<?php echo $user_id?>" title="Delete">Delete user</a>
                                        
                
                </div>
             </div>
          </div>  
        <?php } ?>
    </form>
</div> 