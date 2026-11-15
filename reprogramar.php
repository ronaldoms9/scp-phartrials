<?
	include_once "seguranca.php";
	$projeto = 18;
  
	$query = "select nro_participante, nro_paciente, centro from crf_paciente_pre_rand where projeto = $projeto";
	$res_vol = $conexao->pesquisa($query);
	while($row_vol = mysqli_fetch_assoc($res_vol)){
		echo "VOLUNTARIO -> " . $row_vol['nro_participante'] . "<br>";
		$prox = $row_vol['nro_paciente'];
		$prox_vol = $prox;
		$query = "select * from crf_forms where projeto = $projeto order by ordem";
		$resForms = $conexao->pesquisa($query);
		while($row_forms = mysqli_fetch_assoc($resForms)){
			$query = "select * from crf_entrada_forms where projeto = $projeto and paciente = ".$prox." and centro = ".$row_vol['centro']." and form_id = " . $row_forms['id'];
			echo $query . "<br>";
			$res_form = $conexao->pesquisa($query);
			if(mysqli_num_rows($res_form)==0){ 
				echo " NAO TEM <br>" ; //Falta programar aqui
				$query = "insert into crf_entrada_forms (projeto, paciente, centro, form_id) values ('$projeto', '".$prox."', '".$row_vol['centro']."', '".$row_forms['id']."');";
				echo $query . "<br>";
				$conexao->pesquisa($query);
			}else{
				$row_form = mysqli_fetch_assoc($res_form);
				
				$query = "select id from crf_forms_campos where formulario = ".$row_forms['id'];
				echo "*****" . $query . "<br>";
  			    $resCampos = $conexao->pesquisa($query);
				while($rowCampos= mysqli_fetch_assoc($resCampos)){
					$query = "select id from crf_entrada_campos where form_id = ".$row_form['id']." and campo_id = " . $rowCampos['id'];
					$res_campo = $conexao->pesquisa($query);
					if(mysqli_num_rows($res_campo)==0){
						$query = "insert into crf_entrada_campos (projeto, form_id, campo_id) values ($projeto, ".$row_form['id'].", ".$rowCampos['id'].");";
						echo "--------" . $query . "<br>";
						$conexao->pesquisa($query); 
					}					 
				}			
			}
			
		}
		
	  
	  
	} 
	
  
	/*$query = "select * from agenda";
	$res_vol = $conexao->pesquisa($query);
	while($row_vol = mysqli_fetch_assoc($res_vol)){
		$agendamento = $row_vol['id'];
		
		$query = "select * from cfg_visitas";
		$res_form = $conexao->pesquisa($query);
		while($row_form = mysqli_fetch_assoc($res_form)){
			$formulario = $row_form['id'];
			//Verificar se existe o formulário na agenda
			$query = "select * from crf_agenda_forms where agendamento = ".$agendamento." and form_id = " . $formulario;
			$res_f = $conexao->pesquisa($query);
			if(mysqli_num_rows($res_f)==0){
				$query = "insert into crf_agenda_forms (agendamento, form_id, log_usuario) values ($agendamento,$formulario,1)";
				$conexao->pesquisa($query);
			}else{
				$row_f = mysqli_fetch_assoc($res_f);
				$form_id = $row_f['id'];
				
				$query = "select * from cfg_visitas_campos where formulario = " . $formulario;
				$res_c = $conexao->pesquisa($query);
				while($row_c = mysqli_fetch_assoc($res_c)){
					$campo_id = $row_c['id'];
					
					//Verifico se existe o campo no formulário X
					$query = "select * from crf_agenda_campos where form_id = ".$form_id." and campo_id = " . $campo_id;
					//echo $query;
					$res_campo = $conexao->pesquisa($query);
					if(mysqli_num_rows($res_campo)==0){
						$query = "insert into crf_agenda_campos (form_id, campo_id) values ($form_id,$campo_id);";
						$conexao->pesquisa($query);
					}
				}
			}
		}
		
	}*/
  
  echo "FIM";