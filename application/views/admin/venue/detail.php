<div class="venue-details">
    <form class="form-horizontal form-bordered" role="form">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    Venue 
                    
                </h4>

                <div class="panel-options">
                    <!--a href="#" data-rel="collapse"><i class="fa fa-fw fa-minus"></i></a>
                    <a href="#" data-rel="reload"><i class="fa fa-fw fa-refresh"></i></a-->
                    <?php $page =$this->session->userdata('cur_page');
                    	  $page = $page?$page:0;
                    ?>
                    <a href="<?php echo base_url().'admin/venue/venues/'.$page?>" data-rel="close">
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
                    
                    <div class="col-sm-6 col-md-5 mbsm" style="overflow-x: hidden">
                        <a href="<?php echo base_url(); ?>admin/venue/image/<?php echo $venue_id; ?>">
                        	<?php $image = $data->img_1 ?$data->img_1: $this->config->item('assets_url').'images/default.png' ?>
                        	<img   alt="image"  src="<?php echo base_url()?>timthumb.php?src=<?php echo base64_encode($image) ; ?>&w=200&q=100&h=150" />
                           
                        </a>   
                    </div>
                    <div class="col-sm-6  col-md-7 profile-details">
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
                        <div class="row">
                            <div class="col-xs-3">
                                Country  
                            </div>
                            <div class="col-xs-9"> 
                                <p id="view_zip">                                    
                                    <a id="country" class="edit_venue"><?php echo $data->country; ?></a>
                                   
                                </p>
                            </div>
                        </div>     
                    </div>
                </div>
                <div class="panel-body">
                	<div class="form-group" id="view_neighborhood1">
                        <label class="control-label col-xs-3">Grade</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">                                
                                 <a id="rank"  data-value="<?php echo $data->rank; ?>", data-type="select"  class="edit_venue"><?php echo $data->rank; ?></a>
                            </p>
                        </div>
                    </div>
                    <div class="form-group" id="view_neighborhood">
                        <label class="control-label col-sm-3 col-xs-4 ">Neighborhood</label>
                        <div class="controls col-sm-9 col-xs-8">
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
                                <a id="phone" class="edit_venuephone"><?php echo $data->phone; ?></a>                               
                            </p>
                        </div>
                    </div> 

                    <div class="form-group" id="view_lat">
                        <label class="control-label col-xs-3">Latitude</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">                              
                                <a id="lat" class="edit_venuelat"><?php echo $data->lat; ?></a>
                            </p>
                        </div>
                    </div>

                    <div class="form-group" id="view_lng">
                        <label class="control-label col-xs-3">Longitude</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">
                               
                                <a id="lng" class="edit_venuelat"><?php echo $data->lng; ?></a>
                            </p>
                        </div>
                    </div>  

                    <div class="form-group" id="view_url">
                        <label class="control-label col-xs-3">URL</label>
                        <div class="controls col-xs-9">
                            <p class="form-control-static">
                              
                                <a id="url" class="edit_venue_url"><?php echo $data->venue_url; ?></a>
                               
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
                    <label class="control-label col-xs-12 ">
                    <a class="confirm btn btn-danger" href="#" data-type="venue"  data-url="<?php echo base_url();?>admin/venue/delete_venue/<?php echo $venue_id?>" title="Delete">Delete Venue</a>
	                <a href="<?php echo base_url();?>admin/venue/duplicate_venue/<?php echo $venue_id?>"  class="btn btn-danger">Duplicate Venue</a> 
               		</label>
                </div>
              </div> 
           
            <?php } ?>
        </div> 
    </form>
</div>  

