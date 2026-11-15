<?php
	if(!isset($_SESSION)){  session_start();    }
	include_once __DIR__."/conexao.class.php";
	include_once __DIR__."/../funcoes.php";

	class CentroPesquisa{
		public $id;
		public $centro_pai;
		public $numero;
		public $nome;
		public $apelido;
		public $email;
		public $telefone;
		public $comentario;
		public $endereco;
		public $bairro;
		public $cidade;
		public $investigador;
		public $coordenador;
		public $tipo;
		public $dados_tr;
		public $dados_cv;
		public $dados_lab;
		public $dados_centro;
		public $dados_bombeiros;
		public $projeto;
		public $cep;
		public $conexao; //Ponteiro para a conexao
		public $quem_assina;

		function __construct($id){
			$this->conexao = new Conexao();
			if($id>0){

				$query = "select * from cfg_centro where id = " . $id;
				$resCli = $this->conexao->pesquisa($query);
				if(mysqli_num_rows($resCli)>0){
					$rowCli = mysqli_fetch_assoc($resCli);
					$this->id 					= $id;
					$this->centro_pai			= $rowCli['centro_pai'];
					$this->numero 				= $rowCli['numero'];
					$this->nome					= $rowCli['nome'];
					$this->apelido				= $rowCli['apelido'];
					$this->email				= $rowCli['email'];
					$this->telefone				= $rowCli['telefone'];
					$this->comentario			= $rowCli['comentario'];
					$this->endereco				= $rowCli['endereco'];
					$this->bairro				= $rowCli['bairro'];
					$this->cidade				= $rowCli['cidade'];
					$this->investigador			= $rowCli['investigador'];
					$this->coordenador			= $rowCli['coordenador'];
					$this->tipo					= $rowCli['tipo'];
					$this->projeto				= $rowCli['projeto'];
					$this->dados_tr				= $rowCli['dados_tr'];
					$this->dados_cv				= $rowCli['dados_cv'];
					$this->dados_lab				= $rowCli['dados_lab'];
					$this->dados_centro			= $rowCli['dados_centro'];
					$this->dados_bombeiros		= $rowCli['dados_bombeiros'];
					$this->cep					= $rowCli['cep'];
					$this->quem_assina			= $rowCli['quem_assina'];
				}
			}
		}
		function inclui(){
			$varConexao = new Conexao();
			$query = "select max(CAST(numero as INTEGER)) as numero from cfg_centro where projeto = " . $this->projeto;
			$res = $varConexao->pesquisa($query);
			$row = mysqli_fetch_assoc($res);
			$numero = $row['numero'];
			$this->numero = $numero + 1;

			$query = "insert into cfg_centro
						(centro_pai,
						numero,
						nome,
						email,
						telefone,
						endereco,
						investigador,
						coordenador,
						projeto,
						dados_tr,
						dados_cv,
						dados_lab,
						dados_centro,
						dados_bombeiros,
						cep,
						quem_assina)
						values ('".$this->centro_pai."',
						'".$this->numero."',
						'".$this->nome."',
						'".$this->email."',
						'".$this->telefone."',
						'".$this->endereco."',
						'".$this->investigador."',
						'".$this->coordenador."',
						".$this->projeto.",
						'".$this->dados_tr."',
						'".$this->dados_cv."',
						'".$this->dados_lab."',
						'".$this->dados_centro."',
						'".$this->dados_bombeiros."',
						'".$this->cep."',
						'".$this->quem_assina."')";
			$this->conexao->pesquisa(formata_utf8_decode($query));
			echo $query;
		}
		function salva(){
			$query = "update
						cfg_centro
							set   centro_pai   = '".$this->centro_pai."',
							      numero       = '".$this->numero."',
							      nome         = '".$this->nome."',
							      email        = '".$this->email."',
							      telefone     = '".$this->telefone."',
							      endereco     = '".$this->endereco."',
							      investigador = '".$this->investigador."',
							      coordenador  = '".$this->coordenador."',
							      projeto      = '".$this->projeto."',
							      dados_tr     = '".$this->dados_tr."',
							      dados_cv     = '".$this->dados_cv."',
							      dados_lab    = '".$this->dados_lab."',
							      dados_centro = '".$this->dados_centro."',
							      dados_bombeiros = '".$this->dados_bombeiros."',
							      cep          = '".$this->cep."',
							      quem_assina  = '".$this->quem_assina."'
							where id           = ".$this->id;
			$this->conexao->pesquisa(formata_utf8_decode($query));
			echo $query;
		}
		function exclui(){
			$query = "delete from cfg_centro where id = " . intval($this->id);
			$this->conexao->pesquisa($query);
		}
	}
	class CentroCadastro{
		public $id;
		public $nome;
		public $email;
		public $telefone;
		public $endereco;
		public $investigador;
		public $coordenador;
		public $dados_tr;
		public $dados_cv;
		public $dados_lab;
		public $dados_centro;
		public $dados_bombeiros;
		public $conexao; //Ponteiro para a conexao

		function __construct($id){
			$this->conexao = new Conexao();
			if($id>0){
				$query = "select * from cad_centro where id = " . $id;
				$resCli = $this->conexao->pesquisa($query);
				if(mysqli_num_rows($resCli)>0){
					$rowCli = mysqli_fetch_assoc($resCli);
					$this->id 					= $id;
					$this->nome					= $rowCli['nome'];
					$this->email				= $rowCli['email'];
					$this->telefone				= $rowCli['telefone'];
					$this->endereco				= $rowCli['endereco'];
					$this->investigador			= $rowCli['investigador'];
					$this->coordenador			= $rowCli['coordenador'];
					$this->dados_tr				= $rowCli['dados_tr'];
					$this->dados_cv				= $rowCli['dados_cv'];
					$this->dados_lab				= $rowCli['dados_lab'];
					$this->dados_centro			= $rowCli['dados_centro'];
					$this->dados_bombeiros		= $rowCli['dados_bombeiros'];
				}
			}else{
				$this->id           = 0;
				$this->nome         = "";
				$this->email        = "";
				$this->telefone     = "";
				$this->endereco     = "";
				$this->investigador = "";
				$this->coordenador  = "";
				$this->dados_tr     = "";
				$this->dados_cv     = "";
				$this->dados_lab    = "";
				$this->dados_centro = "";
				$this->dados_bombeiros = "";
			}
		}
		function inclui(){
			$query = "insert into cad_centro (nome,
												email,
												telefone,
												endereco,
												investigador,
												coordenador,
												dados_tr,
												dados_cv,
												dados_lab,
												dados_centro,
												dados_bombeiros)
												values (
												'".$this->nome."',
												'".$this->email."',
												'".$this->telefone."',
												'".$this->endereco."',
												'".$this->investigador."',
												'".$this->coordenador."',
												'".$this->dados_tr."',
												'".$this->dados_cv."',
												'".$this->dados_lab."',
												'".$this->dados_centro."',
												'".$this->dados_bombeiros."')";
			$this->conexao->pesquisa(formata_utf8_decode($query));
			echo $query;
		}
		function salva(){
			$query = "update cad_centro set
								nome = '".$this->nome."',
								email = '".$this->email."',
								telefone = '".$this->telefone."',
								endereco = '".$this->endereco."',
								investigador = '".$this->investigador."',
								coordenador = '".$this->coordenador."',
								dados_tr = '".$this->dados_tr."',
								dados_cv = '".$this->dados_cv."',
								dados_lab = '".$this->dados_lab."',
								dados_centro = '".$this->dados_centro."',
								dados_bombeiros = '".$this->dados_bombeiros."'
									where id = ".$this->id;
			$this->conexao->pesquisa(formata_utf8_decode($query));
			echo $query;
		}
	}