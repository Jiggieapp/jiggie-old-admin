<div class="mtop10">&nbsp;</div>
<div id="img-tems" class="img-tems">
<?php echo showMessage() ?>
  <?php 
    if(!empty($users)) {
        foreach($users as $user) { ?>
        		<div class="item col-xs-12 col-sm-6 col-md-3">
            		<div class="thumbnail">
            			<a class="user-img" href="<?php echo base_url(); ?>admin/user/image/<?php echo $user->id; ?>" style="opacity: 1;">
		                    <img   alt="image" class="img-responsive" src="<?php echo base_url()?>timthumb.php?src=<?php echo base64_encode($user->profile_image_url) ; ?>&w=200&q=100&h=150" />
		                </a>
		                <a class="user-over" href="<?php echo base_url(); ?>admin/user/image/<?php echo $user->id; ?>" style="opacity: 0;">
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
		                    
		                </a>
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

