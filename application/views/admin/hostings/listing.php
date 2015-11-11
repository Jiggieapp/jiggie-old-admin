<div class="table-responsive">
<table  class="table table-striped dataTable" >
<thead>     
    <tr>
        <th class="sorting  " data-field="time">Date/time</th>
        <th class="sorting" data-field="name">Venue</th>
        <th class="sorting  " data-field="description">Description</th>
        <!--th class="sorting  " data-field="email">Host Email</th-->
        <th class="sorting" data-field="uname">Host name</th>
        <th class=" sorting" data-field="num_chat">Chats</th>
        <th class="  " data-field="age">Repeats</th>
        <th class=" " data-field="">Verified</th>
        <th class="sorting  sorting_desc" data-field="created_at">Created</th>
    </tr>
</thead>
<tbody >
  <?php $ct=0;
    if(!empty($hostings)) {
        foreach($hostings as $hosting) { $ct++;?>
            <tr class="odd gradeA">
                <td class=" "><?php echo date("m/d/y H:i:s", strtotime($hosting->time));?></td>
                <td><?php echo $hosting->name;?></td>
                <td class=" "><?php echo (strlen($hosting->description) > 30) ? substr($hosting->description,0,20).'...' : $hosting->description;?></td>
                <!--td class=" "><?php  //echo $hosting->email; ?></td-->
                <td ><?php  echo anchor("admin/hostings/details/".$hosting->hid, $hosting->uname);?></td>
                <td class=""><?php echo $hosting->num_chat? '<a href="'.base_url().'admin/chat/chat_list/0/'.urlencode('hosting:'.$hosting->hid).'" target="_blank" >'.$hosting->num_chat.'</a>':0  ?></td>
                <td class=" "><?php echo $hosting->is_recurring ;?></td>
                <td class=" "><?php echo $hosting->hosting_status ;?></td>
                <td class=" "><?php echo  date("m/d/y", strtotime($hosting->created_at));?></td>

            </tr>
         <?php } 
     }
     ?>   
 </tbody>
</table>
</div>
<div class="pagination col-md-12">
	<span class="col-md-6"><?php echo $paginate ?></span>
	<span class="col-md-6 text-right">Displaying <span id="paginate_count"><?php echo $ct ?></span> out of <?php echo $total_count?> results</span>
</div>