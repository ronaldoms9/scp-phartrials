<?php
    include_once "../class/conexao.class.php";
    $varConexao = new Conexao();


    $varForm = 54;
    $varForm = 111;

    $query = "select * from cfg_visitas_campos where formulario = $varForm";
    $resForm = $varConexao->pesquisa($query);
    while($rowForm = mysqli_fetch_assoc($resForm)){
        $query = "select * from crf_agenda_forms where form_id = $varForm";
        $resFormulariosExistentes = $varConexao->pesquisa($query);
        while($rowFormulariosExistentes = mysqli_fetch_assoc($resFormulariosExistentes)){
            $query = "select * from crf_agenda_campos where form_id = " . $rowFormulariosExistentes['id'] . " and campo_id = " . $rowForm['id'];
            $resCamposExistentes = $varConexao->pesquisa($query);
            if(mysqli_num_rows($resCamposExistentes)==0){
                $query = "insert into crf_agenda_campos (form_id, campo_id) values (".$rowFormulariosExistentes['id'].", ".$rowForm['id'].")";
                $varConexao->pesquisa($query);
                echo $query . "<br>";
            }
        }
    }


echo "<br><br>FIM";
