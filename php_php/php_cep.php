<?php
    include_once "../class/conexao.class.php";
    include_once "../class/cep.class.php";

    $act = strtoupper($_GET['act']);
    $conexao = new Conexao();

    switch($act){
        case "LISTA":
            $query = "select id, nome from cad_cep order by nome";
            $res = $conexao->pesquisa($query);
            while($row = mysqli_fetch_assoc($res)){
                ?>
                <tr onclick='javascript:selecionaCEP(<?=$row["id"];?>)'
                     class='lista_formularios'
                     style="cursor: pointer";>
                    <td><?=$row['id'];?></td>
                    <td><?=$row['nome'];?></td>
                </tr>
                <?php
            }
        break;
        case "CONSULTA":
            $cep = new CEP($_GET['id']);
            if($cep->id==0){
                $resp = array(
                    "resultado" => "NAO"
                );
            }else{
                $resp = array(
                    "resultado" => "OK",
                    "id"        => $cep->id,
                    "nome"      => $cep->nome,
                    "aprovacao" => $cep->aprovacao,
                    "validade"  => $cep->validade,
                    "composicao" => $cep->composicao,
                    "comp_validade" => $cep->comp_validade
                );
            }
            $resp = array_map('utf8_encode', $resp);
            $resp = json_encode($resp);
            echo $resp;
        break;
        case "SALVA":
            echo "ID->".$_GET['id'];
            $cep                = new CEP($_GET['id']);
            $cep->nome          = $_POST['form_nome'];
            $cep->aprovacao     = $_POST['form_aprovacao'];
            $cep->validade      = $_POST['form_validade'];
            $cep->composicao    = $_POST['form_composicao'];
            $cep->comp_validade = $_POST['form_comp_validade'];

            if($_GET['tipo']=="NOVO"){
                echo $cep->inclui();
            }else{
                echo $cep->salva();
            }
            echo "OK";
        break;

    }