<?php
	if(!isset($_SESSION)){  session_start();    }
?>
		<meta charset="utf-8" />
        <title><?=$titulo;?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #3 for " name="description" />
        <meta content="" name="author" />


		<link href="plugins/font/css/font-awesome.css" rel="stylesheet">
		<link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/global/css/geral.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Oxygen&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Mono:wdth,wght@62.5,100;64.4,487&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@1,300&display=swap" rel="stylesheet">



		<style>
			.select2-selection{background-color:white !important;}
			.select2-search input {
								background-color:white !important;
								font-family: 'Old Standard TT', serif;
								font-size: 13x}
			.select2-results {
								background-color:white !important;
								min-height: 150px;
								max-height: 450px;
								overflow-y: auto;
								font-family: 'Oxygen', sans-serif;
								font-size: 10x}
			.select_pesquisa {
								font-family: 'Oxygen', sans-serif;
								font-size: 11x
							 }
			.tabela-head{
				font-family: 'Noto Sans Mono', monospace;
				font-size: 12px;
			}
			.tabela-texto{
				font-family: 'Roboto Condensed', sans-serif;
				font-size: 12px !important;
			}
		</style>

		<link rel="shortcut icon" href="favicon.ico" />


		<style>
			i.fa {
				font-family:fontawesome !important;
			}
			.cabecalho_funcao{
				font-size: 10pt;
				font-weigth: bold
			}
		</style>
		<script>
			function popup(url){
				params  = 'width=700';
				params += ', height=500';
				params += ', top=0, left=0'
				params += ', fullscreen=yes';

				newwin=window.open(url,'windowname4', params);
				if (window.focus) {newwin.focus()}
				return false;
		    }
		</script>