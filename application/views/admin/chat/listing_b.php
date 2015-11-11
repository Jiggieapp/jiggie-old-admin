<div class="table-responsive">
<table  class="table table-striped dataTable" >
<thead>   
    <tr>
        <th class="sorting sorting_asc" data-field="channel_id">Channel ID</th>
        <th >Created by</th>
        <th >Joined by</th>
        <th >Messages</th>
        <th class="sorting " data-field="created_at">Created</th>
        <!--<th class="sorting" data-field="created_at">Last updated</th>-->
        
    </tr>
</thead>
<tbody >
  <?php $user_chat1 = "";$user_chat2 = "";$flag = "";$ct=0;
    if(!empty($chats)) {
        $count = 1;
        foreach($chats as $chat) { $ct++;?>
            <tr class="odd gradeA">
                <td><a  data-toggle="modal" data-target="#myModal" class="get_modal" id="channel_<?php echo $count;?>" >
                      <?php echo $chat->channel_id;?>
                    </a>
                </td>
                <td class="center"><?php if($chat->host1 == 1) { 
                     echo $chat->name1;
                     $user_chat1 = $chat->user1;
                     $flag = "u1";
                    } else {
                        echo $chat->name2;
                        $user_chat2 = $chat->user2;
                    } 
                 
                 ?>
                    
                </td>
                <td class="center "><?php if($chat->host1 == 0) { 
                     echo $chat->name1;
                     $user_chat1 = $chat->user1;
                     $flag = "u2";
                    } else {
                        echo $chat->name2;
                        $user_chat2 = $chat->user2;
                    } 
                 
                 ?><span class="conversation_<?php echo $count;?>" user_1="<?php echo $user_chat1;?>" user_2="<?php echo $user_chat2; ?>" channel_id="<?php echo $chat->channel_id;?>" flag="<?php echo $flag;?>"></span>
                </td>
                <td class=""><?php echo $this->chat_model->getChatMessageCount($chat->channel_id);?></td>
                <td class=""><?php  echo date("m/d/Y",strtotime($chat->created_at));?></td>
                <?php ?>
                
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

<!-- Button trigger modal -->


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



    