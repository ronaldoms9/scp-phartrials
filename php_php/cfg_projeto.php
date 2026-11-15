<?
	include_once "../class/conexao.class.php";
	include_once "../class/projeto.class.php";

	if(isset($_GET['act'])){$act = strtoupper($_GET['act']);}else{$act = "";}

	if($act=="ZERA_CRF"){
		$idProjeto = $_POST['id'];
		$conexao = new Conexao();
		//Zero nros na tabeela cfg_centro
		$query = "update cfg_centro set voluntario = 0, vol_pre_rand = 0 where projeto = " .$idProjeto;
		$conexao->pesquisa($query);
		//deleto tudo na tabela crf_paciente_pre_rand
		$query = "delete from crf_paciente_pre_rand where projeto = " . $idProjeto;
		$conexao->pesquisa($query);
		//Apago formulários já inseridos
		$query = "delete from crf_entrada_forms where projeto = " . $idProjeto;
		$conexao->pesquisa($query);
		//Zero a randomizacao
		$query = "update crf_rand_semente set paciente_alocado = '', centro = 0 where projeto = " . $idProjeto;
		$conexao->pesquisa($query);
		echo "OK";
	}
	if($act=="SALVA"){
		$tipo = $_GET['tipo'];
		if($tipo == "NOVO"){
			$projeto = new Projeto(0);
			$projeto->numero             = $_POST['form_numero'];
			$projeto->tipo				 = $_POST['form_tipo'];
			$projeto->nome               = addslashes($_POST['form_nome']);
			$projeto->descricao          = addslashes($_POST['form_descricao']);
			$projeto->patrocinadorId     = $_POST['form_patrocinador'];
			$projeto->periodos           = $_POST['form_periodos'];
			$projeto->grupos             = $_POST['form_grupos'];
			$projeto->n                  = $_POST['form_n'];
			$projeto->versao             = $_POST['form_versao'];
			$projeto->codigo             = $_POST['form_codigo'];
			$projeto->versao_plano       = $_POST['versao_plano'];
			$projeto->data_protocolo     = $_POST['form_data_protocolo'];
			$projeto->coletas            = addslashes($_POST['form_coletas']);
			$projeto->comentarios        = nl2br(addslashes($_POST['form_comentarios']));
			$projeto->registro_valido    = 1;
			$projeto->idade_minima       = $_POST['form_idade_minima'];
			$projeto->idade_maxima       = $_POST['form_idade_maxima'];
			$projeto->plano_chkOpc1       = (isset($_POST['chkOpc1']) && $_POST['chkOpc1'] == '1') ? 1 : 0;
			$projeto->plano_chkOpc2       = (isset($_POST['chkOpc2']) && $_POST['chkOpc2'] == '1') ? 1 : 0;
			$projeto->plano_chkOpc3       = (isset($_POST['chkOpc3']) && $_POST['chkOpc3'] == '1') ? 1 : 0;
			$projeto->plano_chkOpc4       = (isset($_POST['chkOpc4']) && $_POST['chkOpc4'] == '1') ? 1 : 0;
			$projeto->plano_chkOpc5       = (isset($_POST['chkOpc5']) && $_POST['chkOpc5'] == '1') ? 1 : 0;
			$projeto->plano_chkOpc6       = (isset($_POST['chkOpc6']) && $_POST['chkOpc6'] == '1') ? 1 : 0;
			$projeto->plano_chkOpc7       = (isset($_POST['chkOpc7']) && $_POST['chkOpc7'] == '1') ? 1 : 0;

			$projeto->inclui();
		}else{
			$projeto = new Projeto($_POST['form_id']);
			$projeto->numero             = $_POST['form_numero'];
			$projeto->tipo               = $_POST['form_tipo'];
			$projeto->nome               = addslashes($_POST['form_nome']);
			$projeto->descricao          = addslashes($_POST['form_descricao']);
			$projeto->patrocinadorId     = $_POST['form_patrocinador'];
			$projeto->periodos           = $_POST['form_periodos'];
			$projeto->grupos             = $_POST['form_grupos'];
			$projeto->n                  = $_POST['form_n'];
			$projeto->versao             = $_POST['form_versao'];
			$projeto->codigo             = $_POST['form_codigo'];
			$projeto->versao_plano       = $_POST['versao_plano'];
			$projeto->data_protocolo     = $_POST['form_data_protocolo'];
			$projeto->coletas            = addslashes($_POST['form_coletas']);
			$projeto->comentarios        = nl2br(addslashes($_POST['form_comentarios']));
			$projeto->registro_valido    = 1;
			$projeto->idade_minima       = $_POST['form_idade_minima'];
			$projeto->idade_maxima       = $_POST['form_idade_maxima'];
			$projeto->plano_chkOpc1       = (isset($_POST['chkOpc1']) && $_POST['chkOpc1'] == '1') ? 1 : 0;
			$projeto->plano_chkOpc2       = (isset($_POST['chkOpc2']) && $_POST['chkOpc2'] == '1') ? 1 : 0;
			$projeto->plano_chkOpc3       = (isset($_POST['chkOpc3']) && $_POST['chkOpc3'] == '1') ? 1 : 0;
			$projeto->plano_chkOpc4       = (isset($_POST['chkOpc4']) && $_POST['chkOpc4'] == '1') ? 1 : 0;
			$projeto->plano_chkOpc5       = (isset($_POST['chkOpc5']) && $_POST['chkOpc5'] == '1') ? 1 : 0;
			$projeto->plano_chkOpc6       = (isset($_POST['chkOpc6']) && $_POST['chkOpc6'] == '1') ? 1 : 0;
			$projeto->plano_chkOpc7       = (isset($_POST['chkOpc7']) && $_POST['chkOpc7'] == '1') ? 1 : 0;

			$projeto->salva();
		}
	}
	if($act=="LISTA"){
		$conexao = new Conexao();
		$query = "select
					*
						from proj_projeto
							order by substring(numero, 1, 6) desc, substring(numero, 8, 4)";
		$res = $conexao->pesquisa($query);
		while($row = mysqli_fetch_assoc($res)){
			?>
			<tr
				 onclick='javascript:selecionaEstudo(<?=$row["id"];?>)'
				 class='lista_formularios'
				 style="cursor: pointer";>
				 <td width="40"><center><b><?=$row['numero'];?></center></b></td>
				<td>
					<?
						echo $row['nome'];
					?>
				</td>
			</tr>
			<?php
		}
	}
	if($act=="ALTERAR"){
		$conexao = new Conexao();
		$query = "update proj_projeto set
				numero 			= 	'".$_POST['numero']."',
				nome 			= 	'".$_POST['nome']."',
				descricao		=	'".$_POST['descricao']."',
				patrocinador	=	'".$_POST['patrocinador']."',
				centro			=	'".$_POST['centro']."',
				centro_analitico=	'".$_POST['centro_analitico']."',
				centro_estatistico=	'".$_POST['centro_estatistico']."',
				periodos		=	'".$_POST['periodos']."',
				grupos			=	'".$_POST['grupos']."',
				n				=	'".$_POST['n']."',
				versao			=	'".$_POST['versao']."',
				dash			=	'".$_POST['dash']."',
				dashproprio		=	'".$_POST['dashproprio']."',
				codigo			=	'".$_POST['codigo']."',
				pais			=	'".$_POST['pais']."',
				tipo			=	'".$_POST['tipo']."',
				formulacao=	'".$_POST['formulacao']."',
				log_usuario		=	'".$_SESSION['user_id']."',
				versao_plano		=	'".$_POST['versao_plano']."',
				data_protocolo		=	'".$_POST['data_protocolo']."',
				idade_minima		=	'".$_POST['idade_minima']."',
				idade_maxima		=	'".$_POST['idade_maxima']."',
				comentarios		=	'".nl2br($_POST['comentarios'])."'
				where id = " . $_POST['id'];
		$query = utf8_decode($query);
		$conexao->pesquisa($query);
	}
	if($act=="NOVO"){
		$projeto                     = new Projeto($varEmitente, $varData);
		$projeto->numero             = $_POST['numero'];
		$projeto->nome               = $_POST['nome'];
		$projeto->descricao          = $_POST['descricao'];
		$projeto->patrocinadorId     = $_POST['patrocinador'];
		$projeto->centro             = $_POST['centro'];
		$projeto->centro_analitico   = $_POST['centro_analitico'];
		$projeto->centro_estatistico = $_POST['centro_estatistico'];
		$projeto->periodos           = $_POST['periodos'];
		$projeto->grupos             = $_POST['grupos'];
		$projeto->n                  = $_POST['n'];
		$projeto->versao             = $_POST['versao'];
		$projeto->dash               = $_POST['dash'];
		$projeto->log_usuario        = $_POST['log_usuario'];
		$projeto->dashproprio        = $_POST['dashproprio'];
		$projeto->codigo             = $_POST['codigo'];
		$projeto->pais               = $_POST['pais'];
		$projeto->data_recebimento   = $_POST['data_recebimento'];
		$projeto->formulacao         = $_POST['formulacao'];
		$projeto->tipo               = $_POST['tipo'];
		$projeto->data_finalizacao   = $_POST['data_finalizacao'];
		$projeto->registro_valido    = 1;
		$projeto->versao_plano       = $_POST['versao_plano'];
		$projeto->data_protocolo     = $_POST['data_protocolo'];
		$projeto->idade_minima       = $_POST['idade_minima'];
		$projeto->idade_maxima       = $_POST['idade_maxima'];
		$projeto->comentarios        = nl2br($_POST['comentarios']);

		$ret = $projeto->inclui();
	}
	if($act=="CONSULTA"){
		$id 	=	$_GET['id'];
		$varProjeto = new Projeto($id);

		if($varProjeto->id==0){
			$resp = array(
				"resultado" => "NAO"
			);
		}else{
			$resp = array(
				'resultado'          => "OK",
				'id'                 => $varProjeto->id,
				'numero'             => $varProjeto->numero,
				'nome'               => $varProjeto->nome,
				'descricao'          => $varProjeto->descricao,
				'patrocinador'       => $varProjeto->patrocinadorId,
				'centro'             => $varProjeto->centro,
				'centro_analitico'   => $varProjeto->centro_analitico,
				'centro_estatistico' => $varProjeto->centro_estatistico,
				'periodos'           => $varProjeto->periodos,
				'grupos'             => $varProjeto->grupos,
				'n'                  => $varProjeto->n,
				'versao'             => $varProjeto->versao,
				'dash'               => $varProjeto->dash,
				'dashproprio'        => $varProjeto->dashproprio,
				'codigo'             => $varProjeto->codigo,
				'pais'               => $varProjeto->pais,
				'tipo'               => $varProjeto->tipo,
				'data_recebimento'   => $varProjeto->data_recebimento,
				'formulacao'         => $varProjeto->formulacao,
				'log_usuario'        => $varProjeto->log_usuario,
				'data_finalizacao'   => $varProjeto->data_finalizacao,
				'registro_valido'    => $varProjeto->registro_valido,
				'versao_plano'       => $varProjeto->versao_plano,
				'data_protocolo'     => $varProjeto->data_protocolo,
				'coletas'            => $varProjeto->coletas,
				'comentarios'        => str_replace("<br />", "", $varProjeto->comentarios),
				'idade_minima'       => $varProjeto->idade_minima,
				'idade_maxima'       => $varProjeto->idade_maxima,
				'chkOpc1'            => ($varProjeto->plano_chkOpc1 == '1') ? '1' : '0',
				'chkOpc2'            => ($varProjeto->plano_chkOpc2 == '1') ? '1' : '0',
				'chkOpc3'            => ($varProjeto->plano_chkOpc3 == '1') ? '1' : '0',
				'chkOpc4'            => ($varProjeto->plano_chkOpc4 == '1') ? '1' : '0',
				'chkOpc5'            => ($varProjeto->plano_chkOpc5 == '1') ? '1' : '0',
				'chkOpc6'            => ($varProjeto->plano_chkOpc6 == '1') ? '1' : '0',
				'chkOpc7'            => ($varProjeto->plano_chkOpc7 == '1') ? '1' : '0'
			);
		}
		$resp = array_map('utf8_encode', $resp);
		$resp = json_encode($resp);
		echo $resp;
	}
	if($act=="GERA_TMF"){
		$id 		= $_GET['id'];
		$projeto 	= $_SESSION['projeto'];
		$varProjeto = new Projeto($id);
		$ret = $varProjeto->geraTmf($id);
	}
	if($act=="GERA_TMF_RESUMIDO"){
		$id 		= $_POST['id'];
		$projeto 	= $_SESSION['projeto'];
		$varProjeto = new Projeto($id);
		//$ret = $varProjeto->geraTmfResumido($id);
	}
	if($act=="GERA_TMF_BIO"){
		$id 		= $_POST['id'];
		$projeto 	= $_SESSION['projeto'];
		$varProjeto = new Projeto($id);
		$ret = $varProjeto->geraTmfBio($_SESSION['projeto']);
	}