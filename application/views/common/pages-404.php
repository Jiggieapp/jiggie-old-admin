<!doctype html>
<html class="no-js">
    <head>
        <!-- Meta, title, CSS, favicons, etc. -->
                <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Party Host- Page not found </title>
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
    <body class="body-error body-error-404">
    <div class="container">
        <div class="error-container">
            <div id="container">
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
	</div>
        </div>
    </div>
</body>

</html>