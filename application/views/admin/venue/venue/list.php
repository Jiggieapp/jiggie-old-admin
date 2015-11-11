<div class="table-responsive">
<table  class="table table-striped dataTable" >
<thead>
    <tr>
        <th class="sorting " data-field="name">Name</th>
        <th class="sorting  " data-field="address">Address</th>
        <th class="sorting  " data-field="neighborhood">Neighborhood</th>
        <th class="sorting  " data-field="city">City</th>
        <th class="sorting  " data-field="description">Description</th>
        <th class="" data-field="">Image</th>
        <th class="sorting" data-field="host_cnt">Hostings</th>
    </tr>
</thead>
<tbody >
 <?php $ct=0;
if(!empty($venues)) { 
    foreach($venues as $venue) {$ct++; ?>
        <tr class="odd gradeA">
            <td><a href="<?php echo base_url();?>admin/venue/venue_details/<?php echo $venue->id; ?>"><?php echo $venue->name;?></a></td>
            <td class=" "><?php echo (strlen($venue->address) > 20) ? substr($venue->address, 0, strrpos(substr($venue->address, 0, 20).'', ' ')).'...' : $venue->address;?></td>
            <td class=" "><?php echo $venue->neighborhood;?></td>
            <td class=" "><?php echo $venue->city;?></td>
            <td class=" "><?php  echo (strlen($venue->description) > 20) ?  substr($venue->description, 0, strrpos(substr($venue->description, 0, 20).'', ' ')).'...' : $venue->description;?></td>
				 <?php if($venue->img_1){
					$image = $venue->img_1;
				}else if($venue->img_2){
					$image = $venue->img_2;
				}else if($venue->img_3){
					$image = $venue->img_3;
				}else if($venue->img_4){
					$image = $venue->img_4;
				}else if($venue->img_5){
					$image = $venue->img_5;
				}else{
					$image = $this->config->item('assets_url').'images/default.png';
				}?>
            <td><img src="<?php echo $image;?>" width="60" height="60"/></td>
            <td class=" "><?php  echo $venue->host_cnt?  $venue->host_cnt:0?></td>
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