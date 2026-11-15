<?php
    include_once __DIR__."/../class/conexao.class.php";
    $varConexao = new Conexao();
    $query = "select * from agenda_check_participante where periodo = 1";
    $res = $varConexao->pesquisa($query);
    while($row = mysqli_fetch_assoc($res)){
        $varProjeto = $row['projeto'];
        $varCodigo  = $row['codigo'];

        $varLetras = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		$letra = $varLetras[rand(0, count($varLetras) - 1)];
		$codigo = $letra . rand(1, 9999554);

        if($varCodigo!=""){
            $query = "update agenda_check_participante set hash = '$codigo' where projeto = '$varProjeto' and codigo = '$varCodigo'";
            $varConexao->pesquisa($query);
            echo $query . "<br>";
        }
    }
?>