<?
	include "seguranca.php";
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
		<meta charset="utf-8" />
		<meta http-equiv="Cache-control" content="public">
        <title>SPC - Sistema de pesquisa clínica</title>
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
		<script src="js/sweetalert2.all.min.js"></script>
		<link rel="shortcut icon" href="favicon.ico" /> 
		<style>
			.datepicker {
				z-index: 99999 !important;
			}
		</style>
	</head>
	<body>
			<div class="modal-content" id="myModal">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">CheckList PRÉ Monitoria</h4>
				</div>
				<div class="modal-body">
					<!--<ul class="dropdown-menu dropup scrollable-menu">-->
					<ul>
					
						<?
							$query = "select * from agenda_checklist order by ordem";
							$resCheck = $conexao->pesquisa($query);
							while($rowCheck = mysqli_fetch_assoc($resCheck)){	

								$query = "select * from agenda_checklist_agenda 
									where agenda_id =  ".$_GET['id']." 																												
										and  agenda_checklist_id = " . $rowCheck['id'];
								//echo $query;
								$res   =  $conexao->pesquisa($query);
								$checked = "";
								$label   = "";
								if(mysqli_num_rows($res)>0){
									$row = mysqli_fetch_assoc($res);
									if($row['checked']=="1"){
										$nome = $row['usuario'];
										$nome = explode(" ", $nome);				
										$label = " [" . $nome[0] . " - " . $row['datahora'] . "]";
										$checked = " checked ";
									}else{
										$checked = "";
										$label = "";
									}
								}
								$label = $rowCheck['item'] . " - " . $label;
								?>
									<li>
										<input 
											type="checkbox" 
											value="1" <?=$checked;?> 
											name="check_<?=$rowCheck['id'];?>" 
											id="check_<?=$rowCheck['id'];?>" 
											onclick="muda_check(<?=$rowCheck['id'];?>, this.checked, <?=$_GET['id'];?>);">
											<font size="1px"><?=$label;?></font>
									</li>
								<?
								
							
							}
						?>
					</ul>
					
					<div class="col-md-4"></div>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancelar</button>
					<button type="button" data-dismiss="modal" class="btn green" onclick="continuar();">Continuar</button>
				</div>
			</div>
	</body>
	<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>		      
	<script type="text/javascript" src="assets/global/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>                
	<script src="js/global.js" type="text/javascript"></script>            
	<script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>				
	<link rel="stylesheet" href="css/sweetalert2.css">		
	<script src="js/funcoes.js" type="text/javascript"></script>
	<script src="js/jquery.idletimeout.js" type="text/javascript"></script>
	<script src="js/jquery.idletimer.js" type="text/javascript"></script>
	<script src="js/paginas/idle.js" type="text/javascript"></script>
	<script src="js/ui.datepicker-pt-BR.js" type="text/javascript"></script>
	<script src="js/moment.min.js"></script>
	<script src="plugin/js/fullcalendar.min.js"></script>
	<script src="plugin/js/locale/pt-br.js"></script>
	<script src="js/funcoes.js"></script>
	
</html>