<?php
	include_once "seguranca.php";

	$id = $_GET[base64_encode('codigo')];
	$act = strtoupper($_GET[base64_encode('act')]);

	$tabela = "crf_rand_tratamento";
	$pagina = "ecrf_config_rand_tratamento.php";
	$campos = array("nome", "descricao");
	$tipos  = array("S", "S");

	$projeto = 10;



	$query = "select * from agenda where projeto = $projeto ";
	$resAgenda = $conexao->pesquisa$query);
	while($rowAgenda = mysqli_fetch_assoc($resAgenda)){
		echo "Agendamento -> " . $rowAgenda['id'] . " / " . $rowAgenda['data_caption'] ."<br>";
		$query = "select * from cfg_visitas where tipo = '".$rowAgenda['tipo_monitoria']."'";
		$resForm = $conexao->pesquisa$query);
		while($rowForm = mysqli_fetch_assoc($resForm)){
			$query = "select * from crf_agenda_forms where agendamento = ".$rowAgenda['id']." and form_id = " . $rowForm['id'];
			$resTemForm = $conexao->pesquisa$query);
			if(mysqli_num_rows($resTemForm)==0){
				echo "NÃO tem FORM<br>";
			}else{
				echo "** TEM FORM<br>";
				while($rowTemForm = mysqli_fetch_assoc($resTemForm)){					
					$query = "select * from cfg_visitas_campos where formulario = " . $rowForm['id'];					
					$resCampo = $conexao->pesquisa$query);
					while($rowCampo = mysqli_fetch_assoc($resCampo)){
						$query = "select * from crf_agenda_campos where form_id = ".$rowTemForm['id']." and campo_id = " . $rowCampo['id'];
						$resTemCampo = $conexao->pesquisa$query);
						if(mysqli_num_rows($resTemCampo)==0){
							//echo $query . "<br>";
							echo "**** NAO TEM CAMPO<br>";
							//$rowCampo = mysqli_fetch_assoc($resTemCampo);
							$formulario = $rowTemForm['id'];
							$campo =$rowCampo['id'];
							$query = "insert into crf_agenda_campos (form_id, campo_id) values ('$formulario', '$campo');";
							echo $query."<br>";
							$conexao->pesquisa$query);
						}else{
							echo "**** TEM CAMPO<br>";
						}
					}
				}
			}
		}
	}

  /*
	$query = "select nro_participante, nro_paciente, centro from crf_paciente_pre_rand where projeto = " . $projeto;
	$res_vol = $conexao->pesquisa$query);
	while($row_vol = mysqli_fetch_assoc($res_vol)){
		echo "VOLUNTARIO -> " . $row_vol['nro_participante'] . "<br>";
		$prox = $row_vol['nro_paciente'];
		$prox_vol = $prox;
		$query = "select * from crf_forms order by ordem";
		$resForms = $conexao->pesquisa$query);
		while($row_forms = mysqli_fetch_assoc($resForms)){
			echo "---- " . $row_forms['nome'] . "<br>";
			$query = "select * from crf_entrada_forms where projeto = $projeto and paciente = ".$prox." and centro = ".$row_vol['centro']." and form_id = " . $row_forms['id'];
			//echo $query . "<br>";
			$res_form = $conexao->pesquisa$query);
			if(mysqli_num_rows($res_form)==0){ 
				echo " NAO TEM <br>" ; //Falta programar aqui
			}else{
				//echo "TEM <br>";
				$row_form = mysqli_fetch_assoc($res_form);
				
				$query = "select id from crf_forms_campos where formulario = ".$row_forms['id'];
  			    $resCampos = $conexao->pesquisa$query);
				while($rowCampos= mysqli_fetch_assoc($resCampos)){
					$query = "select id from crf_entrada_campos where form_id = ".$row_form['id']." and campo_id = " . $rowCampos['id'];
					$res_campo = $conexao->pesquisa$query);
					if(mysqli_num_rows($res_campo)==0){
						$query = "insert into crf_entrada_campos (form_id, campo_id) values (".$row_form['id'].", ".$rowCampos['id'].");";
						echo "--------" . $query . "<br>";
						$conexao->pesquisa$query);
					}
					 
					 //$conexao->pesquisa$query);
				}

			}
			
		}
		
	  
	  
	}
	*/