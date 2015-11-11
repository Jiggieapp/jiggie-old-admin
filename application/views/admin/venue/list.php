<div class="table-responsive">
<table  class="table table-striped dataTable" >
<thead>
    <tr>
        <th class="sorting sorting_asc" data-field="name">Name</th>
        <th class="sorting  " data-field="address">Address</th>
        <th class="sorting  " data-field="neighborhood">Neighborhood</th>
        <th class="sorting  " data-field="city">City</th>
        <th class="sorting  " data-field="description">Description</th>
        <th class="" data-field="">Image</th>
        <th class="sorting  " data-field="">Hostings</th>
    </tr>
</thead>
<tbody >
 <?php 
if(!empty($venues)) { 
    foreach($venues as $venue) { ?>
        <tr class="odd gradeA">
            <td><a href="<?php echo base_url();?>admin/venue/venue_details/<?php echo $venue->id; ?>"><?php echo $venue->name;?></a></td>
            <td class=" "><?php echo $venue->address;?></td>
            <td class=" "><?php echo $venue->neighborhood;?></td>
            <td class=" "><?php echo $venue->city;?></td>
            <td class=" "><?php echo (strlen($venue->description) > 60) ? substr($venue->description,0,20).'...' : $venue->description;?></td>
            <td><img src="<?php echo $venue->img_1;?>" width="60" height="60"/></td>
            <td class=" "><?php echo $this->venue_model->getVenueHosting($venue->id);?></td>
        </tr>
     <?php } 
 }
 ?>   
 </tbody>
</table>
</div>
<div class="pagination"><?php echo $paginate ?></div>