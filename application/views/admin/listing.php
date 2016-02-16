<?php echo showMessage() ;
$current_controller=  strtolower($current_controller);
$exid = ($current_controller=='hostings')?'hostings':'' ;
 
?>
<div class="" id="section_list" data-section="<?php echo $current_controller?>" data-clickedfrom="" data-clickedfromurl="">
    <div class="panel panel-default">
        <div class="panel-heading" id="test-heading" style="display: none">
            <h4 class="panel-title"><?php echo $p_title ?></h4>

            <div class="panel-options">
                 <?php $perPage = null ?>
                <?php if($current_controller != "chat" && $current_controller != "blocklists" && $add_link!='') { ?>
                <a href="<?php echo $add_link ?>" title="Add New" id="addnewobj" ><i class="fa fa-fw fa-plus"></i></a>
                <?php } ?>
                    <a target="_blank" class="export_options"  style="cursor:pointer;" title="Export" ><i class="fa fa-fw fa-folder "></i></a>
            </div>
        </div>
        <div class="panel-body" data-url="<?php echo @$data_url?>" data-page="0" > 
        	  <div id="toolbar" class="row" style="display: none">    		
                                <div class="col-sm-2 col-md-1 p5 mbsm">                                 	      
                                        <select name="table-basic_length"id="sort-list" aria-controls="table-basic" class="form-control input-sm select2-offscreen perpagelist" tabindex="-1" title="">
                                            <option value="10" <?php if($perPage=='10'){ echo 'selected'; } ?>>10</option>
                                            <option value="25" <?php if($perPage=='25'){ echo 'selected'; } ?>>25</option>
                                            <option value="50" <?php if($perPage=='50'){ echo 'selected'; } ?>>50</option>
                                            <?php if($current_controller != "chat") { ?>
                                            <option value="100" <?php if($perPage=='100'){ echo 'selected'; } ?>>100</option>
                                            <?php } ?> 
                                        </select>                                        		
								</div>
                                <?php if($current_controller == "user"): 
                                	if($this->router->fetch_method()=='users_imageview'){
                                		$ic ='bt_act';$lc='';
									}	else{
										$lc = 'bt_act';$ic='';
									}	
                                	?>
                                	<div class="col-sm-3 col-md-4 mbsm"> 
	                                	<div class="btn-group" id="displayselection">
								            <a class="btn btn-default btn-sm" id="list" data-dispaly="list" href="#"><span class="fa fa-list"> </span> List View</a> 
								            <a class="btn btn-default btn-sm" id="grid" data-dispaly="grid"href="#"><span class="fa fa-th"></span> Image View</a>
								        </div>
                                    </div>    
                                    <div class="col-sm-4 col-md-3 mbsm" id="broadcast_container">
                                        <div class="btn-group" id="broadcast_message">
                                            <a class="btn btn-default btn-sm" id="broadcast" href="<?php echo site_url('/admin/user/broadcast') ?>"><span class="fa fa-cloud-upload"></span> Broadcast</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if($current_controller == "events"):                         	 	
                                	?>
                                	<div class="col-sm-6 col-md-6 mbsm"> 
	                                	<div class="btn-group" id="displayeventselection">
								            <a class="btn btn-default btn-sm" id="list" data-dispaly="list" href="#"><span class="fa fa-list"> </span> List View</a> 
			            					<a class="btn btn-default btn-sm" id="grid" data-dispaly="calendar"href="#"><span class="fa fa-th"></span> Calendar View</a>
								        </div>
                                    </div> 
                                     <div class=" col-sm-3 col-md-2 mbsm">    
										<select name="sort_status" id="sort_status" aria-controls="table-basic" class="form-control input-sm select2-offscreen"  tabindex="-1" title="">
                                            <!--
                                            <option  value="upcoming">All Upcoming</option>
                                            -->
                                            <option  value="published">Published</option>
                                            <option  value="all">All</option>
                                            
                                            <option  value="draft">Draft</option>				                      		                      		
				                      		<option  value="passed">Passed</option>
				                      		<option  value="inactive">Inactive</option>
				                      		<option  value="soldout">Sold Out</option> 
										</select> 
                                        <input type="hidden" name="selected_sort_status" id="selected_sort_status" value="" />
                                    </div>   
                                <?php endif; ?>
                                <?php if($current_controller == "hostings") { ?>
                                    <div class="  col-sm-3 col-md-2 mbsm">    
                                        <select name="search_venue"id="search-venue" aria-controls="table-basic" class="form-control input-sm select2-offscreen" tabindex="-1" title="">
                                            <option value=""></option>
                                             <?php foreach($venues as $venue) { ?>
                                             	
                                                <option data-id="<?php echo $venue->_id;?>" data-name="<?php echo $venue->name;?>" value="<?php echo $venue->_id;?>" <?php echo $this->session->userdata('hosting_venue') == $venue->_id ? "selected" : ""; ?>><?php echo $venue->name;?></option>
                                             <?php } ?> 	
                                        </select> 
                                        <input type="hidden" name="selected_search_venue" id="selected_search_venue" value="" />
                                    </div>
                                    <!--<div class="col-sm-2 ">
					<div class="input-group">
                                            <input id="search_date" name="date" data-rel="datepicker" class="datepicker form-control  input-sm" type="text"  placeholder="Date" value="" />
                        
                                        </div>
                                    </div>-->

                                    <div class=" col-sm-3 col-md-2 mbsm">    
										<select name="search_recurring" id="search-recurring" aria-controls="table-basic" class="form-control input-sm select2-offscreen"  tabindex="-1" title="">
                                            <option value=""></option>
                                            <option value="1" data-id="1" data-name="Yes" <?php echo $this->session->userdata('hosting_recurring') == 1 ? "selected" : ""; ?>>Yes</option> 	
                                            <option value="2" data-id="2" data-name="No"  <?php echo $this->session->userdata('hosting_recurring') == 2 ? "selected" : ""; ?>>No</option>
										</select> 
                                        <input type="hidden" name="selected_search_recurring" id="selected_search_recurring" value="" />
                                    </div>
                                    <div class="col-sm-4 col-md-2 mbsm">    
										<select name="search_promoter" id="search-promoter" aria-controls="table-basic" class="form-control input-sm select2-offscreen" tabindex="-1" title="">
                                            <option value=""></option>
                                            <option value="1" data-id="1" data-name="Yes" >Yes</option> 	
                                            <option value="0" data-id="0" data-name="No" >No</option>
										</select>
                                        <input type="hidden" name="selected_search_promoter" id="selected_search_promoter" value="" />
                                    </div>
                                    <div class=" col-sm-3 col-md-2 mbsm">    
										<select name="search_verified" id="search-verified" aria-controls="table-basic" class="form-control input-sm select2-offscreen"  tabindex="-1" title="">
                                            <option value=""></option>
                                            <option value="1" data-id="1" data-name="Yes">Yes</option> 	
                                            <option value="0" data-id="0" data-name="No" >No</option>
										</select> 
                                        <input type="hidden" name="selected_search_verified" id="selected_search_verified" value="" />
                                    </div>
                                <?php } ?>
                                <?php if($current_controller == "chat") { ?>                                    
                                        <input type="hidden" name="search_filter" id="search_filter" value="<?php echo $user_filter?>" />
                                   
                                <?php }?>
								<div class="col-sm-4 col-md-3 pull-right-sm pull-right-sm pull-right-md pull-right-lg mbsm" id="search_cntr">
									<div class="input-group">
				                        <input type="text" class="form-control  input-sm chatsearch" name="data-search" id="<?php echo $exid?>data-search" placeholder="Search" value="">
				                        <span class="input-group-btn">
				                            <button id="<?php echo $exid?>formsearch" type="button" class="btn btn-primary btn-sm chatbutton">Go!</button>
				                        </span>
				                    </div>									
								</div>
								<div class="col-sm-4 col-md-3 pull-right-sm pull-right-sm pull-right-md pull-right-lg mbsm " id="cal_viewchanger" style="display: none" >
									<div class="btn-group pull-right" >
										<a class="btn btn-default btn-sm view-buttoncal month"   data-view="month" href="#" >Month</a>
										<a class="btn btn-default btn-sm view-buttoncal basicWeek"  data-view="basicWeek" href="#">Week</a>
										<a class="btn btn-default btn-sm view-buttoncal basicDay"   data-view="basicDay" href="#">Day</a>
									</div>
								</div>
								
    		</div>
    		<p id='loading' style="display: none">loading...</p>
    		<div  id="data-list"  class="row">
    			      	
    		</div>       	
        					
        </div>
    </div>
</div> 
