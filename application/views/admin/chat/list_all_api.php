<div class="table-responsive">
<table  class="table table-striped dataTable" id="chat_sec_api" >
<thead>   
    <tr>
    	<th class="sorting" data-field="last_updated">Last Updated</th>
    	<th class="sorting" data-field="guest">Guest</th>
        <th class="sorting" data-field="host">Host</th>
        <th class="sorting" data-field="topic_venue">Venue</th>
        <th class="sorting" data-field="topic_date">Hosting Date</th>
        <th class="sorting" data-field="count">Count</th>
        <th class="" data-field="last_message" width="20%">Last Message</th>
        <th class="sorting sorting_desc" data-field="created_at">Created</th>
    </tr>
</thead>
<tbody >
	<?php 	 
	$count=1; //var_dump($chats);
	if(!empty($chats)):
		foreach($chats as $chat):?>
			<tr data-backdrop="static" data-id="chatpop<?php echo $count;?>" class="chatrow" style='cursor: pointer' >
				<td>			 
	                 <?php echo date("m/d/Y H:i:s",strtotime(@$chat->last_updated))?> 
	                 			<div id="chatpop<?php echo $count;?>" class="hidden" >
					      		<?php 
				      			
				      			foreach($chat->messages as $msg){
				      				//echo $msg->from_name.' '.$msg->to_name;
					      			if($msg->from_id == $chat->guest_id) {
				                        $cur_user =  $msg->from_name;
				                        $color = "#EFF3F4";
										$user_id = $chat->guest_id;
				                    }
				                    else {
				                        $cur_user =  $msg->to_name;
				                        $color = "#FFFFFF";
										$user_id = $chat->host_id;
				                    }
	
					      		?>
				        		<div class="panel-body npxx" style="background-color:<?php echo$color ?>">
	                                <div class="form-group ">
	                                    <label for="inputEmail3" class="col-sm-4 npxx control-label"><a href="<?php echo base_url().'admin/user/user_details/'.getUserFromFBId($user_id) ?>" target="_blank"><?php echo $msg->from_name ?></a></label>
	                                    <div class="col-sm-5 npxx">
	                                        <?php echo $msg->message ?>
	                                    </div>
	                                    <div class="col-sm-3 npxx">
	                                       <?php echo date("m/d/Y H:i:s",strtotime($msg->created_at))?>
	                                    </div>
	                                </div>                                
	                            </div>
	                            <?php 
									}
	
	                            ?>
					      		</div>
	
	                               
	            </td>
				<td>				
	                 <?php echo $chat->guest ;?>                	
				</td>
				<td>
					<?php echo $chat->host ?>
				</td>
				<td>
					<?php echo $chat->topic_venue?>
				</td>
				<td>
					<?php echo @$chat->topic_date ? date("m/d/Y H:i:s",strtotime(@$chat->topic_date)):"Null"?></td>
				</td>			
				<td>
					<?php echo @$chat->count? @$chat->count:0?>
				</td>
				<td>
					<?php echo $chat->last_message->message ?>
				</td>
				<td> <?php echo date("m/d/Y H:i:s",strtotime(@$chat->created_at))?></td>
			</tr>
		<?php $count++; endforeach;
			else:
				 echo '<tr class="odd gradeA"><td colspan="8 alert alert-warning">No data matching your search.</td></tr>';
			endif;
		 ?>
 </tbody>
</table>
</div>
<div class="pagination col-md-12">
	<span class="col-md-6"><?php echo $paginate ?></span>
	<span class="col-md-6 text-right">Displaying <span id="paginate_count"><?php echo @$count-1 ?></span> out of <?php echo @$total_count?> results</span>
</div>







<div class="modal chatpop" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="chatpop" aria-hidden="true">
  <div class="modal-dialog chat-box">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Chat Details</h4>
      </div>
      <div class="modal-body">
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>        
      </div>
    </div>
  </div>
</div>