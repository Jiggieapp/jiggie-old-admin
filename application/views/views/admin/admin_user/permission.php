<div class="">
    <?php 
         if (!empty($modules)) {
    ?>
    <?php echo form_open_multipart('admin/adminuser/permission/'.$item->id); 
         }
    ?>
    
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">User Permissions</h4>
            </div>
            <div class="panel-body">
               <?php if(isset($sucmsg)){ echo '<div class="alert  alert-block alert-success fade in"><button data-dismiss="alert" type="button" class="close">×</button>'.$sucmsg.'</div>';  } ?>
 				<?php if(isset($errormsg)){ echo '<div class="alert  alert-block alert-danger fade in"><button data-dismiss="alert" type="button" class="close">×</button>'.$errormsg.'</div>';  } ?>

                <?php 
                if (!empty($modules)) {
                ?>
                    <div class="form-group">
                <?php
                foreach ($modules as $p) { ?>
                    <h4 class="panel-title" id="h4_module_<?php echo $p['module_id']; ?>">
                        <label class="checkbox-inline" for="module_<?php echo $p['module_id']; ?>">
                            <input type="checkbox" <?php if( ! $p['nochild'] ){ ?>checked=""<?php }?> id="module_<?php echo $p['module_id']; ?>" name="module_<?php echo $p['module_id']; ?>">
                            <span class="mymodule"><?php echo $p['module']; ?></span>
                        </label>
                    </h4>
                
                    <?php
                        
                            if ( ! empty ( $p['modulepermission'] ) ){
                                ?>
                            <ul id="ul_module_<?php echo $p['module_id']; ?>">
                            <?php
                                    foreach ( $p['modulepermission'] as $ch ){
                                        ?>
                                <li>
                                    <label  for="tall-1" class="checkbox">
                                        <input type="checkbox" name="user_permission[<?php echo $p['module_id']; ?>][]" <?php echo isset($ch['haspermission']) ? 'checked' : ''; ?> value="<?php echo $ch['value']; ?>" /> 
                                        <?php echo $ch['permission']; ?>
                                    </label>
                                </li>
                                <?php
                                    }
                                    ?>
                            </ul>
                            <?php
                            }
                            ?>
                        
                        <?php
                            if ( ! empty ( $p['subcategories'] ) ){
                                ?>
                            <ul>
                            <?php
                                    foreach ( $p['subcategories'] as $sub ){
                                        ?>
                                <li>
                                    <div class="w-box-header">
                                        <label class="checkbox" for="module_<?php echo $sub['module_id']; ?>">
                                            <input type="checkbox" <?php if( ! $sub['nochild'] ){ ?>checked=""<?php }?> id="module_<?php echo $sub['module_id']; ?>" name="module_<?php echo $sub['module_id']; ?>">
                                            <div class="mymoduleinner" title="<?php echo $sub['module']; ?>"><?php echo $sub['module']; ?></div>
                                        </label>
                                    </div>
                                    <?php
                                        if ( ! empty ( $sub['modulepermission'] ) ){
                                            ?>
                                        <ul>
                                        <?php
                                                foreach ( $sub['modulepermission'] as $chs ){
                                                    ?>
                                            <li>
                                                <label for="tall-1" class="checkbox">
                                                    <input type="checkbox" name="user_permission[<?php echo $sub['module_id']; ?>][]" <?php echo isset ( $chs['haspermission'] ) ? 'checked' : ''; ?> value="<?php echo $chs['value']; ?>" /> 
                                                    <?php echo $chs['permission']; ?>
                                                </label>
                                            </li>
                                            <?php
                                                }
                                                ?>
                                        </ul>
                                        <?php
                                        }
                                        ?>
                                    
                                </li>
                                <?php
                                    }
                                    ?>
                            </ul>
                            <?php
                            }
                            ?>
                        
                <?php } 
                
                ?></div>
                <div class="form-group">
                    <label class="control-label col-sm-12"></label>
                    <div class="controls col-sm-12"><button type="submit" class="btn btn-primary">Submit</button>
                    <a class="btn btn-warning" href="<?php echo base_url() . 'admin/adminuser/'; ?>">Cancel</a>
                    </div>
                </div>
                <?php } 
                  else {
                    echo "No permissions has been assigned";
                }
                ?>
                    
                
                
            </div>
        </div>
        <input type="hidden" name="sample" value="0" />
      </form>
</div>      


