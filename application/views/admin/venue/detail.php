<div class="container-fluid-md venue-details">
    <form class="form-horizontal form-bordered" role="form">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    Venue 
                    
                </h4>

                <div class="panel-options">
                    <!--a href="#" data-rel="collapse"><i class="fa fa-fw fa-minus"></i></a>
                    <a href="#" data-rel="reload"><i class="fa fa-fw fa-refresh"></i></a-->
                    <a href="<?php if(isset($_SERVER['HTTP_REFERER'])){ echo $_SERVER['HTTP_REFERER']; } ?>" data-rel="close">
                        <i class="fa fa-fw fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="panel-body " venue_id="<?php echo $venue_id; ?>">
                <?php
                if (isset($error)) {
                    echo $error;
                } else {
                    ?>
                    
                    <div class="col-sm-5">
                        <a href="<?php echo base_url(); ?>admin/venue/image/<?php echo $venue_id; ?>">
                            <img  width="200" height="200" alt="image" class="img-circle img-profile" src="<?php echo $data->img_1; ?>" alt="<?php echo $data->img_title; ?>"/>
                        </a>   
                    </div>
                    <div class="col-sm-7 profile-details">
                        <div class="row">
                            <div class="col-xs-3">
                                Name  
                            </div>
                            <div class="col-xs-9"> 
                                <p id="view_name">                                                
                                    <a id="name" class="edit_venue"><?php echo $data->name; ?></a>
                                            
                                </p>
                            </div>
                        </div>
                        
                        <!--div class="row">
                            <div class="col-xs-3">Grade</div>
                            <div class="col-xs-9"> 
                                <p id="view_grade"> 
                                    <select name="grade" class="form-control" required>
                                        <option value="">Select Grade</option>
                                        <?php //for($i = 1; $i<=100; $i++) { ?>
                                            <option value="<?php //echo $i;?>" <?php //echo $this->input->post("grade") == $i ? "selected" : ""; ?>><?php //echo $i;?></option>
                                        <?php //} ?>
                                    </select>
                                </p>    
                            </div>
                        </div-->
                        
                        <div class="row">
                            <div class="col-xs-3">
                                Address  
                            </div>
                            <div class="col-xs-9"> 
                                <p id="view_address">                                    
                                    <a id="address" class="edit_venue"><?php echo $data->address; ?></a>
                                    
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3">
                                City  
                            </div>
                            <div class="col-xs-9">
                                <p id="view_city">                                    
                                    <a id="city" class="edit_venue"><?php echo $data->city; ?></a>
                                    
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3">
                                State  
                            </div>
                            <div class="col-xs-9">
                                <p id="view_state">                                   
                                    <a id="state" class="edit_venue"><?php echo $data->state; ?></a>
                                                                     </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3">
                                Zip  
                            </div>
                            <div class="col-xs-9"> 
                                <p id="view_zip">                                    
                                    <a id="zip" class="edit_venue"><?php echo $data->zip; ?></a>
                                   
                                </p>
                            </div>
                        </div>     
                    </div>
                </div>
                <div class="panel-body">
                	<div class="form-group" id="view_neighborhood">
                        <label class="control-label col-xs-3">Grade</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">                                
                                 <a id="rank"  data-value="<?php echo $data->rank; ?>", data-type="select"  class="edit_venue"><?php echo $data->rank; ?></a>
                            </p>
                        </div>
                    </div>
                    <div class="form-group" id="view_neighborhood">
                        <label class="control-label col-xs-3">Neighborhood</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">
                                
                                 <a id="neighborhood"  class="edit_venue"><?php echo $data->neighborhood; ?></a>
                            </p>
                        </div>
                    </div>

                    <div class="form-group" id="view_cross_street">
                        <label class="control-label col-xs-3">Cross Street</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">
                               
                                <a id="cross" class="edit_venue"><?php echo $data->cross_street; ?></a>
                            </p>
                        </div>
                    </div>

                    <div class="form-group" id="view_phone">
                        <label class="control-label col-xs-3">Phone</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">
                               
                                <a id="phone" class="edit_venue"><?php echo $data->phone; ?></a>
                                <span class="save_error"></span>
                            </p>
                        </div>
                    </div> 

                    <div class="form-group" id="view_lat">
                        <label class="control-label col-xs-3">Latitude</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">
                              
                                <a id="lat" class="edit_venue"><?php echo $data->lat; ?></a>
                            </p>
                        </div>
                    </div>

                    <div class="form-group" id="view_lng">
                        <label class="control-label col-xs-3">Longitude</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">
                               
                                <a id="lng" class="edit_venue"><?php echo $data->lng; ?></a>
                            </p>
                        </div>
                    </div>  

                    <div class="form-group" id="view_url">
                        <label class="control-label col-xs-3">URL</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">
                              
                                <a id="url" class="edit_venue"><?php echo $data->venue_url; ?></a>
                                <span class="save_error"></span>
                            </p>
                        </div>
                    </div> 

                    <div class="form-group" id="view_description">
                        <label class="control-label col-xs-3">Description</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">
                               
                                <a data-title="Enter comments" data-placeholder="Your comments here..." data-pk="1" data-type="textarea" id="venue_description" href="#" class="editable editable-pre-wrapped editable-click" >
                                    <?php echo $data->description; ?>
                                </a>
                            </p>
                        </div>
                    </div> 
                 
                 <div class="form-group" id="view_recurring">
                    <label class="control-label col-xs-3">Venue status</label>
                    <div class="controls col-xs-9">
                         <input type="checkbox" name="venue_status" id="venue_status" data-off-color="success" data-on-color="primary" data-off-text="INACTIVE"  data-on-text="ACTIVE" data-size="small"value ="1" <?php echo $data->venue_status == 1 ? "checked" : "";?> class="boot-switch"/>
                         <span id="p_venue_status"></span>
                    </div>
                </div>
                    
                 <div class="form-group">
                    <label class="control-label col-xs-3 "></label>
                    <a  href="<?php echo base_url();?>admin/venue/delete_venue/<?php echo $venue_id?>" class="btn btn-danger">Delete Venue</a>
                    <a href="<?php echo base_url();?>admin/venue/duplicate_venue/<?php echo $venue_id?>"  class="btn btn-danger">Duplicate Venue</a> 
                </div>
              </div> 
           
            <?php } ?>
        </div> 
    </form>
</div>  

