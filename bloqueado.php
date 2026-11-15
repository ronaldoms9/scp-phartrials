<?php
	session_start();		
	$_SESSION['valido'] = 0;
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="utf-8" />
	<meta http-equiv="Cache-control" content="public">
	<title><?=$titulo;?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<meta content="Preview page of Metronic Admin Theme #3 for " name="description" />
	<meta content="" name="author" />
	
	<!--20190510 <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />				-->
	<link href="plugins/font/css/font-awesome.css" rel="stylesheet">		
	<link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
	<link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<!--<link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />		-->
	<link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
	<link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />        
	<link href="assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
	<!-- 20190510 <link href="assets/layouts/layout3/css/custom.min.css" rel="stylesheet" type="text/css" /> -->
	<link href="assets/global/css/geral.css" rel="stylesheet" type="text/css" />      		
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">		
	<script src="js/sweetalert2.all.min.js"></script>
	<!--<link rel="stylesheet" href="css/wickedpicker.min.css">	-->
	<link rel="shortcut icon" href="favicon.ico" /> 	
	<style>
		i.fa {
			font-family:fontawesome !important;
		}
           .icone:hover {
                  width:25px;
           }
    </style>    
</head>
    <body class="page-container-bg-solid">
        <div class="page-wrapper">            
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">
                            <div class="page-head">
                                <div class="container-fluid">
                                    <div class="page-title">
                                        <h1><?="USUÁRIO BLOQUEADO";?> </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
                                <div class="container-fluid">
                                    <ul class="page-breadcrumb breadcrumb">                                        
                                    </ul>
									<br><br><br>
                                    <div class="row">
                    					<div class="col-md-3">
                    					</div>
                                        <div class="col-md-6" style="background-color: white">
                    						<br><br>
                                            <H3>Seu usuário foi bloqueado após 3 tentativas de acesso.</h3>
											<h4>Por favor, entre em contato com o administrador do sistema para liberar seu acesso.</h4>
											<br><br><br><br><br><br>
                                        	</div>
                    					<div class="col-md-3">
                    					</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
        
        <!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script>
<script src="assets/global/plugins/ie8.fix.min.js"></script>
<![endif]-->
        <?php
			include "global_js.php";
		?>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</body>	
</html>
