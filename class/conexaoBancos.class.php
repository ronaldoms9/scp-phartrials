<?php
	error_reporting(~E_ALL);
	ini_set('display_errors', 0);
	header('Content-Type:text/html; charset=ISO-8859-1');

	class ConexaoBancos{
		private $user ;
		private $host;
		private $pass ;
		private $db;
		private $teste = 0;	//1 = Banco remoto, testando....   0 = Localhost, produção

		public function __construct($banco){
			$this->user = "root";
			if($this->teste==1){
				$this->host = "www.qsbsoft.com.br";
				$this->db = $banco;
			}else{
				$this->host = "localhost";
				$this->db = $banco;
			}
			$this->pass = "senha123";
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
		public function retornaNomeBanco(){
			return $this->host . " - " . $this->db;
		}
		public function retornaTeste(){return $this->teste;}
	}