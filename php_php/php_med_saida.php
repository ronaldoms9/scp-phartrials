<?php
	session_start();
	include_once "../class/conexao.class.php";
	
	
	$varData 	 = $_POST['data'];
	$varCentro 	 = $_POST['centro'];	
	$qtd		 = $_POST['qtd'];
	$recebido_por= $_PÒST['recebido_por'];
	
	if($qtd>0){
		if($varCentro!=""){
			$conexao = new Conexao();
			$data_lis = $varData;
			if(data_lis!=""){
				$data_lis = explode("/", $varData);
				$data_lis = $data_lis[2].$data_lis[1].$data_lis[0];
			}
			$query = "insert into med_movimento (data, data_lis, qtd, recebido_por, projeto, tipo, centro) 
							values ('$varData', '$data_lis', '$qtd', '$recebido_por', '".$_SESSION['projeto']."', 'S', '".$varCentro."');";
			$conexao->pesquisa($query);
			
			//Atualiazr estoque central
			$query = "select * from med_estoque where projeto = '".$_SESSION['projeto']."' and centro = " . $varCentro;
			$resEst = $conexao->pesquisa($query);
			$qtdCentro = 0;
			if(mysqli_num_rows($resEst)==0){	//Ainda não tem estoque. Crio já com a qtd
				$query = "insert into med_estoque (centro, estoque, projeto) values ('".$varCentro."', '$qtd', '".$_SESSION['projeto']."');";
				$conexao->pesquisa($query);
			}else{
				$row = mysqli_fetch_assoc($resEst);
				$qtd = intval($row['estoque']) + $qtd;
				$query = "update med_estoque set estoque = '$qtd' 
							where centro = '".$varCentro."' 
								and projeto = " .$_SESSION['projeto'];
				$conexao->pesquisa($query);
			}
			//Estoque central
			$query = "select * from med_estoque where projeto = '".$_SESSION['projeto']."' and centro = 0";
			$resEst = $conexao->pesquisa($query);
			$rowCentral = mysqli_fetch_assoc($resEst);
			$qtd = $rowCentral['estoque']  - $_POST['qtd'];
			
			$query = "update med_estoque set estoque = $qtd
						where centro = '0' 
								and projeto = " .$_SESSION['projeto'];		
			$conexao->pesquisa($query);
			echo "Entrega registrada com sucesso";
		}else{
			echo "Centro não selecionado. Operação abortada";
		}
	}else{
		echo "Quantidade inválida";
	}