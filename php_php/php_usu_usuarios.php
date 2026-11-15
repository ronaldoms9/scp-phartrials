<?
	session_start();
	include_once __DIR__ . "/../class/conexao.class.php";
	include_once __DIR__ . "/../class/usuario.class.php";
	include_once __DIR__ . "/../funcoes.php";

	$varConexao = new Conexao();

	switch(strtoupper($_GET['act'])){
		case "NOVO_LISTA_USUARIO":
			$query = "select id, usuario, ativo from usu_usuario order by usuario";
			$resUsuario = $varConexao->pesquisa($query);
			while($rowUsuario = mysqli_fetch_assoc($resUsuario)){
				echo "<tr>";
				echo "<td><center>".($rowUsuario['ativo']=="on"?"Sim":"Não")."</center></td>";
				echo "<td>".$rowUsuario['usuario']."</td>";
				echo "<td><a href='javascript:selecionaUsuario(".$rowUsuario['id'].")'><img src='imagens/seta_direita.png' width='20' height='20'></a></td>";
				echo "</tr>";
			}
			break;

		case "SALVA":
			$varUsuario = new Usuario($_GET['id']);
			$varUsuario->usuario 	= $_POST['varusuario'];
			$varUsuario->nome 		= $_POST['varnome'];
			$varUsuario->email 		= $_POST['varemail'];
			$varUsuario->centro		= $_POST['varcentro'];
			$varUsuario->descricao 	= $_POST['vardescricao'];
			$varUsuario->ativo 		= $_POST['varativo'];
			$varUsuario->tipo_usuario = $_POST['vartipo_usuario'];
			$varUsuario->troca_senha = $_POST['vartroca_senha'];
			$varUsuario->envia_email = $_POST['varenvia_email'];
			$varUsuario->patrocinador = $_POST['varpatrocinador'];
			$varUsuario->salvar();
			echo $_GET['id'];
			break;
	}

	if($_GET['act']=='VINCULA_ESTUDO'){
		if($_POST['idValor']=="false"){
			$query = "delete from usu_estudo_usuario where estudo = '".$_POST['idProjeto']."' and usuario = '".$_POST['idUsuario']."'";
		}
		if($_POST['idValor']=="true"){
			$query = "insert into usu_estudo_usuario (estudo, usuario) values ('".$_POST['idProjeto']."', '".$_POST['idUsuario']."');";
		}
		$conexao = new Conexao();
		$conexao->pesquisa($query);
		echo $query;
	}
	if($_GET['act']=='LISTA_USUARIO'){
		$varUsuario = new Usuario(null);
		$varUsuario->exibeListagem();

	}
	if($_GET['act']=='MOSTRA_USUARIO'){
		$varUsuario = new Usuario($_POST['id']);

		if($varUsuario->id>0){
			$response = array(
				"status"       => "success",
				"message"      => "Usuário encontrado",
				"usuario"      => formata_utf8_encode($varUsuario->usuario),
				"nome"         => formata_utf8_encode($varUsuario->nome),
				"email"        => formata_utf8_encode($varUsuario->email),
				"celular"      => formata_utf8_encode($varUsuario->celular),
				"tipo_usuario" => formata_utf8_encode($varUsuario->tipo_usuario),
				"ativo"        => formata_utf8_encode($varUsuario->ativo)
			  );
			  $response = array_map('utf8_encode', $response);
			  echo json_encode($response);
			  return;
		}else{
			$response = array(
				"status" => "error",
				"message" => "Usuário não encontrado"
			);
			$response = array_map('utf8_encode', $response);
			echo json_encode($response);
			return;
		}
	}


	if($_GET['act']=='SALVA_USUARIO'){

		$varId = $_POST['id'];
		$varUsuario = new Usuario($varId);

		$varUsuario->usuario			=	$_POST['usuario'];
		$varUsuario->nome				=	$_POST['nome'];
		$varUsuario->email				=	$_POST['email'];
		$varUsuario->celular			=	$_POST['celular'];
		$varUsuario->tipo_usuario		=	$_POST['tipo_usuario'];
		$varUsuario->ativo				=	$_POST['ativo'];
		$varUsuario->log_usuario		=	$_SESSION['user_id'];


		if($varId>0){
			$varUsuario->salvar();
			echo $varId;
		}else{
			$varUsuario->novo();
			echo $varUsuario->id;
		}
	}
	if($_GET['act']=="ALTERA"){
		$varConexao = new Conexao();
		$query = "update usu_usuario set senha = md5('".$_POST['senha']."') where id = " . $_SESSION['user_id'];
		$varConexao->pesquisa($query);
		echo $query;
	}