<?php
	include_once "seguranca.php";

  $id = $_GET[base64_encode('codigo')];
  $act = strtoupper($_GET[base64_encode('act')]);

  $tabela = "arq_tipos_arquivos";
  $pagina = "arq_arquivos.php";
  $campos = array("nome", "pai");
  $tipos  = array("S", "S");

  $formatacao = "font-size:11px; vertical-align:middle;";




?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
        <meta charset="utf-8" />
        <title><?=$titulo;?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #3 for " name="description" />
        <meta content="" name="author" />
        <!--<link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />-->
        <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' rel='stylesheet'/>
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <link href="assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/layouts/layout3/css/custom.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/css/geral.css" rel="stylesheet" type="text/css" />
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->

	<link rel="shortcut icon" href="assets/ico/favicon.png">


</head>
    <body class="page-container-bg-solid page-footer-fixed">
        <div class="page-wrapper">
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">
                            
                            <div class="page-content">
                                <div class="container-fluid">
                                     <div class="row">

		                                	          <div class="tab-content">
                                                           <div class="row">
                                                                <div class="col-md-3"></div>
                                                                <div class="col-md-6">
                                                                <?php
                                                                   //totais
                                                                   echo "LUIS" . date("Y-m-d");
                                                                   $query = "select count(id) as QTD from arq_arquivos where data >= 2017-06-10 and data <= 2017-06-10";
                                                                ?>   
                                                                    <div class="tab-pane fade active in" id="tab_1_1">
                                                                    	<div class="portlet light ">
        					                                                    <div class="portlet-body">
        					                                                        <div class="row number-stats margin-bottom-30">
        					                                                            <div class="col-md-6 col-sm-6 col-xs-6">
        					                                                                <div class="stat-left">
        					                                                                    <div class="stat-chart">
        					                                                                        <!-- do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break -->
        					                                                                        <div id="sparkline_bar"></div>
        					                                                                    </div>
        					                                                                    <div class="stat-number">
        					                                                                        <div class="title"> Total de uploads</div>
        					                                                                        <div class="number"> 2460 </div>
        					                                                                    </div>
        					                                                                </div>
        					                                                            </div>
        					                                                            <div class="col-md-6 col-sm-6 col-xs-6">
        					                                                                <div class="stat-right">
        					                                                                    <div class="stat-chart">
        					                                                                        <!-- do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break -->
        					                                                                        <div id="sparkline_bar2"></div>
        					                                                                    </div>
        					                                                                    <div class="stat-number">
        					                                                                        <div class="title"> Total de downloads </div>
        					                                                                        <div class="number"> 719 </div>
        					                                                                    </div>
        					                                                                </div>
        					                                                            </div>
        					                                                        </div>
        					                                                        
        					                                                        <div class="table-scrollable table-scrollable-borderless">
        					                                                            <table class="table table-hover table-light">
        					                                                                <thead>
        					                                                                        <th> <?=traduzir("Usuário");?> </th>
        					                                                                        <th> <?=traduzir("Uploads");?> </th>
        					                                                                        <th> <?=traduzir("Downloads");?> </th>
        					                                                                        <th> <?=traduzir("Validações");?> </th>
        					                                                                        <th> % </th>
        					                                                                </thead>
        					                                                                <tr>
        					                                                                    <td><center><a href="javascript:;" class="primary-link">Brain</a></center></td>
        					                                                                    <td><center>$345</center></td>
        					                                                                    <td><center>45</center></td>
        					                                                                    <td><center>124</center></td>
        					                                                                    <td><center><span class="bold theme-font">80%</span></center></td>
        					                                                                </tr>
        					                                                                
        					                                                            </table>
        					                                                        </div>
        					                                                    </div>
        					                                                    </div>
        					                                                </div>
                                                                    </div>
                                                              </div>
                                                              </div>
                                                 </div>  
                                	           </div>	
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
        <div class="quick-nav-overlay"></div>
        <!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script>
<script src="assets/global/plugins/ie8.fix.min.js"></script>
<![endif]-->
        <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
        <script src="assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>
        
		  <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>
        <script src="assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/horizontal-timeline/horizontal-timeline.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>        
        
        
</body>
</html>
