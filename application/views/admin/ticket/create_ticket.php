<div id="">
    <form class="form-horizontal form-bordered" role="form" id="ticket-form" method="post">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create Ticket</h4>
            </div>
            <div class="panel-body" id="section_create_event">
               
				<?php echo showMessage(); //echo $error;?>
                <?php if (isset($error)) { ?>
                    <div class="alert alert-block  alert-danger fade in">
                        <button class="close" type="button" data-dismiss="alert">×</button><?php echo $error; ?></div>
                <?php } ?>
				<p class='f11'>All <span class="asterisk">*</span> marked fields are mandatory</p>
				<div class="form-group">
                    <label class="control-label col-sm-3">Event Title </label>
					<?php //$eventid = $events->event_type =='special' ? $events->_id:$events->event_id?>
                    <div class="controls col-sm-4">
                    	<?php  echo $events->title?> 
                    	<!--input type="hidden" name="w_event_id" value="<?php echo $events->event_id ?>" />
                    	<input type="hidden" name="s_event_id" value="<?php echo $events->_id ?>" />  
                    	<input type="hidden" name="event_type" value="<?php echo $events->event_type ?>" /-->                       
                    </div>
                </div>
                <div class="form-group" id="hosting_name_div">
                    <label class="control-label col-sm-3">Name<span class="asterisk">*</span></label>

                    <div class="controls col-sm-4" id ="hosting_name_div_err">
                    	<input class="typeahead form-control" name="name" id="name" type="text" placeholder="Ticket name" value="<?php echo $this->input->post('name'); ?>" required>
                        <input type="hidden" name="event_id" value="<?php echo $event_id?>" />
                        <input type="hidden" name="is_recurring_event" value="<?php echo $type?>" />
                    </div>
                </div>
				
                <div class="form-group">
                    <label class="control-label col-sm-3">Sale Type <span class="asterisk">*</span></label>
                    <div class="controls col-sm-4">
                        <select id="ticket_type" class="form-control" name="ticket_type">							
							<option <?php echo $this->input->post("ticket_type") == "reservation" ? "selected" : ""; ?> value="reservation">Table Reservation</option>
							<option <?php echo $this->input->post("ticket_type") == "booking" ? "selected" : ""; ?> value="booking">Table Booking</option> 
							<option <?php echo $this->input->post("ticket_type") == "purchase" ? "selected" : ""; ?> value="purchase">Table Purchase</option>
						</select>
                    </div>
                </div>
				
				<div class="form-group">
                    <label class="control-label col-sm-3"># of Tickets Available</label>
                     <div class="controls col-sm-4">                     	 
                     		<input name="quantity" id="quantity" type="text" class="form-control"   placeholder="0" value="<?php echo $this->input->post('quantity'); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Allowed # of Guests</label>

                    <div class="controls col-sm-4">
                        <input name="guest" id="guest" type="text" class="form-control"   placeholder="0" value="<?php echo $this->input->post('guest'); ?>" />

                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Currency</label>
                     <div class="controls col-sm-4">
                        <select name="currency" class="form-control" id="ticket_currency">
                            <option value="IDR">Rp</option>
                            <option value="USD">$</option>
                        </select>
                    </div>
                </div>
				<div class="form-group">
                    <label class="control-label col-sm-3">Price</label>
                     <div class="controls col-sm-4">
                     	<div class="input-group">
                     		<span class="input-group-addon curr">Rp</span>	
                        	<input name="price" id="price" type="text" class="form-control"   placeholder="0" value="<?php echo $this->input->post('price'); ?>" />
						</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Deposit</label>
                     <div class="controls col-sm-4">
                     	<div class="input-group">
                        	<input name="deposit" id="deposit" type="text" class="form-control"   placeholder="0" value="<?php echo $this->input->post('deposit'); ?>" />
							<span class="input-group-addon">%</span>
						 </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Price per additional guest</label>
                     <div class="controls col-sm-4">
                     	<div class="input-group">
                     		<span class="input-group-addon curr">Rp</span>	
                        	<input name="add_guest" id="add_guest" type="text" class="form-control"   placeholder="0" value="<?php echo $this->input->post('add_guest'); ?>" />
						</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Additional Fees</label>
                     <div class="controls col-sm-4">
                       	<span class="col-sm-6 nplr"> <input type="checkbox" <?php echo $this->input->post('chk_adminfee')==1?'checked' :''?>  value="1" id="chk_adminfee" name="chk_adminfee"/> Admin Fee</span>
						<span class="col-sm-6 nplr">
							<div class="input-group">
							  <span class="input-group-addon curr">Rp</span>
							    <input name="admin_fee" id="admin_fee" type="text" disabled="disabled" class="form-control"   placeholder="0" value="<?php echo $this->input->post('admin_fee'); ?>" />
							</div>							
						</span>
                    </div>
                </div>
                <div class="form-group">
                     <label class="control-label col-sm-3"></label>
                     <div class="controls col-sm-4">
                       	<span class="col-sm-6 nplr"> <input type="checkbox" <?php echo $this->input->post('chk_tax')==1?'checked' :''?> value="1" id="chk_tax" name="chk_tax"/> Tax</span>
						<span class="col-sm-6 nplr">
							<div class="input-group">							    
							     <input name="tax" id="tax" type="text" class="form-control" disabled="disabled"  placeholder="0" value="<?php echo $this->input->post('tax'); ?>" />
								  <span class="input-group-addon">%</span>
								  			
							</div>											
						</span>
                    </div>
                    <span class="col-sm-2 p6"><label class="curr">Rp</label> <label id="tax_amt">0</label></span>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3"></label>
                     <div class="controls col-sm-4">
                       	<span class="col-sm-6 nplr"> <input type="checkbox" <?php echo $this->input->post('chk_tip')==1?'checked' :''?>  value="1" id="chk_tip" name="chk_tip"/> Tip</span>
						<span class="col-sm-6 nplr">
							<div class="input-group">							    
							     <input name="tip" id="tip" type="text" class="form-control" disabled="disabled"  placeholder="0" value="<?php echo $this->input->post('tip'); ?>" />
								  <span class="input-group-addon">%</span>	
								  
							</div>
						</span>						
                    </div>
                    <div class="col-sm-2 p6"><label class="curr">Rp</label> <label id="tip_amt">0</label></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Estimated Total</label>
                     <div class="controls col-sm-4">
                        <input name="total" id="total" readonly="true" type="text" class="form-control"   placeholder="0" value="<?php echo $this->input->post('total'); ?>" />

                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3"> Description
                    	
                    	</label>
                    <div class="controls col-sm-6">
                        <textarea name="description" class="form-control autogrow" placeholder="Description" rows="4" style="height: 105px;"><?php echo $this->input->post('description'); ?></textarea>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="control-label col-sm-3">Status <span class="asterisk">*</span></label>
                    <div class="controls col-sm-4">
                        <select id="ticket_status" class="form-control" name="ticket_status">							
							<option <?php echo $this->input->post("ticket_status") == "active" ? "selected" : ""; ?> value="active">Active</option>
							<option <?php echo $this->input->post("ticket_status") == "inactive" ? "selected" : ""; ?> value="inactive">Inactive</option> 
							<option <?php echo $this->input->post("ticket_status") == "sold out" ? "selected" : ""; ?> value="sold out">Sold out</option>
						</select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Payment Timelimit <span class="asterisk">*</span></label>
                    <div class="controls col-sm-4">
                      <input name="payment_timelimit" id="payment_timelimit" type="text" class="form-control" placeholder="180" value="<?php echo $this->input->post('payment_timelimit') ? $this->input->post('payment_timelimit') : '180'; ?>" /> 
                    </div>
                    <div class="col-sm-2">
                        <label>minutes</label>
                    </div>
                </div>
               <div id="termsandcond">
               		<div class="form-group">
	                    <label class="control-label col-sm-3">Purchase confirmations 
	                    	<p>(I understand that ...) </p>	
	                    </label>
	                    <div class="controls col-sm-4">
	                    	 <input type="checkbox" value="1" id="chk_fullamt" name="chk_fullamt"  <?php echo $this->input->post('chk_fullamt')==1?'checked' :''?> /> Full Amount
							 <textarea id="full_amt_box" name="full_amt_box" class="form-control autogrow" placeholder="" rows="4" style="height: 105px;"><?php echo  set_post_value('full_amt_box','My credit card will be charged the full amount shown below') ; ?></textarea>	
	                    </div>
	                </div>
	             
	                <div class="form-group">
	                    <label class="control-label col-sm-3"></label>
	                     <div class="controls col-sm-4">
	                        <input type="checkbox" value="1" id="chk_matching" name="chk_matching" value="1" <?php echo $this->input->post('chk_matching')==1?'checked' :''?> /> Matching CC &ID
							 <textarea id="matching_box" name="matching_box" class="form-control autogrow" placeholder="" rows="4" style="height: 105px;"><?php echo set_post_value('matching_box','The venue will ask to see a matching credit card and ID upon arrival') ; ?></textarea>	
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label class="control-label col-sm-3"></label>
	                     <div class="controls col-sm-4">
	                        <input type="checkbox" value="1" id="chk_returns" name="chk_returns" value="1" <?php echo $this->input->post('chk_returns')==1?'checked' :''?> /> No returns 
							 <textarea id="returns_box" name="returns_box" class="form-control autogrow" placeholder="" rows="4" style="height: 105px;"><?php echo set_post_value('returns_box','This purchase is non-refundable') ?></textarea>	
	                    </div>
	                </div>
	                <div id="newconfirmationsarea">
	                	<input type="hidden" name="cnfm_count" id="cnfm_count"  value="0"/>
	                	<input type="hidden" name="cnfm_ids" id="cnfm_ids"  value=""/>
	                	
	                </div>
	                <div class="form-group">
	                    <label class="control-label col-sm-3"></label>
	                    <div class="controls col-sm-4 newconfirmation"><a href="#" style="color: blue">New Purchase Confirmation</a> </div>
	                </div>
	                
	                <!--div class="form-group">
	                    <label class="control-label col-sm-3"></label>
	                     <div id="confirmation_cnt" class="controls col-sm-4" style="display:<?php echo $this->input->post('chk_label')==1?'block' :'none'?>">
	                     	<input type="checkbox" value="1" id="chk_cnf_label" name="chk_label" value="1" <?php echo $this->input->post('chk_label')==1?'checked' :''?> />
	                     	<p><input name="new_label" id="new_label" type="text" class="form-control"   placeholder="Label" value="<?php echo $this->input->post('new_label'); ?>" /></p>
	                     	
	                     	<textarea id="confirmation" name="confirmation" class="form-control autogrow" placeholder="" rows="4" style="height: 105px;"><?php echo $this->input->post('Confirmation'); ?></textarea>	
						</div>
	                </div-->
	                
               </div>
                
                
               

                

                <div class="form-group">
                    <label class="control-label col-sm-3"></label>
                    <div class="controls col-sm-4">
                        <button type="submit" class="btn btn-primary">Create Ticket</button>
                        <?php $ev_type = $type == 1?"event-weekly":"event-special"?>
                        <a class="btn btn-default" href="<?php echo base_url().'admin/events/#/'.$ev_type.'='.$event_id?>">Cancel</a>
                        <a class="btn btn-default rest_btn" href="#" >Reset</a>
                        
                       
                    </div>
                </div>

            </div>
        </div>
       
    </form>
    
</div> 
