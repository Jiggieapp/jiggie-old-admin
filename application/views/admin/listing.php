<?php echo showMessage() ?>
<div class="container-fluid-md">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title"><?php echo $p_title ?>        	</h4>

            <div class="panel-options">
                 <?php $perPage = $this->session->userdata('per_page'); ?>
                <?php if($current_controller != "chat" && $current_controller != "block") { ?>
                <a href="<?php echo $add_link ?>" title="Add New" ><i class="fa fa-fw fa-plus"></i></a>
                <?php } ?>
                    <a href="<?php echo $export_link ?>" title="Export" ><i class="fa fa-fw fa-folder"></i></a>
            </div>
        </div>
        <div class="panel-body" data-url="<?php echo $data_url?>" > 
        	  <div id="toolbar" class="row">    		
                                <div class="col-xs-3 col-sm-2 col-md-1">    
                                        <select name="table-basic_length"id="sort-list" aria-controls="table-basic" class="form-control input-sm select2-offscreen" tabindex="-1" title="">
                                            <option value="10" <?php if($perPage=='10' && $current_controller=='user'){ echo 'selected'; } ?>>10</option>
                                            <option value="25" <?php if($perPage=='25' && $current_controller=='user'){ echo 'selected'; } ?>>25</option>
                                            <option value="50" <?php if($perPage=='50' && $current_controller=='user'){ echo 'selected'; } ?>>50</option>
                                            <option value="100" <?php if($perPage=='100' && $current_controller=='user'){ echo 'selected'; } ?>>100</option>
                                        </select>			
				</div>
                                <?php if($current_controller == "user"): ?>
                                	<div class="btn-group">
							            <a class="btn btn-default btn-sm" id="list" href="<?php echo base_url() . 'admin/user/users'; ?>"><span class="fa fa-list"> </span> List</a> 
							            <a class="btn btn-default btn-sm" id="grid" href="<?php echo base_url() . 'admin/user/users_imageview'; ?>"><span class="fa fa-th"></span> Grid</a>
							        </div>
                                        
                                <?php endif; ?>
                                <?php if($current_controller == "hostings") { ?>
                                    <div class="  col-sm-3 col-md-2">    
                                        <select name="search_venue"id="search-venue" aria-controls="table-basic" class="form-control input-sm select2-offscreen" tabindex="-1" title="">
                                            <option value=""></option>
                                             <?php foreach($venues as $venue) { ?>
                                                <option data-id="<?php echo $venue["id"];?>" data-name="<?php echo $venue["name"];?>" value="<?php echo $venue["id"];?>" <?php echo $this->input->post("venue") == $venue["id"] ? "selected" : ""; ?>><?php echo $venue["id"];?></option>
                                             <?php } ?> 	
                                        </select> 
                                        <input type="hidden" name="selected_search_venue" id="selected_search_venue" value="" />
                                    </div>
                                    <!--<div class="col-sm-2 ">
					<div class="input-group">
                                            <input id="search_date" name="date" data-rel="datepicker" class="datepicker form-control  input-sm" type="text"  placeholder="Date" value="" />
                        
                                        </div>
                                    </div>-->
                                    <div class=" col-sm-3 col-md-2">    
					<select name="search_recurring" id="search-recurring" aria-controls="table-basic" class="form-control input-sm select2-offscreen"  tabindex="-1" title="">
                                            <option value=""></option>
                                            <option value="1" data-id="1" data-name="Yes">Yes</option> 	
                                            <option value="0" data-id="2" data-name="No">No</option>
					</select> 
                                        <input type="hidden" name="selected_search_recurring" id="selected_search_recurring" value="" />
                                    </div>
                                    <div class="col-sm-4 col-md-2">    
					<select name="search_promoter" id="search-promoter" aria-controls="table-basic" class="form-control input-sm select2-offscreen" tabindex="-1" title="">
                                            <option value=""></option>
                                            <option value="1" data-id="1" data-name="Yes">Yes</option> 	
                                            <option value="0" data-id="2" data-name="No">No</option>
					</select>
                                        <input type="hidden" name="selected_search_promoter" id="selected_search_promoter" value="" />
                                    </div>
                                    <div class=" col-sm-3 col-md-2">    
					<select name="search_verified" id="search-verified" aria-controls="table-basic" class="form-control input-sm select2-offscreen"  tabindex="-1" title="">
                                            <option value=""></option>
                                            <option value="1" data-id="1" data-name="Yes">Yes</option> 	
                                            <option value="0" data-id="2" data-name="No">No</option>
					</select> 
                                        <input type="hidden" name="selected_search_verified" id="selected_search_verified" value="" />
                                    </div>
                                <?php } ?>
				<div class="col-sm-3 pull-right-sm pull-right-sm pull-right-md pull-right-lg">
				<div class="input-group">
                        <input type="text" class="form-control  input-sm " name="data-search" id="data-search" placeholder="Search">
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
