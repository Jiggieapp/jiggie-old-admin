<div class="table-responsive">
<table  class="table table-striped dataTable" >
<thead>   
    <tr>
        <th class="sorting sorting_asc" data-field="name">Full name</th>
        <th class="sorting" data-field="age">Age</th>
        <th class="sorting" data-field="gender">Sex</th>
        <th class="sorting" data-field="location">Location</th>
        <th class="sorting" data-field="created_at">Joined</th>
        <th class="sorting " data-field="cnt_chat">Chats</th>
        <th class="sorting " data-field="cnt_hosting">Hostings</th>
        <th class="sorting" data-field="invited_by">Invited By</th>
        <th class="sorting" data-field="cnt_invite">Invites</th>
        <th class="sorting" data-field="verified">Verified</th>
        <th class="sorting" data-field="user_status">Status</th>
    </tr>
</thead>
<tbody >
  <?php 
    if(!empty($users)) {
        foreach($users as $user) { ?>
            <tr class="odd gradeA">
                <td><?php echo anchor("admin/user/user_details/".$user->id ,$user->name);?></td>
                <td class="center"><?php echo $user->age ;?></td>
                <td class="center"><?php echo $user->gender ?></td>
                <td class=" "><?php echo $user->location  ?></td>
                <td class=" "><?php echo date("m/d/y",strtotime($user->created_at ));?></td>
                <td class=""><?php echo $this->user_model->getUserChat($user->id);  ?></td>
                <td class=""><?php echo $this->user_model->getUserHosting($user->id); ?></td>
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
<div class="pagination"><?php echo $paginate ?></div>