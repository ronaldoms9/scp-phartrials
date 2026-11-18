<?php
/*foreach($_POST as $nome_campo => $valor){
               $comando = $nome_campo . "=" . $valor;
               echo $comando."<br>";
}*/
	
	session_start();	
	include_once "class/email.class.php";
	if($_SESSION['valido']<1){
		echo "<script>window.location.replace('expirado.php');</script>";
		exit();		
	};
	
	include "conexao.php";

    function tira_lixo($str){
    $acento = array ("-", ",", "+", "#", "á", "à", "ã", "É", "é", "ê", "Á", "À", "Ã", "ç", "Ç", "í", "Í", " ", "[", "]", "(", ")");
    $letra = array ("_", "_","_", "_", "a", "a", "a", "E", "e", "e", "A", "A", "A", "c", "C", "i", "I", "_", "_", "_", "_", "_");
    return str_replace ($acento, $letra, $str);
    }


    //Pego a variável de controle
    $act = strtoupper(base64_decode($_GET[base64_encode('opc')]));
    

	switch($act) {
		/////////////////////////////////////////////////
		// Fazendo login
		/////////////////////////////////////////////////
    	case "LOGIN":
             //inicialização de variáveis
             $login = strtoupper($_POST['form_usuario']);
             $senha = strtoupper($_POST['form_senha']);
			 
			 
			$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Luis Bueno", 0);							
			$message = "Tentativa de login no sistema em  " . date('d/m/Y H:i') . " por " . $login ;			
			$varEmail->enviar("TENTATIVA DE LOGIN ", $message);
			 
			 
			 
             $ip = $_SERVER["REMOTE_ADDR"];
             $query = "SELECT id,  upper(usuario) as usuario, upper(senha) as senha, nome, tipo_usuario, centro, ultimo_acesso, troca_senha FROM usu_usuario WHERE registro_valido = 1 and upper(usuario) = '".$login."'";
             $res = mysql_query($query, $conexao);
             $data_atual = date('Y-m-d H:i');
             if(mysql_num_rows($res)==0){
                $volta = "login.php?erro=1";
                $query = "insert into usu_acessos (usuario,senha,data_hora,ip,resultado) values ('$login','$senha','$data_atual','$ip','NAO ACHOU NOME USUARIO');";
                $user = mysql_query($query,$conexao);
             }
             else
             {
                 $row = mysql_fetch_array($res);
                 if ( strtoupper($senha) != strtoupper($row['senha'])) {
                    $volta = "login.php?erro=2";
                    $query = "insert into usu_acessos (usuario,senha,data_hora,ip,resultado) values ('$login','$senha','$data_atual','$ip','SENHA ERRADA');";
                     $user = mysql_query($query,$conexao);
                 }else{
                    $query = "update usu_pessoas set acesso_web = acesso_web +1, ultimo_acesso = '".DataAtualToLIS()."' where ID = " . $row[0];
                    $user = mysql_query($query,$conexao);
                    //início da sessao
                    session_start();
                    $query = "insert into usu_acessos (usuario,senha,data_hora,ip,resultado) values ('$login','$senha','$data_atual','$ip','LOGIN OK');";
                    $user = mysql_query($query,$conexao);
                    
                    $_SESSION['user_nome'] = $row['usuario'];
                    $_SESSION['user_password'] = $row['senha']; 
                    
                    $_SESSION['nome_usuario'] = $row['nome'];
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['centro'] = $row['centro'];
					
										
                    $_SESSION['user_tipo'] = $row['tipo_usuario'];
                    $_SESSION['user_senha'] = $row['senha'];
                    $_SESSION['tipo_usuario'] = $row['tipo_usuario'];
                    
                    $_SESSION['valido'] = 1;
                    $_SESSION['sessao'] = rand(5698,98745);
                    $query = "select * from usu_usuario_tipo where id = " .$row['tipo_usuario'];
                    $res_tipo = mysql_query($query,$conexao);
                    $row_tipo = mysql_fetch_assoc($res_tipo);
                    $_SESSION['nome_tipo_usuario'] = $row_tipo['nome'];


                    $data_atual = DataAtualToLIS();
                    $ult_acesso = $row['ultimo_acesso'];
                    $diferenca = intval($data_atual) - intval($ult_acesso);
                    
                    //if(($diferenca>30)||(strtoupper($row['troca_senha'])=="ON")){
                    //    $volta = "senha_expirada.php";
                    //}else{
                        $volta = "inicial.php";
                        $query = "update usu_usuario set ultimo_acesso = '".$data_atual."' where id = " . $row['id'];
                        mysql_query($query,$conexao);
                    //}
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
            $volta = "index2.php";
    }
$volta = "Location: ". $volta;
header($volta);
?>
