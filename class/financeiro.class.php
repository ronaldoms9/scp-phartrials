<?php
	include_once "conexao.class.php";


	class FinItem {
		public $id;
		public $item;
		public $ordem;
		public $valor;


		public function __construct($id){
			if($id>0){
				$varConexao = new Conexao();
				$query 		= "select * from fin_itens where id = " . $id;
				$res	= $varConexao->pesquisa($query);
				$row	= mysqli_fetch_assoc($res);

				$this->id 				= $id;
				$this->item				= $row['item'];
				$this->ordem			= $row['ordem'];
				$this->valor			= $row['valor'];
			}
		}
	}
	class FinParticipante{
		public $id;
		public $projeto;
		public $numero;
		public $codigo;
		public $status;
		public $grupo;
		public $periodo;
		public $log_usuario;

		public function __construct($id){
			if($id > 0){
				$conexao = new Conexao();

				$query 	= "select * from agenda_check_participante where id = " . $id;
				$res   	= $conexao->pesquisa($query);
				if(mysqli_num_rows($res)>0){
					$row	= mysqli_fetch_assoc($res);
					$this->projeto 		=	$row['projeto'];
					$this->numero 		=	$row['numero'];
					$this->codigo 		=	$row['codigo'];
					$this->status 		=	$row['status'];
					$this->grupo 		=	$row['grupo'];
					$this->periodo 		=	$row['periodo'];
					$this->log_usuario	=	$row['log_usuario'];
				}
			}
		}
	}
	class FinParticipanteItem{
		public $id;
		public $participante;
		public $varParticipante;
		public $item;
		public $varItem;
		public $visita;
		public $feito;
		public $data;
		public $data_lis;
		public $cobrado;
		public $data_cobrado;
		public $data_cobrado_lis;

		public function __construct($id){
			if($id > 0){
				$conexao = new Conexao();
				$query 	= "select * from fin_participante_item where id = " . $id;
				$res	= $conexao->pesquisa($query);
				if(mysqli_num_rows($res)>0){
					$row 	=	mysqli_fetch_assoc($res);

					$this->id					=	$id;
					$this->participante			=	$row['participante'];
					$this->varParticipante		=	new FinParticipante($row['participante']);
					$this->item					=	$row['item'];
					$this->varItem				=	new FinItem($row['item']);
					$this->visita				=	$row['visita'];
					$this->feito				=	$row['feito'];
					$this->data					=	$row['data'];
					$this->data_lis				=	$row['data_lis'];
					$this->cobrado				=	$row['cobrado'];
					$this->data_cobrado			=	$row['data_cobrado'];
					$this->data_cobrado_lis		=	$row['data_cobrado_lis'];
				}
			}
		}
	}
	/*
	$varTeste = new FinParticipanteItem(2);
	echo $varTeste->participante;
	echo "<br>FIM";
	*/
