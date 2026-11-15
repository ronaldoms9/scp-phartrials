<?
	include_once __DIR__ . "/conexao.class.php";
	include_once __DIR__ . "/log.class.php";
	include_once __DIR__ . "/email.class.php";
	include_once __DIR__ . "/participante.class.php";
	include_once __DIR__ . "/centro.class.php";
	include_once __DIR__ . "/patrocinador.class.php";


	class Projeto{
		public $id;
		public $numero;
		public $nome;
		public $descricao;
		public $patrocinadorId;
		public $patrocinador;
		public $centroId;
		public $centro;
		public $centro_analitico;
		public $centro_estatistico;
		public $periodos;
		public $grupos;
		public $n;
		public $versao;
		public $log_usuario;
		public $registro_valido;
		public $dash;
		public $dashproprio;
		public $codigo;
		public $pais;
		public $formulacao;
		public $data_recebimento;
		public $data_aviso_revisor;
		public $data_resposta_revisor;
		public $data_envio_centro;
		public $data_retorno_centro;
		public $data_finalizacao;
		public $data_ult_atualizacao;
		public $data_limite;
		public $followup;
		public $finalizado;
		public $txtfollowup;

		public $rev_rc_satus;
		public $rev_rc_flw;
		public $rev_ra_satus;
		public $rev_ra_flw;
		public $rev_re_satus;
		public $rev_re_flw;
		public $rev_rf_satus;
		public $rev_rf_flw;
		public $idade_minima;
		public $idade_maxima;


		public $versao_plano;
		public $data_protocolo;
		public $coletas;
		public $comentarios;
		public $sql;


		public $plano_chkOpc1;
		public $plano_chkOpc2;
		public $plano_chkOpc3;
		public $plano_chkOpc4;
		public $plano_chkOpc5;
		public $plano_chkOpc6;
		public $plano_chkOpc7;

		public $conexao; //Ponteiro para a conexao
		function __construct($id){
			if($id>0){
				$this->conexao = new Conexao();
				$query = "select * from proj_projeto where id = " . $id;
				$resCli = $this->conexao->pesquisa($query);
				if(mysqli_num_rows($resCli)>0){
					$rowCli = mysqli_fetch_assoc($resCli);
					$this->id                 = $id;
					$this->numero             = $rowCli['numero'];
					$this->nome               = $rowCli['nome'];
					$this->descricao          = $rowCli['descricao'];
					$this->patrocinadorId     = $rowCli['patrocinador'];
					$this->patrocinador       = new Patrocinador($rowCli['patrocinador']);
					$this->centro             = $rowCli['centro'];
					$this->centro_analitico   = $rowCli['centro_analitico'];
					$this->centro_estatistico = $rowCli['centro_estatistico'];
					$this->periodos           = $rowCli['periodos'];
					$this->grupos             = $rowCli['grupos'];
					$this->n                  = $rowCli['n'];
					$this->versao             = $rowCli['versao'];
					$this->log_usuario        = $rowCli['log_usuario'];
					$this->registro_valido    = $rowCli['registro_valido'];
					$this->dash               = $rowCli['dash'];
					$this->dashproprio        = $rowCli['dashproprio'];
					$this->codigo             = $rowCli['codigo'];
					$this->pais               = $rowCli['pais'];
					$this->tipo               = $rowCli['tipo'];
					$this->formulacao         = $rowCli['formulacao'];

					$this->data_recebimento      = $rowCli['data_recebimento'];
					$this->data_aviso_revisor    = $rowCli['data_aviso_revisor'];
					$this->data_resposta_revisor = $rowCli['data_resposta_revisor'];
					$this->data_envio_centro     = $rowCli['data_envio_centro'];
					$this->data_retorno_centro   = $rowCli['data_retorno_centro'];
					$this->data_finalizacao      = $rowCli['data_finalizacao'];
					$this->data_ult_atualizacao  = $rowCli['data_ult_atualizacao'];
					$this->data_limite           = $rowCli['data_limite'];
					$this->followup              = $rowCli['followup'];
					$this->status                = $rowCli['status'];
					$this->finalizado            = $rowCli['finalizado'];
					$this->txtfollowup           = $rowCli['txtfollowup'];

					$this->rev_rc_status = $rowCli['rev_rc_status'];
					$this->rev_rc_flw    = $rowCli['rev_rc_flw'];
					$this->rev_ra_status = $rowCli['rev_ra_status'];
					$this->rev_ra_flw    = $rowCli['rev_ra_flw'];
					$this->rev_re_status = $rowCli['rev_re_status'];
					$this->rev_re_flw    = $rowCli['rev_re_flw'];
					$this->rev_rf_status = $rowCli['rev_rf_status'];
					$this->rev_rf_flw    = $rowCli['rev_rf_flw'];

					$this->versao_plano   = $rowCli['versao_plano'];
					$this->data_protocolo = $rowCli['data_protocolo'];
					$this->coletas        = $rowCli['coletas'];
					$this->comentarios    = $rowCli['comentarios'];
					$this->idade_minima   = $rowCli['idade_minima'];
					$this->idade_maxima   = $rowCli['idade_maxima'];
					$this->plano_chkOpc1  = $rowCli['plano_chkOpc1'];
					$this->plano_chkOpc2  = $rowCli['plano_chkOpc2'];
					$this->plano_chkOpc3  = $rowCli['plano_chkOpc3'];
					$this->plano_chkOpc4  = $rowCli['plano_chkOpc4'];
					$this->plano_chkOpc5  = $rowCli['plano_chkOpc5'];
					$this->plano_chkOpc6  = $rowCli['plano_chkOpc6'];
					$this->plano_chkOpc7  = $rowCli['plano_chkOpc7'];
					$this->sql            = $query;
					$this->resultado      = "OK";
				}else{
					$this->resultado			= "NAO";
				}
			}
		}
		function  getTotalCampoPorNome($nome, $valor, $centro=0){
			$conexao = new Conexao();
			$query = "SELECT
					count(*) as qtd
						FROM vw_valor_campo
							where 1 = 1
							and nome = '$nome'
							and valor = '$valor'";
			if($centro!=0){
				$query .= " and centro = ".$centro;
			}
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function getCRFTotalCampos($centro=0){
			$conexao = new Conexao();
			$query = "select
						count(*) as qtd
						from crf_entrada_campos
							inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
							inner join crf_entrada_forms on crf_entrada_campos.form_id = crf_entrada_forms.id
								where crf_forms_campos.tipo not in ('divisao')";
			if($centro!=0){
				$query .= " and centro = ".$centro;
			}
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function getCRFTotalCamposMonitorados($centro=0){
			$conexao = new Conexao();
			$query = "select
						count(*) as qtd
						from crf_entrada_campos
							inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
							inner join crf_entrada_forms on crf_entrada_campos.form_id = crf_entrada_forms.id
								where crf_forms_campos.tipo not in ('divisao')
								and crf_entrada_campos.monitoria_status = '1'";
			if($centro!=0){
				$query .= " and centro = ".$centro;
			}
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function getTotalEventos($centro=0){
			$conexao = new Conexao();
			$query = "SELECT
					count(*) as qtd
						FROM vw_valor_campo
							where 1 = 1
							and nome in ('EA#_HOUVE')
							and valor = '1 - Sim'";
			if($centro!=0){
				$query .= " and centro = ".$centro;
			}
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function getTotalEventosGraves($centro=0){
			$conexao = new Conexao();
			$query = "SELECT
					count(*) as qtd
						FROM vw_valor_campo
							where 1 = 1
							and nome in ('EA#_GRAVE')
							and valor = '1 - Sim'";
			if($centro!=0){
				$query .= " and centro = ".$centro;
			}
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function getTotalQueriesPorCentro($centro){
			$conexao = new Conexao();
			$query = "select
						 count(id) as qtd
						 from crf_entrada_campos_query
						 where  centro = $centro";
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function getTotalQueries(){
			$conexao = new Conexao();
			$query = "select
						 count(id) as qtd
						 from crf_entrada_campos_query
						 where 1 = 1";
			if($_SESSION['centro']!="40"){
				$query .= " and centro = ".$_SESSION['centro'];
			}
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function getTotalQueriesRespondidas(){
			$conexao = new Conexao();
			$query = "select
						 count(id) as qtd
						 from crf_entrada_campos_query
						 where 1 = 1
						 	and status in (2,3)";
			if($_SESSION['centro']!="40"){
				$query .= " and centro = ".$_SESSION['centro'];
			}
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function getTotalQueriesAbertas(){
			$conexao = new Conexao();
			$query = "select
						 count(id) as qtd
						 from crf_entrada_campos_query
						 where 1 = 1
						 	and status not in (3)";
			if($_SESSION['centro']!="40"){
				$query .= " and centro = ".$_SESSION['centro'];
			}
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function getTotalQueriesFechadas(){
			$conexao = new Conexao();
			$query = "select
						 count(id) as qtd
						 from crf_entrada_campos_query
						 where 1 = 1
						 and status in (3)";
			if($_SESSION['centro']!="40"){
				$query .= " and centro = ".$_SESSION['centro'];
			}
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function getTotalTriados($centro = 0){
			$temp = 0;
			$conexao = new Conexao();
			$query = "select
						count(id) as qtd
							from
							crf_paciente_pre_rand
								where 1 = 1";
			if($_SESSION['centro']!="40"){
				$query .= " and centro = ".$_SESSION['centro'];
			}
			if($centro!=0){
				$query .= " and centro = ".$centro;
			}
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function getTotalEmSelecao(){
			$conexao = new Conexao();
			$temp = 0;
			$query = "SELECT
						count(*) as qtd
						FROM vw_valor_campo
							where nome = 'TRI_CRI_ELEGIB'
							and valor = ''";
			if($_SESSION['centro']!="40"){
				$query .= " and centro = ".$_SESSION['centro'];
			}
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function getTotalSelecionados($centro = 0){
			$conexao = new Conexao();
			$temp = 0;
			$query = "SELECT
						count(*) as qtd
						FROM vw_valor_campo
							where nome = 'TRI_RAND_REALIZADA'
							and valor = '1 - Sim'";
			if($_SESSION['centro']!="40"){
				$query .= " and centro = ".$_SESSION['centro'];
			}
			if($centro!=0){
				$query .= " and centro = ".$centro;
			}
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function getTotalNaoSelecionados(){
			$conexao = new Conexao();
			$temp = 0;
			$query = "SELECT
						count(*) as qtd
						FROM vw_valor_campo
							where nome = 'TRI_CRI_ELEGIB'
							and valor = '2 - Não Elegível / INAPTO'";
			if($_SESSION['centro']!="40"){
				$query .= " and centro = ".$_SESSION['centro'];
			}
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function getTotalDescontinuados(){
			$conexao = new Conexao();
			$temp = 0;
			$query = "SELECT
						count(*) as qtd
						FROM vw_valor_campo
							where nome = 'ALTA_STATUS'
							and valor = '2 - Não'";
			if($_SESSION['centro']!="40"){
				$query .= " and centro = ".$_SESSION['centro'];
			}
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function getTotalFinalizados(){
			$conexao = new Conexao();
			$temp = 0;
			$query = "SELECT
						count(*) as qtd
						FROM vw_valor_campo
							where nome = 'ALTA_STATUS'
							and valor = '1 - Sim'";
			if($_SESSION['centro']!="40"){
				$query .= " and centro = ".$_SESSION['centro'];
			}
			$resQtd = $conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp = $rowQtd['qtd'];
			if($temp==""){$temp=0;}
			return $temp;
		}
		function incluiPaciente($centro, $nasc, $varCodigo, $varSexo, $varMeses){
			$conexao = new Conexao();
			//Pego o próximo nro de sujeito screening disponivel
			$query = "select vol_pre_rand+1 as prox, numero from cfg_centro where id = '".$centro."'";
			$resCentro = $conexao->pesquisa($query);
			$rowCentro = mysqli_fetch_assoc($resCentro);
			$prox_vol 		= $rowCentro['prox'];
			$rand = rand(324,453534534);
			//Insiro na tabela crf_paciente_pre_rand
			$query = "insert into crf_paciente_pre_rand
					(centro,
					nro_paciente,
					nro_paciente_formatado,
					data_hora,
					nro_triagem,
					nro_participante,
					projeto,
					nasc,
					iniciais,
					incluido_por,
					sexo)
				values ('$centro',
						$prox_vol,
						'".str_pad($prox_vol,3,"000",STR_PAD_LEFT)."',
						'".date("Y-m-d H:i:s")."',
						'".$prox_vol."',
						'$prox_vol',
						".$_SESSION['projeto'].",
						'$nasc',
						'$varCodigo',
						'".$_SESSION['user_id']."',
						'".$varSexo."');";
			$conexao->pesquisa($query);
			$query = "update cfg_centro set vol_pre_rand = vol_pre_rand+1 where id = '".$centro."'";
			$resCentro = $conexao->pesquisa($query);

			/*
			//Loop nos formulários screening
			$query 		= "select * from crf_forms
								where registro_valido = 1
									and UPPER(tipo) in ('SCREENING', 'DIVISAO')
									and projeto = ".$_SESSION['projeto']."
									order by ordem";
			$resForms	= $conexao->pesquisa($query);
			while($rowForms = mysqli_fetch_assoc($resForms)){
				//Insiro o registro do formulário
				$query = "insert into crf_entrada_forms (paciente, form_id, centro, projeto)
								values ($prox_vol,
										".$rowForms['id'].",
										".$centro.",
										".$_SESSION['projeto'].");";

				$conexao->pesquisa($query);
				$query = "select id from crf_entrada_forms
								where paciente = '".$prox_vol."'
									and form_id = '".$rowForms['id']."'
									and centro = '".$centro."'";
				$resProx 	= $conexao->pesquisa($query);
				$rowProx 	= mysqli_fetch_assoc($resProx);
				$prox_form 	= $rowProx['id'];

				//inserior os campos
				//Pego os campos daquele formulário
				$query = "select id, ordem from crf_forms_campos where formulario = ".$rowForms['id'];
				$resCampos = $conexao->pesquisa($query);
				while($rowCampos= mysqli_fetch_assoc($resCampos)){
					$query = "insert into crf_entrada_campos (projeto, form_id, campo_id, ordem) values (".$_SESSION['projeto'].", $prox_form, ".$rowCampos['id'].", '".$rowCampos['ordem']."');";
					$conexao->pesquisa($query);
				}
			}


			$varCRFParticipante = new CRFPaciente($_SESSION['projeto'], $centro, $prox_vol);
			$varCRFParticipante->setaValor('CODIGO_PARTICIPANTE', $varCodigo);
			*/
			echo "OK";
		}
		function imp_incluiPaciente($centro, $participante){
			$conexao = new Conexao();
			$prox_vol = $participante;
			if(is_numeric($prox_vol)){
				$paciente = $prox_vol;
			}else{
				$paciente = intval(substr($prox_vol, 2,50));
			}
			//Insiro na tabela crf_paciente_pre_rand
			$query = "insert into crf_paciente_pre_rand
				(centro, nro_paciente, nro_paciente_formatado, data_hora, nro_triagem, nro_participante, projeto, nasc, sexo, incluido_por)
				values ('$centro',
						'$paciente',
						'".str_pad($participante,3,"000",STR_PAD_LEFT)."',
						now(),
						'".$participante."',
						'$participante',
						".$_SESSION['projeto'].",
						'',
						'',
						'".$_SESSION['user_id']."');";
			$conexao->pesquisa($query);

			//Loop nos formulários screening
			$query 		= "select * from crf_forms where projeto = ".$_SESSION['projeto']." order by ordem";
			$resForms	= $conexao->pesquisa($query);
			while($rowForms = mysqli_fetch_assoc($resForms)){
				//Insiro o registro do formulário
				$query = "insert into crf_entrada_forms (paciente, form_id, centro, projeto)
								values ('$paciente',
										".$rowForms['id'].",
										".$centro.",
										".$_SESSION['projeto'].");";
				$conexao->pesquisa($query);
				$query = "select id from crf_entrada_forms
								where paciente = '".$paciente."'
									and form_id = '".$rowForms['id']."'
									and centro = '".$centro."'
									and projeto = '".$_SESSION['projeto']."'";
				$resProx 	= $conexao->pesquisa($query);
				$rowProx 	= mysqli_fetch_assoc($resProx);
				$prox_form 	= $rowProx['id'];

				//inserior os campos
				//Pego os campos daquele formulário
				$query = "select id from crf_forms_campos where formulario = ".$rowForms['id'];
				$resCampos = $conexao->pesquisa($query);
				while($rowCampos= mysqli_fetch_assoc($resCampos)){
					$query = "insert into crf_entrada_campos (projeto, form_id, campo_id) values ('".$_SESSION['projeto']."', $prox_form, ".$rowCampos['id'].");";
					$conexao->pesquisa($query);
				}
			}
			$query = "update cfg_centro set vol_pre_rand = vol_pre_rand+1 where id = '".$centro."'";
			$resCentro = $conexao->pesquisa($query);
			echo "OK";
		}
		function salva(){

			$this->conexao = new Conexao();
			$query = "update proj_projeto set
				numero                = '".$this->numero."',
				nome                  = '".$this->nome."',
				descricao             = '".$this->descricao."',
				patrocinador          = '".$this->patrocinadorId."',
				centro                = '".$this->centro."',
				centro_analitico      = '".$this->centro_analitico."',
				centro_estatistico    = '".$this->centro_estatistico."',
				periodos              = '".$this->periodos."',
				grupos                = '".$this->grupos."',
				n                     = '".$this->n."',
				versao                = '".$this->versao."',
				dash                  = '".$this->dash."',
				dashproprio           = '".$this->dashproprio."',
				codigo                = '".$this->codigo."',
				pais                  = '".$this->pais."',
				tipo                  = '".$this->tipo."',
				formulacao            = '".$this->formulacao."',
				log_usuario           = '".$_SESSION['user_id']."',
				txtfollowup           = '".$this->txtfollowup."',
				data_recebimento      = '".$this->data_recebimento."',
				data_aviso_revisor    = '".$this->data_aviso_revisor."',
				data_resposta_revisor = '".$this->data_resposta_revisor."',
				data_envio_centro     = '".$this->data_envio_centro."',
				data_retorno_centro   = '".$this->data_retorno_centro."',
				data_finalizacao      = '".$this->data_finalizacao."',
				followup              = '".$this->followup."',
				finalizado            = '".$this->finalizado."',
				status                = '".$this->status."',
				registro_valido       = '1',
				versao_plano          = '".$this->versao_plano."',
				data_protocolo        = '".$this->data_protocolo."',
				coletas               = '".$this->coletas."',
				comentarios           = '".$this->comentarios."',
				idade_minima          = '".$this->idade_minima."',
				idade_maxima          = '".$this->idade_maxima."',
				plano_chkOpc1         = '".$this->plano_chkOpc1."',
				plano_chkOpc2         = '".$this->plano_chkOpc2."',
				plano_chkOpc3         = '".$this->plano_chkOpc3."',
				plano_chkOpc4         = '".$this->plano_chkOpc4."',
				plano_chkOpc5         = '".$this->plano_chkOpc5."',
				plano_chkOpc6         = '".$this->plano_chkOpc6."',
				plano_chkOpc7         = '".$this->plano_chkOpc7."'
				where id = " . $this->id;
			$this->conexao->pesquisa(formata_utf8_decode($query));
			echo $query;
		}
		function inclui(){
			$this->conexao = new Conexao();
			$query = "insert into proj_projeto (tipo, formulacao, data_recebimento, numero, nome, descricao, patrocinador, centro,
						centro_analitico, centro_estatistico, periodos,
						grupos, n, versao, dash, log_usuario, registro_valido, dashproprio, codigo, status , pais, versao_plano, data_protocolo, coletas,comentarios, idade_minima, idade_maxima, plano_chkOpc1, plano_chkOpc2, plano_chkOpc3, plano_chkOpc4, plano_chkOpc5, plano_chkOpc6, plano_chkOpc7) values (
						'".$this->tipo."',
						'".$this->formulacao."',
						'".$this->data_recebimento."',
						'".$this->numero."',
						'".$this->nome."',
						'".$this->descricao."',
						'".$this->patrocinadorId."',
						'".$this->centro."',
						'".$this->centro_analitico."',
						'".$this->centro_estatistico."',
						'".$this->periodos."',
						'".$this->grupos."',
						'".$this->n."',
						'".$this->versao."',
						'".$this->dash."',
						'".$_SESSION['user_id']."',
						1,
						'".$this->dashproprio."',
						'".$this->codigo."',
						'N/D',
						'".$this->pais."',
						'".$this->versao_plano."',
						'".$this->data_protocolo."',
						'".$this->coletas."',
						'".$this->comentarios."',
						'".$this->idade_minima."',
						'".$this->idade_maxima."',
						'".$this->plano_chkOpc1."',
						'".$this->plano_chkOpc2."',
						'".$this->plano_chkOpc3."',
						'".$this->plano_chkOpc4."',
						'".$this->plano_chkOpc5."',
						'".$this->plano_chkOpc6."',
						'".$this->plano_chkOpc7."'
					);";
			$this->conexao->pesquisa(formata_utf8_decode($query));

			//Pegar o ID do projeto para poder gerar o check-list
			$query = "select id from proj_projeto where numero = '".$this->numero."'";
			$resProjeto = $this->conexao->pesquisa($query);
			if(mysqli_num_rows($resProjeto)>0){//Deu certo o add

				$rowProjeto = mysqli_fetch_assoc($resProjeto);
				$idProjeto = $rowProjeto['id'];

				$this->geraCheckList($idProjeto);
			}
		}
		function geraCheckList($idProjeto){
			$arrFases = array("Inicial", "Follow-up", "Dieta", "Pós");
			foreach($arrFases as $fase){
				$query = "insert into agenda_check_fase (projeto, item) values (".$idProjeto.", '".$fase."');";
				$this->conexao->pesquisa($query);
			}


			$query = "select * from agenda_check_fase where projeto = 0 order by id";
			$resFases = $this->conexao->pesquisa($query);
			while($rowFases = mysqli_fetch_assoc($resFases)){

				$query = "select * from agenda_check_fase where projeto = ".$idProjeto." and item = '".$rowFases['item']."'";
				$resFaseNova = $this->conexao->pesquisa($query);
				$rowFaseNova = mysqli_fetch_assoc($resFaseNova);
				$periodoNovo = $rowFaseNova['id'];

				$query = "select * from agenda_check where periodo = '".$rowFases['id']."' and projeto = '0'";
				echo $query . "<br>";
				$resFase = $this->conexao->pesquisa($query);
				if(mysqli_num_rows($resFase)>0){//Deu certo o add
					while($rowFase = mysqli_fetch_assoc($resFase)){
						$query = "insert into agenda_check (projeto, periodo, ordem, nome, label, mascara, opcoes, exporta, calculo, calculo_tipo, calculo_campos, valor_minimo, valor_maximo, valor, valor6) values (
							'".$idProjeto."',
							'".$periodoNovo."',
							'".$rowFase['ordem']."',
							'".$rowFase['nome']."',
							'".$rowFase['label']."',
							'".$rowFase['mascara']."',
							'".$rowFase['opcoes']."',
							'".$rowFase['exporta']."',
							'".$rowFase['calculo']."',
							'".$rowFase['calculo_tipo']."',
							'".$rowFase['calculo_campos']."',
							'".$rowFase['valor_minimo']."',
							'".$rowFase['valor_maximo']."',
							'',
							''
						);";
						$this->conexao->pesquisa($query);
					}
				}
			}
			//Aqui já gerou. Vou arrumar o campo dos calculos
			//Aqui da Idade
			$query = "select id from agenda_check where projeto = ".$idProjeto." and nome = 'Idade'";
			$resIdade = $this->conexao->pesquisa($query);
			$rowIdade = mysqli_fetch_assoc($resIdade);
			$varIdade = $rowIdade['id'];
			$query = "select id from agenda_check where projeto = ".$idProjeto." and nome = 'Data da internação'";
			$resIdade = $this->conexao->pesquisa($query);
			$rowIdade = mysqli_fetch_assoc($resIdade);
			$varDataInternacao = $rowIdade['id'];
			$query = "select id from agenda_check where projeto = ".$idProjeto." and nome = 'Data de Nascimento'";
			$resIdade = $this->conexao->pesquisa($query);
			$rowIdade = mysqli_fetch_assoc($resIdade);
			$varDataNascimento = $rowIdade['id'];
			$query = "update agenda_check set calculo_campos = '".$varDataInternacao.",".$varDataNascimento."' where id = ".$varIdade;
			$this->conexao->pesquisa($query);
			//Aqui da IMC
			$query = "select id from agenda_check where projeto = ".$idProjeto." and nome = 'IMC'";
			$resIdade = $this->conexao->pesquisa($query);
			$rowIdade = mysqli_fetch_assoc($resIdade);
			$varIMC = $rowIdade['id'];
			$query = "select id from agenda_check where projeto = ".$idProjeto." and nome = 'Peso'";
			$resIdade = $this->conexao->pesquisa($query);
			$rowIdade = mysqli_fetch_assoc($resIdade);
			$varPeso = $rowIdade['id'];
			$query = "select id from agenda_check where projeto = ".$idProjeto." and nome = 'Altura'";
			$resIdade = $this->conexao->pesquisa($query);
			$rowIdade = mysqli_fetch_assoc($resIdade);
			$varAltura = $rowIdade['id'];
			$query = "update agenda_check set calculo_campos = '".$varAltura.",".$varPeso."' where id = ".$varIMC;
			$this->conexao->pesquisa($query);



		}
		function geraTmf($projeto){
			$conexao = new Conexao();
			//Deleto registros da tabela arq_tmf

			$query  = "delete from arq_tmf where projeto = " . $_SESSION['projeto'];
			//$conexao->pesquisa($query);

			//PEgar os centros para montar POR CENTRO
			$query = "select id, numero, nome, apelido from cfg_centro where projeto = ".$_SESSION['projeto']." order by cast(numero as int)";
			//$query = "select id, numero, nome, apelido from cfg_centro where projeto = ".$_SESSION['projeto']." and id = 51 order by cast(numero as int)";
			$resCentros = $conexao->pesquisa($query);
			$indCentro = 1;
			while($rowCentros = mysqli_fetch_assoc($resCentros)){
				$nomeCentro = $rowCentros['numero'] . " - " . $rowCentros['apelido'];
				$idCentro	= $rowCentros['id'];
				$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, log_usuario, centro) values (
							'".$nomeCentro."',
							'',
							'0',
							'".$indCentro."',
							'".$_SESSION['projeto']."',
							'".$_SESSION['user_id']."',
							'".$rowCentros['id']."'
							);
				";
				$conexao->pesquisa($query);
				$query = "select * from arq_tmf where centro = '".$rowCentros['id']."'";
				$resIdCentro = $conexao->pesquisa($query);
				$rowIdCentro = mysqli_fetch_assoc($resIdCentro);
				$idCentro = $rowIdCentro['id'];
				$query  = "select * from arq_tipos_arquivos where pai = 0 order by ordem";
				$resTmf = $conexao->pesquisa($query);
				$varOrdem 	= 1;

				while($rowTmf = mysqli_fetch_assoc($resTmf)){
					$varNome 	= $rowTmf['nome'];
					$varNomeEn 	= $rowTmf['nome_en'];
					$varIDAnt	= $rowTmf['id'];

					//insiro na tabela de tmf
					$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, id_tipo_arquivo, log_usuario, centro) values (
						'$varNome',
						'$varNomeEn',
						'$idCentro',
						'$varOrdem',
						'".$_SESSION['projeto']."',
						'$varIDAnt',
						'".$_SESSION['user_id']."',
						'$idCentro'
					);";
					$resIns = $conexao->pesquisa($query);
					$query = "select * from arq_tmf where nome = '$varNome' and ordem = '$varOrdem' and centro = $idCentro";
					$resPai = $conexao->pesquisa($query);
					$rowPai = mysqli_fetch_assoc($resPai);
					$idPai  = $rowPai['id'];

					//Nível 2
					$query  = "select * from arq_tipos_arquivos where pai = $varIDAnt order by ordem";
					$resTmfNvl2 = $conexao->pesquisa($query);
					$varOrdemNvl2 	= 1;

					while($rowTmfNvl2 = mysqli_fetch_assoc($resTmfNvl2)){
						$varNomeNvl2 	= $rowTmfNvl2['nome'];
						$varNomeEnNvl2 	= $rowTmfNvl2['nome_en'];
						$varIDAntNvl2	= $rowTmfNvl2['id'];

						//insiro na tabela de tmf
						$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, id_tipo_arquivo, log_usuario, centro) values (
							'$varNomeNvl2',
							'$varNomeEnNvl2',
							'$idPai',
							'$varOrdemNvl2',
							'".$_SESSION['projeto']."',
							'$varIDAntNvl2',
							'".$_SESSION['user_id']."',
							'$idCentro'
						);";
						$resIns = $conexao->pesquisa($query);

						$query = "select * from arq_tmf where nome = '$varNomeNvl2' and ordem = '$varOrdemNvl2' and centro = $idCentro";
						$resPai = $conexao->pesquisa($query);
						$rowPai = mysqli_fetch_assoc($resPai);
						$idPaiNvl2  = $rowPai['id'];

						//Nível 3
						$query  = "select * from arq_tipos_arquivos where pai = $varIDAntNvl2 order by ordem";
						$resTmfNvl3 = $conexao->pesquisa($query);
						$varOrdemNvl3 	= 1;

						while($rowTmfNvl3 = mysqli_fetch_assoc($resTmfNvl3)){
							$varNomeNvl3 	= $rowTmfNvl3['nome'];
							$varNomeEnNvl3 	= $rowTmfNvl3['nome_en'];
							$varIDAntNvl3	= $rowTmfNvl3['id'];

							//insiro na tabela de tmf
							$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, id_tipo_arquivo, log_usuario, centro) values (
								'$varNomeNvl3',
								'$varNomeEnNvl3',
								'$idPaiNvl2',
								'$varOrdemNvl3',
								'".$_SESSION['projeto']."',
								'$varIDAntNvl3',
								'".$_SESSION['user_id']."',
								'$idCentro'
							);";
							$resIns = $conexao->pesquisa($query);
							$varOrdemNvl3++;
						}
						$varOrdemNvl2++;
					}
					$varOrdem++;
				}
				$indCentro++;
			}

			//Enviar email

			$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Luis Bueno", 1);
			$message = "Função GERA TMF acionada em " . date('d/m/Y H:i') . " por " . $_SESSION['nome_usuario'] . " para o estudo " . $_SESSION['projeto_nome'];
			$varEmail->enviar("GERA TMF", $message);

		}

		function geraTmfBio($projeto){
			$conexao = new Conexao();
			$query  = "delete from arq_tmf where projeto = " . $_SESSION['projeto'];
			$conexao->pesquisa($query);

			//Pegar os centros para montar POR CENTRO
			$query = "select id, numero, nome, apelido from cfg_centro where projeto = ".$_SESSION['projeto'];
			$resCentros = $conexao->pesquisa($query);
			if(mysqli_num_rows($resCentros)==0){//CRIO CENTRO
				$query = "insert into cfg_centro(projeto, numero, nome, apelido, mit, email, telefone, comentario, endereco, bairro, cidade, investigador, coordenador, log_usuario, tipo)
							values ('".$_SESSION['projeto']."', '1', 'CENTRO1', 'CENTRO1', '', '', '', '', '', '', '', '', '', 1, 3);";
				$conexao->pesquisa($query);
			}
			$query = "select id, numero, nome, apelido from cfg_centro where projeto = ".$_SESSION['projeto'];
			$resCentros = $conexao->pesquisa($query);
			$indCentro = 1;
			while($rowCentros = mysqli_fetch_assoc($resCentros)){
				$nomeCentro = $rowCentros['nome'];
				$idCentro	= $rowCentros['id'];
				$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, log_usuario, centro, id_tipo_arquivo) values (
							'".$nomeCentro."',
							'',
							'0',
							'".$indCentro."',
							'".$_SESSION['projeto']."',
							'".$_SESSION['user_id']."',
							'".$rowCentros['id']."',
							0
							);
				";
				$conexao->pesquisa($query);

				$query = "select * from arq_tmf where centro = '".$rowCentros['id']."'";
				$resIdCentro = $conexao->pesquisa($query);
				$rowIdCentro = mysqli_fetch_assoc($resIdCentro);
				$idCentro = $rowIdCentro['id'];

				//insiro na tabela de tmf
				$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, id_tipo_arquivo, log_usuario, centro) values ('CONTATOS DO ESTUDO','CONTATOS DO ESTUDO','$idCentro','1','".$_SESSION['projeto']."','0','".$_SESSION['user_id']."','$idCentro');";
				$conexao->pesquisa($query);
				$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, id_tipo_arquivo, log_usuario, centro) values ('CORRESPONDÊNCIAS GERAIS','CORRESPONDÊNCIAS GERAIS','$idCentro','1','".$_SESSION['projeto']."','0','".$_SESSION['user_id']."','$idCentro');";
				$conexao->pesquisa($query);
				$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, id_tipo_arquivo, log_usuario, centro) values ('PRODUTO INVESTIGACIONAL','PRODUTO INVESTIGACIONAL','$idCentro','1','".$_SESSION['projeto']."','0','".$_SESSION['user_id']."','$idCentro');";
				$conexao->pesquisa($query);
				$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, id_tipo_arquivo, log_usuario, centro) values ('PROTOCOLO e EMENDAS','PROTOCOLO e EMENDAS','$idCentro','1','".$_SESSION['projeto']."','0','".$_SESSION['user_id']."','$idCentro');";
				$conexao->pesquisa($query);

				$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, id_tipo_arquivo, log_usuario, centro) values ('MONITORIAS','MONITORIAS','$idCentro','1','".$_SESSION['projeto']."','0','".$_SESSION['user_id']."','$idCentro');";
				$conexao->pesquisa($query);
				$query = "select * from arq_tmf where projeto = '".$_SESSION['projeto']."' and nome = 'MONITORIAS'";
				$resIdCentro = $conexao->pesquisa($query);
				$rowIdCentro = mysqli_fetch_assoc($resIdCentro);
				$idItem = $rowIdCentro['id'];

				$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, id_tipo_arquivo, log_usuario, centro) values ('Monitor Visit Log','Monitor Visit Log','$idItem','1','".$_SESSION['projeto']."','0','".$_SESSION['user_id']."','$idCentro');";
				$conexao->pesquisa($query);
				$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, id_tipo_arquivo, log_usuario, centro) values ('Relatórios de Monitoria','Relatórios de Monitoria','$idItem','1','".$_SESSION['projeto']."','0','".$_SESSION['user_id']."','$idCentro');";
				$conexao->pesquisa($query);
				$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, id_tipo_arquivo, log_usuario, centro) values ('Cartas de Confirmação de Monitoria','Cartas de Confirmação de Monitoria','$idItem','1','".$_SESSION['projeto']."','0','".$_SESSION['user_id']."','$idCentro');";
				$conexao->pesquisa($query);
				$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, id_tipo_arquivo, log_usuario, centro) values ('Cartas de Follow Up','Cartas de Follow Up','$idItem','1','".$_SESSION['projeto']."','0','".$_SESSION['user_id']."','$idCentro');";
				$conexao->pesquisa($query);
				$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, id_tipo_arquivo, log_usuario, centro) values ('Plano de Monitoria','Plano de Monitoria','$idItem','1','".$_SESSION['projeto']."','0','".$_SESSION['user_id']."','$idCentro');";
				$conexao->pesquisa($query);
				$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, id_tipo_arquivo, log_usuario, centro) values ('CV do Monitor','CV do Monitor','$idItem','1','".$_SESSION['projeto']."','0','".$_SESSION['user_id']."','$idCentro');";
				$conexao->pesquisa($query);

				$query = "insert into arq_tmf (nome, nome_en, pai, ordem, projeto, id_tipo_arquivo, log_usuario, centro) values ('OUTROS','OUTROS','$idCentro','1','".$_SESSION['projeto']."','0','".$_SESSION['user_id']."','$idCentro');";
				$conexao->pesquisa($query);

				$indCentro++;
			}
			$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Luis Bueno", 1);
			$message = "Função GERA TMF BIO acionada em " . date('d/m/Y H:i') . " por " . $_SESSION['nome_usuario'] . " para o estudo " . $_SESSION['projeto_nome'];
			$varEmail->enviar("GERA TMF BIO ", $message);
		}
		function getFinalizado(){
			if($this->finalizado==0){return "Não";}
			if($this->finalizado==1){return "Sim";}
		}
		function getStatusEnvCorrecao(){
			$conexao = new Conexao();
			$query 	= "select count(id) as qtd from agenda where projeto = '".$this->id."'
							and ((rel_alertado = '0') or (rel_alertado = '') or (ISNULL(rel_alertado)))";
			$resSta = $conexao->pesquisa($query);
			$rowSta = mysqli_fetch_assoc($resSta);
			return $rowSta['qtd'];
		}
		function getStatusEnvRetornado(){
			$conexao = new Conexao();
			$query 	= "select count(id) as qtd from agenda where projeto = '".$this->id."'
							and ((rel_revisado_quem = '0') or (rel_revisado_quem = '') or (ISNULL(rel_revisado_quem)))";
			$resSta = $conexao->pesquisa($query);
			$rowSta = mysqli_fetch_assoc($resSta);
			return $rowSta['qtd'];
		}
		function getStatusEnvPatrocinador(){
			$conexao = new Conexao();
			$query 	= "select count(id) as qtd from agenda where projeto = '".$this->id."'
							and ((rel_enviado = '0') or (rel_enviado = '') or (ISNULL(rel_enviado)))";
			$resSta = $conexao->pesquisa($query);
			$rowSta = mysqli_fetch_assoc($resSta);
			return $rowSta['qtd'];
		}
		function temAgendamento(){
			$varConexao = new Conexao();
			$query = "select count(id) as qtd from agenda where projeto = " . $this->id;
			$resQtd = $varConexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$qtd 	= $rowQtd['qtd'];
			if($qtd==""){$qtd=0;}
			return $qtd;
		}
		function pesquisaCodigo($centro, $codigo){
			$varConexao = new Conexao();
			$codigo = strtoupper($codigo);
			$query  = "SELECT id FROM crf_paciente_pre_rand where centro = $centro and iniciais = '$codigo'";
			$resCod = $varConexao->pesquisa($query);
			if(mysqli_num_rows($resCod)>0){
				return 1;
			}else{
				return 0;
			}
		}
	}

	/*$varProjeto = new Projeto(466);
	$varProjeto->geraCheckList(466);
	echo "<br>FIM";*/