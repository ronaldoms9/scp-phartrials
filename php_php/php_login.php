<?php	
	function DataAtualToLIS(){		return date('Y-m-d H:i');	}
	$data_atual = DataAtualToLIS();
	$notAllowedCommands = array(
		'DELETE',
		'TRUNCATE',
		'DROP',
		'USE',
		'UNION',
		'GRANT'
	);
	$tem = false;
	//Loop em todas as variáveis vindas de _GET para verificar se não tem nenhum SQL junto
	foreach ($_POST as $key => $value) { 
		if(preg_match('[' . implode(' |', $notAllowedCommands ) . ']i', strtoupper($value)) == true) {$tem = true;}
	}
	if($tem == false){
		
		$act = substr($_GET['act'],0,20);
		
		if($act=="SOLICITA"){			
			include_once "../class/email.class.php";			
			$nome 		= $_POST['nome'];
			$email 		= $_POST['email'];
			$cpf 		= $_POST['cpf'];
			$centro 	= $_POST['centro'];								
			$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Luis Eduardo", 1);
			$message = utf8_encode(file_get_contents('../modelos/modelo_solicitacao_acesso.html'));			
			$message = str_replace('%nome%', $nome, $message);							
			$message = str_replace('%email%', $email, $message);
			$message = str_replace('%cpf%', $cpf, $message);
			$message = str_replace('%centro%', $centro, $message);			
			$varEmail->enviar("Solicitação de Acesso", $message);	
			echo "Solicitação realizada com sucesso!";
		}
		if($act=="RESETAR"){
			include_once "../class/conexao.class.php";
			include_once "../class/usuario.class.php";
			
			$conexao = new Conexao();			
			$email = $_POST['email'];
			$email = strtoupper($email);
			if(filter_var($email, FILTER_VALIDATE_EMAIL)) {			
				$query 		= "select id from usu_usuario where UPPER(email) = '".$email."'";
				$resEmail 	= $conexao->pesquisa($query);
				if(mysqli_num_rows($resEmail)>0){
					$rowEmail = mysqli_fetch_assoc($resEmail);
					$idUsr    = $rowEmail['id'];
					$varUsuario = new Usuario($idUsr);
					$varUsuario->resetaSenha();
					echo utf8_decode("Email com as instruções para resetar sua senha enviado com sucesso!");					
				}else{
					include_once "../class/email.class.php";
					$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Luis Eduardo", 0);
					$message = utf8_decode("Email: " . $email . "<br>Pedido para resetar a senha com email inválido!");
					$varEmail->enviar("Reset de senha inválido", $message);
					echo utf8_decode("Email com as instruções para resetar sua senha enviado com sucesso!");
				}					
			}else{
				include_once "../class/email.class.php";
				$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Luis Eduardo", 0);
				$message = utf8_decode("Email: " . $email . "<br>Pedido para resetar a senha com email inválido!");
				$varEmail->enviar("Reset de senha inválido", $message);
				echo utf8_decode("Email inválido. Por favor, revise suas informações");
			}
		}
		if($act=="LOGIN"){			
			include_once "../class/conexao.class.php";
			include_once "../class/email.class.php";
			include_once "../class/usuario.class.php";
			
			$conexao = new Conexao();			
			$login 	= strtoupper($_POST['usuario']);
			$senha 	= $_POST['senha'];
			$ip 	= $_SERVER["REMOTE_ADDR"];
			
			$query = "SELECT 
						id,  
						upper(usuario) as usuario, 
						nome, 
						tipo_usuario, 
						centro, 
						ultimo_acesso, 
						troca_senha, 
						data_senha 
							FROM usu_usuario 
								WHERE patrocinador = 0 
								and registro_valido = 1 
								and upper(usuario) = '".$login."'";
			$res = $conexao->pesquisa($query);

			if(mysqli_num_rows($res)==0){
				$volta = "login.php?erro=1";
				$query = "insert into usu_acessos 
							(usuario,
							senha,
							data_hora,
							ip,
							resultado, 
							data) values (
							'$login',
							'$senha',
							'$data_atual',
							'$ip',
							'NAO ACHOU NOME USUARIO', 
							'".DataAtualToLIS()."');";
				$user = $conexao->pesquisa($query);
				$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica", "eduqsb@gmail.com", "Luis Bueno", 0);
				$varEmail->enviar("Tentativa de login com EMAIL INVÁLIDO", "Usuário: $login <br> Senha: $senha <br> IP: $ip <br> Data/Hora: $data_atual");
				echo utf8_decode("Nome de usuário/Senha inválido");
			}else{
				$row = mysqli_fetch_array($res);
				$varUsuario = new Usuario($row['id']);
				$query = "SELECT id,  
							upper(usuario) as usuario, 
							upper(senha) as senha, 
							nome, tipo_usuario, 
							centro, 
							ultimo_acesso, 
							troca_senha, 
							data_senha 
								FROM usu_usuario 
									WHERE senha = md5('$senha') 
										and registro_valido = 1 
										and upper(usuario) = '".$login."'";
				$res 		= $conexao->pesquisa($query);
				$row 		= mysqli_fetch_array($res);				
				if(mysqli_num_rows($res)==0){
					echo utf8_decode("Nome de usuário/Senha inválido");
					//Senha inválida				
					$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Luis Bueno", 0);
					$varEmail->enviar("Tentativa de login com EMAIL INVÁLIDO", "Usuário: $login <br> Senha: $senha <br> IP: $ip <br> Data/Hora: ".DataAtualToLIS());				
					$query = "insert into usu_acessos (
									usuario,
									senha,
									data_hora,
									ip,
									resultado, 
									data) 
									values (
									'$login',
									'$senha',
									'$data_atual',
									'$ip',
									'SENHA ERRADA', 
									'".DataAtualToLIS()."');";
					$user = $conexao->pesquisa($query);
					$query = "select  count(id) as qtd 
									from usu_acessos 
										where resultado = 'SENHA ERRADA' 
											and usuario = '$login' 
											and data = '".DataAtualToLIS()."'";
					$res   = $conexao->pesquisa($query);
					$row   = mysqli_fetch_assoc($res);
					if($row['qtd']>=3){						
						$volta = "bloqueado.php";
						$query = "update usu_usuario set registro_valido = 0 where upper(usuario) = '$login'";
						$conexao->pesquisa($query);
						
						$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Luis Bueno", 1);
						$varEmail->enviar("Usuário BLOQUEADO por tentar mais de 3x fazer o login", "Usuário: $login <br> Senha: $senha <br> IP: $ip <br> Data/Hora: $data_atual");						
					}
				}else{					
					//início da sessao					
					if(!isset($_SESSION)){  session_start();    }
					$query = "insert into usu_acessos (usuario,senha,data_hora,ip,resultado, data) values ('$login','$senha','$data_atual','$ip','LOGIN OK', '".DataAtualToLIS()."');";
					$user = $conexao->pesquisa($query);

					$_SESSION['user_nome'] 			= $row['usuario'];
					$_SESSION['user_password'] 		= $row['senha']; 					
					$_SESSION['nome_usuario'] 		= $row['nome'];
					$_SESSION['user_id'] 			= $row['id'];
					$_SESSION['usuario_id'] 			= $row['id'];
					$_SESSION['centro'] 			= $row['centro'];										
					$_SESSION['user_tipo'] 			= $row['tipo_usuario'];
					$_SESSION['user_senha']			= $row['senha'];
					$_SESSION['tipo_usuario'] 		= $row['tipo_usuario'];
					$_SESSION['valido'] 			= 1;
					$_SESSION['horario_sessao'] 	= date("d/m/Y H:i");;
					$_SESSION['sessao'] 			= rand(5698,98745);

					$query_usu = "select * from usu_usuario_tipo where id = " . $_SESSION['user_tipo'];
					$res_usu = $conexao->pesquisa($query_usu);
					$row_usu = mysqli_fetch_assoc($res_usu);
					$_SESSION['tipo_nome'] = $row_usu['nome'];					

					$query = "select * from usu_usuario_tipo where id = " .$row['tipo_usuario'];
					$res_tipo = $conexao->pesquisa($query);
					$row_tipo = mysqli_fetch_assoc($res_tipo);
					$_SESSION['nome_tipo_usuario'] = $row_tipo['nome'];

					
					$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Luis Bueno", 0);
					$varEmail->enviar("Login no sistema", "Usuário: $login <br> Data/Hora: $data_atual <br>IP: $ip");

					$data_atual = DataAtualToLIS();
					$ult_acesso = $row['ultimo_acesso'];
					$dat_senha = $row['data_senha'];
					$diferenca = intval($data_atual) - intval($dat_senha);
					if(($diferenca>365)||(strtoupper($row['troca_senha'])=="ON")){
						if(strtoupper($row['troca_senha'])=="ON"){
							$volta = "senha_expirada.php";
						}else{
							if($diferenca>360){
								$volta = "senha_expirada.php";
							}else{							
								$volta = "inicial.php";
								$query = "update usu_usuario set acesso_web = acesso_web +1, ultimo_acesso = '".$data_atual."' where id = " . $row['id'];
								$conexao->pesquisa($query);							
							}
						}
					}else{
						$query = "update usu_usuario set acesso_web = acesso_web +1, ultimo_acesso = '".$data_atual."' where id = " . $row['id'];
						$conexao->pesquisa($query);
						$volta = "inicial.php";
					}					
					echo $volta;
				}
			}
		}
	}