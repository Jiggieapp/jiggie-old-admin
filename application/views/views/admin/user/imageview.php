
<div class="mtop10">&nbsp;</div>
<div class="table-responsive">
<table  class="table table-striped dataTable" >
<thead>   
    <tr>
        <th class="sorting " data-field="name">Full name</th>
        <th class="sorting" data-field="age">Age</th>
        
        <th class="sorting" data-field="location">Location</th>
        <th class="sorting sorting_desc" data-field="created_at">Joined</th>
        
    </tr>
</thead>
</table>
</div>
<div id="img-tems" class="img-tems">
<?php echo showMessage() ?>
  <?php 
    if(!empty($users)) {
        foreach($users as $user) { ?>
        		<div class="item col-xs-12 col-sm-6 col-md-3">
            		<div class="thumbnail">
            			<a class="user-img" href="<?php echo base_url(); ?>admin/user/image/<?php echo $user->id; ?>" style="opacity: 1;">
            				<?php 
            					if($user->profile_image_url){
            						$image = $user->profile_image_url;
            					}else if($user->profile_image_url_2){
            						$image = $user->profile_image_url_2;
            					}else if($user->profile_image_url_3){
            						$image = $user->profile_image_url_3;
            					}else if($user->profile_image_url_4){
            						$image = $user->profile_image_url_4;
            					}else if($user->profile_image_url_5){
            						$image = $user->profile_image_url_5;
            					}else{
            						$image = $this->config->item('assets_url').'images/default.png';
            					}
            			    ?>
		                    <img   alt="image" class="img-responsive" src="<?php echo base_url()?>timthumb.php?src=<?php echo base64_encode($image) ; ?>&w=200&q=100&h=150" />
		                </a>
		                <!--a class="user-over" href="<?php echo base_url(); ?>admin/user/image/<?php echo $user->id; ?>" style="opacity: 0;">
		                	<div class="row">
		                    	<div class="col-xs-12">
		                    		 <?php echo ($user->location)? 'Location :'.$user->location:''  ?>
		                    	</div>
		                    </div>
		                    <div class="row">
		                        <div class="col-xs-12">
		                        	<?php echo ($user->age)? 'Age :'.$user->age:''  ?> 
		                        </div>
		                    </div>
		                    <div class="row">
		                        <div class="col-xs-12">
		                        	<?php echo ($user->created_at)? 'Created :'.date("m/d/y",strtotime($user->created_at )):''  ?> 		                          
		                        </div>
		                    </div>
		                    
		                </a-->
		                <div class="caption">
		                    <span class="group inner list-group-item-heading"> <?php echo anchor("admin/user/user_details/".$user->id ,$user->name);?></span>
		                    
		                </div>
		                
		                
            		</div>
            	</div>
               
               
                
               

         <?php } 
     } else {
         echo '<div class="col-md-12"><div class=" col-md-12 alert alert-warning">No data matching your search.</div></div>';
     }
     ?>   
                 
</div> 
<div class="pagination col-md-12"><?php echo $paginate ?></div>

