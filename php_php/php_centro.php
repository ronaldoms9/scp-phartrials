<?php
    include_once "../class/conexao.class.php";
    include_once "../class/centro.class.php";

    $act = strtoupper($_GET['act']);
    $conexao = new Conexao();

    switch($act){
        case "LISTA":
            $query = "select id, nome from cad_centro order by nome";
            $res = $conexao->pesquisa($query);
            while($row = mysqli_fetch_assoc($res)){
                ?>
                <tr
                    onclick='javascript:selecionaCentro(<?=$row["id"];?>)'
                    class='lista_formularios'
                    style="cursor: pointer";>
                    <td width="40"><center><b><?=$row['id'];?></center></b></td>
                    <td>
                        <?php
                            echo $row['nome'];
                        ?>
                    </td>
                </tr>
                <?php
            }
        break;
        case "LISTA_ESTUDO":
            $query = "select id, numero, nome from cfg_centro where projeto = '".$_SESSION['projeto']."' order by nome";
            $res = $conexao->pesquisa($query);
            while($row = mysqli_fetch_assoc($res)){
                ?>
                <tr
                    onclick='javascript:selecionaCentro(<?=$row["id"];?>)'
                    class='lista_formularios'
                    style="cursor: pointer";>
                    <td width="40"><center><b><?=$row['numero'];?></center></b></td>
                    <td>
                        <?php
                            echo $row['nome'];
                        ?>
                    </td>
                </tr>
                <?php
            }
        break;
        case "CONSULTA":
            $id     = $_GET['id'];
            $centro = new CentroCadastro($id);
            $resp   = array(
                "resultado"    => "OK",
                "id"           => $centro->id,
                "nome"         => $centro->nome,
                "email"        => $centro->email,
                "telefone"     => $centro->telefone,
                "endereco"     => $centro->endereco,
                "investigador" => $centro->investigador,
                "coordenador"  => $centro->coordenador,
                "dados_tr"     => $centro->dados_tr,
                "dados_cv"     => $centro->dados_cv,
                "dados_lab"    => $centro->dados_lab,
                "dados_centro" => $centro->dados_centro,
                "dados_bombeiros" => $centro->dados_bombeiros,
                "cep"          => $centro->cep
            );
            $resp = array_map('utf8_encode', $resp);
            $resp = json_encode($resp);
            echo $resp;
        break;
        case "CONSULTA_MODELO":
            $id     = $_GET['id'];
            $centro = new CentroCadastro($id);
            $resp   = array(
                "resultado"    => "OK",
                "id"           => $centro->id,
                "nome"         => $centro->nome,
                "email"        => $centro->email,
                "telefone"     => $centro->telefone,
                "endereco"     => $centro->endereco,
                "investigador" => $centro->investigador,
                "coordenador"  => $centro->coordenador,
                "dados_tr"     => $centro->dados_tr,
                "dados_cv"     => $centro->dados_cv,
                "dados_lab"    => $centro->dados_lab,
                "dados_centro" => $centro->dados_centro,
                "dados_bombeiros" => $centro->dados_bombeiros
            );
            $resp = array_map('utf8_encode', $resp);
            $resp = json_encode($resp);
            echo $resp;
        break;
        case "CONSULTA_ESTUDO":
            $id     = $_GET['id'];
            $centro = new CentroPesquisa($id);
            $resp   = array(
                "resultado"    => "OK",
                "id"           => $centro->id,
                "numero"       => $centro->numero,
                "centro_pai"   => $centro->centro_pai,
                "nome"         => $centro->nome,
                "email"        => $centro->email,
                "telefone"     => $centro->telefone,
                "endereco"     => $centro->endereco,
                "investigador" => $centro->investigador,
                "coordenador"  => $centro->coordenador,
                "dados_tr"     => $centro->dados_tr,
                "dados_cv"     => $centro->dados_cv,
                "dados_lab"    => $centro->dados_lab,
                "dados_centro" => $centro->dados_centro,
                "dados_bombeiros" => $centro->dados_bombeiros,
                "cep"          => $centro->cep,
                "quem_assina" => $centro->quem_assina
            );
            $resp = array_map('utf8_encode', $resp);
            $resp = json_encode($resp);
            echo $resp;
        break;
        case "SALVA":
            $id                      = $_POST['form_id'];
            $centro                  = new CentroCadastro($id);
            $centro->nome            = $_POST['form_nome'];
            $centro->email           = $_POST['form_email'];
            $centro->telefone        = $_POST['form_telefone'];
            $centro->endereco        = $_POST['form_endereco'];
            $centro->investigador    = $_POST['form_investigador'];
            $centro->coordenador     = $_POST['form_coordenador'];
            $centro->dados_tr        = $_POST['form_dados_tr'];
            $centro->dados_cv        = $_POST['form_dados_cv'];
            $centro->dados_lab       = $_POST['form_dados_lab'];
            $centro->dados_centro    = $_POST['form_dados_centro'];
            $centro->dados_bombeiros = $_POST['form_dados_bombeiros'];
            if($_GET['tipo'] == "NOVO"){
                $centro->inclui();
            }else{
                $centro->salva();
            }
            $resp = array(
                "resultado" => "OK"
            );
            $resp = array_map('utf8_encode', $resp);
            $resp = json_encode($resp);
            echo $resp;
        break;
            case "SALVA_ESTUDO":
            $id                      = $_POST['form_id'];
            $centro                  = new CentroPesquisa($id);
            $centro->centro_pai      = $_POST['form_centro_modelo'];
            $centro->numero          = $_POST['form_numero'];
            $centro->nome            = $_POST['form_nome'];
            $centro->email           = $_POST['form_email'];
            $centro->telefone        = $_POST['form_telefone'];
            $centro->endereco        = $_POST['form_endereco'];
            $centro->investigador    = $_POST['form_investigador'];
            $centro->coordenador     = $_POST['form_coordenador'];
            $centro->projeto         = $_SESSION['projeto'];
            $centro->dados_tr        = $_POST['form_dados_tr'];
            $centro->dados_cv        = $_POST['form_dados_cv'];
            $centro->dados_lab       = $_POST['form_dados_lab'];
            $centro->dados_centro    = $_POST['form_dados_centro'];
            $centro->cep             = $_POST['form_cep'];
            $centro->dados_bombeiros = $_POST['form_dados_bombeiros'];
            $centro->quem_assina     = $_POST['form_quem_assina'];
            if($_GET['tipo'] == "NOVO"){
                $centro->inclui();
            }else{
                $centro->salva();
            }
            $resp = array(
                "resultado" => "OK"
            );
            $resp = array_map('utf8_encode', $resp);
            $resp = json_encode($resp);
            echo $resp;
        break;
        case "EXCLUIR_ESTUDO":
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if ($id <= 0) {
                $resp = array("resultado" => "ERRO", "mensagem" => "ID inválido");
            } else {
                $centro = new CentroPesquisa($id);
                if (!empty($centro->id)) {
                    $centro->exclui();
                    $resp = array("resultado" => "OK", "mensagem" => "Centro excluído com sucesso");
                } else {
                    $resp = array("resultado" => "ERRO", "mensagem" => "Centro não encontrado");
                }
            }
            $resp = array_map('utf8_encode', $resp);
            echo json_encode($resp);
        break;
    }