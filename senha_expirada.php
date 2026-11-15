<?	
	
	include_once "seguranca.php";	
	include_once "class/conexao.class.php";	
	
	
	
	$conexao = new Conexao();
	
	$id_usuario = $_SESSION['user_id'];	
	if(isset($_GET[base64_encode('act')])){$act = strtoupper($_GET[base64_encode('act')]);}else{$act = "";}
	
	if($act=="A"){		
		$senha = $_POST['form_atual'];		
		$nova  = $_POST['form_nova'];		
		$query = "select id from usu_usuario where id = ".$_SESSION['user_id']." and senha = md5('".$senha."')";		
		$res = $conexao->pesquisa($query);		
		if(mysqli_num_rows($res)>0){			 
			$query= "update usu_usuario set log_usuario = '$id_usuario', 
							senha = md5('$nova'), 
							troca_senha = '',
							data_senha = '".DataAtualToLIS()."', 
							acesso_web = acesso_web +1, 
							ultimo_acesso = '".DataAtualToLIS()."' 
							where id = ".$_SESSION['user_id'];			 
			$conexao->pesquisa($query);			 
			$msg=2;		
		}else{ 
			$msg=1; 
		}	
	}else{
		$msg = "";
	}
?>
<!DOCTYPE html>
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
			i.fa {			font-family:fontawesome !important;		}           
			.icone:hover {                  width:25px;           }    
		</style>    
		<script>            
			<?                
				if($msg==1){echo "alert('".traduzir('Senha atual inválida', $_SESSION['lang']).".');";}                
				if($msg==2){echo "alert('Senha alterada com sucesso!'); document.location ='inicial.php';";}            
			?>            
			function valida(){                            
				var senha = document.getElementById("form_nova").value;                
				var conf  = document.getElementById("form_conf").value;     				
				if(senha!=conf){
					exibe_mensagem("Erro", "A nova senha não confere com a confirmação", "");
				}else{                
					if(retorna_complexidade(document.getElementById("form_nova").value)>=100){
						document.form_info.submit();
					}else{
						exibe_mensagem("Erro", "A nova senha não atende aos requisitos mínimos de complexidade", "");
					}                
				}            
			}            			
			function tem_numeros(texto){				
				var numeros="0123456789";				
				for(i=0; i<texto.length; i++){					
					if (numeros.indexOf(texto.charAt(i),0)!=-1){						
						return 1;					
					}				
				}			
				return 0;			
			} 								
			function retorna_complexidade(senha){				var forca = 0;				if(tem_numeros(senha)==1){ forca += 25;}									if((senha.length >= 8)){ forca += 25; }				if(senha.match(/[a-z]+/)){forca += 25;}				if(senha.match(/[A-Z]+/)){forca += 25;}					return forca;			}            function real(){  					var forca = 0;					senha = document.getElementById("form_nova").value;					if(senha!=""){												forca = parseInt(retorna_complexidade(senha));																document.getElementById("esconde").style.display = 'block';					}else{						document.getElementById("esconde").style.display = 'none';					}															document.getElementById("progresso").value = forca;															var conf = document.getElementById("form_conf");                    var imagem_conf = document.getElementById("img_conf");                    if((senha==conf.value)&&(senha!="")){                        imagem_conf.src ="imagens/img_ok.png";                    }else{ imagem_conf.src ="imagens/img_x.png";                    }					return forca;            }                </script>	<style>	  label {		display: inline-block;		width: 5em;	  }	  </style></head>    <body class="page-container-bg-solid">        <div class="page-wrapper">                        <div class="page-wrapper-row full-height">                <div class="page-wrapper-middle">                    <div class="page-container">                        <div class="page-content-wrapper">                            <div class="page-head">                                <div class="container-fluid">                                    <div class="page-title">                                        <h1><?=traduzir("SENHA EXPIRADA", $_SESSION['lang']);?> </h1>                                    </div>                                </div>                            </div>                            <div class="page-content">                                <div class="container-fluid">                                    <ul class="page-breadcrumb breadcrumb">                                                                            </ul>									<br><br><br>                                    <div class="row">                    					<div class="col-md-3">                    					</div>                                        <div class="col-md-6" style="background-color: white">                    						<br><br>                                            <? $acao = "senha_expirada.php?".gera_parametro("act","A"); ?>                    						<form class="form-horizontal" role="form" action="<?=$acao;?>" method="post" id="form_info" name="form_info">                                                <div class="form-group">                                                     <label class="col-md-4 control-label"><?=traduzir("Senha atual", $_SESSION['lang']);?></label>                                                     <div class="col-sm-5">                                                           <input type="password" <?=$centralizado;?> class="form-control" id="form_atual" name="form_atual">                    								</div>													                    							</div>                    							<div class="form-group">                                                     <label class="col-md-4 control-label"><?=traduzir("Nova senha", $_SESSION['lang']);?></label>                                                     <div class="col-sm-5">                                                          <input type="password" <?=$centralizado;?> class="form-control" id="form_nova" name="form_nova" onKeyUp="real()" title="Comprimento mínimo: 8 caracteres;. Obrigatório ter letras E número. Obrigatório ter alguma letra em maiúsculo"> (<?=traduzir("Mínimo de 8 caracteres", $_SESSION['lang']);?>)                    								</div>													<div  style="display: none" id="esconde"><div class="col-sm-2"><center><progress id="progresso" value="0" max="100"><font size="1px"><span id="complexidade"></span></font></center></div></div>                     								                    							</div>                    							<div class="form-group">                                                     <label class="col-md-4 control-label"><?=traduzir("Confirmação", $_SESSION['lang']);?></label>                                                     <div class="col-sm-5">                                                          <input type="password" <?=$centralizado;?> class="form-control" id="form_conf" name="form_conf" onKeyUp="real()">                    								</div>                    								<div class="col-sm-1" style="vertical-align:middle">                                                          <img name="img_conf" id="img_conf" src="imagens/img_x.png" style="vertical-align:middle">                    								</div>                    							</div>                    							<div class="form-group">                    								<div class="col-sm-12"><center>                                                          <button type="button" class="btn grey-mint" onclick="javascript:valida();">  <?=traduzir("Alterar senha", $_SESSION['lang']);?> </button>                                                    </div>                                                </div>                                            </form>                                        	</div>                    					<div class="col-md-3">                    					</div>                                    </div>                                </div>                            </div>                        </div>                    </div>                 </div>            </div>        </div>        <!--[if lt IE 9]><script src="assets/global/plugins/respond.min.js"></script><script src="assets/global/plugins/excanvas.min.js"></script><script src="assets/global/plugins/ie8.fix.min.js"></script><![endif]-->        <?			include "global_js.php";		?>		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>		<script>			$( function() {				$( document ).tooltip();			} );  					</script>	</body>	</html>