<div class="table-responsive">
<table  class="table table-striped dataTable" >
<thead>   
    <tr>
        <th class="sorting " data-field="name">Full name</th>
        <th class="sorting" data-field="age">Age</th>
        <th class="sorting" data-field="gender">Sex</th>
        <th class="sorting" data-field="location">Location</th>
        <th class="sorting sorting_desc" data-field="created_at">Joined</th>
        <th class="sorting " data-field="cnt_chat">Chats</th>
        <th class="sorting " data-field="num_host">Hostings</th>
        <th class="sorting" data-field="invited_by">Invited By</th>
        <th class="sorting" data-field="cnt_invite">Invites</th>
        <th class="sorting" data-field="verified">Verified</th>
        <th class="sorting" data-field="user_status">Status</th>
    </tr>
</thead>
<tbody >
  <?php $ct=0;
    if(!empty($users)) {
        foreach($users as $user) { $ct++;?>
            <tr class="odd gradeA">
                <td>
                	<?php  $name= (strlen($user->name) > 20) ?  substr($user->name, 0, strrpos(substr($user->name, 0, 20).'', ' ')).'...' : $user->name;
                	 echo anchor("admin/user/user_details/".$user->id ,$name);?></td>
                <td class="center"><?php echo $user->age ;?></td>
                <td class="center"><?php echo $user->gender ?></td>
                <td class=" "><?php 
                 $location = (strlen($user->location) > 20) ?  substr($user->location, 0, strrpos(substr($user->location, 0, 20).'', ' ')).'...' : $user->location;
                echo $location  ?></td>
                <td class=" "><?php echo date("m/d/y",strtotime($user->created_at ));?></td>
                <td class=""><?php echo $user->cnt_chat ?></td>
                <td class=""><?php echo $user->num_host ?></td>
                <td class=" "><?php echo $user->invited_by;?></td>
                <td class=" "><?php echo $user->cnt_invite ?></td>
                <td><?php echo $user->verified ?></td>
                <td class=" "><?php echo $user->user_status ?></td>
            </tr>
         <?php } 
     } else {
         echo '<tr class="odd gradeA"><td colspan="11 alert alert-warning">No data matching your search.</td></tr>';
     }
     ?>   
 </tbody>
</table>
</div>
<div class="pagination col-md-12">
	<span class="col-md-6"><?php echo $paginate ?></span>
	<span class="col-md-6 text-right">Displaying <span id="paginate_count"><?php echo $ct ?></span> out of <?php echo $total_count?> results</span>
</div>