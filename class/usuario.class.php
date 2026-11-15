<?
	if(!isset($_SESSION)){  session_start();    }
	include_once "conexao.class.php";
	include_once "email.class.php";
	include_once __DIR__."/../funcoes.php";

	class Usuario{
		public $id;
		public $usuario;
		public $nome;
		public $funcao;
		public $funcao_nome;
		public $descricao;
		public $email;
		public $centro;
		public $senha;
		public $acesso_web;
		public $ativo;
		public $tipo_usuario;
		public $ultimo_acesso;
		public $troca_senha;
		public $envia_email;
		public $data_senha;
		public $assinatura;
		public $patrocinador;
		public $celular;

		public $conexao;

		function __construct($id){
			$this->conexao = new Conexao();
			if($id>0){
				$query 		= "select usu_usuario.*,  usu_usuario_tipo.nome as funcao_nome
									from usu_usuario
									left join usu_usuario_tipo on usu_usuario.tipo_usuario = usu_usuario_tipo.id
									where usu_usuario.id = " . $id;
				$resUsu 	= $this->conexao->pesquisa($query);
				$rowUsu	= mysqli_fetch_assoc($resUsu);
				$this->id				= 	$rowUsu['id'];
				$this->usuario			=	$rowUsu['usuario'];
				$this->nome				=	$rowUsu['nome'];
				$this->funcao			=	$rowUsu['funcao'];
				$this->descricao		=	$rowUsu['descricao'];
				$this->email			=	$rowUsu['email'];
				$this->centro			=	$rowUsu['centro'];
				$this->senha			=	$rowUsu['senha'];
				$this->acesso_web		=	$rowUsu['acesso_web'];
				$this->ativo			=	$rowUsu['ativo'];
				$this->tipo_usuario		=	$rowUsu['tipo_usuario'];
				$this->ultimo_acesso	=	$rowUsu['ultimo_acesso'];
				$this->troca_senha		=	$rowUsu['troca_senha'];
				$this->envia_email		=	$rowUsu['envia_email'];
				$this->data_senha		=	$rowUsu['data_senha'];
				$this->assinatura		=	$rowUsu['assinatura'];
				$this->patrocinador		=	$rowUsu['patrocinador'];
				$this->funcao_nome		=	$rowUsu['funcao_nome'];
				$this->celular			=	$rowUsu['celular'];
			}
		}
		function resetaSenha(){
			$conexao = new Conexao();

			$novaSenha 	= rand(659,33215) . "atc" . rand(2369, 244488);
			$query 		= "update usu_usuario set
								senha = md5('$novaSenha'),
								troca_senha = 'on',
								registro_valido = 1
								where id = " . $this->id;
			$conexao->pesquisa($query);
			$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa ClÃ­nica - SPC", "eduqsb@gmail.com", "Luis Eduardo", 0);
			$message = utf8_encode("Email: " . $this->email . "<br>Pedido para resetar a senha efetuado com sucesso!");
			$varEmail->enviar("Reset de senha", $message);
			return "OK";
		}
		function login(){

		}
		function exibeListagem(){
			$conexao 	= new Conexao();
			$query 		= "select * from usu_usuario order by usuario";
			$resUsu		= $conexao->pesquisa($query);
			echo '<fieldset style="height: 400px;overflow-y: scroll;">
					<ul id="sidebar-nav" class="sidebar-nav margin-bottom-30px">';
			while($rowUsu = mysqli_fetch_assoc($resUsu)){
				$varUsuarioAtual = new Usuario($rowUsu['id']);
				echo '<li class="list-group-item">
						<table width="100%">
							<tr>
								<td width="80%"><a href="javascript:selecionaUsuario('.$varUsuarioAtual->id.');">'.$varUsuarioAtual->usuario.'</a></td>
								<td width=20%><!--<a href="javascript:deletaCampo('.$rowCampo["id"].');"><i class="fa fa fa-minus float-right" alt="Excluir"></i></a>--></td>
							</tr>
						</table>
					  </li>';
			}
			echo '</ul>';
		}
		function salvar(){
			$conexao = new Conexao();
			$query = "update usu_usuario set
					usuario 		=  '".$this->usuario."',
					nome			=  '".$this->nome."',
					email			=  '".$this->email."',
					centro			=  '".$this->centro."',
					descricao 		=  '".$this->descricao."',
					ativo			=  '".$this->ativo."',
					tipo_usuario	=  '".$this->tipo_usuario."',
					log_usuario		=  '".$this->log_usuario."',
					troca_senha		=  '".$this->troca_senha."',
					envia_email		=  '".$this->envia_email."',
					patrocinador	=  '".$this->patrocinador."',
					celular		=  '".$this->celular."'
				where id = " . $this->id;
			$conexao->pesquisa($query);
		}
		function novo(){
			$this->senha = rand(659,33215) . "phartrials_" . rand(2369, 244488);
			$conexao = new Conexao();
			$query = "insert into usu_usuario (usuario, nome, email, centro, descricao, ativo, tipo_usuario, log_usuario, troca_senha, envia_email, patrocinador, celular, senha)
					values (
					'".formata_utf8_decode($this->usuario)."',
					'".formata_utf8_decode($this->nome)."',
					'".formata_utf8_decode($this->email)."',
					'".$this->centro."',
					'".$this->descricao."',
					'".$this->ativo."',
					'".$this->tipo_usuario."',
					'".$this->log_usuario."',
					'".$this->troca_senha."',
					'".$this->envia_email."',
					'".$this->patrocinador."',
					'".$this->celular."',
					md5('".$this->senha."')
				)";
			echo $query."<br>";
			$conexao->pesquisa($query);
			$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", $this->email, $this->nome, 0);

			$actual_link = "https://$_SERVER[HTTP_HOST]";
			$actual_link = "https://monitoria.phartrials.com.br";

			$message = utf8_encode(file_get_contents(__DIR__.'/../modelos/modelo_novo_usuario.html'));
			$message = str_replace('%nome%', $this->nome,$message);
			$message = str_replace('%usuario%', $this->usuario,$message);
			$message = str_replace('%senha%', $this->senha,$message);
			$message = str_replace('%endereco%', $actual_link,$message);

			$varEmail->enviar("Novo usuário", $message);
			echo "OK";
		}
	}


