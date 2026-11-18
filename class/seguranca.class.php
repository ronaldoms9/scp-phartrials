<?php
	session_start();
	include_once "conexao.class.php";

	class AcessoFormulario{
		public $id;
		public $pagina;
		public $grupo_id;
		public $acessa;
		public $edita;
		public $deleta;
		public $fechaquery;
		public $monitora;
		public $sql;


		public function __construct($pagina){
			if($pagina!=""){
				$conexao = new Conexao();
				$query		= "select * from vw_pagina_grupo where pagina = '$pagina' and grupo_id = " . $_SESSION['user_tipo'];
				$res 	= $conexao->pesquisa($query);
				$row	= mysqli_fetch_assoc($res);

				/*$this->id				= $row['id'];
				$this->pagina			= $row['pagina'];
				$this->grupo_id			= $row['grupo_id'];
				$this->acessa			= $row['acessa'];
				$this->edita			= $row['edita'];
				$this->deleta			= $row['deleta'];
				$this->monitora			= $row['monitora'];
				$this->fechaquery		= $row['fechaquery'];
				$this->sql				= $query;*/
			}
		}
	}