<div class="table-responsive">
<table  class="table table-striped dataTable" >
<thead>   
    <tr>
        <th class="sorting sorting_asc" data-field="name">Full name</th>
        <th class="sorting  " data-field="email">Email</th>
        <th class="sorting  " data-field="created_data">Created Date</th>
    
        <th class="" data-field="user_status"></th>
    </tr>
</thead>
<tbody >
  <?php 
    if(!empty($users)) {
        foreach($users as $user) { ?>
            <tr class="odd gradeA">
                <td><?php echo anchor("admin/adminuser/details/".$user->id ,$user->name);?></td>
                <td class="center  "><?php echo $user->email ;?></td>
                
                <td><?php echo date("m/d/Y H:i:s", strtotime($user->created_at)) ?></td>
                <td class=" " align="center">
                	<a title="Permission" href="<?php echo base_url()?>admin/adminuser/permission/<?php echo $user->id;?>"><i class="fa fa-sitemap"></i></a>
                     | <a href="<?php echo base_url()?>admin/adminuser/delete/<?php echo $user->id;?>" title="Delete"><i class="fa fa-times"></i></a>
                </td>
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