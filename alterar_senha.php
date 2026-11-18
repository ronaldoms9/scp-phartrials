<?php
	include_once "seguranca.php";
	
	$id_usuario = $_SESSION['user_id'];

  $act = strtoupper($_GET[base64_encode('act')]);
  if($act=="A"){
     $senha = $_POST['form_atual'];
     $nova  = $_POST['form_nova'];
     $query = "select id, senha from usu_usuario where id = ".$_SESSION['user_id']." and senha = md5('".$senha."')";
     $res = $conexao->pesquisa($query);
     if(mysqli_num_rows($res)>0){		
		//if(strtoupper($row['senha'])!=strtoupper($nova)){
			
			$query = "select * from usu_senhas_usadas where usuario_id = ". $_SESSION['user_id'] . " and senha = md5('".$nova."')";
			$res_sen = $conexao->pesquisa($query);
			if(mysqli_num_rows($res_sen)>0){
				$msg=4;
			}else{			
				$query = "insert into usu_senhas_usadas (usuario_id, senha, data) values (".$_SESSION['user_id'].", md5('$nova'), '".DataAtualToLIS()."') ";
				$conexao->pesquisa($query);
				$query= "update usu_usuario set log_usuario = '$id_usuario', senha = md5('$nova') where id = ".$_SESSION['user_id'];
				$conexao->pesquisa($query);
				$msg=2;
			}
		}else{
			$msg=3;
		}
    }
  
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<?php
		include "global_css.php";
	?>
    <script>
            function valida(){            
                var senha = document.getElementById("form_nova").value;
                var conf  = document.getElementById("form_conf").value;                
                if(retorna_complexidade(document.getElementById("form_nova").value)>=100){					
					document.form_info.submit();
				}else{alert("A nova senha não atende aos requisitos mínimos de complexidade");}                
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
			function retorna_complexidade(senha){
				var forca = 0;
				if(tem_numeros(senha)==1){ forca += 25;}					
				if((senha.length >= 8)){ forca += 25; }
				if(senha.match(/[a-z]+/)){forca += 25;}
				if(senha.match(/[A-Z]+/)){forca += 25;}	
				return forca;
			}
            function real(){  
					var forca = 0;
					senha = document.getElementById("form_nova").value;
					if(senha!=""){						
						forca = parseInt(retorna_complexidade(senha));										
						document.getElementById("esconde").style.display = 'block';
					}else{
						document.getElementById("esconde").style.display = 'none';
					}										
					document.getElementById("progresso").value = forca;										
					var conf = document.getElementById("form_conf");
                    var imagem_conf = document.getElementById("img_conf");
                    if((senha==conf.value)){
                        imagem_conf.src ="imagens/img_ok.png";
                    }else{ imagem_conf.src ="imagens/img_x.png";
                    }					
					return forca;
            }
            
    </script>

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
                                        <h1><?=traduzir("Alteração de Senha", $_SESSION['lang']);?> </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
                                <div class="container-fluid">
                                    <ul class="page-breadcrumb breadcrumb">
                                        <li>
                                            <a href="inicial.php"><?=traduzir("Início", $_SESSION['lang']);?></a>
                                            <i class="fa fa-circle"></i>
                                        </li>
                                        <li>
                                            <span><?=traduzir("Alterar Senha", $_SESSION['lang']);?></span>
                                        </li>
                                    </ul>

                                    <div class="row">
                    					<div class="col-md-3">
                    					</div>
                                        <div class="col-md-6" style="background-color: white">
                    						<br><br>
                                            <?php $acao = "alterar_senha.php?".gera_parametro("act","A"); ?>
                    						<form class="form-horizontal" role="form" action="<?=$acao;?>" method="post" id="form_info" name="form_info">
                                                <div class="form-group">
                                                     <label class="col-md-4 control-label"><?=traduzir("Senha atual", $_SESSION['lang']);?></label>
                                                     <div class="col-sm-5"> 
                                                          <input type="password" <?=$centralizado;?> class="form-control" id="form_atual" name="form_atual">
                    								</div>													
                    							</div>
                    							<div class="form-group">
                                                     <label class="col-md-4 control-label"><?=traduzir("Nova senha", $_SESSION['lang']);?></label>
                                                     <div class="col-sm-5">
                                                          <input type="password" <?=$centralizado;?> class="form-control" id="form_nova" name="form_nova" onKeyUp="real()" title="Comprimento mínimo: 8 caracteres;. Obrigatório ter letras E número. Obrigatório ter alguma letra em maiúsculo"> <font size="1">(<?=traduzir("Comprimento mínimo: 8 caracteres;. Obrigatório ter letras E número. Obrigatório ter alguma letra em maiúsculo", $_SESSION['lang']);?>)</font>
                    								</div>
													<div  style="display: none" id="esconde"><div class="col-sm-2"><center><progress id="progresso" value="0" max="100"><font size="1px"><span id="complexidade"></span></font></center></div></div>                     								
                    							</div>
                    							<div class="form-group">
                                                     <label class="col-md-4 control-label"><?=traduzir("Confirmação", $_SESSION['lang']);?></label>
                                                     <div class="col-sm-5">
                                                          <input type="password" <?=$centralizado;?> class="form-control" id="form_conf" name="form_conf" onKeyUp="real()">
                    								</div>
                    								<div class="col-sm-1" style="vertical-align:middle">
                                                          <img name="img_conf" id="img_conf" src="imagens/img_x.png" style="vertical-align:middle">
                    								</div>
                    							</div>
                    							<div class="form-group">
                    								<div class="col-sm-12"><center>
                                                          <button type="button" class="btn grey-mint" onclick="javascript:valida();">  <?=traduzir("Alterar senha", $_SESSION['lang']);?> </button>
                                                    </div>
                                                </div>
                                            </form>
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
            <?php
                include "rodape.php";
            ?>
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
		<script>
			$( function() {
				$( document ).tooltip();
			} );  	
			<?php
                if($msg==1){echo "alert('Senha atual inválida');";}
                if($msg==2){echo "alert('Senha alterada com sucesso');";}
				if($msg==3){echo "alert('A nova senha não pode ser igual a senha anterior');";}
				if($msg==4){echo "alert('Atenção. Sua senha não pode ser igual as últimas utilizadas. Operação cancelada');";}
            ?>	
		</script>	
</body>	
</html>
