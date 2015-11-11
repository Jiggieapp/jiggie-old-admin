<div class="container-fluid-md">    
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Profile images</h4>
            </div>
            <div class="panel-body p-phptos" >    
            	<div id="img-cnt" >
            		<?php                             
                	$this->load->view('admin/user/image_listing',$this->gen_contents); 
                ?>
            	</div>        	
                
               
            </div>
        </div>
</div>    
