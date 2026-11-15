<?
	session_start();
	include_once "../class/conexao.class.php";
	
	
	$varData = $_POST['data'];
	$varLote = $_POST['lote'];
	$varValidade = $_POST['validade'];
	$qtd		 = $_POST['qtd'];
	$recebido_por= $_PÒST['recebido_por'];
	
	if($qtd>0){
		$conexao = new Conexao();
		$data_lis = $varData;
		if(data_lis!=""){
			$data_lis = explode("/", $varData);
			$data_lis = $data_lis[2].$data_lis[1].$data_lis[0];
		}
		$query = "insert into med_movimento (data, data_lis, lote, validade, qtd, recebido_por, projeto, tipo) values ('$varData', '$data_lis', '$varLote', '$varValidade', '$qtd', '$recebido_por', '".$_SESSION['projeto']."', 'E');";
		$conexao->pesquisa($query);
		
		//Atualiazr estoque central
		$query = "select * from med_estoque where projeto = '".$_SESSION['projeto']."' and centro = 0";
		$resEst = $conexao->pesquisa($query);
		if(mysqli_num_rows($resEst)==0){	//Ainda não tem estoque. Crio já com a qtd
			$query = "insert into med_estoque (centro, estoque, projeto) values ('0', '$qtd', '".$_SESSION['projeto']."');";
			$conexao->pesquisa($query);
		}else{
			$row = mysqli_fetch_assoc($resEst);
			$qtd = $qtd + intval($row['estoque']);
			$query = "update med_estoque set estoque = '$qtd' where centro = 0 and projeto = " .$_SESSION['projeto'];
			$conexao->pesquisa($query);
		}
		$rowEst = mysqli_fetch_assoc($resEst);
		echo "Recebimento registrado com sucesso";
	}else{
		echo "Quantidade inválida";
	}