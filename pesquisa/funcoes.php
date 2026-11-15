<?php
	function traduzir($texto_entrada){
		$conexao = new Conexao();
		$texto = "";
		$query_tr = "select * from traducao where portugues = '$texto_entrada';";
		$res_tr = $conexao->pesquisa($query_tr);
		$lang = $_SESSION['lang'];
		if(mysqli_num_rows($res_tr)==0){$texto = $texto;}else{
			$row_tr = mysqli_fetch_assoc($res_tr);
			if($lang == "p"){$texto = $texto;}
			if($lang == "i"){$texto = $row_tr['ingles'];}
			if($lang == "e"){$texto = $row_tr['espanhol'];}
		}
		if($texto == ""){$texto = $texto_entrada;}
		return $texto;
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
	function LIStoDATA_HORA($entrada){
		/*$entrada = explode(" ", $entrada);
		$data = $entrada[0];
		$hora = $entrada[1];
		$data = explode("-", $data);
		return $data[2]."/".$data[1]."/".$data[0]. " " . $hora;*/
		//202101200230
		return substr($entrada, 6, 2) ."/". substr($entrada, 4, 2) ."/". substr($entrada, 0, 4) . " - " .substr($entrada, 8, 2) . ":" . substr($entrada, 10, 2);
	}