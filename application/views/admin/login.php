<!doctype html>
<html class="no-js">
    <head>
        <!-- Meta, title, CSS, favicons, etc. -->
                <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Party Host &middot; Admin </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <!--<link rel="shortcut icon" href="/favicon.ico">-->
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="stylesheet" href="<?php echo $this->config->item('assets_css_url')?>bootstrap.css">
        <link rel="stylesheet" href="<?php echo $this->config->item('assets_css_url')?>iriy-admin.css">
        <link rel="stylesheet" href="<?php echo $this->config->item('assets_css_url')?>font-awesome/css/font-awesome.css">

        <!--[if lt IE 9]>
        <script src="assets/libs/html5shiv/html5shiv.min.js"></script>
        <script src="assets/libs/respond/respond.min.js"></script>
        <![endif]-->
        
    </head>
    <body class="body-sign-in">
    <div class="container">
        <div class="panel panel-default form-container">
            <div class="panel-body">
                
                <?php echo form_open("admin/home/", array('id' => 'loginForm', "role" => "form","novalidate"=>"novalidate"));?>    
                    <h3 class="text-center margin-xl-bottom">Welcome Back!</h3>
                    <?php echo showMessage() ?>
                    <div class="form-group text-center">
                        <label class="sr-only" for="email">Email Address</label>
                        <input name="email" type="email" class="form-control input-lg" id="email" placeholder="Email Address" required />
                    </div>
                    <div class="form-group text-center">
                        <label class="sr-only" for="password">Password</label>
                        <input name="password" type="password" class="form-control input-lg" id="password" placeholder="Password" required/>
                    </div>

                    
                    <button type="submit" class="btn btn-primary btn-block btn-lg">Sign in</button>
                <?php echo form_close();?>
            </div>
            <div class="panel-body text-center">
                <div class="margin-bottom">
                    <a class="text-muted text-underline" href="<?php echo base_url()?>admin/home/forget_password">Forgot Password?</a>
                </div>
                <!--<div>
                    Don't have an account? <a class="text-primary-dark" href="pages-sign-up.html">Sign up here</a>
                </div>-->
            </div>
        </div>
    </div>
</body>
 <script type="text/javascript" src="<?php echo $this->config->item('assets_js_url') ; ?>jquery-1.9.0.js"></script>
  <script src="<?php echo $this->config->item('assets_url')?>js/bs3/js/bootstrap.min.js"></script>
 <script type="text/javascript" src="<?php echo $this->config->item('assets_url')?>plugins/jquery-validation/jquery.validate.min.js"></script>
 <script type="text/javascript" src="<?php echo $this->config->item('assets_js_url')?>/login.js"></script>
 
</html>
