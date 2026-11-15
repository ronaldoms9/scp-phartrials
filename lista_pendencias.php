<?php
	include_once "seguranca.php";
	$tabela = "lista_pendencias";
	$pagina = "lista_pendencias.php";
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<?php
		include "global_css.php";
	?>
    <style>
           .menu {
            background:#F5F5F5;
            margin: auto;
            padding:10px;
            text-align:center;
            border: 1px solid #888;

            -moz-border-radius:7px;
            -webkit-border-radius:7px;
             border-radius:7px;
            }
    </style>
    <script>
    function excluir(ID,NOME){
	  var resp = confirm("Tem certeza que deseja excluir o desvio '"+NOME+"'?");
	  if(resp==true){
		   window.location = "ecrf_desvios.php?opc=X&id="+ID;
	  }
             }
    </script>
    <?=$msg;?>
</head>

<body>
       <div class="page-wrapper">
            <?php
               include "menu_principal.php";
            ?>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">
                            <div class="page-head">
                                <div class="container-fluid">
                                    <div class="page-title">
                                        <h1><?=traduzir("Lista de Achados e Pendências", $_SESSION['lang']);?> </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
                                <div class="container-fluid">

                                </div>
				<div class="row">
						  <div class="col-md-1"></div>
								<div class="col-md-10" style="background-color: white">
									<iframe frameborder="0"  src="agendamentos_lista_pendencia.php" style="width: 100%;height:600px" frameborder="1" marginheight="0" marginwidth="0" scrolling="yes" ></iframe>

								</div>
				</div>
				<hr><br>
			</div>
		</div>

	</div></div></div>
            <?php
                include "rodape.php";
            ?>
        </div>
        <?php
			include "global_js.php";
		?>
</body>
</html>