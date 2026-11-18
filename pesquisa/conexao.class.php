<?php
	require_once __DIR__ . '/../class/env.php';
	class Conexao{
		private $user ;
		private $host;
		private $pass ;
		private $db;

		public function __construct(){
			$this->user = env('DB_ROOT_USER', 'root');
			$this->host = env('DB_HOST', 'localhost');
			$this->pass = env('DB_ROOT_PASS');
			$this->db = "atcgen_monitoria_novo";

		}
		public function conecta(){
			$link = new mysqli($this->host, $this->user, $this->pass, $this->db);
			return $link;
		}
		public function pesquisa($query){
			$conexaoN = $this->conecta();
			$result = $conexaoN->query($query);
			return $result;
		}
	}