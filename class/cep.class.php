<?php
	include_once "conexao.class.php";
    include_once "../funcoes.php";
	class CEP{
		public $id;
		public $nome;
		public $aprovacao;
		public $validade;
		public $composicao;
		public $comp_validade;
		public $log_usuario;
		public $registro_valido;

		function __construct($id){
			$conexao = new Conexao();

			if($id>0){
				$query = "select * from cad_cep where id = " . $id;
				$resPatro = $conexao->pesquisa($query);
				$rowPatro = mysqli_fetch_assoc($resPatro);

				$this->id              = $id;
				$this->nome            = $rowPatro['nome'];
				$this->aprovacao       = $rowPatro['aprovacao'];
				$this->validade        = $rowPatro['validade'];
				$this->composicao      = $rowPatro['composicao'];
				$this->comp_validade   = $rowPatro['comp_validade'];
				$this->log_usuario     = $rowPatro['log_usuario'];
				$this->registro_valido = $rowPatro['registro_valido'];
			}else{
				$this->id              = 0;
			}
		}

		function inclui(){
			$conexao = new Conexao();
			$query = "insert into cad_cep (nome, aprovacao, validade, composicao, comp_validade, log_usuario, registro_valido) values ('".$this->nome."', '".$this->aprovacao."', '".$this->validade."', '".$this->composicao."', '".$this->comp_validade."', '".$_SESSION['usuario']."', 1)";
            echo $query;
			$conexao->pesquisa(formata_utf8_decode($query));
            return "OK";
		}
		function salva(){
			$conexao = new Conexao();
			$query = "update cad_cep set nome = '".$this->nome."', aprovacao = '".$this->aprovacao."', validade = '".$this->validade."', composicao = '".$this->composicao."', comp_validade = '".$this->comp_validade."', log_usuario = '".$_SESSION['usuario']."' where id = ".$this->id;
			$conexao->pesquisa(formata_utf8_decode($query));
            echo $query;
			return "OK";
		}

	}