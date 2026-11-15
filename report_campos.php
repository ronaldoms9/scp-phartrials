<?php
	include_once "seguranca.php";
?>

<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
        <meta charset="utf-8" />
        <title><?=$titulo;?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #3 for " name="description" />
        <meta content="" name="author" />
        <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' rel='stylesheet'/>
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/layouts/layout3/css/custom.min.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="favicon.ico" />
    <script>
        function deletar(){
                 var resp = confirm("Tem certeza que deseja DELETAR o formulário?");
                 if(resp == true){
                         window.location = "<?=$pagina;?>?<?=gera_parametro("codigo", $id)."&".gera_parametro("act","D");?>";
                 }
        }
    </script>
</head>
    <body class="page-container-bg-solid page-footer-fixed">
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
                                        <h1><?=traduzir("Formulários do projeto", $_SESSION['lang']);?> </h1>
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
                                            <span><?=traduzir("Formulários do projeto", $_SESSION['lang']);?></span>
                                        </li>
                                    </ul>

                                </div>
				<div class="row">
                    <div class="col-md-12">
                            <table class="table table-striped">
                               <thead>
                                      <th>Formulário</th>
                                      <th>Texto a ser exibido no CRF</th>
                                      <th>Campo</th>
                                      <th><center>Tipo de<br>campo</center></th>
                                      <th><center>Valores<br>de Seleção</center></th>
                                      <th><center>Formato</center></th>
                                      <th><center>Legenda</center></th>
                                      <th><center>Valor<br>mínimo</center></th>
                                      <th><center>Valor<br>máximo</center></th>
                                      <th><center>Tipo de<br>cálculo</center></th>
                                      <th><center>Aceita só<br>numérico?</center></th>
                                      <th><center>Obri<br>gatório?</center></th>
                                      <th><center>Tem que ser<br>menor que termo?</center></th>
                               </thead>
                               <tbody>
                               <?php
                                 $query = "SELECT  CF.ordem, ";
                                 $query .= "                                 	CF.nome, ";
                                 $query .= "                                        CF.texto_crf, ";
                                 $query .= "                                        CFC.texto, ";
                                 $query .= "                                        CFC.tipo, ";
                                 $query .= "                                        CFC.obrigatorio, ";
                                 $query .= "                                        CFC.numero_min, ";
                                 $query .= "                                        CFC.numero_max, ";
                                 $query .= "                                        CFC.selecao_valores, ";
                                 $query .= "                                        CFC.formato, ";
                                 $query .= "                                        CFC.calculo_tipo, ";
                                 $query .= "                                        CFC.legenda, ";
                                 $query .= "                                        CFC.data_menor_termo, ";
                                 $query .= "                                        CFC.data_menor_termo_alerta, ";
                                 $query .= "                                        CFC.so_numero, ";
                                 $query .= "                                        CFC.so_letra, ";
                                 $query .= "                                        CFC.data_menor_hoje, ";
                                 $query .= "                                        CFC.tipo_campo ";
                                 $query .= "                                    FROM crf_forms CF ";
                                 $query .= "                                         inner join crf_forms_campos CFC on CF.id = CFC.formulario ";
                                 $query .= "                                    where CF.registro_valido = 1 ";
                                 $query .= "                                    	order by CF.ordem, CFC.ordem ";
                                 $resLista = $conexao->pesquisa($query);
                                 $ant = "";
                                 while($rowLista=mysqli_fetch_assoc($resLista)){
                                 ?>
                                 <tr>
                                     <td><?php if($rowLista['nome']!=$ant){echo trim($rowLista['nome']);}?></td>
                                     <td><?php if($rowLista['nome']!=$ant){echo trim($rowLista['texto_crf']);}?></td>
                                     <td><?=$rowLista['texto'];?></td>
                                     <td><?php
                                           $tipo = $rowLista['tipo'];
                                           if($tipo!="select"){echo strtoupper($tipo);}else{echo "SELEÇÃO";}
                                     ?></td>
                                     
                                     <td>
                                     <?php
                                          $opcoes = "";
                                          $opcoes = str_replace("<br />","%",$rowLista['selecao_valores']);
                                          $opcoes = str_replace("\n","",$opcoes);
                                          $combo = "";
                                          $temp  = "";
                                          for($z=0;$z<strlen($opcoes);$z++){
                                              if(substr($opcoes,$z,1)!="%"){$temp.=substr($opcoes,$z,1);}
                                              else{
                                                  $temp=trim($temp);
                                                  $temp_selected = "";
                                                  echo $temp."<br>";
                                                  $temp="";
                                              }
                                          }
                                     ?>
                                     </td>
                                     
                                     <td><center><?=$rowLista['formato'];?></center></td>
                                     <td><center><?=$rowLista['legenda'];?></center></td>
                                     <td><center><?=$rowLista['numero_min'];?></center></td>
                                     <td><center><?=$rowLista['numero_max'];?></center></td>
                                     <td><?=$rowLista['calculo_tipo'];?></td>
                                     <td></td>
                                     <td></td>
                                     <td></td>
                                 </tr>
                                 <?php
                                   $ant = $rowLista['nome'];
                                 }
                               ?>
                               </tbody>
                            </table>
                    <br><br>
					</div>
				</div>
				</form>
			</div>
		</div>




  <br><br>

		<!-- END CALL-TO-ACTION -->
		<!-- FOOTER -->

		<!-- END FOOTER -->
	</div>
	<!-- END WRAPPER -->
	<!-- JAVASCRIPTS -->
	<script src="assets/js/jquery-2.1.1.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/plugins/slick/slick.min.js"></script>
	<script src="assets/js/plugins/stellar/jquery.stellar.min.js"></script>
	<script src="assets/js/plugins/jquery-easypiechart/jquery.easypiechart.min.js"></script>
	<script src="assets/js/plugins/autohidingnavbar/jquery.bootstrap-autohidingnavbar.min.js"></script>
	<script src="assets/js/repute-scripts.js"></script>

</body>

</html>
