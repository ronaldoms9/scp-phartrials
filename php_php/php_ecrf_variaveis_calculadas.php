<?php
	session_start();

	include_once "../class/conexao.class.php";
	$conexao = new Conexao();

	error_reporting(E_ERROR);
    ini_set('display_errors', 1);


	$projeto = 1;
	/*$query = "select nro_participante, nro_paciente, centro from crf_paciente_pre_rand where projeto = $projeto";
	$res_vol = $conexao->pesquisa($query);
	while($row_vol = mysqli_fetch_assoc($res_vol)){
		$prox = $row_vol['nro_paciente'];
		$prox_vol = $prox;
		$query = "select * from crf_forms where projeto = $projeto and id = 34 order by ordem";
		$resForms = $conexao->pesquisa($query);
		while($row_forms = mysqli_fetch_assoc($resForms)){
			$query = "select * from crf_entrada_forms where projeto = $projeto and paciente = ".$prox." and centro = ".$row_vol['centro']." and form_id = " . $row_forms['id'];
			$res_form = $conexao->pesquisa($query);
			if(mysqli_num_rows($res_form)==0){
				$query = "insert into crf_entrada_forms (projeto, paciente, centro, form_id) values ('$projeto', '".$prox."', '".$row_vol['centro']."', '".$row_forms['id']."');";
				$conexao->pesquisa($query);
			}else{
				$row_form = mysqli_fetch_assoc($res_form);

				$query = "select id from crf_forms_campos where formulario = ".$row_forms['id'];
  			    $resCampos = $conexao->pesquisa($query);
				while($rowCampos= mysqli_fetch_assoc($resCampos)){
					$query = "select id from crf_entrada_campos
									where form_id = ".$row_form['id']."
									and campo_id = " . $rowCampos['id'];
					$res_campo = $conexao->pesquisa($query);
					if(mysqli_num_rows($res_campo)==0){
						$query = "insert into crf_entrada_campos (projeto, form_id, campo_id) values ($projeto, ".$row_form['id'].", ".$rowCampos['id'].");";
						$conexao->pesquisa($query);
					}
				}
			}
		}
	}*/

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
								and nro_paciente = '$pre'
								and projeto = " . $callProjeto;
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

			$temp = $this->dataInclusao;
			if($temp!=""){
				$temp = explode(" ", $temp);
				$hora = $temp[1];
				$temp = explode("-", $temp[0]);
				$this->dataInclusaoFormatado = $temp[2] . "/" . $temp[1] . "/" . $temp[0] . " " . $hora;
			}
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
	}
	function idadeMeses($dia, $mes, $ano, $dataTCLE){
		$data1 = new DateTime(date('Y-m-d'));
		$dataTCLE = explode("/", $dataTCLE);
		$data1 = new DateTime($dataTCLE[2]."-".$dataTCLE[1]."-".$dataTCLE['0']);
		$data2 = new DateTime("$ano-$mes-$dia");
		$intervalo = $data1->diff( $data2 );
		$tot = ($intervalo->y*12) + $intervalo->m;
		if($tot==""){
			$tot = 0;
		}
		return $tot;
	}
	function isDate($value){
		if (!$value) {
			return false;
		}
		try {
			new \DateTime($value);
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

	function retornaIdadeAtualTCLE($campoNascimento, $TCLE){
		$varDataCRF = $campoNascimento;
		if($varDataCRF!=""){
			$varDataTEMP = explode("/", $varDataCRF);
			$varDataLIS = $varDataTEMP[2]."-".$varDataTEMP[1]."-".$varDataTEMP[0];
			if(isDate($varDataLIS)){
				return idadeMeses($varDataTEMP[0], $varDataTEMP[1], $varDataTEMP[2], $TCLE);
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	function idadeApartir($nasc, $base){        //Recebe em formato dd/mm/aaaa
		$base = explode("/", $base);
		$nasc = explode("/", $nasc);
		$hoje = mktime(0, 0, 0, $base[1], $base[0], $base[2]);

		$nascimento = mktime(0, 0, 0, $nasc[1], $nasc[0], $nasc[2]);
		$idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
		return $idade;
	}
	function busca_valor($paciente, $centro, $variavel){
		$conexao = new Conexao();

		$query 	= "select * from vw_valor_campo
					where projeto = 1
					and nro_triagem = '$paciente'
					and centro = '$centro'
					and nome = '$variavel'";

		$res   	= $conexao->pesquisa($query);
		$row	= mysqli_fetch_assoc($res);
		return $row['valor'];
	}
	function seta_valor($centro, $participante, $campo, $valor){

		$conexao = new Conexao();


		$query = "select * from vw_valor_campo
						where nro_triagem = '".$participante."'
						and centro = '".$centro."'
						and nome = '".$campo."'";
		$res_valor = $conexao->pesquisa($query);
		$row_valor = mysqli_fetch_assoc($res_valor);

		$id_campo = $row_valor['FORMULARIO_CAMPO_ID'];
		$id_form  = $row_valor['entrada_forms_id'];
		$campo_id = $row_valor['campo_id'];

		$query = "update crf_entrada_campos set valor = '$valor' where id = $id_campo and form_id = $id_form";
		//echo $query . "<br>";
		$conexao->pesquisa($query);
	}

	/*if($_GET['act']=="GERAR"){

		$conexao = new Conexao();
		$query = "SELECT
						cfg_centro.id,
						crf_paciente_pre_rand.centro,
						crf_paciente_pre_rand.nasc,
						cfg_centro.apelido,
						cfg_centro.numero,
						crf_paciente_pre_rand.nro_paciente,
						crf_paciente_pre_rand.paciente_pos_rand,
						crf_paciente_pre_rand.data_rand,
                        crf_rand_tratamento.nome as tratamento
					FROM crf_paciente_pre_rand
						inner join cfg_centro on crf_paciente_pre_rand.centro = cfg_centro.id
                        left join crf_rand_semente on crf_paciente_pre_rand.centro = crf_rand_semente.centro AND
                        	crf_rand_semente.paciente_alocado = crf_paciente_pre_rand.nro_participante and
                            crf_rand_semente.projeto = crf_paciente_pre_rand.projeto
                       left join crf_rand_tratamento on crf_rand_semente.tratamento = crf_rand_tratamento.id
							and crf_paciente_pre_rand.nro_participante = crf_paciente_pre_rand.nro_triagem
								order by crf_paciente_pre_rand.nro_paciente
								";

		$conexao = new Conexao();
		$resPart = $conexao->pesquisa($query);


		$varMorto = false;


		while($rowPart = mysqli_fetch_assoc($resPart)){		//Lista em todos os pacientes do projeto
			$centro = $rowPart['centro'];
			$numero = $rowPart['nro_paciente'];
			$varParticipante = new Participante($centro, $numero);

			//Nome do centro
			seta_valor($centro, $numero, "CALC_CENTRO_NOME", $rowPart['apelido']);
			//Tratamento alocado
			seta_valor($centro, $numero, "CALC_TRATAMENTO", $rowPart['tratamento']);
			//Número do participante
			seta_valor($centro, $numero, "NUMERO_PARTICIPANTE", $varParticipante->retornaNroFormatado());


			//Data do TCLE
			$dataTCLE 			= busca_valor($numero, $centro, "TRI_TCLE_TERMO_DATA");
			$dataNasc 			= $rowPart['nasc'];
			$dataElegibilidade 	= busca_valor($numero, $centro, "TRI_ELE_DATA");

			if($dataTCLE!=""){
				$idadeMeses 	= retornaIdadeAtualTCLE($dataNasc, $dataTCLE);
				if($dataElegibilidade!=""){
					$idadeMesesEle 	= retornaIdadeAtualTCLE($dataNasc, $dataElegibilidade);
				}else{
					$dataElegibilidade = 0;
				}
				if($idadeMeses<24){
					seta_valor($centro, $numero, "VAR_CALC_GRUPO_IDADE", 1);
				}
				if($idadeMeses>=24){
					seta_valor($centro, $numero, "VAR_CALC_GRUPO_IDADE", 2);
				}
				seta_valor($centro, $numero, "VAR_CALC_IDADE", retornaIdadeAtualTCLE($dataNasc, $dataTCLE));
				seta_valor($centro, $numero, "VAR_CALC_IDADE_ELEGIVEL", $idadeMesesEle);
				//echo "Idade Meses: " . retornaIdadeAtualTCLE($dataNasc, $dataTCLE) . "<br>";
				seta_valor($centro, $numero, "VAR_CALC_IDADE_ANOS",  idadeApartir($dataNasc, $dataTCLE));
			}



		}

	}*/

	/*$varTeste = new Participante(303, 1);
	echo "<br>fim";*/