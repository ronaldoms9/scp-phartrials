<?
	include_once "seguranca.php";
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
        <?
			include "global_css.php";
		?>
    <script>
    <?
        if($msg==1){ echo "alert('Centro atualizado com sucesso');"; }
        if($msg==2){ echo "alert('Centro cadastrado com sucesso');"; }
        if($msg==3){ echo "alert('Centro deletado com sucesso');"; }
    ?>
    </script>
</head>
    <body class="page-container-bg-solid page-footer-fixed">
        <div class="page-wrapper">
            <?
               include "menu_principal.php";
            ?>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">
                            <div class="page-head">
                                <div class="container-fluid">
                                    <div class="page-title">
                                        <h1><?=traduzir("Add campos faltantes", $_SESSION['lang']);?> </h1>
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
                                            <span><?=traduzir("Add Campos faltantes", $_SESSION['lang']);?></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="row">
                                        <div class="col-md-2"></div>                                            
                                        <div class="col-md-8" style="background-color:#FFF;height: 400px;overflow-y: scroll;">                        					
											<?											
												//Formulário
												$query = "select crf_forms.id as form_id_principal, crf_forms.nome, crf_entrada_forms.id, crf_entrada_forms.paciente, crf_entrada_forms.form_id, crf_entrada_forms.centro from crf_forms left join crf_entrada_forms on crf_forms.id = crf_entrada_forms.form_id where isnull(crf_entrada_forms.form_id)";
												$res = $conexao->pesquisa($query);
												while($row = mysqli_fetch_assoc($res)){
													$form_id = $row['form_id_principal'];
													
													$query = "SELECT * FROM crf_entrada_forms where form_id = 1";
													$res_pac = $conexao->pesquisa($query);
													while($row_pac = mysqli_fetch_assoc($res_pac)){
														$query = "insert into crf_entrada_forms (paciente, form_id, centro) values ('".$row_pac['paciente']."', '".$form_id."', '".$row_pac['centro']."');";
														echo "FORM -> " . $query . "<br>";
														$conexao->pesquisa($query);
													}
												}
												//Verifico na View vw_add_campo_1 os campos faltantes
												$query = "SELECT * FROM vw_add_campo_1 where isnull(CAMPO_ID)";
												$res_add = $conexao->pesquisa($query);
												while($row_add = mysqli_fetch_assoc($res_add)){
													$query = " insert into crf_entrada_campos (form_id, campo_id) values (".$row_add['id_do_form'].",".$row_add['id'].");";
													$conexao->pesquisa($query);
													echo "CAMPO -> " . $query . "<br>";
												}
											?>
														
                        				</div>
                    					
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?
                include "rodape.php";
            ?>
        </div>
</div>
</div>
        <?
			include "global_js.php";
		?>
</body>
</html>

