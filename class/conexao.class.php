<?php
	require_once __DIR__ . '/env.php';
	require_once __DIR__ . '/error_log.php';

	header('Content-Type:text/html; charset=ISO-8859-1');
	$titulo = utf8_decode("SPC - Sistema de Pesquisa Clínica");
	//$mailIP		= "92.112.176.237";
	//$mailIP		= "mail.qsbsoft.com.br";
	//$mailIP		= "localhost";
	$mailIP			="mail.smtp2go.com";
	$urlProjeto = "F:/projetos/pt/pt/";

	$urlProjeto = "/home/qsbsoft/public_html/subdomain/monitoria/";
	$url 		= "https://monitoria.qsbsoft.com.br/";

	set_time_limit(0);
	ini_set('memory_limit', '4095M'); // 4 GBs minus 1 MB

	class Conexao{
		private $user ;
		private $host;
		private $pass ;
		private $db;
		private $teste = 0;	//1 = Banco remoto, testando....   0 = Localhost, produção

		public function __construct(){
			$this->user = env('DB_USER');
			if($this->teste==1){
				$this->host = env('DB_REMOTE_HOST');
				//$this->host = "localhost";
				$this->db = env('DB_NAME');
			}else{
				$this->host = env('DB_HOST', 'localhost');
				//$this->host = "172.168.13.110";
				$this->db = env('DB_NAME');
			}
			$this->pass = env('DB_PASS');
		}
		public function conecta(){
			$link = new mysqli($this->host, $this->user, $this->pass, $this->db);
			if($link->connect_errno){
				error_log("MYSQL CONEXAO (" . $link->connect_errno . "): " . $link->connect_error
					. " | URI: " . ($_SERVER['REQUEST_URI'] ?? '-'));
			}
			return $link;
		}
		public function pesquisa($query){
			$conexaoN = $this->conecta();
			$conexaoN->query("SET GLOBAL time_zone = '-3:00';");
			$result = $conexaoN->query($query);
			if($result === false){
				// mysqli_report esta OFF (env.php): sem isso, erro de SQL nao aparece em lugar nenhum
				error_log("MYSQL QUERY (" . $conexaoN->errno . "): " . $conexaoN->error
					. " | URI: " . ($_SERVER['REQUEST_URI'] ?? '-')
					. " | SQL: " . substr(preg_replace('/\s+/', ' ', $query), 0, 500));
			}
			return $result;
		}
		public function retornaNomeBanco(){
			return $this->host . " - " . $this->db;
		}
		public function retornaTeste(){return $this->teste;}
	}