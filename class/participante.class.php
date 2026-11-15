<?
	include_once __DIR__ . "/../seguranca.php";
	include_once __DIR__ . "/pesq_centro.class.php";
	include_once __DIR__ . "/usuario.class.php";
	include_once __DIR__ . "/centro.class.php";
	include_once __DIR__ . "/../funcoes.php";


	class Participante{
		public $id;
		public $centro;
		public $centroNro;
		public $centroNome;
		public $nro_triagem;
		public $nro_participante;
		public $nro_paciente;
		public $nro_paciente_formatado;
		public $paciente_pos_rand;
		public $randomizado;
		public $nasc;
		public $sexo;
		public $iniciais;
		public $projeto;
		public $dataInclusao;
		public $dataInclusaoFormatado;
		public $responsavel;
		function __construct($centro, $pre, int $callProjeto = 0){
			if($callProjeto==0){
				$callProjeto=$_SESSION['projeto'];
			}
			$query = "select crf_paciente_pre_rand.*
							from crf_paciente_pre_rand
							where
								centro = '$centro'
								and nro_paciente = '$pre'";
			$conexao = new Conexao();
			$resPar = $conexao->pesquisa($query);
			$rowPar = mysqli_fetch_assoc($resPar);

			$query = "select * from cfg_centro where id = " . $rowPar['centro'];
			$resCen = $conexao->pesquisa($query);
			$rowCen = mysqli_fetch_assoc($resCen);

			$this->id 						= 	$rowPar['id'];
			$this->centro 					= 	$rowPar['centro'];
			$this->centroNro 				= 	$rowCen['numero'];
			$this->centroNome 				= 	$rowCen['nome'];
			$this->nro_triagem 				= 	$rowPar['nro_triagem'];
			$this->nro_participante 		= 	$rowPar['nro_participante'];
			$this->nro_paciente 			= 	$rowPar['nro_paciente'];
			$this->nro_paciente_formatado 	= 	$rowPar['nro_paciente_formatado'];
			$this->paciente_pos_rand 		= 	$rowPar['paciente_pos_rand'];
			$this->randomizado 				= 	$rowPar['randomizado'];
			$this->nasc 					= 	$rowPar['nasc'];
			$this->sexo 					= 	$rowPar['sexo'];
			$this->iniciais 				= 	$rowPar['iniciais'];
			$this->projeto 					= 	$rowPar['projeto'];
			$this->dataInclusao				=   $rowPar['data_hora'];
			$this->usuario					=   new Usuario($rowPar['incluido_por']);

			$temp = $this->dataInclusao;
			if($temp!=""){
				$temp = explode(" ", $temp);
				$hora = $temp[1];
				$temp = explode("-", $temp[0]);
				$this->dataInclusaoFormatado = $temp[2] . "/" . $temp[1] . "/" . $temp[0] . " " . $hora;
			}
		}
		public function getIdFormulario($nomeFormulario){
			$varConexao = new Conexao();
			$query      = "select id from crf_forms where nome = '$nomeFormulario'";
			$res        = $varConexao->pesquisa($query);
			$row        = mysqli_fetch_assoc($res);
			$idForm     = $row['id'];


			$query = "select
							id
							from crf_entrada_forms
								where form_id = $idForm
								and paciente = ".$this->nro_triagem."
								and centro = ".$this->centro;
			$res        = $varConexao->pesquisa($query);
			$row        = mysqli_fetch_assoc($res);
			$idForm     = $row['id'];
			return $idForm;
		}
		public function getStatus(){

			if($this->buscaValor("TRI_CRI_SN_ELEG")=="2 - N�o"){
				return utf8_encode("Falha de sele��o");
			}
			if($this->buscaValor("TRI_CRI_SN_ELEG")=="1 - Sim"){
				return  "Em andamento";
			}
			if($this->buscaValor("ALTA_STATUS")=="1 - A participante concluiu o estudo de acordo com o protocolo"){
				return "Finalizado";
			}
			if($this->buscaValor("ALTA_STATUS")=="2 - A participante foi descontinuada do estudo"){
					return utf8_encode("Descontinuada");
			}
			if($this->buscaValor("ALTA_STATUS")=="3 - O estudo foi interrompido precocemente"){
					return utf8_encode("Estudo interrompido");
			}
			return "Em recrutamento";
		}
		function retornaTCLE($campo){
			$conexao = new Conexao();
			$query = "select
							valor
						from vw_valor_campo
							where projeto = " . $_SESSION['projeto'] . "
							and entrada_forms_centro = ".$this->centro."
							and nro_triagem = ".$this->nro_triagem."
							and nome = '".$campo."' ORDER BY FORMULARIO_CAMPO_ID  DESC";
			$resValor = $conexao->pesquisa($query);
			//Aqui vou pegar o ULTIMO campo
			$rowValor = mysqli_fetch_assoc($resValor);
			$temp = $rowValor['valor'];
			if(trim($temp)!=""){
				$temp = explode("/", $temp);
				$temp = $temp[2].$temp[1].$temp[0];
			}
			return $temp;
		}
		function retornaNroFormatado(){
			$labPre = $this->nro_triagem;
			$labPos = $this->paciente_pos_rand;
			$labCen = $this->centro;
			$label_paciente = str_pad($this->centroNro,2,"00",STR_PAD_LEFT)."-".str_pad($labPre,3,"000",STR_PAD_LEFT)."S";
			if($labPos>0){
				$label_paciente .= str_pad($labPos,3,"000",STR_PAD_LEFT)."R";
			}
			return $label_paciente;
		}
		function retornaIdadeAtual($campoNascimento){
			$varDataCRF = $this->buscaValor($campoNascimento);
			if($varDataCRF!=""){
				$varDataTEMP = explode("/", $varDataCRF);
				$varDataLIS = $varDataTEMP[2]."-".$varDataTEMP[1]."-".$varDataTEMP[0];
				if(isDate($varDataLIS)){
					return idadeMeses($varDataTEMP[0], $varDataTEMP[1], $varDataTEMP[2]);
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		function buscaValor($campo){
			$conexao = new Conexao();
			$query 	= "select valor
								from vw_valor_campo
						where
							nro_triagem = '".$this->nro_triagem."'
							and centro = '".$this->centro."'
							and nome = '$campo'";
			$res   	= $conexao->pesquisa($query);
			//return $query;
			if(mysqli_num_rows($res)>0){
				$row	= mysqli_fetch_assoc($res);
				return $row['valor'];
			}else{
				return "";
			}
		}
		function buscaValorX($campo, $ordem){
			$varConexao = new Conexao();
			if($ordem!=0){

				$query 	= "select valor
								from vw_valor_campo
						where
							nro_triagem = '".$this->nro_triagem."'
							and centro = '".$this->centro."'
							and nome = '$campo'
							and ordem = $ordem";
			}else{
				$query 	= "select valor from vw_valor_campo
						where 	nro_triagem = '".$this->nro_triagem."'
								and centro = '".$this->centro."'
								and nome = '$campo'";
			}

			$res   	= $varConexao->pesquisa($query);
			if(mysqli_num_rows($res)>0){
			$row	= mysqli_fetch_assoc($res);
				return $row['valor'];
			}else{
				return "";
			}
		}
		function buscaCampo($campo){
			$varConexao = new Conexao();
			$query 	= "select FORMULARIO_CAMPO_ID
								from vw_valor_campo
						where
							nro_triagem = '".$this->nro_triagem."'
							and centro = '".$this->centro."'
							and nome = '$campo'";
			$res   	= $varConexao->pesquisa($query);
			if(mysqli_num_rows($res)>0){
			$row	= mysqli_fetch_assoc($res);
				return $row['FORMULARIO_CAMPO_ID'];
			}else{
				return "";
			}
		}
		function setaValor($campo, $valor){
			$conexao = new Conexao();
			$query 	= "select FORMULARIO_CAMPO_ID from vw_valor_campo
						where 	nro_triagem = '".$this->nro_triagem."'
								and centro = '".$this->centro."'
								and nome = '$campo'";
			$res   	= $conexao->pesquisa($query);
			$row	= mysqli_fetch_assoc($res);
			$idCampo = $row['FORMULARIO_CAMPO_ID'];
			$query   = "update crf_entrada_campos set valor = '$valor' where id = " . $idCampo;
			$conexao->pesquisa($query);
		}
		function setaValorX($campo, $valor, $ind){
			$conexao = new Conexao();
			$query 	= "select FORMULARIO_CAMPO_ID from vw_valor_campo
						where 	nro_triagem = '".$this->nro_triagem."'
								and centro = '".$this->centro."'
								and nome = '$campo'
								and form_ordem = $ind";
			echo $query . "<BR>";
			$res   	= $conexao->pesquisa($query);
			$row	= mysqli_fetch_assoc($res);
			$idCampo = $row['FORMULARIO_CAMPO_ID'];
			$query   = "update crf_entrada_campos set valor = '$valor' where id = " . $idCampo;
			$conexao->pesquisa($query);
		}
		function randomizarParticipante(){
			$conexao = new Conexao();
			$varConexao = new Conexao();
			/*Função para randomizar um paciente.
			Nesse caso em específico, vou verificar a idade dele para saber em qual lista de randomização vou alocar. Essa rotina foi feita para os estudos do butantan

			Lista1 = Até idade 60 anos
			Lista2 = Acima de 60 anos

			Retornos:
				1 - OK
				2 - IDADE INVÝLIDA
				3 - RANDOMIZADO
			*/
			$id_usuario = $_SESSION['user_id'];
			$varIdade = $this->retornaIdadeAtual("TRI_DC_NASCIMENTO");
			try{

				if(!is_numeric($varIdade)){
					$arrFinal = array(
						"resp" 			=> "FALHA",
						"descricao"		=> "Idade invalida"
					);
					$array = array_map( 'htmlentities' , $arrFinal );
					$json = html_entity_decode( json_encode( $array ) );
					echo $json;
					return;
				}else{
					//Se for do tipo randomizacao, começo a rotina de randomizaçao
					if($this->randomizado==""){	//Caso ainda não tiver sido randomizado, vou randomizar. Se já tiver, não faço nada! NAAAAAAAAAAAAAAAAAAADA!
						$varCentro = new CentroPesquisa($this->centro);
						//Pego a data e hora atual
						$radn_data_atual = date("d/m/Y");
						$rand_time_atual = date("H:i");


						/*
						06-23 = lista 1
						24-35 = lista 2


						Se a idade for de 24 a 35, j� falo que a lista � a 2.
						Se n�o for, procuro se tem vaga na lista 1.
						Tendo vaga na lista 1, aloco a�. N�o tendo, jogo pra lista 2

						Depois atualizo o contador das idades

						*/
						if($varIdade>=24){
							$varLista = 2;
						}else{
							$queryR = "select * from crf_rand_semente
								where lista = 1
									and paciente_alocado = ''
										order by sequencia";
							$resQtd = $varConexao->pesquisa($queryR);
							if(mysqli_num_rows($resQtd)>0){
								//Tem dispon�vel, vou de lista 1
								$varLista = 1;
							}else{
								//N�o tem dispon�vel, vou de lista 2
								$varLista = 2;
							}
						}
						//Incrementar a qtd do contador (TOTAL)
						if($varIdade<=23){
							$query = "update proj_projeto set contlista1 = contlista1 + 1";
						}else{
							$query = "update proj_projeto set contlista2 = contlista2 + 1";
						}
						$varConexao->pesquisa($query);

						//Verifico na tabela de tratamentos a proxima sequencia disponível
						$randCentro = $varCentro->id;
						$randPaciente = $this->nro_triagem;

						/*$queryR = "select * from crf_rand_semente
										where lista = ".$varLista."
											and paciente_alocado = ''
											and centro = '".$this->centro."'
											and projeto =  1
												order by sequencia";*/
						$queryR = "select * from crf_rand_semente
							where lista = ".$varLista."
								and paciente_alocado = ''
								and projeto =  1
									order by sequencia";

						$resR = $conexao->pesquisa($queryR);
						$rowR = mysqli_fetch_assoc($resR);
						$proxSeq = $rowR['sequencia'];
						$tratamento = $rowR['tratamento'];
						$proxId  = $rowR['id'];

						//Pego o próximo número de randomização do centro em questão
						$queryC = "select max(voluntario) as ult from cfg_centro where id = " . $this->centro;
						$resC = $conexao->pesquisa($queryC);
						$rowC = mysqli_fetch_assoc($resC);
						$proxPA = $rowC['ult']+1;

						//Atualizo planilha cfg_centro
						$query = "update cfg_centro set log_usuario = '$id_usuario', voluntario = '$proxPA' where id = " . $this->centro;
						$conexao->pesquisa($query);

						//Atualizo planilha crf_rand_semente
						$query = "update crf_rand_semente set log_usuario = '$id_usuario',
																paciente_alocado = '".$this->nro_triagem."',
																centro = ".$this->centro."
																	where id = " . $proxId;
						$conexao->pesquisa($query);
						$proxPA = str_pad($proxPA, 3, "000", STR_PAD_LEFT);
						$campo_data = date("d/m/Y");
						$campo_hora = date("H:i");
						$query = "update crf_paciente_pre_rand set
									log_usuario = '$id_usuario',
									paciente_pos_rand = '$proxPA',
									randomizado = '1',
									meses = '$varIdade',
									randomizado_por = '".$_SESSION['user_id']."' ,
									data_rand = '".$campo_data."',
									hora_rand = '".$campo_hora."'
										where centro = '".$this->centro."'
											and nro_paciente = '$randPaciente'";
						$conexao->pesquisa($query);

						//Já esta randomizado. Aqui pego os formulários e incluo no paciente
						//Pego os formulários que são randomizado
						$queryF = "select * from crf_forms
									where tipo <> 'Screening'
										and tipo <> 'PAGINA'
										and projeto = 1
										order by ordem";
						$resF   = $conexao->pesquisa($queryF);
						while($rowF = mysqli_fetch_assoc($resF)){
							$query = "insert into crf_entrada_forms (log_usuario, paciente, form_id,
								centro, projeto, ordem) values ('$id_usuario', '$randPaciente','".$rowF['id']."','".$this->centro."', '1', 0);";
							$conexao->pesquisa($query);
							//Pego o FORM criado
							$query = "select id from crf_entrada_forms where projeto = ".$_SESSION['projeto']." and paciente = '".$randPaciente."' and centro = '".$this->centro."' and form_id = '".$rowF['id']."'";
							$res   = $conexao->pesquisa($query);
							$row   = mysqli_fetch_assoc($res);
							$query = "select * from crf_forms_campos where formulario = " . $rowF['id'];
							$resC  = $conexao->pesquisa($query);
							while($rowC = mysqli_fetch_assoc($resC)){
								$query = "insert into crf_entrada_campos (form_id, campo_id, monitoria_status, log_usuario, log_usuario_dupla, ordem)
											values
												('".$row['id']."', '".$rowC['id']."', 0, '$id_usuario', 0, '".$rowC['ordem']."');";
								$conexao->pesquisa($query);
							}
						}


						$this->setaValor("TRI_RAND_DATA_RANDOMIZACAO", $campo_data);
						$this->setaValor("TRI_RAND_HORA_RANDOMIZACAO", $campo_hora);
						$this->setaValor("TRI_RAND_SEQUENCIA", $proxSeq);
						$this->setaValor("V2_VAC_SEQUENCIA", $proxSeq);
						$this->setaValor("TRI_RAND_QUEM_RANDOMIZOU", $_SESSION['nome_usuario']);
						$query 	 = "select * from crf_rand_tratamento where id = " . $tratamento;
						$resTrat = $conexao->pesquisa($query);
						$rowTrat = mysqli_fetch_assoc($resTrat);
						$this->setaValor("TRI_RAND_TRATAMENTO", $rowTrat['nome']);
						$this->setaValor("V2_VAC_TRATAMENTO", $rowTrat['nome']);

						$varConexao = new Conexao();
						//Aqui seto os exames laboratoriais
						$query = "select id from crf_entrada_forms
									where projeto = " . $_SESSION['projeto'] . "
										and centro =  ". $this->centro . "
										and paciente = " . $this->nro_triagem . "
										and form_id in (18)
										order by form_id";
						$resForm = $varConexao->pesquisa($query);
						while($rowForm = mysqli_fetch_assoc($resForm)){
							$idForm 	= $rowForm['id'];
							$query 		= "SELECT id FROM crf_entrada_campos where form_id = $idForm";
							$resCampos 	= $varConexao->pesquisa($query);
							while($rowCampos = mysqli_fetch_assoc($resCampos)){
								$idCampo 	= $rowCampos['id'];
								$varCampo 	= new Campo($idCampo);
								$nomeCampo 	= $varCampo->TipoCampo->nome;
								if(strpos($nomeCampo, "REFER")==true){
									$query = "SELECT * FROM
													exa_faixa_referencia
														where sexo = '".$this->sexo."'
														and codigo_campo like('%".$nomeCampo."%') and centro = " . $this->centro;
									$resRef = $varConexao->pesquisa($query);
									if(mysqli_num_rows($resRef)>0){	//AChou a referencia
										$rowRef = mysqli_fetch_assoc($resRef);
										$varReferencia = $rowRef['valor'];
										$this->setaValor($nomeCampo, $varReferencia);
									}
								}
							}
						}
					}
				}
			} catch (Exception $e) {
				return 2;
			}
		}
	}
	/*$varTeste = new Participante(297, 10);
	echo $varTeste->retornaNroFormatado();
	echo $varTeste->retornaTCLE("TRI_TCLE_TERMO_DATA");
	//echo $varTeste->setaValor("TRI_DC_INICIAIS", "TESTE");

	echo "<br>FIM";*/