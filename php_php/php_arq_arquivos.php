<?
	session_start();
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	include_once __DIR__."/../class/conexao.class.php";
	include_once __DIR__."/../class/tmf.class.php";
	include_once __DIR__."/../class/email.class.php";
	include_once __DIR__."/../class/projeto.class.php";

	$varConexao = new Conexao();

	if(isset($_GET['act'])){$act = $_GET['act'];}else{$act = "";}

	if($act=="SALVA_COMENTARIO_REVISOR"){
		$varId         = $_POST['id'];
		$varComentario = $_POST['comentario'];
		$varStatus     = $_POST['status'];

		$query = "update
					arq_arquivos
						set comentario_revisor = '$varComentario',
						comentario_revisor_status = '$varStatus'
							 where id = $varId";
		$varConexao->pesquisa($query);

	}
	if($act=="MUDA_STATUS_DIR"){
		$varConexao = new Conexao();

		$id = $_POST['id'];
		$status = $_POST['status'];

		$query = "update arq_tmf set status = $status where id = $id";
		$varConexao->pesquisa($query);
	}
	if($act=="META"){
		$varConexao = new Conexao();
		$id = $_POST['id'];
		$varSessao 			= $_POST['sessao'];
		$varZona 			= $_POST['zona'];
		$varArtefato 		= $_POST['artefato'];
		$varClassificacao 	= $_POST['classificacao'];
		$varTipo 			= $_POST['tipo'];
		$varDocumento		= $_POST['documento'];
		$varMeta			= utf8_decode($_POST['meta']);
		$varData			= $_POST['data'];
		$varEmissor			= $_POST['emissor'];
		$varDono			= $_POST['dono'];


		$query = "select * from arq_meta where id = " . $id;
		$resTem = $varConexao->pesquisa($query);
		//$rowTem = mysqli_fetch_assoc($resTem);
		if(mysqli_num_rows($resTem)==0){
			$query = "insert into arq_meta (arquivo) values ('".$id."');";
			$varConexao->pesquisa($query);
		}
		$query = "update arq_meta set sessao = '$varSessao',
			zona = '$varZona',
			artefato = '$varArtefato',
			classificacao = '$varClassificacao',
			tipo = '$varTipo',
			documento = '$varDocumento',
			meta = '$varMeta',
			data = '$varData',
			emissor = '$varEmissor',
			dono = '$varDono'
				where arquivo = " . $id;
		$varConexao->pesquisa($query);
		echo $query;
	}
	if($act=="SALVA_COMENTARIO_PASTA"){
		$varId 			= $_POST['id'];
		$varComentario	= $_POST['comentario'];
		$varConexao = new Conexao();
		$query = "update arq_tmf set comentario = '$varComentario' where id = " . $varId;
		$varConexao->pesquisa($query);
	}
	if($act=="SALVA_COMENTARIO"){
		$varId 			= $_POST['id'];
		$varComentario	= $_POST['comentario'];
		echo $varId;
		$varFile = new File($varId);
		$varFile->salvaComentario($varComentario);
		echo "FIM".$varId;
	}
	if($act=="RENOMEAR_ARQUIVO"){
		$varId 		= $_POST['id'];
		$varNome 	= $_POST['nome'];
		$varFile 	= new File($varId);
		$varFile->renomeiaArquivo($varNome);
	}
	if($act=="VALIDA_ARQUIVO"){
		$varId 			= $_GET['id'];
		$varComentarios = $_POST['comentarios'];
		$varSenha		= $_POST['senha'];
		$varFile = new File($varId);
		$varFile->valida($varComentarios, $varSenha);
	}
	if($act=="REJEITA_ARQUIVO"){
		$varId 			= $_POST['id'];
		$varMotivo 		= $_POST['motivo'];
		$varFile = new File($varId);
		$varFile->rejeita($varMotivo);
	}
	if($act=="DESVALIDA_ARQUIVO"){
		$varId 			= $_GET['id'];
		$varComentarios = $_POST['comentarios'];
		$varSenha		= $_POST['senha'];
		$varFile = new File($varId);
		$varFile->desvalida($varComentarios, $varSenha);
	}
	if($act=="LISTAGEM_TOTAL"){
		$varTmf = new Tmf($_SESSION['projeto']);
		$varTmf->montaTree(null);
	}
	if($act=="LISTAGEM_FILTRO"){
		$varTmf = new Tmf($_SESSION['projeto']);
		$filtro = $_GET['filtro'];
		$varTmf->montaTree($filtro);
	}
	if($act=="RENOMEAR"){
		$varNode = new Node($_GET['id']);
		$varNode->renomeia($_GET['nome']);
	}
	if($act=="NOVO"){
		$varNode = new Node($_GET['id']);
		$varNode->novo($_GET['nome']);
		echo "OK";
	}
	if($act=="DELETA_ARQUIVO"){
		$varFile = new File($_GET['id']);
		$varFile->deleta($_GET['id'],$_POST['motivo']);
	}
	if($act=="DELETA"){
		$varNode = new Node($_GET['id']);
		$varNode->deleta($_GET['id']);
	}
	if($act=="ARQUIVOS"){
		$varNode = new Node($_GET['id']);
		$varNode->listaArquivos();
	}
	if($act=="ARQUIVOS_IND"){
		$varNode = new Node($_GET['id']);
		$varNode->listaArquivosInd();
	}
	if($act=="UPLOAD"){
		$conexao = new Conexao();
		$descricao 	= $_GET['descricao'];
		$email		= $_GET['email'];

		set_time_limit(0);
		$usuario = $_SESSION['user_id'];
		$grupo = $_GET['grupo'];
		$descricao = $_GET['descricao'];
		$varProjeto = new Projeto($_SESSION['projeto']);

		if($grupo<1){echo "ERRO";}else{
			$id = rand(5479,125545778);
			$nome_arquivo = $_FILES['file']['name'];
			$arqv_temporario = $_FILES['file']['tmp_name'];
			$tamanho = $_FILES['file']['size'];
			$versao = 1;
			$caminho=$urlProjeto."arquivos/".$id."_".$nome_arquivo;
			echo $caminho;

			if(move_uploaded_file($arqv_temporario, "$caminho")){
				$nome = formataNome($nome_arquivo);

				$varArquivo = new File(0);
				$varArquivo->projeto_id 		= $_SESSION['projeto'];
				$varArquivo->centro     		= $_SESSION['centro'];
				$varArquivo->descricao  		= $descricao;
				$varArquivo->grupo_id   		= $grupo;
				$varArquivo->nome       		= $nome;
				$varArquivo->arquivo			= $caminho;
				$varArquivo->tamanho    		= $tamanho;
				$varArquivo->data				= date("d/m/Y");
				$varArquivo->hora				= date("H:i");
				$varArquivo->data_lis			= date("Y-m-d H:i:s");
				$varArquivo->usuario			= $usuario;
				$varArquivo->versao				= $versao;
				$varArquivo->versao_anterior 	= 0;

				$idArquivo = 0;
				$idArquivo = $varArquivo->inserir();
				if($idArquivo>0){
					$novoArquivo = new File($idArquivo);
					$novoArquivo->registraAcao("UPLOAD");
					$query = "select * from arq_tmf where id = ".$grupo;
					$res_g = $conexao->pesquisa($query);
					$row_g = mysqli_fetch_assoc($res_g);
					$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "tmf@phartrials.com.br", "PHARTRIALS", 0);
					$message = utf8_encode(file_get_contents('../modelos/modelo_upload.html'));
					$message = str_replace('%usuario%', $_SESSION['nome_usuario'],$message);
					$message = str_replace('%localizacao%', utf8_encode(retorna_caminho_completo($row_g['pai'],1)."<br>"."|->".$row_g['nome']),$message);
					$message = str_replace('%projeto%', $varProjeto->nome,$message);
					$message = str_replace('%arquivo%', "<a href='https://monitoria.phartrials.com.br/upload_link.php?target=".$idArquivo."'>".$nome_arquivo."</a>",$message);
					$message = str_replace('%tamanho%', tamanhoArquivo($tamanho),$message);
					$message = str_replace('%data_hora%', date('d/m/Y H:i'),$message);
					$message = str_replace('%nome_usuario%', utf8_encode($_SESSION['nome_usuario']),$message);
					$message = str_replace('%comentario%', utf8_encode($descricao),$message);
					$varEmail->enviar("[" . $varProjeto->nome . "] - UPLOAD de arquivo", $message);
					echo "OK";
				}
			}else{echo "ERRO_UPLOAD - " . $_FILES['file']['error'];}
		}
	}
	if($act=="UPLOAD_VERSAO"){
		$varConexao = new Conexao();
		set_time_limit(0);
		$descricao 			= $_GET['descricao'];
		$email				= $_GET['email'];
		$versao_anterior 	= $_GET['versao'];
		$grupo 				= $_GET['grupo'];
		$varProjeto = new Projeto($_SESSION['projeto']);

		$id = rand(5479,125545778);
		$nome_arquivo = $_FILES['file']['name'];
		$arqv_temporario = $_FILES['file']['tmp_name'];
		$tamanho = $_FILES['file']['size'];
		$caminho=$urlProjeto."arquivos/".$id."_".$nome_arquivo;

		$varArquivoVerAnterior = new File($versao_anterior);
		$versao = intval($varArquivoVerAnterior->versao)+1;
		$grupo_id = $varArquivoVerAnterior->grupo_id;

		$varVersaoAnterior = $varArquivoVerAnterior->versao_anterior;
		if($varVersaoAnterior==0){$varVersaoAnterior = $versao_anterior;}

		if(move_uploaded_file($arqv_temporario, $caminho)){
			$nome = formataNome($nome_arquivo);

			$varArquivoNovo = new File(0);
			$varArquivoNovo->projeto_id 		= $_SESSION['projeto'];
			$varArquivoNovo->centro     		= $_SESSION['centro'];
			$varArquivoNovo->descricao  		= $descricao;
			$varArquivoNovo->grupo_id   		= $grupo;
			$varArquivoNovo->nome       		= $nome;
			$varArquivoNovo->arquivo			= $caminho;
			$varArquivoNovo->tamanho    		= $tamanho;
			$varArquivoNovo->data				= date("d/m/Y");
			$varArquivoNovo->hora				= date("H:i");
			$varArquivoNovo->data_lis			= date("Y-m-d H:i:s");
			$varArquivoNovo->usuario			= $_SESSION['user_id'];
			$varArquivoNovo->versao				= $versao;
			$varArquivoNovo->versao_anterior 	= $varVersaoAnterior;

			$idArquivo = 0;
			$idArquivo = $varArquivoNovo->inserir();

			if($idArquivo>0){
				$novoArquivo = new File($idArquivo);
				$novoArquivo->registraAcao("UPLOAD");

				$query = "select * from arq_tmf where id = ".$grupo;
				$res_g = $varConexao->pesquisa($query);
				$row_g = mysqli_fetch_assoc($res_g);
				$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "tmf@phartrials.com.br", "PHARTRIALS", 0);
				$message = utf8_encode(file_get_contents('../modelos/modelo_upload.html'));
				$message = str_replace('%usuario%', $_SESSION['nome_usuario'],$message);
				$message = str_replace('%localizacao%', utf8_encode(retorna_caminho_completo($row_g['pai'],1)."<br>"."|->".$row_g['nome']),$message);
				$message = str_replace('%projeto%', $varProjeto->nome,$message);
				$message = str_replace('%arquivo%', "<a href='https://monitoria.phartrials.com.br/upload_link.php?target=".$idArquivo."'>".$nome_arquivo."</a>",$message);
				$message = str_replace('%tamanho%', tamanhoArquivo($tamanho),$message);
				$message = str_replace('%data_hora%', date('d/m/Y H:i'),$message);
				$message = str_replace('%nome_usuario%', utf8_encode($_SESSION['nome_usuario']),$message);
				$message = str_replace('%comentario%', utf8_encode($descricao),$message);
				$varEmail->enviar("[". $varProjeto->nome . "] - UPLOAD de arquivo", $message);
				echo "OK";
			}
			echo $grupo;
		}else{echo "ERRO->".$_FILES['file']['error'] ;}
	}

