<?php
	function ordenaDados(array $matriz): array{
		// Faz uma cópia para não alterar o array original
		$resultado = $matriz;
		usort($resultado, function ($a, $b) {
			// Segurança caso não exista índice 0
			$valorA = $a[0] ?? null;
			$valorB = $b[0] ?? null;
			// Ordenação natural (texto ou número)
			return $valorA <=> $valorB;
		});
		return $resultado;
	}
	function getParticipanteStatus($nro_paciente, $codigo_paciente){
		$varConexao = new Conexao();
		$query = "select status from agenda_check_participante where numero = ".$nro_paciente." and codigo = '".$codigo_paciente."'";
		//echo $query;
		$res = $varConexao->pesquisa($query);
		$row = mysqli_fetch_assoc($res);
		if($row['status']=="T"){return "Titular";}
		if($row['status']=="R"){return "Reserva";}
		if($row['status']=="E"){return "Excluído";}
		if($row['status']=="D"){return "Desistente";}
		if($row['status']=="F"){return "Falha de seleção";}
		return "";
	}
	function getParticipanteGrupo($nro_paciente, $codigo_paciente){
		$varConexao = new Conexao();
		$query = "select grupo from agenda_check_participante where numero = ".$nro_paciente." and codigo = '".$codigo_paciente."'";
		//echo $query;
		$res = $varConexao->pesquisa($query);
		$row = mysqli_fetch_assoc($res);
		return $row['grupo'];
	}
	function retornaDiferencaHorario($horaEsperada, $horaRealizada) {

		// Cria objetos DateTime a partir das horas
		$h1 = DateTime::createFromFormat('H:i', $horaEsperada);
		$h2 = DateTime::createFromFormat('H:i', $horaRealizada);

		// Verifica se as horas são válidas (formato H:i)
		if ($h1 === false || $h2 === false) {
			return "";
		}

		// Calcula a diferença entre as duas
		$diff = $h1->diff($h2);

		// Converte a diferença para minutos
		$minutos = ($diff->h * 60) + $diff->i;

		// Se a horaRealizada for menor que a horaEsperada, ajusta o sinal
		if ($h2 < $h1) {
			$minutos *= -1;
		}

		return $minutos;
	}
	function formata_utf8_decode($entrada){
		return mb_convert_encoding($entrada, 'ISO-8859-1', 'UTF-8');
	}
	function formata_utf8_encode($entrada){
		return mb_convert_encoding($entrada, 'UTF-8', 'ISO-8859-1');
	}
	function nomeRandomico(){
		$letras = array("a","b","c","d","e","f","g");
		$temp = $letras[rand(0,6)].$letras[rand(0,6)].$letras[rand(0,6)].$letras[rand(0,6)].$letras[rand(0,6)].$letras[rand(0,6)];
		$temp .= "_" . rand(rand(123,46564),rand(123132,55555555));
		$temp .= "_" . rand(rand(123,46564),rand(123132,55555555));
		return $temp;
	}
	function retorna_nome($entrada){
	  $nome = explode("-", $entrada);
	  if(count($nome)==2){
		return $nome[1];
	  }else{
		 return $nome[1] . " " . $nome[2] . " " . $nome[3] . " " . $nome[4];
	  }
	}
	function geraDate($nome){
		echo "
		$('#".$nome."').datepicker({
			dateFormat: 'dd/mm/yy',
			locale: 'pt-br',
			dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
			dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
			dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
			monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
			monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
			nextText: 'Proximo',
			prevText: 'Anterior'
		});
		";
	}
	function unlinkRecursive($dir, $deleteRootToo) {
		if(!$dh = @opendir($dir))
		{
			return;
		}
		while (false !== ($obj = readdir($dh)))
		{
			if($obj == '.' || $obj == '..')
			{
				continue;
			}
			if (!@unlink($dir . '/' . $obj))
			{
				unlinkRecursive($dir.'/'.$obj, true);
			}
		}
		closedir($dh);
		if ($deleteRootToo)
		{
			@rmdir($dir);
		}
		return;
	}
	if (!function_exists('traduzir')) { // relatorios (rep_*) definem uma versao propria
		function traduzir($texto_entrada){
			include_once "class/conexao.class.php";
			$conexao = new Conexao();
			$texto = "";
			$query_tr = "select * from traducao where portugues = '$texto_entrada';";
			$res_tr = $conexao->pesquisa($query_tr);
			$lang = $_SESSION['lang'];

			if(mysqli_num_rows($res_tr)==0){
					$texto = $texto;
					$query = "insert into traducao (portugues) values ('$texto_entrada')";
					$conexao->pesquisa($query);
			}else{
				$row_tr = mysqli_fetch_assoc($res_tr);
				if($lang == "p"){$texto = $texto;}
				if($lang == "i"){$texto = $row_tr['ingles'];}
				if($lang == "e"){$texto = $row_tr['espanhol'];}
			}
			if($texto == ""){$texto = $texto_entrada;}
			return $texto;
		}
	}
	function retorna_caminho_completo($grupo,$espaco){
		include_once "class/conexao.class.php";

		$espacos = "";
		$conexao = new Conexao();
		$query = "select pai, nome, ordem from arq_tmf where id = ".$grupo;
		$res_g = $conexao->pesquisa($query);
		$row_g = mysqli_fetch_assoc($res_g);
		for($i=0;$i<=$espaco;$i++){$espacos = $espacos . "&nbsp;";}
		if(is_numeric(substr($row_g['nome'],0,1))){
			$temp = "|-".$row_g['nome'];
		}else {
			$temp = "|-".$row_g['ordem'] . " - " . $row_g['nome'];
		}
		$pai = 0;
		$pai = $row_g['pai'];
		$temp2 = "";
		if($pai>0){
			$temp2 = retorna_caminho_completo($pai,$espaco+2) . "<br>" . $temp2;
		}

		return $temp2 . $temp;
	}
	function duplica_arvore($id, $novo){
		global $conexao;
		$query = "select * from arq_tipos_arquivos where pai = ".$id;
		$res = $conexao->pesquisa($query);
		if($res->num_rows>0){
			while($row = $res->fetch_assoc()){
				$query = "insert into arq_tipos_arquivos (nome, pai, ordem, patrocinador, site) values (";
				$query .= "'".$row['nome']."',";
				$query .= "'".$novo."',";
				$query .= "'".$row['ordem']."',";
				$query .= "'".$row['patrocinador']."',";
				$query .= "'".$row['site']."')";
				$conexao->pesquisa($query);
				$query = "select id from arq_tipos_arquivos where nome = '".$row['nome']."' and pai = '".$novo."'";
				$res_no   = $conexao->pesquisa($query);
				if($res_no->num_rows>0){
					$row_novo = $res_no->fetch_assoc();
					$id_novo = $row_novo['id'];
					duplica_arvore($row['id'],$id_novo);
				}
			}
		}
	}
	function qtd_uploads($id){
		global $conexao;
		$qtd_nivel = 0;
		$query = "select count(id) as qtd from arq_arquivos where projeto_id = ".$_SESSION['projeto']." and grupo_id = " . $id;
		$result = pesquisa($query);
		$row = mysqli_fetch_assoc($result);
		$qtd_nivel = $row['qtd'];
		$qtd_sub = 0;
		$query = "select id from arq_tipos_arquivos where pai = $id";
		$result = pesquisa($query);
		while($row = mysqli_fetch_assoc($result)){
			$qtd_sub = $qtd_sub + qtd_uploads($row['id']);
		}
		return $qtd_nivel + $qtd_sub;
	}
function qtd_uploads_isf($id){
	global $conexao;
	$qtd_nivel = 0;
	$query = "select count(id) as qtd from arq_arquivos_isf where grupo_id = " . $id;
	$result = pesquisa($query);
	$row = mysqli_fetch_assoc($result);
	$qtd_nivel = $row['qtd'];
	$qtd_sub = 0;
	$query = "select id from arq_tipos_arquivos_isf where pai = $id";
	$result = pesquisa($query);
	while($row = mysqli_fetch_assoc($result)){
		$qtd_sub = $qtd_sub + qtd_uploads($row['id']);
	}
	return $qtd_nivel + $qtd_sub;
}
function qtd_filhos($id){
	global $conexao;
	$query = "select id from arq_tipos_arquivos where pai = " . $id;
	$res   = $conexao->pesquisa($query);
	$tot = $res->num_rows;
	$tot_sub = 0;
	while($row = $res->fetch_assoc()){
		$tot_sub = $tot_sub + qtd_filhos($row['id']);
	}
	return $tot + $tot_sub;
}
function qtd_filhos_ok($id){
	global $conexao;
	$query = "select id from arq_arquivos where grupo_id = " . $id . " and status_id = 1";
	$res   = $conexao->pesquisa($query);
	$tot = $res->num_rows;
	if($tot>0){$tot_atual = 1;}
	$tot_sub = 0;
	$query = "select id from arq_tipos_arquivos where pai = " . $id;
	$res   = $conexao->pesquisa($query);
	while($row = $res->fetch_assoc()){
		$tot_sub = $tot_sub + qtd_filhos_ok($row['id']);
	}
	return $tot_atual + $tot_sub;
}
function valida_tamanho_texto($entrada,$tamanho_max){
	$temp = $entrada;
	//$palavras = split(" ", $temp);
	//$temp = "";
	//for($iTam=0;$iTam<count($palavras);$iTam++){
	//	if(strlen($palavras[$iTam])<$tamanho_max){
	//	     $temp .= " " . $palavras[$iTam];
	//	}else {
	//
	//	}
	//}
	return $temp;
}
function zero_por_hifen($entrada){
   if($entrada==0){return "-";}else{return $entrada;}
}
function DataHoraAtualToLIS(){
    $sDIA = date('d');
    $sMES = date('m');
    $sANO = date('Y');
    $sHOR = date('H');
    $sMIN = date('i');
    return $sANO.$sMES.$sDIA.$sHOR.$sMIN;
}
function DataAtualToLIS(){
    $sDIA = date('d');
    $sMES = date('m');
    $sANO = date('Y');
    //$sHOR = date('H');
    //$sMIN = date('i');
    return $sANO.$sMES.$sDIA.$sHOR.$sMIN;
}
function quebra_linha_ao_contrario($entrada,$delimitador,$coluna){
    $saida = "";
    $col = 1;
    for($i=strlen($entrada)-1;$i>=0;$i--){
        if(substr($entrada,$i,1)==$delimitador){
         $col = $col + 1;
        }else{
          if($col==$coluna){
            $saida = substr($entrada,$i,1) . $saida;
          }
        }
    }
    return $saida;
}
function quebra_linha($entrada,$delimitador,$coluna){
    $saida = "";
    $col = 1;
    $entrada = trim($entrada);
    for($i=0;$i<=strlen($entrada)-1;$i++){
        if(substr($entrada,$i,1)==$delimitador){
         $col = $col + 1;
        }else{
          if($col==$coluna){
            $saida .= substr($entrada,$i,1);
          }
        }
    }
    return $saida;
}
function HoraToLIS($hora){
    $sHOR = quebra_linha_ao_contrario($hora,":",2);
    $sMIN = quebra_linha_ao_contrario($hora,":",1);
    return $sHOR . $sMIN;
}
function troca_char($entrada,$orig,$troca){
           $temp = "";
           for($i=0;$i<strlen($entrada);$i++){
               if(substr($entrada,$i,1)==$orig){
                  $temp .= $troca;
               }else{
                  $temp .= substr($entrada,$i,1);
               }
           }
           return $temp;
  }
function listoDATA($entrada){
           if(strlen(trim($entrada))>0){
              if( (!nao_tem($entrada,"/")) && (strlen(trim($entrada))>=8)){
                  return substr($entrada,6,2)."/".substr($entrada,4,2)."/".substr($entrada,0,4);
              }
           }
}
function LIStoDATA_HORA($entrada){
           $entrada = explode(" ", $entrada);
           $data = $entrada[0];
           $hora = $entrada[1];
           $data = explode("-", $data);
           return $data[2]."/".$data[1]."/".$data[0]. " " . $hora;
}
function NOVO_LIStoDATA_HORA($entrada){
	$data = substr($entrada, 0, 8);
	$hora = substr($entrada, 8, 4);
	$hora = substr($hora, 0,2).":".substr($hora, 2, 2);
	return substr($data, 6, 2) . "/" . substr($data, 4,2) . "/" . substr($data, 0, 4) . " " . $hora;
}
function listoDATA_HIFEN($entrada){
           if(strlen(trim($entrada))>0){
              if( (!nao_tem($entrada,"/")) && (strlen(trim($entrada))==8)){
                  return substr($entrada,6,2)."-".substr($entrada,4,2)."-".substr($entrada,0,4);
              }
           }
}
function DataToLIS($entrada){
           if(strlen(trim($entrada))>0){
              if( (nao_tem($entrada,"/")) && (strlen(trim($entrada))==10)){
                  return substr($entrada,6,4).substr($entrada,3,2).substr($entrada,0,2);
              }
              if( (nao_tem($entrada,"/")) && (strlen(trim($entrada))==8)){
                  return substr($entrada,6,2).substr($entrada,3,2).substr($entrada,0,2);
              }
           }
}
function tira_0($entrada){
	   for($i=0;$i<=strlen($entrada)-1;$i++)  {
		if( (substr($entrada,$i,1)!="0") || (strlen($temp)>0))           {
		 $temp = $temp . substr($entrada,$i,1);           }
	   }
	   return $temp;
}
function hora_atual(){
	   $time = mktime(date('H')-2,date('i'),date('s')); //Caso não esteja em HORARIO DE VERAO, colocar -3
	   $hora = gmdate("H:i:s",$time);
	   return $hora;
}
function nao_tem($entrada, $caracter){
  $temp = false;
  for($i=0;$i<=strlen($entrada);$i++)
  {
	 if(substr($entrada,$i,1)==$caracter){
		 $temp = true;           }
  }
  return $temp;
}
function idade($dia,$mes,$ano){        //Recebe em formato dd/mm/aaaa
 $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
 $nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);
 $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
 return $idade;
}
function calc_idade($data_nasc){
	if(strlen($data_nasc)==0){ return ""; }
	$data_nasc = explode("-", listoDATA_HIFEN($data_nasc));
	$data = date("d-m-Y");
	$data = explode("-", $data);
	$anos = $data[2] - $data_nasc[2];
	if ( $data_nasc[1] >= $data[1] ){
	   if ( $data_nasc[0] <= $data[0] ){
		   return $anos;
	   }else{
			 return $anos-1;
	   }
	}
	else
	{
		return $anos;
	}
}
function retorna_codigo($nome, $nascimento, $sexo){
  $nomes = explode(" ", $nome);
  if($sexo==1){$vSEXO="M";}
  if($sexo==2){$vSEXO="F";}
  $vNOME = substr($nomes[0],0,1).substr($nomes[count($nomes)-1],0,1).substr($nascimento,6,2).substr($nascimento,4,2).substr($nascimento,2,2).$vSEXO;
  return $vNOME;
}
function busca_id($nome_id, $tabela, $campo, $valor, $tipo){
         $tipo=strtoupper($tipo);
         $campo=strtoupper($campo);
         $tabela=strtoupper($tabela);
         if($tipo=="INTEGER"){$query='select '.$nome_id.', '.$campo.' from "'.$tabela.'" where '.$nome_id.' = '.$valor;}
         if($tipo=="STRING"){$query='select '.$nome_id.', '.$campo.' from "'.$tabela.'" where '.$nome_id.' = "'.$valor.'"';}
         if($tipo=="TEXTO"){$query='select '.$nome_id.', '.$campo.' from "'.$tabela.'" where '.$nome_id.' = "'.$valor.'"';}
         if($tipo=="BOOLEAN"){$query='select '.$nome_id.', '.$campo.' from "'.$tabela.'" where '.$nome_id.' = '.$valor;}
         $user = ibase_query($query, $conexao);
         $row  = ibase_fetch_row($user);
         return $row[1];
}
function add_hora_na_hora($entrada,$qtd_add){
         $horario = "";
         $qtd = quebra_linha_ao_contrario($qtd_add,":",1); //Peguei os minutos
         $hora = quebra_linha_ao_contrario($qtd_add,":",2); //Peguei os minutos
         $hora = intval($hora)*60;
         $qtd = $hora+$qtd;
         if(strlen($entrada)>5){
           $horario = quebra_linha_ao_contrario($entrada," ",1);
         }else{
            $horario = $entrada;
         }
         $time = strtotime($horario);
         return date("H:i", strtotime('+'.$qtd.' minutes', $time));
}
function add_dia_data_hora($entrada,$horario){
         $data = quebra_linha($entrada," ",1); //Pego só a data
         $data = explode("/",$data);
         $ano = $data[2];
         $mes = $data[1];
         $dia = $data[0];
         $data_inicial = strtotime($ano."/".$mes."/".$dia);
         $inicio = tira_0(quebra_linha($horario,":",1));
         if($inicio==""){return $dia."/".$mes."/".$ano;}else{
            $divisao = intval(intval($inicio)/24);
            //return $divisao;
            return date('d/m/Y', strtotime("$divisao days",$data_inicial));
         }
}
function DigV($entrada){
    $total = 0;
    for($i=0;$i<strlen($entrada);$i++){
        $total = $total + ord($entrada[$i]);
    }
    $resto = ($total % 256);
    return strtoupper(dechex($resto));
}
function gera_parametro($variavel, $valor){
    $variavel = base64_encode($variavel);
    $valor    = $valor;
    return $variavel."=".$valor;
}
function calc_idade_base($data_nasc,$base){
		if(($data_nasc!="")&&($base!="")){
			if(strlen($data_nasc)==0){ return ""; }
			$data_nasc = explode("/", $data_nasc);
			$data = $base;
			$data = explode("/", $data);
			$anos = $data[2] - $data_nasc[2];
			if ( $data_nasc[1] >= $data[1] ){
			   if ( $data_nasc[0] <= $data[0] ){
				   return $anos;
			   }else{
					 return $anos-1;
			   }
			}
			else
			{
				return $anos;
			}
		}else{
			return "";
		}

  }
function IMC($peso, $altura){
    $peso = str_replace(",",".",$peso);
    $altura = str_replace(",",".",$altura);
    if( ($peso!="") && ($altura!="") ){
       return  number_format($peso/($altura*$altura),2);
    }
}
function busca_valor($paciente, $centro, $variavel){
	include_once "class/conexao.class.php";
	$conexao = new Conexao();
	$query 	= "select * from vw_valor_campo where nro_triagem = '$paciente' and centro = '$centro' and nome = '$variavel'";
	$res   	= $conexao->pesquisa($query);
	$row	= mysqli_fetch_assoc($res);
	return $row['valor'];
}
function buscaValorAgendamentoTotal($varAgendamento, $campoID){
	$conexao = new Conexao();
	$query   = "SELECT
					agenda.id,
					agenda.projeto,
					crf_agenda_campos.valor
					from agenda
						inner join crf_agenda_forms on agenda.id = crf_agenda_forms.agendamento
						inner join crf_agenda_campos on crf_agenda_forms.id = crf_agenda_campos.form_id
							and crf_agenda_campos.campo_id = $campoID
					where agenda.id = " . $varAgendamento;
	$resVal 	= $conexao->pesquisa($query);
	$rowVal = mysqli_fetch_assoc($resVal);
	return $rowVal['valor'];
}
function tamanhoArquivo($tamanho){
	   $kb = 1024;
	   $mb = 1048576;
	   $gb = 1073741824;
	   $tb = 1099511627776;
	   if($tamanho<$kb){
		 return $tamanho." bytes";
	   }else if($tamanho>=$kb&&$tamanho<$mb){
		 $kilo = number_format($tamanho/$kb,2);
		 return $kilo." KB";
	   }else if($tamanho>=$mb&&$tamanho<$gb){
		$mega = number_format($tamanho/$mb,2);
		return $mega." MB";
	   }else if($tamanho>=$gb&&$tamanho<$tb){
		$giga = number_format($tamanho/$gb,2);
		return $giga." GB";
	  }
	}
?>