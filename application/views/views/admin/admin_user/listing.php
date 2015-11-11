<div class="table-responsive">
<table  class="table table-striped dataTable" >
<thead>   
    <tr>
        <th class="sorting " data-field="name">Full Name</th>
        <th class="sorting  " data-field="email">Email Address</th>
        <th class="sorting  sorting_desc" data-field="created_at">Created Date</th>
    
        <th class="" data-field="user_status"></th>
    </tr>
</thead>
<tbody >
  <?php $ct=0;
    if(!empty($users)) {
        foreach($users as $user) {  $ct++;?>
            <tr class="odd gradeA">
                <td><?php echo anchor("admin/adminuser/details/".$user->id ,$user->name);?></td>
                <td class="center  "><?php echo $user->email ;?></td>
                
                <td><?php echo date("m/d/Y H:i:s", strtotime($user->created_at)) ?></td>
                <td class=" " align="center">
                	<a title="Permission" href="<?php echo base_url()?>admin/adminuser/permission/<?php echo $user->id;?>"><i class="fa fa-sitemap"></i></a>
                     | <a class="confirm" data-type="admin user"  href="#" data-url="<?php echo base_url()?>admin/adminuser/delete/<?php echo $user->id;?>" title="Delete"><i class="fa fa-times"></i></a>
                </td>
            </tr>
         <?php  } 
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