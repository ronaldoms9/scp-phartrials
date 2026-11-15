<?php
	require_once __DIR__ . '/env.php';
	class ConexaoMensageiro{
        private $user ;
		private $host;
		private $pass ;
		private $db;
		public function __construct(){
			$this->user = env('DB_USER');
            $this->host = env('DB_HOST', 'localhost');
            $this->db = env('MSG_DB_NAME', 'mensageiro');
			$this->pass = env('DB_PASS');
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
	}
    class Mensageiro{
        public $conexao;
        public function __construct(){
            $this->conexao = new ConexaoMensageiro();
        }
        public function enviaWhats($numero,$texto){
            $dataAtual = date("d/m/Y");
            $dataLis = date("Ymd");
            $horaAtual = date("H:i:s");
            $horaAtualLis = str_replace(":", "", $horaAtual);
            $query = "insert into envio
                            (projeto,
                            tipo,
                            msg_entrada_data,
                            msg_entrada_data_lis,
                            msg_entrada_hora,
                            msg_numero,
                            msg_mensagem,
                            status)
                            values
                            ('PT',
                            'WHATS',
                            '$dataAtual',
                            '$dataLis',
                            '$horaAtual',
                            '$numero',
                            '$texto',
                            '0')";
            echo $query;
            $this->conexao->pesquisa($query);
        }
        public function enviaEmail($email,$titulo,$texto){
            $data = date("d/m/Y");
            $dataLis = date("Ymd");
            $hora = date("H:i:s");
            $query = "insert into envio
                    (projeto,
                    tipo,
                    email_mensagem,
                    email_titulo,
                    email_toMail,
                    email_data,
                    email_data_lis,
                    email_hora
                    )
                    values
                    (
                        'PT',
                        'EMAIL',
                        '$texto',
                        '$titulo',
                        '$email',
                        '$data',
                        '$dataLis',
                        '$hora'
                    );
            ";
            $this->conexao->pesquisa($query);
        }
    }
    /*$varMensageiro = new Mensageiro();
    $varMensageiro->enviaEmail("eduqsb@gmail.com", "Teste", "Teste");
    echo "<br>Fim";*/