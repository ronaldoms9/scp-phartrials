<?php
	class Conexao{
		private $user ;
		private $host;
		private $pass ;
		private $db;

		public function __construct(){
			$this->user = "root";
			$this->host = "localhost";
			$this->pass = "senha123";
			$this->db = "atcgen_monitoria_novo";

		}
		public function conecta(){
			$link = new mysqli("localhost", "root", "senha123", "atcgen_monitoria_novo");
			return $link;
		}
		public function pesquisa($query){
			$conexaoN = $this->conecta();
			$result = $conexaoN->query($query);
			return $result;
		}
	}