<div class="">
    <form class="form-horizontal form-bordered" role="form" id="VenueForm" method="post" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create Venue</h4>
            </div>
            <div class="panel-body">
                <?php if (isset($error)) { ?>
                    <div class="alert alert-block  alert-danger fade in">
                        <button class="close" type="button" data-dismiss="alert">×</button><?php echo $error; ?></div>
                <?php } ?>
                <p class='f11'>All <span class="asterisk">*</span> marked fields are mandatory</p>
                <div class="form-group" id="venue_name_div">
                    <label class="control-label col-sm-3">Name <span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <input name="name" id="venue_name_create" type="text" class="form-control" placeholder="Name" value="<?php echo set_value('name'); ?>"  required/></div>
                </div>
               
                <div class="form-group">
                    <label class="control-label col-sm-3">Rank <span class="asterisk">*</span></label>

                    <div class="controls col-sm-2">
                        <select name="rank" class="form-control" required>                             
                            <?php for($i = 1; $i<=100; $i++) { ?>
                                <option value="<?php echo $i;?>" <?php echo $this->input->post("rank") == $i ? "selected" : ""; ?>><?php echo $i;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Address</label>
                    <div class="controls col-sm-6">
                    <textarea name="address" class="form-control autogrow" id="venue_address1" placeholder="Address" rows="4" style="height: 105px;"><?php echo $this->input->post('address'); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Address 2</label>
                    <div class="controls col-sm-6">
                    <textarea name="address2" class="form-control autogrow" id="venue_address2" placeholder="Address" rows="4" style="height: 105px;"><?php echo $this->input->post('address2'); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Neighborhood</label>

                    <div class="controls col-sm-4">
                    	
                    	<select name="neighborhood"  class="form-control">

                            <option value="Menteng">Menteng</option>
                            <option value="Kuningan">Kuningan</option>
                            <option value="Kebayoran Baru">Kebayoran Baru</option>
                            <option value="Senayan">Senayan</option>
                            <option value="Permata Hijau">Permata Hijau</option>
                            <option value="Pondok Indah">Pondok Indah</option>
                            <option value="Lebak Bulus">Lebak Bulus</option>
                            <option value="Kemang">Kemang</option>
                            <option value="Cipete">Cipete</option>
                            <option value="Cilandak">Cilandak</option>
                            <option value="Kelapa Gading">Kelapa Gading</option>
                            <option value="Senopati">Senopati</option>
                            <option value="Sarinah">Sarinah</option>
                            <option value="Cikini">Cikini</option>
                        <!--
                    		<?php $postval = $this->input->post("neighborhood");?>
                    		<option <?php echo $postval == "bryant park" ? "selected='true'" : ""; ?> value="bryant park">Bryant Park</option>
							<option <?php echo $postval == "chelsea" ? "selected='true'" : ""; ?> value="chelsea">Chelsea</option>
							<option <?php echo $postval == "east village" ? "selected='true'" : ""; ?> value="east village">East Village</option>
							<option <?php echo $postval == "flatiron" ? "selected='true'" : ""; ?> value="flatiron">Flatiron</option>
							<option <?php echo $postval == "greenwich village" ? "selected='true'" : ""; ?> value="greenwich village">Greenwich Village</option>
							<option <?php echo $postval == "hell's kitchen" ? "selected='true'" : ""; ?> value="hell's kitchen">Hell's Kitchen</option>
							<option <?php echo $postval == "hells kitchen" ? "selected='true'" : ""; ?> value="hells kitchen">Hells Kitchen</option>
							<option <?php echo $postval == "lincoln center" ? "selected='true'" : ""; ?> value="lincoln center">Lincoln Center</option>
							<option <?php echo $postval == "little italy" ? "selected='true'" : ""; ?> value="little italy">Little Italy</option>
							<option <?php echo $postval == "lower east side" ? "selected='true'" : ""; ?> value="lower east side">Lower East Side</option>
							<option <?php echo $postval == "meat packing" ? "selected='true'" : ""; ?> value="meat packing">Meat Packing</option>
							<option <?php echo $postval == "meatpacking district" ? "selected='true'" : ""; ?> value="meatpacking district">Meatpacking District</option>
							<option <?php echo $postval == "midtown" ? "selected='true'" : ""; ?> value="midtown">Midtown</option>
							<option <?php echo $postval == "midtown east" ? "selected='true'" : ""; ?> value="midtown east">Midtown East</option>
							<option <?php echo $postval == "midtown west" ? "selected='true'" : ""; ?> value="midtown west">Midtown West</option>
							<option <?php echo $postval == "nolita" ? "selected='true'" : ""; ?> value="nolita">Nolita</option>
							<option <?php echo $postval == "theater district" ? "selected='true'" : ""; ?> value="theater district">Theater District</option>
							<option <?php echo $postval == "times square" ? "selected='true'" : ""; ?> value="times square">Times Square</option>
							<option <?php echo $postval == "tribeca" ? "selected='true'" : ""; ?> value="tribeca">Tribeca</option>
							<option <?php echo $postval == "west village" ? "selected='true'" : ""; ?> value="west village">West Village</option>
                            -->
                    	</select>
                    	
                      

                    </div>
                 </div>
                
                 
                
                 <div class="form-group">
                    <label class="control-label col-sm-3">City <span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <!--<input name="city" type="text" class="form-control" placeholder="City" value="<?php //echo $this->input->post('city'); ?>" />-->
                        <select name="city" id="venue_city" class="form-control" required>
                           <option value="jakarta">Jakarta</option>
                           <option value="west jakarta">West Java</option>
                        </select>

                    </div>
                 </div>
                <!--
                <div class="form-group">
                    <label class="control-label col-sm-3">State</label>

                    <div class="controls col-sm-4">
                    	<select name="state" id="venue_state" class="form-control" required>
                           <option value="ny">New York</option>                            
                        </select>
                       
                    </div>
                 </div>
                -->
                <div class="form-group">
                    <label class="control-label col-sm-3">Zip<span class="asterisk">*</span></label>

                    <div class="controls col-sm-4">
                        <input required name="zip" type="text" id="venu_zip" class="form-control" placeholder="Zip" value="<?php echo $this->input->post('zip'); ?>" />

                    </div>
                 </div>
                
                 
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Phone</label>

                    <div class="controls col-sm-4">
                        <input  name="phone_number" type="text" class="form-control" placeholder="Phone" value="<?php echo $this->input->post('phone_number'); ?>" />

                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Latitude</label>

                    <div class="controls col-sm-4">
                        <input name="lat" type="text" id="venue_lat" class="form-control" placeholder="Latitude" value="<?php echo $this->input->post('lat'); ?>" />

                    </div>
                    <div class="col-sm-2">
                    	 <a class="btn btn-primary" id="btnLoadLongLat" href="#">Load coordinates</a>
                    	 <span id="invalidaddress" style="color: red"></span>
                    </div>
                 </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Longitude</label>

                    <div class="controls col-sm-4">
                        <input name="long" id="venue_long" type="text" class="form-control" placeholder="Longitude" value="<?php echo $this->input->post('long'); ?>" />

                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Url</label>

                    <div class="controls col-sm-4">
                        <input name="url" type="text" class="form-control" placeholder="Url" value="<?php echo $this->input->post('url'); ?>" />

                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Description</label>
                    <div class="controls col-sm-6">
                    <textarea name="description" class="form-control autogrow" placeholder="Description" rows="4" style="height: 105px;"><?php echo $this->input->post('description'); ?></textarea>
                    </div>
                </div>
                
                <!--div class="form-group">
                    <label class="control-label col-sm-3">Venue status</label>
                    <div class="col-md-3 col-sm-6">
                      <input type="checkbox" name="venue_status" id="vstatus" data-off-color="success" data-off-text="INACTIVE"  data-on-text="ACTIVE" data-size="small" data-on-color="primary" value ="1" checked="true" class="boot-switch"/>
                     
                    
                    </div>
                </div-->
                
                <!--input type="hidden" name="profile_image" id="profile_image" value="" />
                <input type="hidden" name="uploaded_image" id="uploaded_image" value="0" />
                <div id="file-uploader-demo1">		
                    <noscript>			
                            <p>Please enable JavaScript to use file uploader.</p>
                            <!-- or put a simple form for upload here -->
                    <!--/noscript>
                    
                </div>
                <div class="qq-upload-extra-drop-area">Drop files here too</div-->
                <!--div class="form-group">
                    <label class="control-label col-sm-3">Venue images
                    	<p class="f11">Max allowed size:2MB <br/> Allowed file types :png,jpg</p>
                    </label>

                   	<div class="controls col-sm-4">
                          <input type="file" name="image1" class="file1" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                          <input type="file" name="image2" class="file1" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                          <input type="file" name="image3" class="file1" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                          <input type="file" name="image4" class="file1" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">&nbsp;</label>
                    <div class="controls col-sm-4">
                          <input type="file" name="image5" class="file1" data-show-upload="false" data-preview-file-type="text"/>
                    </div>
                </div--> 
                <div class="form-group">
                    <label class="control-label col-sm-3"></label>
                    <div class="controls col-sm-4"><button type="submit" class="btn btn-primary">Create Venue</button>
                    <a class="btn btn-default" href="<?php echo base_url().'admin/venue'; ?>">Cancel</a>
                    </div>
                </div>
                
            </div>
        </div>
        
    </form>
</div>    
