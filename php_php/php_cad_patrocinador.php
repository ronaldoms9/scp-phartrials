<?php
    include_once __DIR__."/../class/conexao.class.php";
    include_once __DIR__."/../class/patrocinador.class.php";
    include_once __DIR__."/../class/projeto.class.php";

    $varConexao = new Conexao();

    $act = $_GET['act'];



    switch($act){
        case "EXCLUI_PESSOA":
            $varPessoa = new Pessoa($_POST['id']);
            echo "EXCLUINDO...." . $varPessoa->exclui();
        break;
        case "BUSCAR_DADOS_PESSOA":
            $varPessoa = new Pessoa($_POST['id']);
            $resp = array(
                "resultado" => "OK",
                "id"        => $varPessoa->id,
                "nome"      => $varPessoa->nome,
                "email"     => $varPessoa->email,
                "telefone"  => $varPessoa->telefone
            );
            $resp = array_map('utf8_encode', $resp);
            $resp = json_encode($resp);
            echo $resp;
        break;
        case "LISTA_PESSOAS":
            $varPatrocinador = new Pessoa($_POST['patrocinador']);
            $query = "select * from cad_patrocinador_pessoa where registro_valido = 1 and patrocinador = ".$_POST['patrocinador'];
            $res = $varConexao->pesquisa($query);
            if(mysqli_num_rows($res)==0){
                echo "<tr>";
                echo "<td colspan='4'>Nenhum dado a exibir...</td>";
                echo "</tr>";
            }else{
                while($row = mysqli_fetch_assoc($res)){
                    echo "<tr>";
                    echo "<td>".$row['nome']."</td>";
                    echo "<td>".$row['email']."</td>";
                    echo "<td>".$row['telefone']."</td>";
                    echo "<td><center><a href='javascript:editarPessoa(".$row['id'].")'><img src='imagens/ico_editar.jpg' width='20' height='20'></a></center></td>";
                    echo "<td><center><a href='javascript:excluirPessoa(".$row['id'].")'><img src='imagens/img_x.png' width='20' height='20'></a></center></td>";
                    echo "</tr>";
                }
            }
        break;
        case "SALVA_PESSOA":
            $varPessoa               = new Pessoa($_POST['pessoaId']);
            $varPessoa->nome         = utf8_decode($_POST['nome']);
            $varPessoa->email        = $_POST['email'];
            $varPessoa->telefone     = $_POST['telefone'];
            $varPessoa->patrocinador = $_POST['patrocinador'];
            echo "ID -> ".$_POST['pessoaId'];
            if($_POST['pessoaId']==0){
                echo $varPessoa->inclui();
            }else{
                echo $varPessoa->salva();
            }
        break;

        case "BUSCAR_DADOS":
            //projeto
            $varProjeto = new Projeto($_SESSION['projeto']);
            if($varProjeto->id==0){
                $resp = array(
                    "resultado" => "NAO"
                );
            }else{
                $varPatrocinador = new Patrocinador($varProjeto->patrocinador->id);
                if($varPatrocinador->id==0){
                    $resp = array(
                        "resultado" => "NAO"
                    );
                }else{
                    $resp = array(
                        "resultado" => "OK",
                        "id"        => $varPatrocinador->id,
                        "nome"      => $varPatrocinador->nome,
                        "responsavel" => $varPatrocinador->responsavel,
                        "endereco"  => $varPatrocinador->endereco,
                        "email1"    => $varPatrocinador->email1,
                        "email2"    => $varPatrocinador->email2,
                        "telefone1" => $varPatrocinador->telefone1,
                        "telefone2" => $varPatrocinador->telefone2
                    );
                    $resp = array_map('utf8_encode', $resp);
                    $resp = json_encode($resp);
                    echo $resp;
                }
            }
        break;
        case "BUSCAR_DADOS_PROJETO":
            //projeto
            $query = " select * from cfg_patrocinador where projeto = ".$_SESSION['projeto'];
            $res = $varConexao->pesquisa($query);
            if(mysqli_num_rows($res)==0){
                $resp = array(
                    "resultado" => "NAO"
                );
            }else{
                $row = mysqli_fetch_assoc($res);
                $resp = array(
                    "resultado" => "OK",
                    "id"        => $row['id'],
                    "nome"      => $row['nome'],
                    "responsavel" => $row['responsavel'],
                    "endereco"  => $row['endereco'],
                    "pessoa_responsavel" => $row['pessoa_responsavel'],
                    "pessoa_1" => $row['pessoa_1'],
                    "pessoa_2" => $row['pessoa_2']
                );
                $resp = array_map('utf8_encode', $resp);
                $resp = json_encode($resp);
                echo $resp;
            }
        break;
        case "LISTA":
            $conexao = new Conexao();
            $query = "select * from cad_patrocinador order by nome";
            $res = $conexao->pesquisa($query);
            while($row = mysqli_fetch_assoc($res)){
                ?>
                <tr
                     onclick='javascript:selecionaEstudo(<?=$row["id"];?>)'
                     class='lista_formularios'
                     style="cursor: pointer";>
                    <td>
                        <?
                            echo $row['nome'];
                        ?>
                    </td>
                </tr>
                <?php
            }
        break;
        case "SALVA":
            echo "Endereço -> ".$_POST['form_endereco'];
            $varPatrocinador            = new Patrocinador($_POST['form_id']);
            $varPatrocinador->nome      = utf8_decode($_POST['form_nome']);
            $varPatrocinador->responsavel = utf8_decode($_POST['form_responsavel']);
            $varPatrocinador->endereco  = utf8_decode($_POST['form_endereco']);
            $varPatrocinador->email1    = $_POST['form_email1'];
            $varPatrocinador->email2    = $_POST['form_email2'];
            $varPatrocinador->telefone1 = $_POST['form_telefone1'];
            $varPatrocinador->telefone2 = $_POST['form_telefone2'];
            if($_GET['tipo']=="NOVO"){
                echo $varPatrocinador->inclui();
            }else{
                echo $varPatrocinador->salva();
            }
            echo "OK";
        break;
        case "SALVA_PATROCINADOR_PROJETO":
            $varConexao = new Conexao();
            $query = "select id from cfg_patrocinador where projeto = ".$_SESSION['projeto'];
            $res = $varConexao->pesquisa($query);
            if(mysqli_num_rows($res)==0){
                $query = "insert into cfg_patrocinador (projeto) values (".$_SESSION['projeto'].")";
                $varConexao->pesquisa($query);

            }
            $query = "update cfg_patrocinador
                        set nome = '".utf8_decode($_POST['form_nome'])."',
                        pessoa_responsavel = '".utf8_decode($_POST['form_pessoa_responsavel'])."',
                        pessoa_1 = '".utf8_decode($_POST['form_pessoa_1'])."',
                        pessoa_2 = '".utf8_decode($_POST['form_pessoa_2'])."',
                        endereco = '".utf8_decode($_POST['form_endereco'])."'
                            where projeto = ".$_SESSION['projeto'];
            $varConexao->pesquisa($query);
            echo "OK";
        break;
        case "INCLUI":
            $varPatrocinador            = new Patrocinador(0);
            $varPatrocinador->nome      = utf8_decode($_POST['form_nome']);
            $varPatrocinador->responsavel = utf8_decode($_POST['form_responsavel']);
            $varPatrocinador->endereco  = utf8_decode($_POST['form_endereco']);
            $varPatrocinador->email1    = $_POST['form_email1'];
            $varPatrocinador->email2    = $_POST['form_email2'];
            $varPatrocinador->telefone1 = $_POST['form_telefone1'];
            $varPatrocinador->telefone2 = $_POST['form_telefone2'];
            echo $varPatrocinador->inclui();
            echo "OK";
        break;
        case "CONSULTA":
            $varPatrocinador = new Patrocinador($_GET['id']);
            if($varPatrocinador->id==0){
                $resp = array(
                    "resultado" => "NAO"
                );
            }else{
                $resp = array(
                    "resultado" => "OK",
                    "id"        => $varPatrocinador->id,
                    "nome"      => $varPatrocinador->nome,
                    "responsavel" => $varPatrocinador->responsavel,
                    "endereco"  => $varPatrocinador->endereco,
                    "email1"    => $varPatrocinador->email1,
                    "email2"    => $varPatrocinador->email2,
                    "telefone1" => $varPatrocinador->telefone1,
                    "telefone2" => $varPatrocinador->telefone2
                );
            }
            $resp = array_map('utf8_encode', $resp);
            $resp = json_encode($resp);
            echo $resp;
        break;
    }