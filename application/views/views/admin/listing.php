<?php echo showMessage() ;
$current_controller=  strtolower($current_controller);
?>
<div class="">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title"><?php echo $p_title ?>        	</h4>

            <div class="panel-options">
                 <?php $perPage = $this->session->userdata('per_page'); ?>
                <?php if($current_controller != "chat" && $current_controller != "blocklists") { ?>
                <a href="<?php echo $add_link ?>" title="Add New" ><i class="fa fa-fw fa-plus"></i></a>
                <?php } ?>
                    <a href="<?php echo $export_link ?>" title="Export" ><i class="fa fa-fw fa-folder"></i></a>
            </div>
        </div>
        <div class="panel-body" data-url="<?php echo @$data_url?>" data-page="0" > 
        	  <div id="toolbar" class="row">    		
                                <div class="col-sm-2 col-md-1 p5 mbsm">    
                                        <select name="table-basic_length"id="sort-list" aria-controls="table-basic" class="form-control input-sm select2-offscreen" tabindex="-1" title="">
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
                                	<div class="col-sm-6 col-md-6 mbsm"> 
	                                	<div class="btn-group">
								            <a class="btn btn-default btn-sm <?php echo $lc;?>" id="list" href="<?php echo base_url() . 'admin/user/users'; ?>"><span class="fa fa-list"> </span> List View</a> 
								            <a class="btn btn-default btn-sm <?php echo $ic;?>" id="grid" href="<?php echo base_url() . 'admin/user/users_imageview'; ?>"><span class="fa fa-th"></span> Image View</a>
								        </div>
                                    </div>    
                                <?php endif; ?>
                                <?php if($current_controller == "hostings") { ?>
                                    <div class="  col-sm-3 col-md-2 mbsm">    
                                        <select name="search_venue"id="search-venue" aria-controls="table-basic" class="form-control input-sm select2-offscreen" tabindex="-1" title="">
                                            <option value=""></option>
                                             <?php foreach($venues as $venue) { ?>
                                                <option data-id="<?php echo $venue["id"];?>" data-name="<?php echo $venue["name"];?>" value="<?php echo $venue["id"];?>" <?php echo $this->session->userdata('hosting_venue') == $venue["id"] ? "selected" : ""; ?>><?php echo $venue["name"];?></option>
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
                                            <option value="0" data-id="2" data-name="No"  <?php echo $this->session->userdata('hosting_recurring') == 2 ? "selected" : ""; ?>>No</option>
										</select> 
                                        <input type="hidden" name="selected_search_recurring" id="selected_search_recurring" value="" />
                                    </div>
                                    <div class="col-sm-4 col-md-2 mbsm">    
										<select name="search_promoter" id="search-promoter" aria-controls="table-basic" class="form-control input-sm select2-offscreen" tabindex="-1" title="">
                                            <option value=""></option>
                                            <option value="1" data-id="1" data-name="Yes" <?php echo $this->session->userdata('hosting_promoter') == 1 ? "selected" : ""; ?>>Yes</option> 	
                                            <option value="0" data-id="2" data-name="No" <?php echo $this->session->userdata('hosting_promoter') == 2 ? "selected" : ""; ?>>No</option>
										</select>
                                        <input type="hidden" name="selected_search_promoter" id="selected_search_promoter" value="" />
                                    </div>
                                    <div class=" col-sm-3 col-md-2 mbsm">    
										<select name="search_verified" id="search-verified" aria-controls="table-basic" class="form-control input-sm select2-offscreen"  tabindex="-1" title="">
                                            <option value=""></option>
                                            <option value="1" data-id="1" data-name="Yes" <?php echo $this->session->userdata('hosting_verified') == 1 ? "selected" : ""; ?>>Yes</option> 	
                                            <option value="0" data-id="2" data-name="No" <?php echo $this->session->userdata('hosting_verified') == 2 ? "selected" : ""; ?>>No</option>
										</select> 
                                        <input type="hidden" name="selected_search_verified" id="selected_search_verified" value="" />
                                    </div>
                                <?php } ?>
								<div class="col-sm-4 col-md-3 pull-right-sm pull-right-sm pull-right-md pull-right-lg mbsm">
									<div class="input-group">
				                        <input type="text" class="form-control  input-sm " name="data-search" id="data-search" placeholder="Search" value="<?php echo $this->session->userdata($current_controller.'_search')?>">
				                        <span class="input-group-btn">
				                            <button id="formsearch" type="button" class="btn btn-primary btn-sm">Go!</button>
				                        </span>
				                    </div>
									
								</div>
    		</div>
    		<div  id="data-list"  class="row">
    			 <?php $this->load->view($ci_view); ?>        	
    		</div>       	
        					
        </div>
    </div>
</div> 
