<?php
    session_start();
    include_once "../class/conexao.class.php";

    error_reporting(E_ALL ^ E_DEPRECATED);
    ini_set('display_errors', 1);

    $varConexao = new Conexao();
    if($_GET['act']=="DELETAR"){
        $id = $_POST['varId'];
        $query = "delete from faq where id = " . $id;
        $varConexao->pesquisa($query);
    }
    if($_GET['act']=='ADD'){
        $pergunta = $_POST['pergunta'];
        $resposta =nl2br($_POST['resposta']);
        $query = "insert into faq (pergunta, resposta, projeto) values ('".utf8_decode($pergunta)."', '".utf8_decode($resposta)."', '".$_SESSION['projeto']."');";
        $varConexao->pesquisa($query);
    }
    if($_GET['act']=="GRID"){
        $query = "select * from faq where projeto = " . $_SESSION['projeto'];
        $resP  = $varConexao->pesquisa($query);
        $i = 1;
        if(mysqli_num_rows($resP)>0){
            while($rowP = mysqli_fetch_assoc($resP)){
                //$pergunta = str_replace("<br />", "<br>", $rowP['pergunta']);
                $pergunta = $rowP['pergunta'];
                $resposta = $rowP['resposta'];
                ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <table width="100%">
                                    <tr>
                                        <td width="90%">
                                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion<?=$i;?>" href="#collapse<?=$i;?>"><?=$pergunta;?></a>
                                        </td>
                                        <td>
                                            <?php
                                            if($_SESSION['user_tipo']==1){
                                            ?>
                                            <center><a href="javascript:deleta(<?=$rowP['id'];?>)"><i class="fa fa-minus-square-o" aria-hidden="true"></i></a></center>
                                            <?php
                                            }
                                            ?>
                                        <td>
                                    </tr>
                                </table>

                            </h4>
                        </div>
                        <div id="collapse<?=$i;?>" class="panel-collapse collapse">
                            <div class="panel-body">
                                <?=$resposta;?>
                            </div>
                        </div>
                    </div>
                <?
                $i++;
            }
        }else{
            echo "Nenhuma pergunta/resposta cadastrada para esse estudo";
        }
    }