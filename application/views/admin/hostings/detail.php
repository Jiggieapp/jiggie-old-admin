<div class="container-fluid-md host-details">
    <form class="form-horizontal form-bordered" role="form">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Hosting Details</h4>
                
                <div class="panel-options">
                    <!--a href="#" data-rel="collapse"><i class="fa fa-fw fa-minus"></i></a>
                    <a href="#" data-rel="reload"><i class="fa fa-fw fa-refresh"></i></a-->
                    <a href="<?php if(isset($_SERVER['HTTP_REFERER'])){ echo $_SERVER['HTTP_REFERER']; } ?>" data-rel="close">
                        <i class="fa fa-fw fa-times"></i>
                    </a>
                </div>
            </div>
            
            <div class="panel-body" hosting_id="<?php echo $hosting_id;?>">
                <p id="data_error" class="error1"></p>
                <?php if(isset($error)) {
                echo $error;
                } else { ?>
                
                <div class="form-group" id="view_userselect">
                    <label class="control-label col-xs-3  ">Name</label>
                    <div class="controls col-xs-9">
                    <p class="form-control-static">
                        <a data-title="Select Users" data-value="<?php echo $user->id; ?>" data-pk="1" data-type="select" id="userselect" href="#" class="editable editable-click" style="color: gray;" data-original-title="" title="">
                            <?php echo $user->name;?>
                        </a>                       
                        <span id="p_userselect"></span>
                   </div>
                </div>
                
                <div class="form-group" id="view_userselect">
                    <label class="control-label col-xs-3  ">Host Email</label>
                    <div class="controls col-xs-9">
                    <p class="form-control-static"><?php echo $user->email;?></p>    
                   </div>
                </div>
                
                
                <div class="form-group" id="view_venue">
                    <label class="control-label col-xs-3  ">Venue</label>
                    <div class="controls col-xs-9">
                    <p class="form-control-static">
                        <a data-title="Select Venue" data-value="<?php echo $venue->id; ?>" data-pk="1" data-type="select" id="venue" href="#" class="editable editable-click" style="color: gray;" data-original-title="" title="">
                            <?php echo $venue->name;?>
                        </a>                       
                        <span id="p_venue"></span>
                    </p>                      
                   </div>
                </div>
                
                <div class="form-group" id="view_theme">
                    <label class="control-label col-xs-3  ">Theme</label>
                    <div class="controls col-xs-9">
                        <p class="form-control-static">                      
                            <a id="theme" class="edit_host"><?php echo $hosting->theme; ?></a>                        
                           
                        </p>
                    </div>
                </div>
                
                <div class="form-group" id="view_description">
                        <label class="control-label col-xs-3  ">Description</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">
                                <a data-title="Enter comments" data-placeholder="Your comments here..." data-pk="1" data-type="textarea" id="host_description" href="#" class="editable editable-pre-wrapped editable-click" >
                                    <?php echo $hosting->description; ?>
                                </a>
                               
                            </p>
                        </div>
                </div>
                
                <div class="form-group" id="view_date">
                    <label class="control-label col-xs-3  ">Date</label>
                    <div class="controls col-xs-9">
                        <p class="form-control-static">
                            <a id="host_date" data-title="Select Date" data-pk="1" data-template="MMM / D / YYYY HH:mm" data-viewformat="MM/DD/YYYY , HH:mm:ss" data-format="YYYY-MM-DD HH:mm" data-value="<?php echo date("Y-m-d H:i:s", strtotime($hosting->time)) ?>" data-type="combodate" href="#" class="editable editable-click" style="display: inline;">
                                    <?php echo date("m/d/Y H:i:s",strtotime($hosting->time));?>
                            </a>
                            
                        </p>
                   </div>
                </div>
                  
                <div class="form-group" id="view_recurring">
                    <label class="control-label col-xs-3  ">Recurring</label>
                    <div class="controls col-xs-9">
                         <input type="checkbox" name="is_recurring" id="is_recurring" data-off-text="NO"  data-on-text="YES" data-size="small" data-off-color="success" data-on-color="primary" value ="1" <?php echo $hosting->is_recurring == 1 ? "checked" : "";?> class="boot-switch"/>
                         <span id="p_recurring"></span>
                    </div>
                </div>
                
                <div class="form-group" id="view_promoter">
                    <label class="control-label col-xs-3  ">Promoter</label>
                    <div class="controls col-xs-9">
                         <input type="checkbox" name="promoter" id="promoter" data-off-text="NO"  data-on-text="YES" data-size="small" data-off-color="success" data-on-color="primary" value ="1" <?php echo $hosting->is_promoter == 1 ? "checked" : "";?> class="boot-switch"/>
                         <span id="p_promoter"></span>
                    </div>
                </div>
                
                <div class="form-group" id="view_rank">
                    <label class="control-label col-xs-3  ">Rank</label>
                    <div class="controls col-xs-9">
                        <p class="form-control-static">                      
                            <a id="rank" class="edit_host"><?php echo $hosting->rank; ?></a>                        
                            
                        </p>
                    </div>
                </div>
                
                <div class="form-group" id="view_hstatus">
                    <label class="control-label col-xs-3 ">Hosting Status</label>
                    <div class="controls col-xs-9">
                         <input type="checkbox" name="hstatus" id="hstatus" data-off-text="INACTIVE"  data-on-text="ACTIVE" data-size="small"  data-off-color="success" data-on-color="primary" value ="1" <?php echo $hosting->hosting_status == 1 ? "checked" : "";?> class="boot-switch"/>
                         <span id="p_hstatus"></span>
                    </div>
                </div>
                
              
                <div class="form-group" id="view_user_image_url">
                    <label class="control-label col-xs-3  ">User Image URL</label>
                    <div class="controls col-xs-9">
                        <p class="form-control-static">                      
                            <a id="user_image_url" class="edit_host"><?php echo $hosting->user_image_url; ?></a>                        
                             
                        </p>
                    </div>
                </div>
                <?php if(count($hosting_chat)>0): ?>
                <div class="form-group" id="view_user_activated_chats">
                    <label class="control-label col-xs-3  ">Activated Chats(<?php echo count($hosting_chat);?>)</label>
                    <div class="controls col-xs-9">
                            <?php foreach($hosting_chat as $key=>$chat): ?>
                             <div class="form-control-static row">  
                               <span class="col-xs-3">  
                                <a  data-toggle="modal" data-target="#myModal" class="get_modal" id="channel_<?php echo $chat['channel_id'];?>" >
                                    <?php if( $hosting->user_id!=$chat['user1']){ echo $chat['name1']; } ?>
                                    <?php if( $hosting->user_id!=$chat['user2']){ echo $chat['name2']; } ?>  
                                </a>
                               </span>    
                                <span class="col-xs-4"><?php echo date("D M d, H:i",strtotime($chat['created_date']));; ?></span>
                               <?php  if($chat['host1'] == 1) { $flag = "u1"; } ?>
                                <?php  if($chat['host1'] == 0) { $flag = "u2"; } ?>
                                <span class="col-xs-2 conversation_<?php echo $key;?>" user_1="<?php echo $chat['user1'];?>" user_2="<?php echo $chat['user2']; ?>" channel_id="<?php echo  $chat['channel_id'];?>" flag="<?php echo $flag;?>"></span>
                              </div>   
                            <?php endforeach; ?> 
                    </div>
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <label class="control-label col-xs-3  "></label>
                    <a  href="<?php echo base_url();?>admin/hostings/delete_hosting/<?php echo $hosting_id?>" class="btn btn-danger btn-sm">Delete hosting</a> 
                    <a  href="<?php echo base_url();?>admin/hostings/duplicate_hosting/<?php echo $hosting_id?>" class="btn btn-danger btn-sm">Duplicate hosting</a>  
                </div>
                <?php } ?>
            </div>
        </div>
    </form>
</div>

<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Chat Detail</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>
