<?
  include_once "class/conexao.class.php";
  include "funcoes.php";

  session_start();
  if(isset($_GET[base64_encode('lan')])){
	$lang = $_GET[base64_encode('lan')];
  }else{
	$lang = "p";
  }
  $_SESSION['lang'] = $lang;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title><?=$titulo;?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Responsive Multipurpose Bootstrap Theme">
	<meta name="author" content="Usuario" >
	<!-- CSS -->
	<link href="assets_face/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="assets_face/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="assets_face/css/main.css" rel="stylesheet" type="text/css">
	<link href="assets_face/css/my-custom-styles.css" rel="stylesheet" type="text/css">

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400italic,400,600,700' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300italic,400italic,700,400,300' rel='stylesheet' type='text/css'>
	<!-- FAVICONS -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets_face/ico/repute144x144.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets_face/ico/repute114x114.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets_face/ico/repute72x72.png">
	<link rel="apple-touch-icon-precomposed" href="assets_face/ico/repute57x57.png">
	<link rel="shortcut icon" href="assets_face/ico/favicon.png">
	<style>
		.btn-primary-outline {
		  background-color: transparent;
		  border-color: #ccc;
		}
		.hero-unit-slider .carousel-inner > .item {
		  position: relative;
		  overflow: hidden;
		  min-height: 280px;
		}
		.hero-unit-slider .carousel-inner > .item > img {
		  width: 100%;
		  max-width: 100% !important;
		  height: auto;
		  min-height: 280px;
		  object-fit: cover;
		  display: block;
		}
		.hero-unit-slider .carousel-caption {
		  top: 0 !important;
		  bottom: 0;
		  left: 0;
		  right: 0;
		  width: auto;
		  padding: 16px;
		  display: -webkit-box;
		  display: -ms-flexbox;
		  display: flex;
		  -webkit-box-orient: vertical;
		  -webkit-box-direction: normal;
		  -ms-flex-direction: column;
		  flex-direction: column;
		  -webkit-box-pack: center;
		  -ms-flex-pack: center;
		  justify-content: center;
		  -webkit-box-align: center;
		  -ms-flex-align: center;
		  align-items: center;
		}
		.hero-unit-slider .hero-heading {
		  font-size: 36px;
		  margin: 0 0 16px;
		  max-width: 100%;
		}
		#btnLogin {
		  display: inline-block !important;
		  visibility: visible !important;
		  position: relative;
		  z-index: 20;
		  margin-top: 8px;
		  white-space: nowrap;
		}
		@media screen and (max-width: 992px) {
		  .hero-unit-slider .hero-heading {
		    font-size: 24px;
		  }
		}
		@media screen and (max-width: 768px) {
		  .hero-unit-slider .carousel-inner > .item,
		  .hero-unit-slider .carousel-inner > .item > img {
		    min-height: 240px;
		  }
		  .hero-unit-slider .hero-heading {
		    font-size: 18px;
		  }
		}
		@media screen and (max-height: 500px), screen and (max-width: 480px) {
		  .hero-unit-slider .carousel-inner > .item,
		  .hero-unit-slider .carousel-inner > .item > img {
		    min-height: 200px;
		  }
		  .hero-unit-slider .hero-heading {
		    font-size: 16px;
		    margin-bottom: 10px;
		  }
		  #btnLogin {
		    font-size: 13px;
		    padding: 6px 14px;
		  }
		}
	</style>
</head>

<body>
	<!-- WRAPPER -->
	<div class="wrapper">
  <!-- NAVBAR -->
		<nav class="navbar navbar-default navbar-dark " role="navigation">
			<div class="container">
				<!-- TOPBAR -->
				<div class="topbar">
					<ul class="list-inline top-nav">
						<li>
							<div class="btn-group">
								<button type="button" class="btn btn-link dropdown-toggle btn-xs" data-toggle="dropdown">
                                <?
                                   if(($lang=="p")||($lang=="")){ echo '<img src="assets_face/img/flags/brasil.png" alt="Brail"> Português <span class="caret"></span>';};
                                   if($lang=="i"){ echo '<img src="assets_face/img/flags/eua.png" alt="Inglês"> Inglês <span class="caret"></span>';};
                                   if($lang=="e"){ echo '<img src="assets_face/img/flags/espanha.gif" alt="Espanhol"> Espanhol <span class="caret"></span>';};
                                ?>
                                </button>
								<ul class="dropdown-menu dropdown-menu-right country-selector" role="menu">
									<li>
										<a href="index.php?<?=gera_parametro("lan", "p");?>"><img src="assets_face/img/flags/brasil.png" alt="Brasil"> Português</a>
									</li>
									<li>
										<a href="index.php?<?=gera_parametro("lan", "i");?>"><img src="assets_face/img/flags/eua.png" alt="Inglês"> English</a>
									</li>
									<li>
										<a href="index.php?<?=gera_parametro("lan", "e");?>"><img src="assets_face/img/flags/espanha.gif" alt="Espanhol"> Spanish</a>
									</li>
								</ul>
							</div>
						</li>
					</ul>
				</div>
				<!-- END TOPBAR -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
						<span class="sr-only">Toggle Navigation</span>
						<i class="fa fa-bars"></i>
					</button>
					<a href="index.html" class="navbar-brand navbar-logo navbar-logo-bigger">

					</a>
				</div>
				<!-- MAIN NAVIGATION -->
				<div id="main-nav" class="navbar-collapse collapse navbar-mega-menu">

				</div>
				<!-- END MAIN NAVIGATION -->
			</div>
		</nav>
		<!-- END NAVBAR -->
		<!-- HERO UNIT -->
		<section class="hero-unit-slider no-margin">
			<div id="carousel-hero" class="slick-carousel">
				<div class="carousel-inner" role="listbox">
					<div class="item active">
						<img src="assets_face/img/sliders/fundo3.jpg" alt="Slider Image">
						<div class="carousel-caption">
							<h2 class="hero-heading">
                            <? if(($lang=="p")||($lang=="")){ echo "SISTEMA DE MONITORIA"; } ?>
                            <? if($lang=="i"){ echo "SISTEMA DE MONITORIA"; } ?>
                            <? if($lang=="e"){ echo "SISTEMA DE MONITORIA"; } ?>
                            </h2>
							<button type="button" id="btnLogin" class="btn btn-primary btn-inverse" onclick="muda_pagina('login.php?<?=gera_parametro("lan", $lang);?>')">LOGIN</button>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- END HERO UNIT -->

      <br><br>


	</div>
	<!-- END WRAPPER -->
	<!-- JAVASCRIPTS -->
	<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="assets/global/plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="js/global.js" type="text/javascript"></script>

	<script>
		function muda_pagina(entrada){
			$.blockUI({ timeout: 2000, message: '<img src="imagens/carregando.gif" width="100">Aguarde, carregando informações...', css: {
				border: 'none',
				padding: '15px',
				backgroundColor: '#000',
				'-webkit-border-radius': '10px',
				'-moz-border-radius': '10px',
				opacity: .5,
				color: '#fff'
			} });
			document.location = entrada;
		}
	</script>
</body>

</html>
