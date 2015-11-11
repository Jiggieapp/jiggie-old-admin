<p class="f11"><span class="asterisk">*</span>Max allowed size:2MB , Allowed file types :png,jpg</p></br>
<?php 
  echo showMessage() ;        
	$count = 1;
	foreach($user as $field => $img){
		if($count %2 == 1){?>
			<div class="img-row col-md-12">
    	<?php }
    	  if(!empty($img)) {  
    	
    	?>
    	
			<div class="col-md-5 img-box-live"  id="update_cnt_imageform<?php echo $count;?>">
				<img src="<?php echo  $img?>" class="img-responsive" />
				 
				 <a id="<?php echo $count;?>" class="btn btn-sm btn-primary img-edit img-edit-link">
					<i class="fa fa-edit"></i>
				</a>
				<ul id="show<?php echo $count;?>" class="photo-drop" style="display: none; ">
	                        <li>
	                        	
	                        		
	                            <form id="imageform<?php echo $count;?>" method="post" enctype="multipart/form-data" action="<?php echo base_url().'admin/user/profile_image/';?>">
					            <a href="#">	
		                      		<label  class="change-upload-photo" id="change_upload_photo<?php echo $count;?>"   trigger="uploadFile<?php echo $count;?>"   old-image-id=""  for="uploadFile<?php echo $count;?>" >
		                      			<input type="file" form-id="imageform<?php echo $count;?>" data-for="<?php echo $user_id?>"   class="hidden ucimageuploader" id="uploadFile<?php echo $count;?>"  name="uploadFile<?php echo $count;?>" data-field="<?php echo $field?>" />
 		                          	 	Change Photo 
	                          			
		                          	</label>  
	                          	</a> 
	                            </form>
	                            </li>
	                             			
	 
					<li><a href="#" url="<?php echo base_url().'admin/user/del_image/';?>" row-id="<?php echo $user_id?>"  old-image-id="<?php echo  $img?>" class="del-image" data-field="<?php echo $field?>" >Delete Photo</a></li>	                           

	                          
				</ul>
			</div>
			
			
	 <?php 
	 }else{
	 	 echo '<div class="col-md-5 img-box-null" id="update_cnt_imageform'.$count.'">	
          <form id="imageform'.$count.'" method="post" enctype="multipart/form-data" action="'.base_url().'admin/user/profile_image/">
          <label title="Click to add a Photo" class="upload-text" id="uploadTrigger'.$count.'" trigger="uploadFile'.$count.'"  for="uploadFile'.$count.'" >
          <input type="file" form-id="imageform'.$count.'"     class="hidden ucimageuploader"  data-field="'.$field.'" id="uploadFile'.$count.'"  name="uploadFile'.$count.'" data-for="'.$user_id.'" />
          Click to add a Photo</label></form></div>';
	 }
	 if($count %2 == 1){?>     
	<div class="col-md-2"></div>
	 <?php } else{ ?>
	 </div>
       <?php     
        }
		$count++; 
	}
	?>