<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>

<title>404 Page Not Found</title>

<link rel="stylesheet" href="/Simple-Task-Board/assets/css/custom-theme/jquery-ui-1.8.16.custom.css">
<link rel="stylesheet" href="/Simple-Task-Board/assets/css/bootstrap.css">
<link rel="stylesheet" href="/Simple-Task-Board/assets/css/extra.css">
<link rel="stylesheet" href="/Simple-Task-Board/assets/css/gridforms.css">

</head>
<body>
<div class="container">
	<div id="content">
		<h1 style="font-size: 70px;"><?php echo $heading; ?><br /><small><?php echo $message; ?></small></h1>
		<p>Either there is an error in the system or the url typed was incorrect. Try using the back button on your browser to get out of here</p>
		<p>If you end up here again please report the problem to <a href="mailto:info@cellkulture.com">info@cellkulture.com</a></p>
		<a href="<?php echo base_url(); ?>" class="btn btn-phenol btn-lg">Return to homepage</a>
	</div>
</div>
</body>
</html>