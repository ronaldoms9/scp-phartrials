<?php
	header('Content-Type:text/html; charset=ISO-8859-1');
    header("Pragma: no-cache");
    session_start();

    include_once __DIR__."/../class/conexao.class.php";
    include_once __DIR__."/../class/atividade.class.php";
    include_once __DIR__."/../funcoes.php";

    $varConexao = new Conexao();

    switch($_GET['act']){
        case	'SALVA_ATIVIDADE':
            $query = "insert into tab_agenda_atividades (atividade) values ('".$_POST['atividadeNome']."')";
            $varConexao->pesquisa($query);
            echo $_POST['atividadeId'];
            break;
        case	'LISTA_ATIVIDADES':
            $query = "select * from tab_agenda_atividades order by atividade";
            $res = $varConexao->pesquisa($query);
            while($row = mysqli_fetch_assoc($res)){
                echo "<tr>";
                echo "<td>".$row['atividade']."</td>";
                echo "<td valign='middle' style='vertical-align: middle;'>";

                    if($row['ativo']==1){
                        echo "<center><input onclick='salvarAtivo(this.checked,".$row['id'].")' type='checkbox' name='ativo' id='ativo' checked></center>";
                    }else{
                        echo "<center><input onclick='salvarAtivo(this.checked,".$row['id'].")' type='checkbox' name='ativo' id='ativo'></center>";
                    }
                echo "</td>";
            }
            echo "</tr>";
            break;
        case	'SALVAR_ATIVO':
            $query = "update tab_agenda_atividades set ativo = ".$_POST['ativo']." where id = ".$_POST['id'];
            echo $query;
            $varConexao->pesquisa($query);
            echo "Atividade salva com sucesso";
            break;
    }
?>