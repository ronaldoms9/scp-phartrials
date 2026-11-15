<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title> Dashboard</title>
	<link rel="stylesheet" href="css/vendors_css.css">
	<link rel="stylesheet" href="css/style.css">
  </head>

<body class="layout-top-nav light-skin theme-primary ">
<div class="wrapper"><br>
  <div class="content-wrapper">
	  <div class="container">
		<section class="content">
			<div class="row">

            <div class="col-12">
                <div class="box">
                    <div class="box-body">
                    <h4 class="box-title mb-5">Dashboards</h4>
                        <div class="clearfix">
                            <button id="btnRecrutamento" type="button" class="waves-effect waves-light btn mb-5 btn-dark" onclick="javascript:trocaDash('recrutamento')" style="padding-right: 10px;">RECRUTAMENTO</button>
                            <span style="padding-right: 5px;"></span>
                            <button id="btnQueries" type="button" class="waves-effect waves-light btn mb-5 btn-default" onclick="javascript:trocaDash('queries')">QUERIES</button>
                            <span style="padding-right: 5px;"></span>
                            <button id="btnEventosAdversos" type="button" class="waves-effect waves-light btn mb-5 btn-default" onclick="javascript:trocaDash('eventos_adversos')">EVENTOS ADVERSOS</button>
                            <span style="padding-right: 5px;"></span>
                            <?
                            $quemVe = array(1,2,8,20,23,24,25);
                            if(in_array($_SESSION['tipo_usuario'], $quemVe)){
                            ?>
                            <button id="btnSdvMonitoria" type="button" class="waves-effect waves-light btn mb-5 btn-default" onclick="javascript:trocaDash('sdv_monitoria')">SDV/MONITORIA</button>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

		</section>
	  </div>
  </div>
</body>
<script>
    function trocaDash(dash){
        document.getElementById('btnRecrutamento').classList.remove('btn-dark');
        document.getElementById('btnRecrutamento').classList.add('btn-default');
        document.getElementById('btnQueries').classList.remove('btn-dark');
        document.getElementById('btnQueries').classList.add('btn-default');
        document.getElementById('btnEventosAdversos').classList.remove('btn-dark');
        document.getElementById('btnEventosAdversos').classList.add('btn-default');
        document.getElementById('btnSdvMonitoria').classList.remove('btn-dark');
        document.getElementById('btnSdvMonitoria').classList.add('btn-default');



        switch(dash){
            case 'recrutamento':
                window.parent.document.getElementById('iframeDashboard').src = 'dashboard/recrutamento.php';
                document.getElementById('btnRecrutamento').classList.remove('btn-default');
                document.getElementById('btnRecrutamento').classList.add('btn-dark');
                break;
            case 'queries':
                window.parent.document.getElementById('iframeDashboard').src = 'dashboard/queries.php';
                document.getElementById('btnQueries').classList.remove('btn-default');
                document.getElementById('btnQueries').classList.add('btn-dark');
                break;
            case 'eventos_adversos':
                window.parent.document.getElementById('iframeDashboard').src = 'dashboard/ea.php';
                document.getElementById('btnEventosAdversos').classList.remove('btn-default');
                document.getElementById('btnEventosAdversos').classList.add('btn-dark');
                break;
            case 'sdv_monitoria':
                window.parent.document.getElementById('iframeDashboard').src = 'dashboard/sdv.php';
                document.getElementById('btnSdvMonitoria').classList.remove('btn-default');
                document.getElementById('btnSdvMonitoria').classList.add('btn-dark');
                break;
        }
    }
</script>
</html>
