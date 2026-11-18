<?php
	session_start();
	include_once "conexao.class.php";

	class CheckList{
		public $id;
		public $numero;
		public $codigo;
		public $status;
		public $grupo;
		public $projeto;

		public function __construct($projeto){
			$this->projeto = $projeto;
		}

		function Inclui(){
			$conexao = new Conexao();
			$query = "insert into agenda_check_participante (projeto, numero, codigo, status, grupo) values (
			'".$_SESSION['projeto']."', '', '', '', '2');";
			$conexao->pesquisa($query);
			echo $query;
		}
		function temEspecifico(){
			$varConexao = new Conexao();
			$query 		= "select id from agenda_check_fase where projeto = " . $this->projeto;
			$resCheck 	= $varConexao->pesquisa($query);
			if(mysqli_num_rows($resCheck)>0){return 1;}else{return 0;}
		}
	}
	class CheckCampo{
		public $id;
		public $periodo;
		public $ordem;
		public $nome;
		public $label;
		public $mascara;
		public $opcoes;
		public $cobranca;
		public $valor;
		public $calculo;
		public $calculo_tipo;
		public $calculo_campos;
		public $valor_minimo;
		public $valor_maximo;
		public $log_usuario;
		public $projeto;

		public function __construct($id){
			if($id>0){
				$this->popula($id);
			}
		}
		function  popula($id){
			$varConexao           = new Conexao();
			$query                = "select * from agenda_check where id = " . $id;
			$res                  = $varConexao->pesquisa($query);
			$row                  = mysqli_fetch_assoc($res);
			$this->id             = $row['id'];
			$this->periodo        = $row['periodo'];
			$this->ordem          = $row['ordem'];
			$this->nome           = $row['nome'];
			$this->label          = $row['label'];
			$this->mascara        = $row['mascara'];
			$this->opcoes         = $row['opcoes'];
			$this->cobranca       = $row['cobranca'];
			$this->valor          = $row['valor'];
			$this->calculo        = $row['calculo'];
			$this->calculo_tipo   = $row['calculo_tipo'];
			$this->calculo_campos = $row['calculo_campos'];
			$this->valor_minimo   = $row['valor_minimo'];
			$this->valor_maximo   = $row['valor_maximo'];
			$this->log_usuario    = $row['log_usuario'];
		}

	}

	class CheckListCampoParticipante{
		public $id;
		public $participante;
		public $campo;
		public $campoClass;
		public $valor;
		public $log_usuario;
		public $pago;
		public $data_pagamento;
		public $data_pagamento_lis;
		public $observacoes;

		public function __construct(){

		}
		function populaCampo($query){
			$varConexao  = new Conexao();
			$resPesquisa = $varConexao->pesquisa($query);
			if(mysqli_num_rows($resPesquisa)>0){
				$row 		 				= mysqli_fetch_assoc($resPesquisa);
				$this->id					= $row['id'];
				$this->participante			= $row['participante'];
				$this->campo				= $row['campo'];
				$this->campoClass			= new CheckCampo($this->campo);
				$this->valor				= $row['valor'];
				$this->log_usuario			= $row['log_usuario'];
				$this->pago					= $row['pago'];
				$this->data_pagamento		= $row['data_pagamento'];
				$this->data_pagamento_lis	= $row['data_pagamento_lis'];
				$this->observacoes			= $row['observacoes'];
			}

		}
		function inicializaId($id){
			if($id>0){
				$query 		= "select * from agenda_check_participante_campo where id = " . $id;
				$this->populaCampo($query);
			}else{
				return null;
			}
		}
		function inicializaParticipante($participante, $campo){
			if($this->criaCampo($participante, $campo)==true){
				$query 		= "select * from agenda_check_participante_campo where participante = $participante and campo = $campo";
				$this->populaCampo($query);
			}
		}
		function criaCampo($participante, $campo){
			$varConexao = new Conexao();
			$query = "select
							id
								from agenda_check_participante_campo
							where participante = '".$participante."'
								and campo = '".$campo."';";
			$resCampo = $varConexao->pesquisa($query);
			if(mysqli_num_rows($resCampo)==0){
				$query = "insert into agenda_check_participante_campo (participante, campo) values ('".$participante."', '".$campo."');";
				$varConexao->pesquisa($query);
			}
			return true;
		}
	}
	$varTeste =
	$varCampo = new CheckListCampoParticipante();
	$varCampo->inicializaParticipante(12276, 54);

	/*echo "Valor->".$varCampo->valor;
	echo "<br>FIM";*/
