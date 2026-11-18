<?php
	include_once "conexao.class.php";
	class Patrocinador{
		public $id;
		public $nome;
		public $responsavel;
		public $endereco;
		public $email1;
		public $email2;
		public $telefone1;
		public $telefone2;

		public $log_usuario;
		public $registro_valido;

		function __construct($id){
			$conexao = new Conexao();

			if($id>0){
				$query = "select * from cad_patrocinador where id = " . $id;
				$resPatro = $conexao->pesquisa($query);
				$rowPatro = mysqli_fetch_assoc($resPatro);

				$this->id              = $id;
				$this->nome            = $rowPatro['nome'];
				$this->responsavel     = $rowPatro['responsavel'];
				$this->endereco        = $rowPatro['endereco'];
				$this->email1          = $rowPatro['email1'];
				$this->email2          = $rowPatro['email2'];
				$this->telefone1       = $rowPatro['telefone1'];
				$this->telefone2       = $rowPatro['telefone2'];
				$this->log_usuario     = $rowPatro['log_usuario'];
				$this->registro_valido = $rowPatro['registro_valido'];
			}else{
				$this->id              = 0;
			}
		}

		function inclui(){
			$conexao = new Conexao();
			$query = "insert into cad_patrocinador (nome, responsavel, endereco, email1, email2, telefone1, telefone2, log_usuario, registro_valido) values ('".$this->nome."', '".$this->responsavel."', '".$this->endereco."', '".$this->email1."', '".$this->email2."', '".$this->telefone1."', '".$this->telefone2."', '".$_SESSION['usuario']."', 1)";
			return $conexao->pesquisa($query);
			return "OK";
		}
		function salva(){
			$conexao = new Conexao();
			$query = "update cad_patrocinador set nome = '".$this->nome."', responsavel = '".$this->responsavel."', endereco = '".$this->endereco."', email1 = '".$this->email1."', email2 = '".$this->email2."', telefone1 = '".$this->telefone1."', telefone2 = '".$this->telefone2."', log_usuario = '".$_SESSION['usuario']."' where id = ".$this->id;
			$conexao->pesquisa($query);
			return "OK";
		}
	}

	class PatrocinadorProjeto{
		public $id;
		public $nome;
		public $pessoa_responsavel;
		public $pessoa_1;
		public $pessoa_2;

		public $endereco;

		public $projeto;
		public $log_usuario;
		public $registro_valido;
		public $sql;
		public $conexao;
		function __construct($projeto){
			$this->conexao            = new Conexao();
			$query                    = "select * from cfg_patrocinador where projeto = " . $projeto;
			$this->sql                = $query;
			$resPatro                 = $this->conexao->pesquisa($query);
			$rowPatro                 = mysqli_fetch_assoc($resPatro);
			$this->id                 = $rowPatro['id'];
			$this->nome               = $rowPatro['nome'];
			$this->pessoa_responsavel = $rowPatro['pessoa_responsavel'];
			$this->pessoa_1           = $rowPatro['pessoa_1'];
			$this->pessoa_2           = $rowPatro['pessoa_2'];
			$this->endereco           = $rowPatro['endereco'];
			$this->projeto            = $rowPatro['projeto'];
			$this->log_usuario        = $rowPatro['log_usuario'];
			$this->registro_valido    = $rowPatro['registro_valido'];
		}
		function inclui(){
			$query = "insert into cfg_patrocinador (nome, responsavel, endereco, email1, email2, telefone1, telefone2, projeto, log_usuario, registro_valido) values ('".$this->nome."', '".$this->responsavel."', '".$this->endereco."', '".$this->email1."', '".$this->email2."', '".$this->telefone1."', '".$this->telefone2."', '".$this->projeto."', '".$_SESSION['usuario']."', 1)";
			return $this->conexao->pesquisa($query);
			return "OK";
		}
		function salva(){
			$query = "update cfg_patrocinador set nome = '".$this->nome."', responsavel = '".$this->responsavel."', endereco = '".$this->endereco."', email1 = '".$this->email1."', email2 = '".$this->email2."', telefone1 = '".$this->telefone1."', telefone2 = '".$this->telefone2."', projeto = '".$this->projeto."', log_usuario = '".$_SESSION['usuario']."' where id = ".$this->id;
			$this->conexao->pesquisa($query);
			return "OK";
		}
	}
	class Pessoa{
		public $id;
		public $nome;
		public $email;
		public $telefone;
		public $patrocinador;
		public $registro_valido;
		public $sql;
		public $conexao;
		function __construct($id){
			$this->conexao = new Conexao();
			$query = "select * from cad_patrocinador_pessoa where id = " . $id;
			$this->sql = $query;
			$resPessoa = $this->conexao->pesquisa($query);
			$rowPessoa = mysqli_fetch_assoc($resPessoa);
			$this->id              = $rowPessoa['id'];
			$this->nome            = $rowPessoa['nome'];
			$this->email           = $rowPessoa['email'];
			$this->telefone        = $rowPessoa['telefone'];
			$this->patrocinador    = $rowPessoa['patrocinador'];
			$this->registro_valido = $rowPessoa['registro_valido'];
		}
		function inclui(){
			$query = "insert into cad_patrocinador_pessoa (nome, email, telefone, patrocinador) values ('".$this->nome."', '".$this->email."', '".$this->telefone."', '".$this->patrocinador."')";
			return $this->conexao->pesquisa($query);
			return "OK";
		}
		function salva(){
			$query = "update cad_patrocinador_pessoa set nome = '".$this->nome."', email = '".$this->email."', telefone = '".$this->telefone."', patrocinador = '".$this->patrocinador."' where id = ".$this->id;
			$this->conexao->pesquisa($query);			return $this->conexao->pesquisa($query);
			return "OK";
		}
		function exclui(){
			$query = "update cad_patrocinador_pessoa set registro_valido = 0 where id = ".$this->id;
			return $this->conexao->pesquisa($query);
			return "OK";
		}
	}