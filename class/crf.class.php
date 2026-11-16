<?php
	include_once __DIR__."/../seguranca.php";
	include_once __DIR__."/../funcoes.php";
	include_once __DIR__."/log.class.php";
	include_once __DIR__."/usuario.class.php";
	include_once __DIR__."/participante.class.php";

	const TIPOS_A_IGNORAR = array("'divisao_mais'", "'divisao'", "'fim_agrupamento'", "'modal'", "'escondido'");

	function retornaValorNome($nomeCampo, $arrayCampos){
		foreach($arrayCampos as $campo){
			if($campo['nome']==$nomeCampo){
				return $campo['valor'];
			}
		}
	}

	class Campo{
		public $id;
		public $form_id;
		public $campo_id;
		public $valor;
		public $valorAnterior;
		public $valor_dupla_digitacao;
		public $monitoria_status;
		public $erro_status;
		public $log_usuario;
		public $log_usuario_dupla;
		public $ordem;
		public $qtd_json;
		public $TipoCampo;
		public $alterado;
		public $MotivoAlteracao;
		public $monitorado_dm;
		public $nr;



		public function __construct($id){
			if($id>0){
				$conexao = new Conexao();
				$query = "select * from crf_entrada_campos where id = " . $id;
				$resCampo = $conexao->pesquisa($query);
				$rowCampo = mysqli_fetch_assoc($resCampo);

				$this->id					 	= $rowCampo['id'];
				$this->form_id 					= $rowCampo['form_id'];
				$this->campo_id 				= $rowCampo['campo_id'];
				$this->TipoCampo				= new TipoCampo($rowCampo['campo_id']);
				$this->alterado					= $rowCampo['alterado'];
				$this->valor 					= $rowCampo['valor'];
				$this->valorAnterior			= $rowCampo['valor'];
				$this->valor_dupla_digitacao 	= $rowCampo['valor_dupla_digitacao'];
				$this->monitoria_status 		= $rowCampo['monitoria_status'];
				$this->erro_status 				= $rowCampo['erro_status'];
				$this->log_usuario 				= $rowCampo['log_usuario'];
				$this->log_usuario_dupla 		= $rowCampo['log_usuario_dupla'];
				$this->MotivoAlteracao 			= $rowCampo['motivo_alteracao'];
				$this->ordem 					= $rowCampo['ordem'];
				$this->qtd_json 				= $rowCampo['qtd_json'];
				$this->monitorado_dm			= $rowCampo['monitorado_dm'];
				$this->nr						= $rowCampo['nr'];
			}
		}
		public function monitora(){
			$conexao = new Conexao();
			$query = "update crf_entrada_campos set
						monitoria_status = '".$this->monitoria_status."',
						log_usuario = '".$_SESSION['user_id']."'
							where id = " . $this->id;
			$conexao->pesquisa($query);
		}
		public function monitoraDM(){
			$conexao = new Conexao();
			$query = "update crf_entrada_campos set
						monitorado_dm = '".$this->monitorado_dm."',
						log_usuario = '".$_SESSION['user_id']."'
							where id = " . $this->id;
			$conexao->pesquisa($query);
			echo $query;
		}
		public function salvar(){
			$conexao = new Conexao();
			$query = "";
			if(($this->valor!=$this->valorAnterior)&&($this->alterado==0)){	//O campo nunca tinha sido alterado, era virgem
				$query = "update crf_entrada_campos set
							valor = '".utf8_decode($this->valor)."',
							monitoria_status = '".$this->monitoria_status."',
							erro_status = '".$this->erro_status."',
							alterado = 1,
							log_usuario = '".$this->log_usuario."',
							log_usuario_dupla = '".$this->log_usuario_dupla."'
							where id = " . $this->id;
			}
			//echo "This->valor=".$this->valor."*ValorAnterior=".$this->valorAnterior;
			if(( $this->valor!==$this->valorAnterior)&&($this->alterado==1)){ //O campo já tinha sido alterado, não era mais virgem
				$query = "update crf_entrada_campos set
							valor = '".utf8_decode($this->valor)."',
							motivo_alteracao = '".$this->motivoAlteracao."',
							monitoria_status = '".$this->monitoria_status."',
							erro_status = '".$this->erro_status."',
							alterado = 1,
							log_usuario = '".$this->log_usuario."',
							log_usuario_dupla = '".$this->log_usuario_dupla."'
							where id = " . $this->id;

			}
			if($query != ""){ $conexao->pesquisa($query); }
			$varFormulario = new Formulario($this->form_id);
			$varFormulario->registraDigitado();
		}
		function getQueriesAbertas(){
			$conexao = new Conexao();
			$query = "select *
							from crf_entrada_campos_query
								where campo_id = ".$this->id."
								and status <> 3";
			$resQtd = $conexao->pesquisa($query);
			return mysqli_num_rows($resQtd);
		}
		function getQueriesSemResposta(){
			$conexao = new Conexao();
			$query = "select *
							from crf_entrada_campos_query
								where campo_id = ".$this->id."
								and status = 1";
			$resQtd = $conexao->pesquisa($query);
			return mysqli_num_rows($resQtd);
		}
		function getTemQuery(){
			if($this->id>0){
				$conexao = new Conexao();
				$query = "select *
								from crf_entrada_campos_query
									where campo_id = ".$this->id;
				$resQtd = $conexao->pesquisa($query);
				if(mysqli_num_rows($resQtd)>0){
					return mysqli_num_rows($resQtd);
				}else{
					return 0;
				}
			}
		}
		function getQtdComentarios(){
			$conexao = new Conexao();
			$query = "select *
							from crf_entrada_campos_comentarios
								where campo_id = ".$this->id;
			$resQtd = $conexao->pesquisa($query);
			return mysqli_num_rows($resQtd);
		}
	}
	class TipoCampo{
		public $id;
		public $nome;
		public $texto;
		public $texto_en;
		public $texto_es;
		public $ordem;
		public $tipo;
		public $obrigatorio;
		public $numero_min;
		public $numero_max;
		public $qtd_decimal;
		public $selecao_valores;
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
		public $data_maior_hoje;
		public $data_dezoito_anos;
		public $tipo_campo;
		public $condicional_vermelho;
		public $condicional_alerta;
		public $condicional_regra_alerta;
		public $select_falso;
		public $acao_descricao_causa;
		public $pagina_altura;
		public $padrao;
		public $registro_valido;
		public $log_usuario;
		public $envia_email;
		public $valor_email;
		public $valor_email_anterior;
		public $emails;
		public $esconde_randomizado;
		public $apos_randomizado;
		public $senha;
		public $quem_ve;
		public $sexo_habilita;
		public $tem_referencia;
		public $modal_pagina;
		public $tem_sdv;
		public $dm_obrigatorio;
		public $dm_depende;
		public $e_horario;
		public $regra_texto2;
		public $texto2;
		public $qtd_decimal_min;

		public function __construct($id){
			if($id>0){
				$conexao = new Conexao();
				$query 		= "select * from crf_forms_campos where id = " . $id;
				$resCampo 	= $conexao->pesquisa($query);
				$rowCampo	= mysqli_fetch_assoc($resCampo);

				$this->id 						= $id;
				$this->nome 					= $rowCampo['nome'];
				$this->texto 					= $rowCampo['texto'];
				$this->texto_en 				= $rowCampo['texto_en'];
				$this->texto_es 				= $rowCampo['texto_es'];
				$this->ordem 					= $rowCampo['ordem'];
				$this->tipo 					= $rowCampo['tipo'];
				$this->obrigatorio 				= $rowCampo['obrigatorio'];
				$this->numero_min				= $rowCampo['numero_min'];
				$this->numero_max 				= $rowCampo['numero_max'];
				$this->qtd_decimal 				= $rowCampo['qtd_decimal'];
				$this->selecao_valores 			= $rowCampo['selecao_valores'];
				$this->formulario 				= $rowCampo['formulario'];
				$this->formato 					= $rowCampo['formato'];
				$this->calculo_tipo 			= $rowCampo['calculo_tipo'];
				$this->calculo_campo1 			= $rowCampo['calculo_campo1'];
				$this->calculo_campo2 			= $rowCampo['calculo_campo2'];
				$this->legenda 					= $rowCampo['legenda'];
				$this->condicional 				= $rowCampo['condicional'];
				$this->botao_acao 				= $rowCampo['botao_acao'];
				$this->botao_enabled 			= $rowCampo['botao_enabled'];
				$this->pagina 					= $rowCampo['pagina'];
				$this->termo 					= $rowCampo['termo'];
				$this->data_menor_termo 		= $rowCampo['data_menor_termo'];
				$this->data_menor_termo_alerta 	= $rowCampo['data_menor_termo_alerta'];
				$this->so_numero 				= $rowCampo['so_numero'];
				$this->so_letra 				= $rowCampo['so_letra'];
				$this->so_leitura 				= $rowCampo['so_leitura'];
				$this->data_menor_hoje 			= $rowCampo['data_menor_hoje'];
				$this->data_maior_hoje 			= $rowCampo['data_maior_hoje'];
				$this->data_dezoito_anos 		= $rowCampo['data_dezoito_anos'];
				$this->tipo_campo 				= $rowCampo['tipo_campo'];
				$this->condicional_vermelho 	= $rowCampo['condicional_vermelho'];
				$this->condicional_alerta 		= $rowCampo['condicional_alerta'];
				$this->condicional_regra_alerta = $rowCampo['condicional_regra_alerta'];
				$this->select_falso 			= $rowCampo['select_falso'];
				$this->acao_descricao_causa 	= $rowCampo['acao_descricao_causa'];
				$this->pagina_altura			= $rowCampo['pagina_altura'];
				$this->padrao 					= $rowCampo['padrao'];
				$this->registro_valido 			= $rowCampo['registro_valido'];
				$this->log_usuario 				= $rowCampo['log_usuario'];
				$this->envia_email 				= $rowCampo['envia_email'];
				$this->valor_email 				= $rowCampo['valor_email'];
				$this->valor_email_anterior		= $rowCampo['valor_email_anterior'];
				$this->emails 					= $rowCampo['emails'];
				$this->esconde_randomizado 		= $rowCampo['esconde_randomizado'];
				$this->apos_randomizado 		= $rowCampo['apos_randomizado'];
				$this->senha	 				= $rowCampo['senha'];
				$this->quem_ve	 				= $rowCampo['quem_ve'];
				$this->sexo_habilita			= $rowCampo['sexo_habilita'];
				$this->tem_referencia			= $rowCampo['tem_referencia'];
				$this->modal_pagina				= $rowCampo['modal_pagina'];
				$this->e_horario				= $rowCampo['e_horario'];
				$this->tem_sdv					= $rowCampo['tem_sdv'];
				$this->dm_obrigatorio			= $rowCampo['dm_obrigatorio'];
				$this->dm_depende				= $rowCampo['dm_depende'];
				$this->regra_texto2				= $rowCampo['regra_texto2'];
				$this->texto2					= $rowCampo['texto2'];
				$this->qtd_decimal_min			= $rowCampo['qtd_decimal_min'];

			}
		}
		public function veCampo($tipoUsuario){
			$temp = false;
			if($this->quem_ve!=""){
				$quebra = explode(",", $this->quem_ve);
				foreach($quebra as $tipo){
					if($tipoUsuario==trim($tipo)){ return true;}
				}
			}else{
				return true;
			}
			return $temp;
		}
		public function naoVeCampo($tipoUsuario, $especifico){
			$temp = false;
			if($this->quem_ve!=""){
				$quebra = explode(",", $this->quem_ve);
				foreach($quebra as $tipo){
					if($tipoUsuario==trim($tipo)){ return true;}
				}
			}else{
				if($especifico==1){
					return false;
				}
				return true;
			}
			return $temp;
		}
	}
	class Formulario{
		public $id;
		public $paciente;
		public $form_id;
		public $centro;
		public $projeto;
		public $digitado;
		public $completo;
		public $log_usuario;
		public $tipoForm;
		public $ordem;


		public function __construct($id){
			if($id>0){
				$conexao = new Conexao();
				$query  = "select * from crf_entrada_forms where id = " .$id;
				$resForm = $conexao->pesquisa($query);
				$rowForm = mysqli_fetch_assoc($resForm);

				$this->id 			= $id;
				$this->paciente	 	= $rowForm['paciente'];
				$this->form_id 		= $rowForm['form_id'];
				$this->centro 		= $rowForm['centro'];
				$this->projeto 		= $rowForm['projeto'];
				$this->digitado 	= $rowForm['digitado'];
				$this->completo 	= $rowForm['completo'];
				$this->log_usuario  = $rowForm['log_usuario'];
				$this->tipoForm		= new TipoFormulario($this->form_id);
				$this->ordem		= $rowForm['ordem'];

			}
		}
		public function getQueriesAbertas(){
			$varConexao = new Conexao();
			$query = "SELECT
						count(*) as qtd
							FROM crf_entrada_campos_query
								inner join crf_entrada_campos on crf_entrada_campos_query.campo_id = crf_entrada_campos.id
								inner join crf_entrada_forms on crf_entrada_campos.form_id = crf_entrada_forms.id
								where crf_entrada_campos_query.status <> 3
									and crf_entrada_forms.id = ". $this->id;
			$resQtd = $varConexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			return $rowQtd['qtd'];
		}
		public function getTemDigitado(){
			$varConexao = new Conexao();
			$query = "select
						count(*) as qtd
							from crf_entrada_campos
								where form_id = ".$this->id."
									and valor <> ''";
			$resQtd = $varConexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp = 0;}
			return $temp;
		}
		public function getStatusFormulario(){
			//Retorna o status de monitoria do formulario
			//0 = N�o monitorado
			//1 = Parcialmente monitorado
			//2 = Totalmente monitorado
			//3 = Formul�rio virgem
			//4 = Branco

			/*
				0 = vermelho
				1 = amarelo
				2 = verde
				3 = cinza
				4 = Branco
			*/


			//return "Dig->" . $this->getTemDigitado() . " - QtdCamposComSDV->" . $this->getQtdCamposComSDV() . " - QtdNR->" . $this->getQtdNR() . " - QtdCamposNoFormulario->" . $this->getQtdCamposNoFormulario() . " - QtdCamposSemSDV->" . $this->getQtdCamposSemSDV() . " - QueriesAbertas->" . $this->getQueriesAbertas();


			//VERDE - Todos os campos est�o preenchidos e todos com SDV
			if( ($this->getTemDigitado()!=0)&&($this->getQueriesAbertas()==0)&&($this->getQtdCamposComSDV()==$this->getQtdCamposNoFormulario()) ){
				return 2;
			}
			if($this->getQueriesAbertas()>0){
				return 0;
			}

			//BRANCO - Todos os campos est�o vazios
			if( ($this->getTemDigitado()==0)&&($this->getQueriesAbertas()==0)&&($this->getQtdCamposComSDV()==0)&&($this->getQtdNR()==0) ){
				return 4;
			}
			//CINZA - AO MENOS UM CAMPO SEM PREENCHIMENTO (SOMENTE)
			if( ($this->getTemDigitado()!=0)&&($this->getQueriesAbertas()==0)&&($this->getQtdCamposComSDV()==0)&&($this->getQtdCamposVazioIgnorandoSDV()>0) ){
				return 3;
			}
			//return "CamposnoFormulario->" . $this->getQtdCamposNoFormulario() . " - CamposSemSDV->" . $this->getQtdCamposSemSDV() . " - VazioIgnorandoSDV->" . $this->getQtdCamposVazioIgnorandoSDV();
			//Amarelo - Todos os campos sem SDV e/ou todos os campos preenchidos
			if (($this->getQtdCamposNoFormulario() == $this->getQtdCamposSemSDV())&&($this->getQtdCamposVazioIgnorandoSDV()==0)){
				return 1;
			}


			return 3;


		}
		public function getQtdNR(){
			$varConexao = new Conexao();
			$query = "select
						count(*) as qtd
							from crf_entrada_campos
								inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
								where
									crf_forms_campos.tipo not in (".implode(",", TIPOS_A_IGNORAR).")
									and crf_entrada_campos.form_id = ".$this->id."
									and crf_entrada_campos.nr = 1";
			$resQtd = $varConexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$varQtd = $rowQtd['qtd'];
			if($varQtd==""){$varQtd = 0;}
			return $varQtd;
		}
		public function eEAG($campoEAG,$valorEAG){
			$varConexao = new Conexao();
			$query		= "select valor from crf_entrada_campos where form_id = " . $this->id . " and campo_id = " . $campoEAG;
			$resEAG		= $varConexao->pesquisa($query);
			if(mysqli_num_rows($resEAG)>0){
				$rowEAG		= mysqli_fetch_assoc($resEAG);
				if($rowEAG['valor']==$valorEAG){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		public function getDataEntryNomes(){
			$varConexao = new Conexao();
			$query = "select
						log_alteracoes.usuario_nome,  max(log_alteracoes.data_hora_lis) as data_hora_lis
							from crf_entrada_forms
								inner join crf_entrada_campos on crf_entrada_forms.id = crf_entrada_campos.form_id
								inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
								left join log_alteracoes on log_alteracoes.tabela = 'crf_entrada_campos' and log_alteracoes.id_tabela = crf_entrada_campos.id
									and log_alteracoes.campo = '1DIGITACAO'
								where
									crf_forms_campos.tipo not in (".implode(",", TIPOS_A_IGNORAR).")
								and crf_entrada_forms.id = " . $this->id . "
									and usuario_nome <> ''
									and usuario_nome <> 'Luis Eduardo Bueno'
									and log_alteracoes.acao = 'A'
									group by log_alteracoes.usuario_nome";
			//echo $query;
			$resNomes = $varConexao->pesquisa($query);
			if(mysqli_num_rows($resNomes)==0){return "";}else{
				$temp = "";
				$qtd  = 1;
				while($rowNomes = mysqli_fetch_assoc($resNomes)){
					$tempLis = $rowNomes['data_hora_lis'];
					$data = substr($tempLis,0,8);
					$hora = substr($tempLis,8,4);

					$data = substr($data,6,2) . "/" . substr($data,4,2). "/". substr($data,0,4);
					$hora = substr($hora, 0,2).":".substr($hora,2,2);

					$tempLis = $data . " - " . $hora;

					if($qtd==1){
						$temp = $tempLis . " - " . $rowNomes['usuario_nome'];
					}else{
						$temp .= "<br>" . $tempLis . " - " . $rowNomes['usuario_nome'];
					}
					$qtd++;
				}
			}
			return $temp;
		}
		public function getSdvPrimeiroSDV(){
			$varConexao = new Conexao();
			$query = "select
						min(log_alteracoes.data_hora_lis) as data_hora_lis
							from crf_entrada_forms
								inner join crf_entrada_campos on crf_entrada_forms.id = crf_entrada_campos.form_id
								inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
								left join log_alteracoes on log_alteracoes.tabela = 'crf_entrada_campos' and log_alteracoes.id_tabela = crf_entrada_campos.id
									and log_alteracoes.campo = 'MONITORADO'
								where
									crf_forms_campos.tipo not in (".implode(",", TIPOS_A_IGNORAR).")
								and crf_entrada_forms.id = " . $this->id . "
									and usuario_nome <> ''
									";
			$resNomes = $varConexao->pesquisa($query);
			if(mysqli_num_rows($resNomes)==0){return "";}else{
				$temp = "";
				$rowNomes = mysqli_fetch_assoc($resNomes);
					$tempLis = $rowNomes['data_hora_lis'];
					$data = substr($tempLis,0,8);
					$temp = $data;

			}
			if($temp=="//"){$temp="";}
			return $temp;
		}
		public function getSdvPrimeiro(){
			$varConexao = new Conexao();
			$query = "select
						min(log_alteracoes.data_hora_lis) as data_hora_lis
							from crf_entrada_forms
								inner join crf_entrada_campos on crf_entrada_forms.id = crf_entrada_campos.form_id
								inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
								left join log_alteracoes on log_alteracoes.tabela = 'crf_entrada_campos' and log_alteracoes.id_tabela = crf_entrada_campos.id
									and log_alteracoes.campo = '1DIGITACAO'
								where
									crf_forms_campos.tipo not in (".implode(",", TIPOS_A_IGNORAR).")
								and crf_entrada_forms.id = " . $this->id . "
									and usuario_nome <> ''
									";
			$resNomes = $varConexao->pesquisa($query);
			if(mysqli_num_rows($resNomes)==0){return "";}else{
				$temp = "";
				$rowNomes = mysqli_fetch_assoc($resNomes);
					$data = $rowNomes['data_hora_lis'];
					$temp = $data;
			}
			if($temp=="//"){$temp="";}
			return $temp;
		}
		public function getUltimaAtualizacaoSemMonitor(){
			$varConexao = new Conexao();
			$query = "select
								log_alteracoes.usuario,
								log_alteracoes.data_hora_lis as ult
							from crf_entrada_campos
								left join log_alteracoes on crf_entrada_campos.id = log_alteracoes.id_tabela
									and log_alteracoes.tabela = 'crf_entrada_campos'
								where crf_entrada_campos.form_id = ".$this->id." and campo <> 'MONITORADO'
									order by log_alteracoes.data_hora_lis desc
									limit 1";
			$res = $varConexao->pesquisa($query);
			$row = mysqli_fetch_assoc($res);
			$temp = $row['ult'];
			if($temp!=""){
				$varUsuario = new Usuario($row['usuario']);
				//$temp = substr($temp,6,2) ."/" . substr($temp,4,2) ."/" . substr($temp,0,4) . " " . substr($temp,8,2).":".substr($temp,10,2);
				$temp = $varUsuario->nome. "<br>[" . $varUsuario->getFuncao() . "]<br>" . substr($temp,6,2) ."/" . substr($temp,4,2) ."/" . substr($temp,0,4) . " " . substr($temp,8,2) . ":" . substr($temp,10,2);
			}

			return $temp;
		}
		public function getPrimeiraAtualizacaoSemMonitor(){
			$varConexao = new Conexao();
			$query = "select
								log_alteracoes.usuario,
								log_alteracoes.data_hora as ult
							from crf_entrada_campos
								left join log_alteracoes on crf_entrada_campos.id = log_alteracoes.id_tabela
									and log_alteracoes.tabela = 'crf_entrada_campos'
								where crf_entrada_campos.form_id = ".$this->id." and campo <> 'MONITORADO'
									order by log_alteracoes.data_hora
									limit 1";
			$res = $varConexao->pesquisa($query);
			//return $query;
			$row = mysqli_fetch_assoc($res);
			$temp = $row['ult'];
			if($temp!=""){
				$varUsuario = new Usuario($row['usuario']);
				//$temp = substr($temp,6,2) ."/" . substr($temp,4,2) ."/" . substr($temp,0,4) . " " . substr($temp,8,2).":".substr($temp,10,2);
				//$temp = $varUsuario->nome. "<br>[" . $varUsuario->getFuncao() . "]<br>" . substr($temp,6,2) ."/" . substr($temp,4,2) ."/" . substr($temp,0,4) . " " . substr($temp,8,2) . ":" . substr($temp,10,2);
				$temp = PostgresToData(substr($temp,0,10));
			}

			//return $temp . " - " . $query;
			return $temp;
		}
		public function getUltimaAtualizacao(){
			$varConexao = new Conexao();
			$query = "select
								log_alteracoes.data_hora_lis as ult
							from crf_entrada_campos
								left join log_alteracoes on crf_entrada_campos.id = log_alteracoes.id_tabela
									and log_alteracoes.tabela = 'crf_entrada_campos'
								where crf_entrada_campos.form_id = ".$this->id."
									order by log_alteracoes.data_hora_lis desc
									limit 1";
			$res = $varConexao->pesquisa($query);
			$row = mysqli_fetch_assoc($res);
			$temp = $row['ult'];
			if($temp!=""){
				//$temp = substr($temp,6,2) ."/" . substr($temp,4,2) ."/" . substr($temp,0,4) . " " . substr($temp,8,2).":".substr($temp,10,2);
				$temp = substr($temp,6,2) ."/" . substr($temp,4,2) ."/" . substr($temp,0,4);
			}
			return $temp;
		}
		public function getValor($nomeCampo){
			$conexao = new Conexao();
			$query = "SELECT crf_entrada_forms.id,
						crf_forms_campos.nome,
						crf_entrada_campos.valor
						FROM crf_entrada_forms
							inner join crf_entrada_campos on crf_entrada_forms.id = crf_entrada_campos.form_id
							inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
							where crf_entrada_forms.id = ".$this->id."
								and crf_forms_campos.nome = '".$nomeCampo."'
			";
			//return $query;
			$resCampo = $conexao->pesquisa($query);
			if(mysqli_num_rows($resCampo)>0){
				$rowCampo = mysqli_fetch_assoc($resCampo);
				return $rowCampo['valor'];
			}else{
				return "";
			}
		}
		function salvar(){
			$query = "update crf_entrada_forms
						set digitado = '".$this->digitado."',
							completo = '".$this->completo."',
							ordem	 = '".$this->ordem."',
							log_usuario = '".$_SESSION['user_id']."'
								where id = " . $this->id;
			$conexao = new Conexao();
			$conexao->pesquisa($query);
		}
		function registraDigitado(){
			//Altera a flag digitado para 1
			$this->digitado = 1;
			$this->salvar();
			$this->verificaCompleto();
		}
		function verificaCompleto(){
			/*
				Aqui verifico se todos os campos já estao preenchidos ou monitorados
				Se sim, flago o formulario como completo
			*/
			$conexao = new Conexao();
			$query = "SELECT count(*) as qtd
							FROM crf_entrada_campos
							where form_id = ".$this->id."
								and ( (valor='')and(monitoria_status=0) );";
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			if($rowQtd['qtd']==0){	//Não tem mais nada em aberto
				$this->completo = 1;
				$this->salvar();
			}
		}
		public function getNomeFormulario(){
			$volta = "";
			if($_SESSION['lang']=='p'){$volta = $this->tipoForm->texto_crf;}
			if($_SESSION['lang']=='i'){$volta = $this->tipoForm->texto_crf_en;}
			if($_SESSION['lang']=='e'){$volta = $this->tipoForm->texto_crf_es;}
			if($volta==""){$volta = $this->tipoForm->texto_crf;}
			if($this->ordem>0){$volta = str_replace("#X", "#".$this->ordem, $volta);}
			return $volta;
		}
		public function getStatusFormularioImagem(){
			$statusMonitoria = $this->getStatusFormulario();
			switch($statusMonitoria){
				case 0:
					return "vermelho";
				case 1:
					return "amarelo";
				case 2:
					return "verde";
				case 3:
					return "cinza";
				case 4:
					return "branco";
				default:
					return "NAO_ACHOU_STATUS".$statusMonitoria;
			}

		}
		public function naoVeFormulario($tipoUsuario){
			$temp = false;
			if($this->tipoForm->nao_ve!=""){
				$quebra = explode(",", $this->tipoForm->nao_ve);
				foreach($quebra as $tipo){
					if($tipoUsuario==trim($tipo)){ return false;}
				}
				return true;
			}else{
				return true;
			}
			//return $temp;
		}

		public function exibeNode($formularioClicado){
			/*
				Aqui trato linha a linha cada formulário que está no CRF
				20250411 - Vou verificar se o campo que ele depende está preenchido. Se estiver, então vou exibir o formulário. Se não estiver, vou deixar com cor diferente (desabilitado) e sem o onclick.
			*/
			//Pegar o paciente
			$valorDoCampo = "";
			$onclick = "#";
			if($this->tipoForm->campo_depende!=""){
				//Esse formulário depende de um campo específico. Vou verificar o valor
				$varParticipante = new Participante($this->centro, $this->paciente);
				$valorDoCampo = $varParticipante->buscaValor(trim($this->tipoForm->campo_depende));

				if($valorDoCampo==""){
					//O campo não está preenchido. Vou desabilitar o formulário
					$classForm = "lista_formularios_desabilitado";
					$onclick = "javascript:alert('Esse formulário ainda não está habilitado. Por favor, preencha a visita anterior para habilitar esse formulário.');";
					//$onclick = $this->tipoForm->campo_depende;
				}else{
					if($this->tipoForm->campo_depende_valor!=""){
						if(trim($valorDoCampo)!=trim($this->tipoForm->campo_depende_valor)){
							//O valor do campo não é o valor esperado. Vou desabilitar o formul�rio
							$classForm = "lista_formularios_desabilitado";
							$onclick = "javascript:alert('Esse formulário ainda não está habilitado. Por favor, preencha a visita anterior para habilitar esse formulário.');";
						}else{
							$classForm = "lista_formularios";
							$onclick = "javascript:mudaForm('".$this->id."', true); ";
						}
					}else{

						$classForm = "lista_formularios";
						$onclick = "javascript:mudaForm('".$this->id."', true); ";

					}
				}
			}else{
				$classForm = "lista_formularios";
				$onclick = "javascript:mudaForm('".$this->id."', true); ";
			}
			?>
				<tr class="<?=$classForm?>"
					<?php
						switch($this->tipoForm->acao){
							case "MULTIPAGINAS":
								echo ' style="font-family: Abel;font-size: 22px;background-color: #f4f4f4 !important"';
								break;
							case "CAMPOS":
								echo ' onclick="'.$onclick.'"';
								break;
							case "DIVISAO":
								echo ' style="font-family: Abel;font-size: 22px;background-color: #f4f4f4 !important"';
								break;
						}
					?>
					id="listaForm_<?=$this->id;?>"
					name="listaForm_<?=$this->id;?>">
					<td class="lista_formularios">
						<a name="ANCORA_<?=$this->id;?>"></a>
						<div name="formItem_<?=$this->id;?>">
							<?php
							$quemVe = array(9999); //Aqui � o icone de status
							if(in_array($_SESSION['user_tipo'], $quemVe)){
								//Pegar o status de monitoria do formulario
								$statusMonitoria = $this->getStatusFormulario();
								//echo $statusMonitoria . "-";
								if($statusMonitoria==0){
									echo '<span id="status_form_'.$this->id.'"><img src="imagens/status_vermelho.png" style="width: 18px;height: 18px;"></span> ';
								}
								if($statusMonitoria==1){
									echo '<span id="status_form_'.$this->id.'"><img src="imagens/status_amarelo.png" style="width: 18px;height: 18px;"></span> ';
								}
								if($statusMonitoria==2){
									echo '<span id="status_form_'.$this->id.'"><img src="imagens/status_verde.png" style="width: 18px;height: 18px;"></span> ';
								}
								if($statusMonitoria==3){
									echo '<span id="status_form_'.$this->id.'"><img src="imagens/status_cinza.png" style="width: 18px;height: 18px;"></span> ';
								}
								if($statusMonitoria==4){
									echo '<span id="status_form_'.$this->id.'"><img src="imagens/status_branco.png" style="width: 18px;height: 18px;"></span> ';
								}
							}
							?>
							<?php
								if( ($this->tipoForm->acao=="CAMPOS")||($this->tipoForm->acao=="RANDOMIZACAO") ){
									if($this->digitado=="0"){ echo '<span><i class="fa fa-square-o" title="Ainda não digitado"></i></span> '; }
									if($this->digitado=="1"){ echo '<span><i class="fa fa-pencil-square-o" title="Já digitado"></i></span> '; }
								}else{
									if($this->tipoForm->acao!="DIVISAO"){
										echo '<span><i class="fa fa-caret-square-o-down" title="Formulários"></i></span> ';
									}
								}
								if($this->id==$formularioClicado){ echo "<b>"; }
								if($this->tipoForm->acao=="DIVISAO"){ echo "<b>";}
								//echo $this->tipoForm->ordem." - ".$this->getNomeFormulario();
								echo $this->getNomeFormulario();
								if($this->id==$formularioClicado){ echo "</b>"; }
								if($this->tipoForm->acao=="DIVISAO"){ echo "</b>";}
							?>
						</div>
					</td>
					<td>
						<?php
							if($this->tipoForm->acao=="MULTIPAGINAS"){
								//Quem NAO vai poder clicar no mais. Isso esta "manual" mas tem que ser automatico
								$quem_nao = array(2,6,8,9,13,14,17,18,19,20,21,23,24,25);
								if( (!in_array($_SESSION['user_tipo'], $quem_nao, false)) && ($this->tipoForm->id=="54") ){
									if(($this->tipoForm->campo_depende=="")||($valorDoCampo!="")){
									?>
									 	<a href="javascript:javascript:addSubPage(<?=$this->tipoForm->id;?>);" class="btn btn-xs default"><i class="fa fa-plus"></i></a>
									<?php
									}
								}else{
									$quem_ve = array(1,2,3,7,17);
									if(in_array($_SESSION['user_tipo'], $quem_ve, false)){
										?>
											<a href="javascript:javascript:addSubPage(<?=$this->tipoForm->id;?>);" class="btn btn-xs default"><i class="fa fa-plus"></i></a>
										<?php
									}
								}


							}
						?>
					</td>
				</tr>
			<?php
		}
		public function getSdvNomes(){
			$varConexao = new Conexao();
			$query = "select
							usu_usuario.nome,  max(log_alteracoes.data_hora) as data_hora_lis
							from crf_entrada_forms
								inner join crf_entrada_campos on crf_entrada_forms.id = crf_entrada_campos.form_id
								inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
								left join log_alteracoes on log_alteracoes.tabela = 'crf_entrada_campos' and log_alteracoes.id_tabela = crf_entrada_campos.id
									and log_alteracoes.campo = 'MONITORADO'
								inner join usu_usuario on log_alteracoes.usuario = usu_usuario.id
								where
									crf_forms_campos.tipo not in (".implode(",", TIPOS_A_IGNORAR).")
								and crf_entrada_forms.id = " . $this->id . "
									and usu_usuario.nome <> ''
									group by usu_usuario.nome";
			$resNomes = $varConexao->pesquisa($query);
			if(mysqli_num_rows($resNomes)==0){return "";}else{
				$temp = "";
				$qtd  = 1;
				while($rowNomes = mysqli_fetch_assoc($resNomes)){
					$tempLis = $rowNomes['data_hora_lis'];
					$tempLis = str_replace("-", "", $tempLis);
					$tempLis = str_replace(":", "", $tempLis);
					$tempLis = str_replace(" ", "", $tempLis);
					$data = substr($tempLis,0,8);
					$hora = substr($tempLis,8,4);

					$data = substr($data,6,2) . "/" . substr($data,4,2). "/". substr($data,0,4);
					$hora = substr($hora, 0,2).":".substr($hora,2,2);

					$tempLis = $data . " - " . $hora;

					if($qtd==1){
						$temp = $tempLis . " - " . $rowNomes['nome'];
					}else{
						$temp .= "<br>" . $tempLis . " - " . $rowNomes['nome'];
					}
					$qtd++;
				}
			}
			return $temp;
		}
		public function getSdvStatus(){
			$varConexao = new Conexao();
			$query = "select
						count(*) as qtd
							from crf_entrada_campos
								inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
								where
									crf_forms_campos.tipo not in (".implode(",", TIPOS_A_IGNORAR).")
									and crf_entrada_campos.form_id = ".$this->id."
									and crf_forms_campos.quem_ve = ''
									and crf_forms_campos.id not in (326, 327)
									and crf_entrada_campos.monitoria_status = 0
									and crf_forms_campos.tem_sdv = 1
									";
			//echo $query . " ";
			$resQtd = $varConexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$varQtd = $rowQtd['qtd'];
			if($varQtd==""){$varQtd = 0;}
			if($varQtd==0){
				return "Completamente monitorado";
			}else{
				if( ($this->getQtdMonitorados()!=$this->getQtdCamposNoFormulario()) ) {
					if($this->getQtdMonitorados()==0){
						return "N�o monitorado";
					}else{
						return "Parcialmente monitorado";
					}
				}
			}
		}
		public function getColorStatus(){
			$temp = $this->getSdvStatus();
			if($temp=="Completamente monitorado"){return "#defcee";}
			if($temp=="N�o monitorado"){return "#fcceca";}
			if($temp=="Parcialmente monitorado"){return "#fafcca";}
		}
		public function getQtdMonitorados(){
			$varConexao = new Conexao();
			$query = "select
						count(*) as qtd
							from crf_entrada_campos
								inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
								where
									crf_forms_campos.tipo not in (".implode(",", TIPOS_A_IGNORAR).")
									and crf_entrada_campos.form_id = ".$this->id."
									and crf_forms_campos.quem_ve = ''
									and crf_entrada_campos.monitoria_status = 1";
			$resQtd = $varConexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$varQtd = $rowQtd['qtd'];
			if($varQtd==""){$varQtd = 0;}
			return $varQtd;
		}
		public function getQtdCamposNoFormulario(){
			$varConexao = new Conexao();
			$query = "select
						count(*) as qtd
							from crf_entrada_campos
								inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
								where
									crf_forms_campos.tipo not in (".implode(",", TIPOS_A_IGNORAR).")
									and crf_entrada_campos.form_id = ".$this->id."
									";
			$resQtd = $varConexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$varQtd = $rowQtd['qtd'];
			if($varQtd==""){$varQtd = 0;}
			return $varQtd;
		}
		public function getQtdCamposSemSDV(){
			$varConexao = new Conexao();
			$query = "select
						count(*) as qtd
							from crf_entrada_campos
								inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
								where
									crf_forms_campos.tipo not in (".implode(",", TIPOS_A_IGNORAR).")
									and crf_entrada_campos.form_id = ".$this->id."
									and crf_entrada_campos.monitoria_status = 0
									";

			$resQtd = $varConexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$varQtd = $rowQtd['qtd'];
			if($varQtd==""){$varQtd = 0;}
			return $varQtd;
		}
		public function getQtdCamposComSDV(){
			$varConexao = new Conexao();
			$query = "select
						count(*) as qtd
							from crf_entrada_campos
								inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
								where
									crf_forms_campos.tipo not in (".implode(",", TIPOS_A_IGNORAR).")
									and crf_entrada_campos.form_id = ".$this->id."
									and crf_entrada_campos.monitoria_status = 1
									";

			$resQtd = $varConexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$varQtd = $rowQtd['qtd'];
			if($varQtd==""){$varQtd = 0;}
			return $varQtd;
		}
		public function getQtdCamposVazio(){
			$varConexao = new Conexao();
			$query = "select
						count(*) as qtd
							from crf_entrada_campos
								inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
								where
									crf_forms_campos.tipo not in (".implode(",", TIPOS_A_IGNORAR).")
									and crf_entrada_campos.form_id = ".$this->id."
									and ( (crf_entrada_campos.valor = '') and (monitoria_status = 0) )
									";
			//return $query;
			$resQtd = $varConexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$varQtd = $rowQtd['qtd'];
			if($varQtd==""){$varQtd = 0;}
			return $varQtd;
		}
		public function getQtdCamposVazioIgnorandoSDV(){
			$varConexao = new Conexao();
			$arrayCampos = array();
			/*$query = "select
						count(*) as qtd
							from crf_entrada_campos
								inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
								where
									crf_forms_campos.tipo not in (".implode(",", TIPOS_A_IGNORAR).")
									and crf_entrada_campos.form_id = ".$this->id."
									and ( (crf_entrada_campos.valor = '')  )
									";
			//return $query;
			$resQtd = $varConexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$varQtd = $rowQtd['qtd'];
			if($varQtd==""){$varQtd = 0;}
			return $varQtd;*/

			//Pegar todos os campos do formulario
			$query = "select
						crf_forms_campos.nome,
						crf_forms_campos.variavel,
						crf_forms_campos.tipo,
						crf_forms_campos.obrigatorio,
						crf_forms_campos.condicional,
						crf_entrada_campos.nr,
						crf_entrada_campos.valor
							from crf_entrada_campos
								inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
								where
									crf_forms_campos.tipo not in (".implode(",", TIPOS_A_IGNORAR).")
									and crf_entrada_campos.form_id = ".$this->id."

									";
			$resCampos = $varConexao->pesquisa($query);
			while($rowCampo = mysqli_fetch_assoc($resCampos)){
				$arrayCampos[] = array("nome" => $rowCampo['nome'], "variavel" => $rowCampo['variavel'], "tipo" => $rowCampo['tipo'], "condicional" => $rowCampo['condicional'], "valor" => $rowCampo['valor'], "nr" => $rowCampo['nr'], "obrigatorio" => $rowCampo['obrigatorio']);
			}
			$tempRetorno = 0;
			for($i=0; $i<count($arrayCampos); $i++){
				if($arrayCampos[$i]['nr']=="1"){continue; }
				if($arrayCampos[$i]['valor']!=""){continue; }
				if(trim($arrayCampos[$i]['condicional'])==""){
					if($arrayCampos[$i]['obrigatorio']!="on"){ continue; }
				}else{
					//Tem condicional. Aqui vou passar para uma fun��o externa para que eu possa fazer recursividade
					if($arrayCampos[$i]['obrigatorio']!="on"){ continue; }




					$campoAtualCondicional = $arrayCampos[$i]['condicional'];
					$campoAtualValor	   = $arrayCampos[$i]['valor'];

					//Verificar se � o EAG. Se for, rodo aqui e ignoro o resto
					$temp = $campoAtualCondicional;
					$temp = $temp.explode(";",$temp);

					if($temp[0]=="EAG"){
						//Fa�o aqui e dou um return
						//console.log("EAG");
						//Pegar os valores dos campos
						$varGravidade   = retornaValorNome("EA#_GRAVIDADE", $arrayCampos);
						$varEsperado    = retornaValorNome("EA#_ESPERADO", $arrayCampos);
						$varCausalidade = retornaValorNome("EA#_CAUSALIDADE", $arrayCampos);

						if(($varGravidade=="2 - EA Grave")&&($varEsperado=="2 - N�o")){
							if(($varCausalidade=="1 - Certa / Definida")||($varCausalidade=="2 - Prov�vel")||($varCausalidade=="3 - Poss�vel")){
								$tempRetorno++;
							}
						}
					}else{
						//Continuo a execu��o
						//tentar fazer o condicional aqui

						$itens = explode("|", $campoAtualCondicional);
						$itens = explode("&", $campoAtualCondicional);
						$qtdAnd = count($itens);

						if($qtdAnd>1){
							$itens = explode("&", $campoAtualCondicional);
							$temTrue = 0;
							foreach($itens as $item){
								$condicionalItem = $item;
								if(($condicionalItem!=null)&&($condicionalItem!="")){
									$condicional    = explode(";", $condicionalItem);
									$campoBuscaTipo = $condicional[0];
									$campoBuscaNome = $condicional[1];
									$campoBuscaCompa = $condicional[2];
									$campoBuscaValor = $condicional[3];
									$valorABuscar = retornaValorNome($campoBuscaNome, $arrayCampos);
									if($campoBuscaTipo=="IF"){
										if($campoBuscaCompa=="!="){
											if($campoBuscaValor!=$valorABuscar){
												$temTrue++;
											}
										}
										if($campoBuscaCompa=="="){
											if($campoBuscaValor==$valorABuscar){
												$temTrue++;
											}
										}
									}
								}
							};
							if($temTrue==$qtdAnd){
								$tempRetorno++;
							}
						}else{
							//Lidar com caso que n�o � AND
							$itens = explode("|", $campoAtualCondicional);
							foreach($itens as $item){
								$condicionalItem = $item;

								$condicional     = explode(";", $condicionalItem);
								$campoBuscaTipo  = $condicional[0];
								$campoBuscaNome  = $condicional[1];
								$campoBuscaCompa = $condicional[2];
								$campoBuscaValor = $condicional[3];
								//Loop em todos os campos ativos novamente

								$valorABuscar = retornaValorNome($campoBuscaNome, $arrayCampos);
								if($campoBuscaTipo=="IF"){
									if($campoBuscaCompa=="!="){
										if($campoBuscaValor!=$valorABuscar){
											$tempRetorno++;
										}
									}
									if($campoBuscaCompa=="="){
										if($campoBuscaValor==$valorABuscar){
											$tempRetorno++;
										}
									}
									if($campoBuscaCompa=="<>"){
										if($campoBuscaValor!=$valorABuscar){
											$tempRetorno++;
										}

									}
								}

							};
						}
					}
				}
			}
			return $tempRetorno;

		}

	}

	class NOVO_Formulario{
		public $id;
		public $paciente;
		public $form_id;
		public $centro;
		public $projeto;
		public $digitado;
		public $completo;
		public $log_usuario;
		public $tipoForm;
		public $ordem;

		public function __construct(){
		}
		public function carrega($id){
			if($id>0){
				$conexao = new Conexao();
				$query  = "select * from crf_entrada_forms where id = " .$id;
				$resForm = $conexao->pesquisa($query);
				$rowForm = mysqli_fetch_assoc($resForm);

				$this->id 			= $id;
				$this->paciente	 	= $rowForm['paciente'];
				$this->form_id 		= $rowForm['form_id'];
				$this->centro 		= $rowForm['centro'];
				$this->projeto 		= $rowForm['projeto'];
				$this->digitado 	= $rowForm['digitado'];
				$this->completo 	= $rowForm['completo'];
				$this->log_usuario  = $rowForm['log_usuario'];
				$this->tipoForm		= new TipoFormulario($this->form_id);
				$this->ordem		= $rowForm['ordem'];
			}
		}
		public function setaId($id){
			$this->id = $id;
		}
		public function getUltimaAtualizacaoSemMonitor(){
			$varConexao = new Conexao();
			$query = "select
								log_alteracoes.usuario,
								log_alteracoes.data_hora_lis as ult
							from crf_entrada_campos
								left join log_alteracoes on crf_entrada_campos.id = log_alteracoes.id_tabela
									and log_alteracoes.tabela = 'crf_entrada_campos'
								where crf_entrada_campos.form_id = ".$this->id."
									and campo <> 'MONITORADO'
									order by log_alteracoes.data_hora_lis desc
									limit 1";
			$res = $varConexao->pesquisa($query);
			$row = mysqli_fetch_assoc($res);
			$temp = $row['ult'];
			if($temp!=""){
				$varUsuario = new Usuario($row['usuario']);
				//$temp = substr($temp,6,2) ."/" . substr($temp,4,2) ."/" . substr($temp,0,4) . " " . substr($temp,8,2).":".substr($temp,10,2);
				$temp = $varUsuario->nome. "<br>[" . $varUsuario->getFuncao() . "]<br>" . substr($temp,6,2) ."/" . substr($temp,4,2) ."/" . substr($temp,0,4) . " " . substr($temp,8,2) . ":" . substr($temp,10,2);
			}

			return $temp;
		}
		public function getUltimaAtualizacao(){
			$varConexao = new Conexao();
			$query = "select
								log_alteracoes.data_hora_lis as ult
							from crf_entrada_campos
								left join log_alteracoes on crf_entrada_campos.id = log_alteracoes.id_tabela
									and log_alteracoes.tabela = 'crf_entrada_campos'
								where crf_entrada_campos.form_id = ".$this->id."
									order by log_alteracoes.data_hora_lis desc
									limit 1";
			$res = $varConexao->pesquisa($query);
			$row = mysqli_fetch_assoc($res);
			$temp = $row['ult'];
			if($temp!=""){
				//$temp = substr($temp,6,2) ."/" . substr($temp,4,2) ."/" . substr($temp,0,4) . " " . substr($temp,8,2).":".substr($temp,10,2);
				$temp = substr($temp,6,2) ."/" . substr($temp,4,2) ."/" . substr($temp,0,4);
			}
			return $temp;
		}
		public function getValor($nomeCampo){
			$conexao = new Conexao();
			$query = "SELECT crf_entrada_forms.id,
						crf_forms_campos.nome,
						crf_entrada_campos.valor
						FROM crf_entrada_forms
							inner join crf_entrada_campos on crf_entrada_forms.id = crf_entrada_campos.form_id
							inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
							where crf_entrada_forms.id = ".$this->id."
								and crf_forms_campos.nome = '".$nomeCampo."'
			";
			//return $query;
			$resCampo = $conexao->pesquisa($query);
			if(mysqli_num_rows($resCampo)>0){
				$rowCampo = mysqli_fetch_assoc($resCampo);
				return $rowCampo['valor'];
			}else{
				return "";
			}
		}
		function salvar(){
			$query = "update crf_entrada_forms
						set digitado = '".$this->digitado."',
							completo = '".$this->completo."',
							ordem	 = '".$this->ordem."',
							log_usuario = '".$_SESSION['user_id']."'
								where id = " . $this->id;
			$conexao = new Conexao();
			$conexao->pesquisa($query);
		}
		function registraDigitado(){
			//Altera a flag digitado para 1
			$this->digitado = 1;
			$this->salvar();
			$this->verificaCompleto();
		}
		function verificaCompleto(){
			/*
				Aqui verifico se todos os campos já estao preenchidos ou monitorados
				Se sim, flago o formulario como completo
			*/
			$conexao = new Conexao();
			$query = "SELECT count(*) as qtd
							FROM crf_entrada_campos
							where form_id = ".$this->id."
								and ( (valor='')and(monitoria_status=0) );";
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			if($rowQtd['qtd']==0){	//Não tem mais nada em aberto
				$this->completo = 1;
				$this->salvar();
			}
		}
		public function getNomeFormulario(){
			$volta = "";
			if($_SESSION['lang']=='p'){$volta = $this->tipoForm->texto_crf;}
			if($_SESSION['lang']=='i'){$volta = $this->tipoForm->texto_crf_en;}
			if($_SESSION['lang']=='e'){$volta = $this->tipoForm->texto_crf_es;}
			if($volta==""){$volta = $this->tipoForm->texto_crf;}
			if($this->ordem>0){$volta = str_replace("#X", "#".$this->ordem, $volta);}
			return $volta;
		}
		public function exibeNode($formularioClicado){
			?>
				<tr class="lista_formularios"
					<?php
					if($this->tipoForm->acao!="MULTIPAGINAS"){
						//Pego qual � o tipo de usu�rio.
						$varTipo = $_SESSION['user_tipo'];
						if($varTipo==9){
							echo ' onclick="javascript:mudaForm('.$this->id.', true, false); "';
						}else{
							echo ' onclick="javascript:mudaForm('.$this->id.', true, true); "';
						}
						echo ' style="cursor: pointer;font-family: Abel;font-size: 22px;"';

					}else{
						echo ' style="font-family: Abel;font-size: 22px;background-color: #f4f4f4 !important"';
					}
					?>
					id="listaForm_<?=$this->id;?>"
					name="listaForm_<?=$this->id;?>">
					<td class="lista_formularios">
						<a name="ANCORA_<?=$this->id;?>"></a>
						<div name="formItem_<?=$this->id;?>">
							<?php
								if( ($this->tipoForm->acao=="CAMPOS")||($this->tipoForm->acao=="RANDOMIZACAO") ){
									if($this->digitado=="0"){ echo '<span><i class="fa fa-square-o" title="Ainda n�o digitado"></i></span> '; }
									if($this->digitado=="1"){ echo '<span><i class="fa fa-pencil-square-o" title="J� digitado"></i></span> '; }
								}else{
									echo '<span><i class="fa fa-caret-square-o-down" title="Formulários"></i></span> ';
								}
								if($this->id==$formularioClicado){ echo "<b>"; }
								//echo $this->tipoForm->ordem." - ".$this->getNomeFormulario();
								echo $this->getNomeFormulario();
								if($this->id==$formularioClicado){ echo "</b>"; }
							?>
						</div>
					</td>
					<td>
						<?php
							if($this->tipoForm->acao=="MULTIPAGINAS"){
								//Quem NAO vai poder clicar no mais. Isso esta "manual" mas tem que ser automatico
								$quem_nao = array(2, 6, 7, 8, 9, 11, 12, 14, 15);
								if(!in_array($_SESSION['user_tipo'], $quem_nao, false)){
									?>
									 <a href="javascript:javascript:addSubPage(<?=$this->tipoForm->id;?>);" class="btn btn-xs default"><i class="fa fa-plus"></i></a>
									<?php
								}
							}
						?>
					</td>
				</tr>
			<?php
		}
	}
	class TipoFormulario{
		public $id;
		public $nome;
		public $texto_crf;
		public $texto_crf_en;
		public $texto_crf_es;
		public $ordem;
		public $descricao;
		public $tipo;
		public $projeto;
		public $acao;
		public $pagina;
		public $visita;
		public $botao_salvar;
		public $visivel;
		public $nao_ve;
		public $campo_depende;
		public $campo_depende_valor;

		public function __construct($id){
			if($id>0){
				$conexao = new Conexao();
				$query 		= "select * from crf_forms where id = " . $id;
				$resForm 	= $conexao->pesquisa($query);
				$rowForm	= mysqli_fetch_assoc($resForm);

				$this->id                  = $id;
				$this->nome                = $rowForm['nome'];
				$this->texto_crf           = $rowForm['texto_crf'];
				$this->texto_crf_en        = $rowForm['texto_crf_en'];
				$this->texto_crf_es        = $rowForm['texto_crf_es'];
				$this->ordem               = $rowForm['ordem'];
				$this->descricao           = $rowForm['descricao'];
				$this->tipo                = $rowForm['tipo'];
				$this->projeto             = $rowForm['projeto'];
				$this->acao                = $rowForm['acao'];
				$this->pagina              = $rowForm['pagina'];
				$this->visita              = $rowForm['visita'];
				$this->botao_salvar        = $rowForm['botao_salvar'];
				$this->visivel             = $rowForm['visivel'];
				$this->nao_ve              = $rowForm['nao_ve'];
				$this->campo_depende       = $rowForm['campo_depende'];
				$this->campo_depende_valor = $rowForm['campo_depende_valor'];
			}
		}
	}
	class CRF{
		public function __construct(){

		}
		function retornaValorPorCodigo($codigo, $centro, $paciente){
			$conexao = new Conexao();
			$query = "select
							valor
						from vw_valor_campo
							where projeto = " . $_SESSION['projeto'] . "
							and entrada_forms_centro = $centro
							and nro_triagem = $paciente
							and nome = '".$codigo."'";
			$resValor = $conexao->pesquisa($query);
			$rowValor = mysqli_fetch_assoc($resValor);
			return $rowValor['valor'];
		}
	}
	class CRFPaciente{
		public $id;
		public $nro_triagem;
		public $nro_participante;
		public $centro;
		public $nro_paciente;
		public $nro_paciente_formatado;
		public $paciente_pos_rand;
		public $log_usuario;
		public $data_hora;
		public $randomizado;
		public $randomizado_por;
		public $data_rand;
		public $hora_rand;
		public $nasc;
		public $sexo;
		public $iniciais;
		public $incluido_por;
		public $projeto;

		public $CRFs;

		public function __construct($projeto, $centro, $nro){
			$varConexao = new Conexao();
			$query = "select
							*
								from crf_paciente_pre_rand
									where
										projeto = $projeto
										and centro = " . $centro . "
										and nro_paciente = " . $nro;

			$resPac= $varConexao->pesquisa($query);
			if(mysqli_num_rows($resPac)==0){
				return 0;
			}else{
				$rowPac 						= mysqli_fetch_assoc($resPac);
				$this->id						= $rowPac['id'];
				$this->nro_triagem				= $rowPac['nro_triagem'];
				$this->nro_participante			= $rowPac['nro_participante'];
				$this->centro					= $rowPac['centro'];
				$this->nro_paciente				= $rowPac['nro_paciente'];
				$this->nro_paciente_formatado	= $rowPac['nro_paciente_formatado'];
				$this->paciente_pos_rand		= $rowPac['paciente_pos_rand'];
				$this->data_hora				= $rowPac['data_hora'];
				$this->randomizado				= $rowPac['randomizado'];
				$this->randomizado_por			= $rowPac['randomizado_por'];
				$this->data_rand				= $rowPac['data_rand'];
				$this->hora_rand				= $rowPac['hora_rand'];
				$this->nasc						= $rowPac['nasc'];
				$this->sexo						= $rowPac['sexo'];
				$this->iniciais					= $rowPac['iniciais'];
				$this->incluido_por				= $rowPac['incluido_por'];
				$this->log_usuario				= $rowPac['log_usuario'];
				$this->projeto					= $rowPac['projeto'];

				$this->CRFs						= array();
				$query = "select
								crf_entrada_forms.id
									from crf_entrada_forms
							inner join crf_forms on crf_entrada_forms.form_id = crf_forms.id
							where  centro =  ". $this->centro . "
								and paciente = " . $this->nro_triagem;
				if($_SESSION['user_tipo']!=1){
					$query .= "			and crf_forms.visivel = 1 ";
				}
				$query .= "				order by form_id,
								crf_entrada_forms.ordem";
				$resForms = $varConexao->pesquisa($query);
				while($rowForms = mysqli_fetch_assoc($resForms)){
					$this->CRFs[] = new Formulario($rowForms['id']);
				}
			}
		}
		public function setaValor($nomeVariavel, $valor){
			$conexao = new Conexao();
			$query = "select * from vw_valor_campo
							where projeto = ".$_SESSION['projeto']."
							and nro_participante = '".$this->nro_participante."'
							and centro = '".$this->centro."'
							and nome = '".$nomeVariavel."'";

			$res_valor = $conexao->pesquisa($query);
			$row_valor = mysqli_fetch_assoc($res_valor);
			$id_campo = $row_valor['FORMULARIO_CAMPO_ID'];
			$id_form  = $row_valor['entrada_forms_id'];
			$campo_id = $row_valor['campo_id'];
			$query = "update crf_entrada_campos
							set valor = '$valor'
								where id = $id_campo
									and form_id = $id_form";
			$conexao->pesquisa($query);
		}
		public function retornaValor($nomeVariavel){
			$conexao = new Conexao();
			$query = "select * from vw_valor_campo
							where projeto = ".$_SESSION['projeto']."
							and nro_participante = '".$this->nro_participante."'
							and centro = '".$this->centro."'
							and nome = '".$nomeVariavel."'";

			$res_valor = $conexao->pesquisa($query);
			$row_valor = mysqli_fetch_assoc($res_valor);
			return $row_valor['valor'];
		}
	}
	class Query{
		public $id;
		public $campo_id;
		public $centro;
		public $datahora_abertura;
		public $usuario_abertura;
		public $status;
		public $datahora_fechamento;
		public $usuario_fechamento;
		public $log_usuario;
		public $classUsuarioAbertura;

		public function __construct($id){
			if($id>0){
				$conexao = new Conexao();
				$query 		= "select * from crf_entrada_campos_query where id = " . $id;
				$res 	= $conexao->pesquisa($query);
				$row	= mysqli_fetch_assoc($res);

				$this->id				= $id;
				$this->campo_id			= $row['campo_id'];
				$this->centro			= $row['centro'];
				$this->datahora_abertura= $row['datahora_abertura'];
				$this->usuario_abertura	= $row['usuario_abertura'];
				$this->status			= $row['status'];
				$this->datahora_fechamento	= $row['datahora_fechamento'];
				$this->usuario_fechamento	= $row['usuario_fechamento'];
				$this->log_usuario			= $row['log_usuario'];
				$this->classUsuarioAbertura	= new Usuario($this->usuario_abertura);
			}
		}
		function getStatus(){
			if($this->status==1){return "Aberto";}
			if($this->status==2){return "Respondido";}
			if($this->status==3){return "Fechado/Conclu�do";}
		}
		function getPaciente(){
			$varConexao = new Conexao();
			$query = "select crf_entrada_forms.id,
						crf_entrada_forms.centro,
						crf_entrada_forms.paciente
						from crf_entrada_campos
							inner join crf_entrada_forms on crf_entrada_forms.id = crf_entrada_campos.form_id
							where crf_entrada_campos.id = " . $this->campo_id;
			$resPac = $varConexao->pesquisa($query);
			$rowPac = mysqli_fetch_assoc($resPac);
			return $rowPac['paciente'];
		}
		function getFormulario(){
			$varConexao = new Conexao();
			$query = "select crf_entrada_forms.id,
						crf_entrada_forms.centro,
						crf_entrada_forms.paciente
						from crf_entrada_campos
							inner join crf_entrada_forms on crf_entrada_forms.id = crf_entrada_campos.form_id
							where crf_entrada_campos.id = " . $this->campo_id;
			$resPac = $varConexao->pesquisa($query);
			$rowPac = mysqli_fetch_assoc($resPac);
			return $rowPac['id'];
		}
		function setTemResposta(){
			$conexao = new Conexao();
			$query = "update crf_entrada_campos_query set status = 2, log_usuario = '".$_SESSION['user_id']."' where id = " . $this->id;
			$conexao->pesquisa($query);
		}
	}
	class QueryItem{
		public $id;
		public $usuario_id;
		public $classUsuario;
		public $query_id;
		public $datahora;
		public $tipo;	//1 - Query, 2 - Resposta
		public $mensagem;
		public $log_usuario;
		public $status;

		public function __construct($id){
			if($id>0){
				$conexao = new Conexao();
				$query 		= "select * from crf_entrada_campos_query_questionamentos
									where id = " . $id;
				$res 	= $conexao->pesquisa($query);
				$row	= mysqli_fetch_assoc($res);
				$this->id				= $id;
				$this->usuario_id		= $row['usuario_id'];
				$this->classUsuario		= new Usuario($row['usuario_id']);
				$this->query_id			= $row['query_id'];
				$this->datahora			= $row['datahora'];
				$this->tipo				= $row['tipo'];
				$this->mensagem			= $row['mensagem'];
				$this->log_usuario		= $row['log_usuario'];
				$this->status			= $row['status'];
			}
		}
		public function getStatus(){
			if($this->status==0){echo "Pendente";}
			if($this->status==1){echo "Respondido";}
		}
		public function temResposta(){
			$conexao = new Conexao();
			$query   = "select * from crf_entrada_campos_query_questionamentos where query_id = " . $this->id . " and tipo = 2 order by id";
			$resTem  = $conexao->pesquisa($query);
			if(mysqli_num_rows($resTem)>0){return true;}else{return false;}
		}
	}

	/*RFPaciente		(crf_paciente_pre_rand)
		-> CRF

			-> Formulario			(crf_entrada_forms)
				-> TipoFormulario	(crf_forms)
			-> Campo				(crf_entrada_campos)
				->TipoCampo			(crf_forms_campos)
	*/

	/*$varTeste = new CRFPaciente(412,505, 96);
	var_dump($varTeste);*/