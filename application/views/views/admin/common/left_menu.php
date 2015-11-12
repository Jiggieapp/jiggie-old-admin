 <div class="sidebar-profile">
   

    <div class="profile-body">
        <i class="fa fa-lg fa-fw fa-user"></i> <h4><?php echo $this->session->userdata('ADMIN_NAME')?></h4>

        <!--div class="sidebar-user-links">
            <a class="btn btn-link btn-xs" href="pages-profile.html" data-placement="bottom" data-toggle="tooltip" data-original-title="Profile"><i class="fa fa-user"></i></a>
            <a class="btn btn-link btn-xs" href="javascript:;"       data-placement="bottom" data-toggle="tooltip" data-original-title="Messages"><i class="fa fa-envelope"></i></a>
            <a class="btn btn-link btn-xs" href="javascript:;"       data-placement="bottom" data-toggle="tooltip" data-original-title="Settings"><i class="fa fa-cog"></i></a>
            <a class="btn btn-link btn-xs" href="pages-sign-in.html" data-placement="bottom" data-toggle="tooltip" data-original-title="Logout"><i class="fa fa-sign-out"></i></a>
        </div-->
    </div>
</div>
<nav>
    <h5 class="sidebar-header">Navigation</h5>
    <ul class="nav nav-pills nav-stacked">
        <li class="nav-dropdown <?php echo strtolower($current_controller) =='home' ? 'active':''?>">
            <a href="<?php echo base_url() ?>admin/home/dashboard" title="Dashboards">
                <i class="fa fa-lg fa-fw fa-home"></i> Overview
            </a>            
        </li>
        <?php if($this->session->userdata('USER_TYPE_ID')==1){?>
        <li class="nav-dropdown <?php echo strtolower($current_controller) =='adminuser' ? 'active':''?>">
            <a href="<?php echo base_url() ?>admin/adminuser/users/" title="Admin Users">
                <i class="fa fa-lg fa-fw fa-cog"></i> Admin Users
            </a>  
        </li>
        <?php }?>
        <li class="nav-dropdown <?php echo strtolower($current_controller) =='user' ? 'active':''?>">
            <a href="<?php echo base_url() ?>admin/user/users/" title="Users">
                <i class="fa fa-lg fa-fw fa-users"></i> Users
            </a>  
        </li>
        <!--
         <li class="nav-dropdown <?php echo strtolower($current_controller) =='hostings' ? 'active':''?>">
            <a href="<?php echo base_url() ?>admin/hostings/hosting/0" title="Hostings">
                <i class="fa fa-lg fa-fw fa-suitcase"></i> Hostings  
            </a>                
        </li>
        -->
         <li class="nav-dropdown <?php echo strtolower($current_controller) =='venue' ? 'active':''?>">
            <a href="<?php echo base_url() ?>admin/venue/venues/" title="Venues">
                <i class="fa fa-lg fa-fw fa-map-marker"></i> Venues  
            </a>                   
        </li>
         <li class="nav-dropdown <?php echo strtolower($current_controller) =='chat' ? 'active':''?>">
            <a href="<?php echo base_url() ?>admin/chat/chat_list/" title="Chats">
                <i class="fa fa-lg fa-fw fa-wechat"></i> Chats 
            </a>            
        </li>
         <li class="nav-dropdown <?php echo strtolower($current_controller) =='blocklists' ? 'active':''?>">
            <a href="<?php echo base_url() ?>admin/blocklists/blocks/" title="Blocks">
                <i class="fa fa-lg fa-fw fa-warning"></i> Blocks  
            </a>
                  
        </li>
       
    </ul>
    
</nav>