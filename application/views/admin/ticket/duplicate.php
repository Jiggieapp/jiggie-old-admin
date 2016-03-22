<?php
$curr = isset($this->currency) ? $this->currency : 'Rp';
?>
<div class="">
    <form class="form-horizontal form-bordered" role="form" id="ticket-form" method="post">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create Ticket</h4>
            </div>
            <div class="panel-body" id="section_create_event">
                <?php //echo showMessage(); //echo $error;?>

                <?php if (isset($error)) { ?>
                    <div class="alert alert-block  alert-danger fade in">
                        <button class="close" type="button" data-dismiss="alert">×</button><?php echo $error; ?></div>
                <?php } ?>
				<p class='f11'>All <span class="asterisk">*</span> marked fields are mandatory</p>
				<div class="form-group">
                    <label class="control-label col-sm-3">Event Title </label>
					<?php //$eventid = $events->event_type =='special' ? $events->_id:$events->event_id?>
                    <div class="controls col-sm-4">
                    	<?php   echo $event->title?> 
                    	<!--input type="hidden" name="w_event_id" value="<?php echo $events->event_id ?>" />
                    	<input type="hidden" name="s_event_id" value="<?php echo $events->_id ?>" />  
                    	<input type="hidden" name="event_type" value="<?php echo $events->event_type ?>" /-->                       
                    </div>
                </div>
                <div class="form-group" id="hosting_name_div">
                    <label class="control-label col-sm-3">Name<span class="asterisk">*</span></label>

                    <div class="controls col-sm-4" id ="hosting_name_div_err">
                    	<input class="typeahead form-control" name="name" id="name" type="text" placeholder="Ticket name" value="<?php echo set_post_value('name', $ticket->name) ;  ?>" required>
                        <input type="hidden" name="event_id" value="<?php echo $ticket->event_id?>" />
                        <input type="hidden" name="is_recurring_event" value="<?php echo $ticket->is_recurring?>" />
                    </div>
                </div>
				
                <div class="form-group">
                    <label class="control-label col-sm-3">Sale Type <span class="asterisk">*</span></label>
                    <div class="controls col-sm-4">
                        <select id="ticket_type" class="form-control" name="ticket_type">							
							<option <?php echo set_post_value('ticket_type', $ticket->ticket_type)  == "reservation" ? "selected" : ""; ?> value="reservation">Table Reservation</option>
							<option <?php echo set_post_value('ticket_type', $ticket->ticket_type)  == "booking" ? "selected" : ""; ?> value="booking">Table Booking</option> 
							<option <?php echo set_post_value('ticket_type', $ticket->ticket_type)  == "purchase" ? "selected" : ""; ?> value="purchase">Table Purchase</option>
						</select>
                    </div>
                </div>
				
				<div class="form-group">
                    <label class="control-label col-sm-3"># of Tickets Available</label>
                     <div class="controls col-sm-4">                     	 
                     		<input name="quantity" id="quantity" type="text" class="form-control"   placeholder="0" value="<?php echo set_post_value('quantity', $ticket->quantity)  ?>" />
                        	
						 
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Allowed # of Guests</label>

                    <div class="controls col-sm-4">
                        <input name="guest" id="guest" type="text" class="form-control"   placeholder="0" value="<?php echo set_post_value('guest', $ticket->guest) ; ?>" />

                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Currency</label>
                     <div class="controls col-sm-4">
                        <select name="currency" class="form-control" id="ticket_currency">
                            <option <?php echo isset($ticket->currency) && $ticket->currency  == 'IDR' ? "selected='selected'" : "" ?> value="IDR">Rp</option>
                            <option <?php echo isset($ticket->currency) && $ticket->currency  == 'USD' ? "selected='selected'" : "" ?> value="USD">$</option>
                        </select>
                    </div>
                </div>
				<div class="form-group">
                    <label class="control-label col-sm-3">Price</label>
                     <div class="controls col-sm-4">
                     	<div class="input-group">
                     		<span class="input-group-addon curr"><?php echo $curr ?></span>	
                        	<input name="price" id="price" type="text" class="form-control"   placeholder="0" value="<?php echo set_post_value('price', $ticket->price)  ?>" />
						</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Deposit</label>
                     <div class="controls col-sm-4">
                     	<div class="input-group">
                        	<input name="deposit" id="deposit" type="text" class="form-control"   placeholder="0" value="<?php echo set_post_value('deposit', $ticket->deposit) ; ?>" />
							<span class="input-group-addon">%</span>
						 </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Price per additional guest</label>
                     <div class="controls col-sm-4">
                     	<div class="input-group">
                     		<span class="input-group-addon curr"><?php echo $curr ?></span>	
                        	<input name="add_guest" id="add_guest" type="text" class="form-control"   placeholder="0" value="<?php echo set_post_value('add_guest', $ticket->add_guest) ; ?>" />
						</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Additional Fees</label>
                     <div class="controls col-sm-4">
                       	<span class="col-sm-6 nplr"> <input type="checkbox" <?php echo set_post_value('chk_adminfee', $ticket->chk_adminfee)==1?'checked' :''?>  value="1" id="chk_adminfee" name="chk_adminfee"/> Admin Fee</span>
						<span class="col-sm-6 nplr">
							<div class="input-group">
							  <span class="input-group-addon">$</span>
							    <input name="admin_fee" id="admin_fee" type="text" class="form-control" <?php echo !set_post_value('chk_adminfee', $ticket->chk_adminfee)?'disabled':'' ?>   placeholder="0" value="<?php echo set_post_value('admin_fee', $ticket->admin_fee)  ?>" />
							</div>							
						</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3"></label>
                     <div class="controls col-sm-4">
                       	<span class="col-sm-6 nplr"> <input type="checkbox" <?php echo set_post_value('chk_tax', $ticket->chk_tax)==1?'checked' :''?> value="1" id="chk_tax" name="chk_tax"/> Tax</span>
						<span class="col-sm-6 nplr">
							<div class="input-group">							    
							     <input name="tax" id="tax" type="text" class="form-control" <?php echo !set_post_value('chk_tax', $ticket->chk_tax)?'disabled':'' ?>  placeholder="0" value="<?php echo set_post_value('tax', $ticket->tax) ; ?>" />
								  <span class="input-group-addon">%</span>								  
							</div>							
						</span>
                    </div>
                    <span class="col-sm-2 p6"><label class="curr"><?php echo $curr ?></label> <label id="tax_amt">0</label></span>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3"></label>
                     <div class="controls col-sm-4">
                       	<span class="col-sm-6 nplr"> <input type="checkbox" <?php echo set_post_value('chk_tip', $ticket->chk_tip)==1?'checked' :''?>  value="1" id="chk_tip" name="chk_tip"/> Tip</span>
						<span class="col-sm-6 nplr">
							<div class="input-group">							    
							     <input name="tip" id="tip" type="text" class="form-control" <?php echo !set_post_value('chk_tip', $ticket->chk_tip)?'disabled':'' ?>  placeholder="0" value="<?php echo set_post_value('tip', $ticket->tip); ?>" />
								  <span class="input-group-addon">%</span>	
								  
							</div>
						</span>						
                    </div>
                     <span class="col-sm-2 p6"><label class="curr"><?php echo $curr ?></label> <label id="tip_amt">0</label></span>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Estimated Total</label>
                     <div class="controls col-sm-4">
                        <input name="total" id="total" type="text" class="form-control"   placeholder="0" value="<?php echo set_post_value('total', $ticket->total); ?>" />

                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3"> Summary
                        
                        </label>
                    <div class="controls col-sm-6">
                        <input type="text" name="summary" class="form-control" placeholder="Summary" rows="4"><?php echo $this->input->post('summary'); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3"> Description
                    	
                    	</label>
                    <div class="controls col-sm-6">
                        <textarea name="description" class="form-control autogrow" placeholder="Description" rows="4" style="height: 105px;"><?php echo set_post_value('description', $ticket->description); ?></textarea>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="control-label col-sm-3">Status <span class="asterisk">*</span></label>
                    <div class="controls col-sm-4">
                    	<?php $status =property_exists($ticket, "status")? $ticket->status:'active'?>
                        <select id="ticket_status" class="form-control" name="ticket_status">							
							<option <?php echo set_post_value('ticket_status', $status) == "active" ? "selected" : ""; ?> value="active">Active</option>
							<option <?php echo set_post_value('ticket_status', $status) == "inactive" ? "selected" : ""; ?> value="inactive">Inactive</option> 
							<option <?php echo set_post_value('ticket_status', $status) == "sold out" ? "selected" : ""; ?> value="hidden">Sold out</option>
						 
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

	                <?php 
	                    $confirmationscount=0;
                        $purchase_confirmations = gettype($ticket->purchase_confirmations) == 'array' ? 
                                            $ticket->purchase_confirmations : json_decode($ticket->purchase_confirmations);
	                    if(count( $purchase_confirmations)): ?>
	                     <div class="form-group">
		                    <label class="control-label col-sm-3">Purchase confirmations 
		                    	<p>(I understand that ...) </p>	
		                    </label>
		                    <div class="controls col-sm-4"></div>
		                </div>
	                	<?php foreach ($purchase_confirmations as $key => $value) {
							 
						
	                	?>
	                	<div class="form-group">
		                    <label class="control-label col-sm-3"></label>
		                     <div class="controls col-sm-4">
		                        <input type="checkbox" value="1" id="chk_<?php echo $key ?>" name="chk_<?php echo $key ?>" value="1" <?php echo set_post_value('chk'.$key, true)==1?'checked' :''?> /> <?php echo $value->label ?>
								<input type="hidden" value="<?php echo $value->label?>" name="txt_<?php echo $key ?>" />
								 <textarea id="<?php echo $key ?>_box" name="<?php echo $key ?>_box" class="form-control autogrow" placeholder="" rows="4" style="height: 105px;"><?php echo set_post_value($key.'_box', $value->body  ); ?></textarea>	
		                    </div>
		                </div>
		                 
	                <?php $confirmationscount=$key; } endif ;?>
	               <input type="hidden" value="<?php echo $confirmationscount?>" name="confirmationscount" />
	                 <!--div class="form-group">
	                 	
	                    <label class="control-label col-sm-3"></label>
	                     <div class="controls col-sm-4 newconfirmation"><a href="#" style="color: blue">New Purchase Confirmation</a> </div>
	                </div>
	                <div class="form-group">
	                    <label class="control-label col-sm-3"></label>
	                     <div id="confirmation_cnt" class="controls col-sm-4" style="display:<?php echo $this->input->post('chk_label')==1?'block' :'none'?>">
	                     	<input type="checkbox" value="1" id="chk_cnf_label" name="chk_label" value="1" <?php echo $this->input->post('chk_label')==1?'checked' :''?> />
	                     	<p><input name="new_label" id="new_label" type="text" class="form-control"   placeholder="Label" value="<?php echo $this->input->post('new_label'); ?>" /></p>
	                     	
	                     	<textarea id="confirmation" name="confirmation" class="form-control autogrow" placeholder="" rows="4" style="height: 105px;"><?php echo $this->input->post('Confirmation'); ?></textarea>	
						</div>
	                </div-->
	                <div id="newconfirmationsarea">
	                	<input type="hidden" name="cnfm_count" id="cnfm_count"  value="0"/>
	                	<input type="hidden" name="cnfm_ids" id="cnfm_ids"  value=""/>
	                	
	                </div>
	                <div class="form-group">
	                    <label class="control-label col-sm-3"></p>	
	                    </label>
	                    <div class="controls col-sm-4 newconfirmation"><a href="#" style="color: blue">New Purchase Confirmation</a> </div>
	                </div>
               		
               </div>
                
                
               

                

                <div class="form-group">
                    <label class="control-label col-sm-3"></label>
                    <div class="controls col-sm-4">
                        <button type="submit" class="btn btn-primary">Create Ticket</button>
                        <a class="btn btn-default" href="<?php echo base_url().'admin/tickets#/event-id='.$ticket->event_id.'/is_recurring='.$ticket->is_recurring; ?>">Cancel</a>
                        <a class="btn btn-default rest_btn" href="#" >Reset</a>
                    </div>
                </div>

            </div>
        </div>
       
    </form>
    
</div> 
