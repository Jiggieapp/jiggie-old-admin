<div class="table-responsive">
<table  class="table table-striped dataTable" id="chat_sec_api" >
<thead>   
    <tr>
    	<th class="sorting" data-field="updated_at">Last Updated</th>
    	<th class="sorting" data-field="guest">Guest</th>
        <th class="sorting" data-field="host">Host</th>
        <th class="sorting" data-field="venue">Venue</th>
        <th class="sorting" data-field="hosting_time">Hosting Date</th>
        <th class="sorting" data-field="chat_count">Count</th>
        <th class="" data-field="last_message" width="20%">Last Message</th>
        <th class="sorting sorting_desc" data-field="created_at">Created</th>
    </tr>
</thead>
<tbody >
	<?php 	 
	$count=1; //var_dump($chats);
	if(!empty($chats)):
		foreach($chats as $chat):?>
			<tr data-backdrop="static" data-id="chatpop<?php echo $count;?>" data-from="<?php echo $chat->host_id?>" data-topic="<?php echo $chat->id ?>" class="chatrow" style='cursor: pointer' >
				<td>			 
	                 <?php echo date("m/d/Y H:i:s",strtotime(@$chat->updated_at))?>		
	                               
	            </td>
				<td>				
	                 <?php echo $chat->guest ;?>                	
				</td>
				<td>
					<?php echo $chat->host ?>
				</td>
				<td>
					<?php echo $chat->venue?>
				</td>
				<td>
					<?php echo @$chat->hosting_time ? date("m/d/Y H:i:s",strtotime(@$chat->hosting_time)):"Null"?></td>
				</td>			
				<td>
					<?php echo @$chat->chat_count? @$chat->chat_count:0?>
				</td>
				<td>
					<?php echo $chat->message; ?>
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