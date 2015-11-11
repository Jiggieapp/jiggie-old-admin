<?php echo showMessage() ;
$current_controller=  strtolower($current_controller);
$exid = ($current_controller=='hostings')?'hostings':'' ;
?>
<div class="" id="section_events" data-section="<?php echo $current_controller?>">
    <div class="panel panel-default">
        <div class="panel-heading" id="test-heading" style="display: none">
            <h4 class="panel-title"><?php echo $p_title ?>        	</h4>

            <div class="panel-options">
               
                    <a target="_blank" class="export_options"  href="<?php echo $export_link ?>" title="Export" ><i class="fa fa-fw fa-folder "></i></a>
            </div>
        </div>
        <div class="panel-body" data-url="<?php echo @$data_url?>" data-page="0" > 
        	<div id="toolbar" class="row">
        		<div class="col-sm-6 col-md-6 mbsm"> 
                	<div class="btn-group" id="displayselection">
			            <a class="btn btn-default btn-sm" id="list" data-dispaly="list" href="#"><span class="fa fa-list"> </span> List View</a> 
			            <a class="btn btn-default btn-sm" id="grid" data-dispaly="grid"href="#"><span class="fa fa-th"></span> Calendar View</a>
			        </div>
			        <p></p>
                </div>    
        	</div>   
    	    
			<div id='calendar'></div>    	
        					
        </div>
    </div>
</div> 
