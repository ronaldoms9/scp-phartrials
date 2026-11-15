<?php
	session_start();
	include "log.class.php";

	class Projeto{
		public $id;
		public $numero;
		public $nome;
		public $descricao;
		public $patrocinador;
		public $centro;
		public $periodos;
		public $grupos;
		public $n;
		public $versao;
		public $log_usuario;
		public $registro_valido;
		public $dash;
		public $dashproprio;
		public $conexao; //Ponteiro para a conexao
		function __construct($id){
			if($id>0){
				$this->conexao = new Conexao();
				$query = "select * from proj_projeto where id = " . $id;
				$resCli = $this->conexao->pesquisa($query);
				if(mysqli_num_rows($resCli)>0){
					$rowCli = mysqli_fetch_assoc($resCli);
					$this->id 					= $id;
					$this->numero 				= $rowCli['numero'];
					$this->nome					= $rowCli['nome'];
					$this->descricao			= $rowCli['descricao'];
					$this->patrocinador			= $rowCli['patrocinador'];
					$this->centro				= $rowCli['centro'];
					$this->periodos				= $rowCli['periodos'];
					$this->grupos				= $rowCli['grupos'];
					$this->n					= $rowCli['n'];
					$this->versao				= $rowCli['versao'];
					$this->log_usuario			= $rowCli['log_usuario'];
					$this->registro_valido		= $rowCli['registro_valido'];
					$this->dash					= $rowCli['dash'];
					$this->dashproprio			= $rowCli['dashproprio'];
					$this->resultado			= "OK";
				}else{
					$this->resultado			= "NAO";
				}
			}
		}
		function incluiPaciente($centro, $nasc, $sexo){
			$conexao = new Conexao();
			//Pego o próximo nro de sujeito screening disponivel
			$query = "select vol_pre_rand+1 as prox, numero from cfg_centro where id = '".$centro."'";
			$resCentro = $conexao->pesquisa($query);
			$rowCentro = mysqli_fetch_assoc($resCentro);
			$varCentroNum 	= $rowCentro['numero'];
			$prox_vol 		= $rowCentro['prox'];

			//Insiro na tabela crf_paciente_pre_rand
			$query = "insert into crf_paciente_pre_rand
				(centro, nro_paciente, nro_paciente_formatado, data_hora, nro_triagem, nro_participante, projeto, nasc, sexo, incluido_por)
				values ('$centro',
						$prox_vol,
						'".str_pad($prox_vol,3,"000",STR_PAD_LEFT)."',
						now(),
						'".$prox_vol."',
						'$prox_vol',
						".$_SESSION['projeto'].",
						'$nasc',
						'$sexo',
						'".$_SESSION['user_id']."');";
			$conexao->pesquisa($query);
			//Loop nos formulários screening
			$query 		= "select * from crf_forms where UPPER(tipo) = 'SCREENING' and projeto = ".$_SESSION['projeto']." order by ordem";
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
				numero 			= 	'".$this->numero."',
				nome 			= 	'".$this->nome."',
				descricao		=	'".$this->descricao."',
				patrocinador	=	'".$this->patrocinador."',
				centro			=	'".$this->centro."',
				periodos		=	'".$this->periodos."',
				grupos			=	'".$this->grupos."',
				n				=	'".$this->n."',
				versao			=	'".$this->versao."',
				dash			=	'".$this->dash."',
				dashproprio		=	'".$this->dashproprio."',
				log_usuario		=	'".$_SESSION['user_id']."',
				registro_valido	=	'1',
				idade_minima		=	'".$this->idade_minima."',
				idade_maxima		=	'".$this->idade_maxima."'
				where id = " . $this->id;
			$query = utf8_decode($query);
			$resCli = $this->conexao->pesquisa($query);
		}
		function inclui(){
			$this->conexao = new Conexao();
			$query = "insert into proj_projeto (numero, nome, descricao, patrocinador, centro, periodos,
						grupos, n, versao, dash, log_usuario, registro_valido, dashproprio, idade_minima, idade_maxima) values (
						'".$this->numero."',
						'".$this->nome."',
						'".$this->descricao."',
						'".$this->patrocinador."',
						'".$this->centro."',
						'".$this->periodos."',
						'".$this->grupos."',
						'".$this->n."',
						'".$this->versao."',
						'".$this->dash."',
						'".$_SESSION['user_id']."',
						1,
						'".$this->dashproprio."',
						'".$this->idade_minima."',
						'".$this->idade_maxima."'
					);";
			$query = utf8_decode($query);
			$resCli = $this->conexao->pesquisa($query);
		}
		function geraTmf($projeto){
			$conexao = new Conexao();
			//Deleto registros da tabela arq_tmf

			$query  = "delete from arq_tmf where projeto = " . $_SESSION['projeto'];
			$conexao->pesquisa($query);

			//PEgar os centros para montar POR CENTRO
			$query = "select id, numero, nome, apelido from cfg_centro where projeto = ".$_SESSION['projeto']." order by cast(numero as int)";
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
					echo $query;
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
						echo $query;
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
							echo $query;
							$resIns = $conexao->pesquisa($query);
							$varOrdemNvl3++;
						}




						$varOrdemNvl2++;
					}



					$varOrdem++;
				}






				$indCentro++;
			}
			echo $query;



		}
	}