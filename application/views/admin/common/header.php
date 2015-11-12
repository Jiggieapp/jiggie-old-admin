 <nav class="navbar navbar-default navbar-static-top no-margin" role="navigation">
	<div class="navbar-brand-group">
	    <a class="navbar-sidebar-toggle navbar-link" data-sidebar-toggle>
	        <i class="fa fa-lg fa-fw fa-bars"></i>
	    </a>
	    <a class="navbar-brand hidden-xxs" href="">
	        <span class="sc-visible">
	            <!--img src="<?php echo $this->config->item('assets_url')?>images/logo.png"  /-->
	        </span>
	        <span class="sc-hidden">
	            <span class="semi-bold">PartyHost</span>
	            ADMIN
	        </span>
	    </a>
	</div>
	
	<ul class="nav navbar-nav navbar-nav-expanded pull-right margin-md-right">
	    <li class="dropdown">
	        <a data-toggle="dropdown" class="dropdown-toggle navbar-user" href="javascript:;">
	           <i class="fa fa-lg fa-fw fa-user"></i>
	            <span class="hidden-xs"><?php echo $this->session->userdata('ADMIN_NAME')?></span>
	            <b class="caret"></b>
	        </a>
	        <ul class="dropdown-menu pull-right-xs">
	            <li class="arrow"></li>	        
                    <li><a href="<?php echo base_url() ?>admin/home/profile">Profile</a></li>
	            <li><a href="<?php echo base_url() ?>admin/home/logout">Log Out</a></li>
	        </ul>
	    </li>
	</ul>
</nav>