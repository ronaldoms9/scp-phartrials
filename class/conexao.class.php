<?
	error_reporting(~E_ALL);
	ini_set('display_errors', 0);

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
			$this->user = "pt";
			if($this->teste==1){
				$this->host = "monitoria.phartrials.com.br";
				//$this->host = "localhost";
				$this->db = "pt_monitoria";
			}else{
				$this->host = "localhost";
				//$this->host = "172.168.13.110";
				$this->db = "pt_monitoria";
			}
			$this->pass = "UDPRVSKVvp8zJgrL";
			//$this->pass = "s2KH7m1NsatP";
		}
		public function conecta(){
			$link = new mysqli($this->host, $this->user, $this->pass, $this->db);
			return $link;
		}
		public function pesquisa($query){
			$conexaoN = $this->conecta();
			$conexaoN->query("SET GLOBAL time_zone = '-3:00';");
			$result = $conexaoN->query($query);
			return $result;
		}
		public function retornaNomeBanco(){
			return $this->host . " - " . $this->db;
		}
		public function retornaTeste(){return $this->teste;}
	}