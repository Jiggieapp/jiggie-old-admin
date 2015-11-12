<div class="table-responsive">
<table  class="table table-striped dataTable" >
<thead>   
   
    <tr>
        <th class="sorting" data-field="name1">Requestor</th>
        <th class="sorting" data-field="name2">Blockee</th>
        <th class="sorting sorting_asc " data-field="created" >Created</th>
        <th class=" ">Chats</th>
        <th></th>
    </tr>
</thead>
<tbody >
  <?php $ct=0;
    if(!empty($blocks)) {
        $count = 1;
        $flag = '';
        foreach($blocks as $block) { $ct++;
            $channel_data = $this->block_model->getChannel($block->user_id1, $block->user_id2);
            $channel_id = $channel_data->channel_id;
            if($block->user_id1 == $channel_data->user_id) {
                $user1 = $block->user_id1;
                $user2 = $block->user_id2;
            } else {
                $user1 = $block->user_id2;
                $user2 = $block->user_id1;
            }
            $number_chats = $this->block_model->getChatCount($channel_id);
        ?>
            <tr class="odd gradeA">
                <td><a data-toggle="modal" data-target="#myModal" class="get_modal_block" id="channel_<?php echo $count;?>" style="cursor:pointer;"><?php echo $block->name1; ?></a></td>
                <td class="center"><?php echo $block->name2 ;?></td>
                <td class="center  "><?php echo date("m/d/Y H:i:s", strtotime($block->created)) ?></td>    
                <td class="center  "><?php echo $number_chats;?></td>
                <td class="center">
                        <?php echo anchor("admin/blocklists/unblock/".$block->id."/".$block->requestor_id."/".$block->blockee_id, "Unblock");?>
                        <span class="conversation_<?php echo $count;?>" user_1="<?php echo $user1;?>" user_2="<?php echo $user2; ?>" channel_id="<?php echo $channel_id; ?>"></span>
                </td>
            </tr>
         <?php $count++;} 
     }
     ?>   
 </tbody>
</table>
</div>
<div class="pagination col-md-12">
	<span class="col-md-6"><?php echo $paginate ?></span>
	<span class="col-md-6 text-right">Displaying <span id="paginate_count"><?php echo $ct ?></span> out of <?php echo $total_count?> results</span>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Chat Detail</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>
