<?php
	include_once "conexao.class.php";
	include_once "centro.class.php";
	include_once "usuario.class.php";
	function trata_aspas($texto){
		return str_replace('"', "`", $texto);
		//return addslashes($temp);
	}

	class Agenda{
		public $id;
		public $ativo;
		public $nro_visita;
		public $periodo;
		public $versao;
		public $monitora;
		public $data;
		public $datafinal;
		public $data_caption;
		public $hora;
		public $hora_fim;
		public $horas;
		public $centro;
		public $atividades;
		public $presentes;
		public $tipo;
		public $realizada;
		public $projeto;

		public $rel_assinado;
		public $rel_assinado_quem;
		public $rel_assinado_quando;

		public $rel_alertado;
		public $rel_alertado_quem;
		public $rel_alertado_data;
		public $rel_respondido;
		public $rel_enviado;
		public $rel_enviado_quem;
		public $rel_enviado_data;

		public $versao_motivo;

		public $titulo;
		public $grupo_periodo;
		public $log_usuario;

		public $monitor;
		public $revisor;

		public $rel_pre_objetivo;
		public $rel_pre_apontamentos;
		public $rel_pre_tipo_documento;
		public $rel_pre_checklist;
		public $conso_data_inicial;
		public $conso_data_final;
		public $contato;

		public $data_impressao;

		public function __construct($id){
			if($id>0){
				$conexao = new Conexao();
				$query 		= "select * from agenda where id = " . $id;
				$resAgenda 	= $conexao->pesquisa($query);
				$rowAgenda	= mysqli_fetch_assoc($resAgenda);
				$this->id					= $id;
				$this->ativo				= $rowAgenda['ativo'];
				$this->nro_visita			= $rowAgenda['nro_visita'];
				$this->periodo				= $rowAgenda['periodo'];
				$this->versao				= $rowAgenda['versao'];
				$this->data					= $rowAgenda['data'];
				$this->datafinal			= $rowAgenda['datafinal'];
				$this->data_caption			= $rowAgenda['data_caption'];
				$this->hora					= $rowAgenda['hora'];
				$this->hora_fim				= $rowAgenda['hora_fim'];
				$this->horas				= $rowAgenda['horas'];
				$this->centro				= new CentroPesquisa($rowAgenda['centro']);
				$this->atividades			= $rowAgenda['atividades'];
				$this->presentes			= $rowAgenda['presentes'];
				$this->tipo					= $rowAgenda['tipo_monitoria'];
				$this->realizada			= $rowAgenda['realizada'];
				$this->projeto				= $rowAgenda['projeto'];
				$this->rel_revisado_quem	= $rowAgenda['rel_revisado_quem'];
				$this->rel_revisado_data	= $rowAgenda['rel_revisado_data'];
				$this->rel_enviado			= $rowAgenda['rel_enviado'];
				$this->rel_respondido		= $rowAgenda['rel_respondido'];
				$this->rel_enviado_quem		= $rowAgenda['rel_enviado_quem'];
				$this->rel_enviado_data		= $rowAgenda['rel_enviado_data'];
				$this->rel_alertado			= $rowAgenda['rel_alertado'];
				$this->rel_alertado_quem	= $rowAgenda['rel_alertado_quem'];
				$this->rel_alertado_data	= $rowAgenda['rel_alertado_data'];
				$this->rel_assinado			= $rowAgenda['rel_assinado'];
				$this->rel_assinado_quem	= $rowAgenda['rel_assinado_quem'];
				$this->rel_assinado_data	= $rowAgenda['rel_assinado_data'];
				$this->titulo				= $rowAgenda['titulo'];
				$this->grupo_periodo		= $rowAgenda['grupo_periodo'];
				$this->log_usuario			= $rowAgenda['log_usuario'];
				$this->monitor				= new Usuario($rowAgenda['monitora']);
				$this->revisor				= new Usuario($rowAgenda['revisor']);
				$this->rel_pre_objetivo		= $rowAgenda['rel_pre_objetivo'];
				$this->rel_pre_apontamentos = $rowAgenda['rel_pre_apontamentos'];
				$this->rel_pre_tipo_documento= $rowAgenda['rel_pre_tipo_documento'];
				$this->rel_pre_checklist	= $rowAgenda['rel_pre_checklist'];
				$this->conso_data_inicial	= $rowAgenda['conso_data_inicial'];
				$this->conso_data_final		= $rowAgenda['conso_data_final'];
				$this->contato				= $rowAgenda['contato'];
				$this->versao_motivo		= $rowAgenda['versao_motivo'];
				$this->data_impressao		= $rowAgenda['data_impressao'];
			}
		}
		public function setaDataImpressao($data){
			$conexao              = new Conexao();
			$this->data_impressao = DataToLIS($data);
			$query                = "update agenda set data_impressao = '".$this->data_impressao."' where id = ".$this->id;
			$conexao->pesquisa($query);
		}
		public function getDataInicial_e_Final(){
			$varConexao = new Conexao();

			$query = "select * from agenda_atividade where agenda = ".$this->id." order by id";
			$res  = $varConexao->pesquisa($query);
			if(mysqli_num_rows($res)>0){
				if(mysqli_num_rows($res)==1){
					$row = mysqli_fetch_assoc($res);
					return $row['data'];
				}else{
					$query = "select * from agenda_atividade where agenda = ".$this->id." order by id limit 1";
					$res  = $varConexao->pesquisa($query);
					$row = mysqli_fetch_assoc($res);
					$dataIni = $row['data'];
					$query = "select * from agenda_atividade where agenda = ".$this->id." order by id desc limit 1";
					$res  = $varConexao->pesquisa($query);
					$row = mysqli_fetch_assoc($res);
					$dataFim = $row['data'];
					return $dataIni . " à " . $dataFim;
				}
			}else{
				return "";
			}
		}
		public function __clone(){
			$this->versao		= $this->versao + 1;
			$this->ativo		= 1;
			$this->versao_motivo	= $this->versao_motivo;
		}
		public function getRelApontamento(){
			return utf8_encode($this->rel_pre_apontamentos);
		}
		public function getRelCheckList(){
			return utf8_encode($this->rel_pre_checklist);
		}
		public function getTipoVisita(){
			if($this->tipo=="Relatorio"){return utf8_encode("Revisão de Relatórios de Estudo");}
			if($this->tipo=="Protocolo"){return utf8_encode("Revisão de Protocolo");}
			if($this->tipo=="Visita Inicial"){return utf8_encode("Visita Inicial");}
			if($this->tipo=="Inicial"){return utf8_encode("Visita Inicial");}
			if($this->tipo=="Visita Regular"){return utf8_encode("Visita Regular");}
			if($this->tipo=="Visita Final"){return utf8_encode("Visita Final");}

		}
		public function getRelObjetivo(){
			return utf8_encode($this->rel_pre_objetivo);
		}
		public function getRelTipoDocumento(){
			return utf8_encode($this->rel_pre_tipo_documento);
		}
		function novaVersao($motivo){
			$this->ativo 		=	0;
			$this->salvaAtualizacao();
			//Crio um registro novo, com os mesmos dados, mas na versao + 1
			$novoAgendamento = clone $this;
			$novoAgendamento->versao_motivo = $motivo;
			$novoAgendamento->salvaNovaVersao($this->id);
		}
		function registrarAlerta(){
			$conexao = new Conexao();
			$data = date('d/m/Y H:i');
			$query = "update agenda set rel_alertado = '1',
						rel_alertado_quem ='".$_SESSION['user_nome']."',
						rel_alertado_data = '".$data."'
						where id = " . $this->id;
			$conexao->pesquisa($query);
			//Atualizo planilha Projeto
			$query = "update proj_projeto set data_aviso_revisor = '$data' where id = " . $this->projeto;
			$conexao->pesquisa($query);

			return '<input disabled type="checkbox" name="check" id="check" checked "></input>
					<font style="font-size:8px"><br>'.$_SESSION['user_nome'].'<br>'.$data.'</font>';
		}
		function registrarRevisado(){
			$conexao = new Conexao();
			$data = date('d/m/Y H:i');
			$query = "update agenda set
						rel_revisado_quem ='".$_SESSION['user_nome']."',
						rel_revisado_data = '".$data."'
						where id = " . $this->id;
			$conexao->pesquisa($query);
			//Atualizo planilha Projeto
			$query = "update proj_projeto set data_resposta_revisor = '$data' where id = " . $this->projeto;
			$conexao->pesquisa($query);

			return '<input disabled type="checkbox" name="check" id="check" checked "></input>
					<font style="font-size:8px"><br>'.$_SESSION['user_nome'].'<br>'.$data.'</font>';
		}
		function salvaAtualizacao(){
			$conexao 	= new Conexao();
			$query 		= "update agenda set
								ativo = '".$this->ativo."',
								nro_visita = '".$this->nro_visita."',
								periodo = '".$this->periodo."',
								versao = '".$this->versao."',
								monitora = '".$this->monitor->id."',
								revisor = '".$this->revisor->id."',
								data = '".$this->data."',
								datafinal = '".$this->datafinal."',
								data_caption = '".$this->data_caption."',
								hora = '".$this->hora."',
								hora_fim = '".$this->hora_fim."',
								horas = '".$this->horas."',
								centro = '".$this->centro->id."',
								atividades = '".$this->atividades."',
								presentes = '".$this->presentes."',
								tipo_monitoria = '".$this->tipo."',
								realizada = '".$this->realizada."',
								log_usuario = '".$this->log_usuario."',
								projeto = '".$this->projeto."',
								rel_enviado = '".$this->rel_enviado."',
								rel_respondido = '".$this->rel_respondido."',
								titulo = '".$this->titulo."',
								grupo_periodo = '".$this->grupo_periodo."',
								rel_revisado_quem = '".$this->rel_revisado_quem."',
								rel_revisado_data = '".$this->rel_revisado_data."',
								rel_enviado_quem = '".$this->rel_enviado_quem."',
								rel_enviado_data = '".$this->rel_enviado_data."',
								rel_alertado = '".$this->rel_alertado."',
								rel_alertado_quem = '".$this->rel_alertado_quem."',
								rel_alertado_data = '".$this->rel_alertado_data."',
								contato = '".$this->contato."',
								versao_motivo = '".$this->versao_motivo."',
								data_impressao = '".$this->data_impressao."'

								where id = " . $this->id;
			$conexao->pesquisa($query);
			return $query;
		}
		function salvaNovo(){
			$conexao 	= new Conexao();
			$query 		= "insert into agenda
							(ativo, nro_visita, periodo, versao, monitora, revisor, data, datafinal, data_caption, hora, hora_fim, horas,
							centro, atividades, presentes, tipo_monitoria, realizada, projeto, titulo, grupo_periodo, log_usuario, contato, versao_motivo, data_impressao)
							values
							(
							'".$this->ativo."',
							'".$this->nro_visita."',
							'".$this->periodo."',
							'".$this->versao."',
							'".$this->monitor->id."',
							'".$this->revisor->id."',
							'".$this->data."',
							'".$this->datafinal."',
							'".$this->data_caption."',
							'".$this->hora."',
							'".$this->hora_fim."',
							'".$this->horas."',
							'".$this->centro->id."',
							'".$this->atividades."',
							'".$this->presentes."',
							'".$this->tipo."',
							'".$this->realizada."',
							'".$this->projeto."',
							'".$this->titulo."',
							'".$this->grupo_periodo."',
							'".$this->log_usuario."',
							'".$this->contato."',
							'".$this->versao_motivo."',
							''
							);";
			$conexao->pesquisa($query);
			return $query;
		}
		function salvaNovaVersao($anterior){
			$conexao 	= new Conexao();
			$query 		= "insert into agenda
							(ativo, nro_visita, periodo, versao, monitora, revisor, data, datafinal, data_caption, hora, hora_fim, horas,
							centro, atividades, presentes, tipo_monitoria, realizada, projeto, titulo, grupo_periodo, log_usuario, contato, versao_motivo)
							values
							(
							'".$this->ativo."',
							'".$this->nro_visita."',
							'".$this->periodo."',
							'".$this->versao."',
							'".$this->monitor->id."',
							'".$this->revisor->id."',
							'".$this->data."',
							'".$this->datafinal."',
							'".$this->data_caption."',
							'".$this->hora."',
							'".$this->hora_fim."',
							'".$this->horas."',
							'".$this->centro->id."',
							'".$this->atividades."',
							'".$this->presentes."',
							'".$this->tipo."',
							'".$this->realizada."',
							'".$this->projeto."',
							'".$this->titulo."',
							'".$this->grupo_periodo."',
							'".$this->log_usuario."',
							'".$this->contato."',
							'".$this->versao_motivo."'
							);";
			$conexao->pesquisa($query);
			$query 	= "select max(id) as ult from agenda";
			$resUlt = $conexao->pesquisa($query);
			$rowUlt = mysqli_fetch_assoc($resUlt);
			$idNovo = $rowUlt['ult'];
			//Loop em todos os formulários da agenda
			$query 	= "select * from crf_agenda_forms where agendamento = " . $anterior;
			$resForm= $conexao->pesquisa($query);
			while($rowForm = mysqli_fetch_assoc($resForm)){
				$id 			= $rowForm['id'];
				$agendamento 	= $rowForm['agendamento'];
				$form_id		= $rowForm['form_id'];
				$log_usuario	= $rowForm['log_usuario'];

				$query = "insert into crf_agenda_forms (agendamento, form_id, log_usuario) values ('".$idNovo."', '$form_id', '1');";
				$conexao->pesquisa($query);

				$query = "select id from crf_agenda_forms where agendamento = '".$idNovo."' and form_id = '".$form_id."'";
				$resN = $conexao->pesquisa($query);
				$rowN = mysqli_fetch_assoc($resN);
				$idNovoFormulario = $rowN['id'];

				//Campos
				$query = "select * from crf_agenda_campos where form_id = " . $id;
				$resCampos = $conexao->pesquisa($query);
				while($rowCampos = mysqli_fetch_assoc($resCampos)){
					$varComentario = $rowCampos['comentario'];
					$varRevisor    = $rowCampos['comentario_revisor'];

					$varComentario = trata_aspas($varComentario);
					$varRevisor    = trata_aspas($varRevisor);

					$query = "insert into crf_agenda_campos (form_id,
															campo_id,
															valor,
															log_usuario,
															erro_status,
															monitoria_status,
															comentario,
															comentario_revisor,
															comentario_revisor_status,
															alterado,
															usuario_original) values (
															'".$idNovoFormulario."',
															'".$rowCampos['campo_id']."',
															'".addslashes($rowCampos['valor'])."',
															'".$rowCampos['log_usuario']."',
															'".$rowCampos['erro_status']."',
															'".$rowCampos['monitoria_status']."',
															'".$varComentario."',
															'".$varRevisor."',
															'".$rowCampos['comentario_revisor_status']."',
															'".$rowCampos['alterado']."',
															'".$rowCampos['usuario_original']."'
															);";
					$conexao->pesquisa($query);
				}

			}
		}
		function getEnvCorrecao(){
			if(($this->rel_alertado!="")&&($this->rel_alertado!="0")){return "Sim<br>".$this->rel_alertado_data;}else{
				return utf8_decode("<font color='red'>Não</font>");
			}
		}
		function getEnvRetornado(){
			if(($this->rel_revisado_quem!="")&&($this->rel_revisado_quem!="0")){return "Sim<br>".$this->rel_revisado_data;}else{return utf8_decode("<font color='red'>Não</font>");}
		}
		function getEnvPatrocinador(){
			if(($this->rel_enviado!="")&&($this->rel_enviado!="0")){return "Sim<br>".$this->rel_enviado_data;}else{return utf8_decode("<font color='red'>Não</font>");}
		}
		function getAssPatrocinador(){
			if(($this->rel_assinado!="")&&($this->rel_assinado!="0")){return "Sim<br>".$this->rel_assinado_data;}else{return utf8_decode("<font color='red'>Não</font>");}
		}
		function getDataInicialDATA(){
			return substr($this->data,6,2) . '/' . substr($this->data,4,2) . '/' .substr($this->data,0,4);
		}
	}
	class Achado {
		public $id;
		public $agendamento;
		public $pagina;
		public $trecho;
		public $critico;
		public $achado;
		public $resposta;
		public $respondido_por;
		public $finalizado;
		public $usuario_id;
		public $ordem;

		public function __construct($id){
			if($id>0){
				$varConexao = new Conexao();
				$query 		= "select * from agenda_protocolo_pendencia where id = " . $id;
				$resPend	= $varConexao->pesquisa($query);
				$rowPend	= mysqli_fetch_assoc($resPend);

				$this->id 				= $id;
				$this->agendamento 		= new Agenda($rowPend['agendamento']);
				$this->pagina			= $rowPend['pagina'];
				$this->trecho			= $rowPend['trecho'];
				$this->critico			= $rowPend['critico'];
				$this->achado			= $rowPend['achado'];
				$this->resposta			= $rowPend['resposta'];
				$this->respondido_por	= $rowPend['respondido_por'];
				$this->finalizado		= $rowPend['finalizado'];
				$this->finalizado		= $rowPend['finalizado'];
				$this->usuario_id		= $rowPend['usuario_id'];
				$this->ordem			= $rowPend['ordem'];
			}
		}
		public function trocaCima(){
			$varConexao = new Conexao();
			$query = "SELECT max(ordem) as ant
						FROM agenda_protocolo_pendencia
							where agendamento = ".$this->agendamento->id." and ordem < " . $this->ordem;
			$resAnt= $varConexao->pesquisa($query);
			$rowAnt= mysqli_fetch_assoc($resAnt);
			$ordAnt = $rowAnt['ant'];
			$query = "select id from agenda_protocolo_pendencia where agendamento = ".$this->agendamento->id." and ordem = " . $rowAnt['ant'];
			$resAnt= $varConexao->pesquisa($query);
			$rowAnt= mysqli_fetch_assoc($resAnt);
			$idAnt = $rowAnt['id'];

			$query 		= "update agenda_protocolo_pendencia set ordem = " . $this->ordem . " where id = " . $idAnt;
			$varConexao->pesquisa($query);
			$query 		= "update agenda_protocolo_pendencia set ordem = " . $ordAnt . " where id = " . $this->id;
			$this->ordem = $ordAnt;
			$varConexao->pesquisa($query);
		}
		public function trocaBaixo(){
			$varConexao = new Conexao();
			$query = "SELECT min(ordem) as ant
						FROM agenda_protocolo_pendencia
							where agendamento = ".$this->agendamento->id." and ordem > " . $this->ordem;
			$resAnt= $varConexao->pesquisa($query);
			$rowAnt= mysqli_fetch_assoc($resAnt);
			$ordAnt = $rowAnt['ant'];
			$query = "select id from agenda_protocolo_pendencia where agendamento = ".$this->agendamento->id." and ordem = " . $rowAnt['ant'];
			$resAnt= $varConexao->pesquisa($query);
			$rowAnt= mysqli_fetch_assoc($resAnt);
			$idAnt = $rowAnt['id'];

			$query 		= "update agenda_protocolo_pendencia set ordem = " . $this->ordem . " where id = " . $idAnt;
			$varConexao->pesquisa($query);
			$query 		= "update agenda_protocolo_pendencia set ordem = " . $ordAnt . " where id = " . $this->id;
			$this->ordem = $ordAnt;
			$varConexao->pesquisa($query);
		}
	}
	class TipoFormulario{
		public $id;
		public $nome;
		public $texto_crf;
		public $texto_crf_en;
		public $ordem;
		public $descricao;
		public $tipo;
		public $log_usuario;
		public $registro_valido;
		public $acao;
		public $pagina;
		public $visita;
		public $botao_salvar;

		public function __construct($id){
			if($id>0){
				$varConexao = new Conexao();
				$query 		= "select * from cfg_visitas where id = " . $id;
				$resPend	= $varConexao->pesquisa($query);
				$row	= mysqli_fetch_assoc($resPend);

				$this->id        		 =   $row['id'];
				$this->nome        		 =   $row['nome'];
				$this->texto_crf         =   $row['texto_crf'];
				$this->texto_crf_en      =   $row['texto_crf_en'];
				$this->ordem         	 =   $row['ordem'];
				$this->descricao         =   $row['descricao'];
				$this->tipo         	 =   $row['tipo'];
				$this->log_usuario       =   $row['log_usuario'];
				$this->registro_valido   =   $row['registro_valido'];
				$this->acao         	 =   $row['acao'];
				$this->pagina         	 =   $row['pagina'];
				$this->visita         	 =   $row['visita'];
				$this->botao_salvar      =   $row['botao_salvar'];
			}
		}
	}
	class TipoCampo {
		public $id;
		public $nome;
		public $texto;
		public $texto_en;
		public $ordem;
		public $numeracao;
		public $tipo;
		public $obrigatorio;
		public $numero_min;
		public $numero_max;
		public $qtd_decimal;
		public $selecao_valores;
		public $log_usuario;
		public $registro_valido;
		public $formulario;
		public $formato;
		public $calculo_tipo;
		public $calculo_campo1;
		public $calculo_campo2;
		public $legenda;
		public $condicional;
		public $botao_acao;
		public $botao_enabled;
		public $pagina;
		public $termo;
		public $data_menor_termo;
		public $data_menor_termo_alerta;
		public $so_numero;
		public $so_letra;
		public $so_leitura;
		public $data_menor_hoje;
		public $data_dezoito_anos;
		public $tipo_campo;
		public $condicional_vermelho;
		public $condicional_alerta;
		public $condicional_regra_alerta;
		public $select_falso;
		public $acao_descricao_causa;
		public $pagina_altura;


		public function __construct($id){
			if($id>0){
				$varConexao = new Conexao();
				$query 		= "select * from cfg_visitas_campos where id = " . $id;
				$resPend	= $varConexao->pesquisa($query);
				$row	= mysqli_fetch_assoc($resPend);

				$this->id        	 		=   $row['id'];
				$this->nome         		=   $row['nome'];
				$this->texto         		=   $row['texto'];
				$this->texto_en         	=   $row['texto_en'];
				$this->ordem         		=   $row['ordem'];
				$this->numeracao         	=   $row['numeracao'];
				$this->tipo         		=   $row['tipo'];
				$this->obrigatorio         	=   $row['obrigatorio'];
				$this->numero_min        	=   $row['numero_min'];
				$this->numero_max         	=   $row['numero_max'];
				$this->qtd_decimal         	=   $row['qtd_decimal'];
				$this->selecao_valores      =   $row['selecao_valores'];
				$this->log_usuario         	=   $row['log_usuario'];
				$this->registro_valido      =   $row['registro_valido'];
				$this->formulario         	=   $row['formulario'];
				$this->formato         		=   $row['formato'];
				$this->calculo_tipo         =   $row['calculo_tipo'];
				$this->calculo_campo1       =   $row['calculo_campo1'];
				$this->calculo_campo2       =   $row['calculo_campo2'];
				$this->legenda         		=   $row['legenda'];
				$this->condicional         	=   $row['condicional'];
				$this->botao_acao         	=   $row['botao_acao'];
				$this->botao_enabled        =   $row['botao_enabled'];
				$this->pagina         		=   $row['pagina'];
				$this->termo         		=   $row['termo'];
				$this->data_menor_termo     =   $row['data_menor_termo'];
				$this->data_menor_termo_alerta  =   $row['data_menor_termo_alerta'];
				$this->so_numero         	=   $row['so_numero'];
				$this->so_letra         	=   $row['so_letra'];
				$this->so_leitura         	=   $row['so_leitura'];
				$this->data_menor_hoje      =   $row['data_menor_hoje'];
				$this->data_dezoito_anos    =   $row['data_dezoito_anos'];
				$this->tipo_campo         	=   $row['tipo_campo'];
				$this->condicional_vermelho =   $row['condicional_vermelho'];
				$this->condicional_alerta   =   $row['condicional_alerta'];
				$this->condicional_regra_alerta =   $row['condicional_regra_alerta'];
				$this->select_falso         	=   $row['select_falso'];
				$this->acao_descricao_causa     =   $row['acao_descricao_causa'];
				$this->pagina_altura         	=   $row['pagina_altura'];
			}
		}
	}
	class Formulario {
		public $id;
		public $agendamento;
		public $form_id;
		public $classFormulario;
		public $log_usuario;

		public function __construct($id){
			if($id>0){
				$varConexao = new Conexao();
				$query 		= "select * from crf_agenda_forms where id = " . $id;
				$resPend	= $varConexao->pesquisa($query);
				$row	= mysqli_fetch_assoc($resPend);

				$this->id        		 =   $row['id'];
				$this->agendamento       =   $row['agendamento'];
				$this->form_id         	 =   $row['form_id'];
				$this->classFormulario	 =   new TipoFormulario($row['form_id']);
				$this->log_usuario       =   $row['log_usuario'];
			}
		}
	}
	class Campo{
		public $id;
		public $form_id;
		public $campo_id;
		public $classTipoCampo;
		public $valor;
		public $valorAnterior;
		public $log_usuario;
		public $log_usuarioAnterior;
		public $monitoria_status;
		public $erro_status;
		public $comentario;
		public $comentario_revisor;
		public $comentario_revisor_status;
		public $alterado;
		public $motivoAlteracao;

		public function __construct($id){
			if($id>0){
				$varConexao = new Conexao();
				$query 		= "select * from crf_agenda_campos where id = " . $id;
				$resPend	= $varConexao->pesquisa($query);
				$row	= mysqli_fetch_assoc($resPend);

				$this->id         					=   $row['id'];
				$this->form_id         				=   $row['form_id'];
				$this->campo_id         			=   $row['campo_id'];
				$this->classTipoCampo				=	new TipoCampo($row['campo_id']);
				$this->valor         				=   $row['valor'];
				$this->valorAnterior				= 	$row['valor'];
				$this->log_usuario         			=   $row['log_usuario'];
				$this->log_usuarioAnterior 			=   $row['usuario_original'];
				$this->monitoria_status         	=   $row['monitoria_status'];
				$this->erro_status         			=   $row['erro_status'];
				$this->comentario         			=   $row['comentario'];
				$this->comentario_revisor         	=   $row['comentario_revisor'];
				$this->comentario_revisor_status    =   $row['comentario_revisor_status'];
				$this->alterado    					=   $row['alterado'];
				$this->motivoAlteracao				=   "";
			}
		}
		public function salvar(){
			$conexao = new Conexao();
			if(($this->valor!=$this->valorAnterior)&&($this->alterado==0)){	//O campo nunca tinha sido alterado, era virgem
				$query = "update crf_agenda_campos set
							motivo_alteracao = '".utf8_decode($this->motivoAlteracao)."',
							valor = '".utf8_decode($this->valor)."',
							alterado = 1,
							usuario_original = '".$this->log_usuario."',
							log_usuario = '".$this->log_usuario."'
								where id = " . $this->id;
			}
			if(($this->valor!=$this->valorAnterior)&&($this->alterado==1)){ //O campo jÃ¡ tinha sido alterado, nÃ£o era mais virgem
				include_once "projeto.class.php";
				include_once "email.class.php";

				$query = "update crf_agenda_campos set
							motivo_alteracao = '".utf8_decode($this->motivoAlteracao)."',
							valor = '".utf8_decode($this->valor)."',
							alterado = 1,
							log_usuario = '".$this->log_usuario."'
								where id = " . $this->id;

				/*if((trim($this->valor)!=trim($this->valorAnterior))&&(trim($this->motivoAlteracao)!="")){
					$varUsuario 	= new Usuario($this->log_usuarioAnterior);
					$varUsuarioAtual 	= new Usuario($_SESSION['user_id']);
					$varFormulario 	= new Formulario($this->form_id);
					$varAgendamento = new Agenda($varFormulario->agendamento);
					$varProjeto		= new Projeto($varAgendamento->projeto);

					//Enviar email
					$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa ClÃ­nica - SPC", $varUsuarioAtual->email, $varUsuarioAtual->nome, 1);
					$message = utf8_encode(file_get_contents('../modelos/modelo_alteracao_campo.html'));
					$message = str_replace('%projeto%', utf8_encode($varProjeto->nome),$message);
					$message = str_replace('%tipo%', utf8_encode("[".$varProjeto->tipo."]"),$message);
					$message = str_replace('%agendamento%', utf8_encode($varAgendamento->nro_visita),$message);
					$message = str_replace('%formulario%', utf8_encode($varFormulario->classFormulario->texto_crf),$message);
					$message = str_replace('%campo%', utf8_encode($this->classTipoCampo->texto),$message);
					$message = str_replace('%data_hora%', date("d/m/Y H:i"),$message);
					$message = str_replace('%usuario_anterior%', utf8_encode($varUsuario->nome),$message);
					$message = str_replace('%usuario%', utf8_encode($_SESSION['user_nome']),$message);
					$message = str_replace('%valor_anterior%', utf8_encode($this->valorAnterior),$message);
					$message = str_replace('%valor_novo%', $this->valor,$message);
					$message = str_replace('%justificativa%', utf8_encode($this->motivoAlteracao),$message);

					//$varEmail->enviar("AlteraÃ§Ã£o de campo", $message);
				}*/
			}
			$conexao->pesquisa($query);
		}
	}