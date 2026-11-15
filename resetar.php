<?
	include_once "seguranca.php";
	
	
	
  echo "Deletando tabela 'crf_entrada_campos_pergunta'...<br>";
  $query = "TRUNCATE TABLE crf_entrada_campos_pergunta";
  $conexao->pesquisa($query);
  
  echo "Deletando tabela 'crf_entrada_campos_comentarios'...<br>";
  $query = "TRUNCATE TABLE crf_entrada_campos_comentarios";
  $conexao->pesquisa($query);
  
  echo "Deletando tabela 'crf_entrada_campos'...<br>";
  $query = "TRUNCATE TABLE crf_entrada_campos";
  $conexao->pesquisa($query);
  
  echo "Deletando tabela 'crf_entrada_forms'...<br>";
  $query = "TRUNCATE TABLE crf_entrada_forms";
  $conexao->pesquisa($query);
  
  echo "Zerando randomização...<br>";
  $query = "update crf_rand_semente set randomizado = 0, paciente_alocado = '', log_usuario = ''";
  echo $query."<br>";
  $conexao->pesquisa($query);
  
  echo "Zerando nros de voluntarios...<br>";
  $query = "update cfg_centro set voluntario = 0, vol_pre_rand = 0";
  $conexao->pesquisa($query);
  
  echo "Zerando crf_paciente_pre_rand.....<br>";
  $query = "TRUNCATE TABLE crf_paciente_pre_rand";
  $conexao->pesquisa($query);
  
  echo "Zerando tabela crf_entrada_campos_comentarios<br>";
  $query = "TRUNCATE TABLE crf_entrada_campos_comentarios";
  $conexao->pesquisa($query);
  
  echo "Zerando tabela crf_entrada_campos_pergunta<br>";
  $query = "TRUNCATE TABLE crf_entrada_campos_pergunta";
  $conexao->pesquisa($query);
  
  echo "Zerando tabela de log....<br>";
  $conexao->pesquisa("TRUNCATE TABLE log_alteracoes",$conexao);

  
?>
  
