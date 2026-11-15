<?php
    include_once "../class/conexao.class.php";
    include_once "../funcoes.php";
    $conexao = new Conexao();
    $varConexao = new Conexao();
/*
    header('Content-Type:text/html; charset=UTF-8');
    ini_set('default_charset', 'UTF-8');
*/
    $separadorRegistro = "|";
    $separadorCampo    = "§";
    $separadorRegistroJSON = "Ø";
    $separadorCampoJSON = "»";




    $act = strtoupper($_GET['act']);

    switch($act){
        case 'JSON_ADD':
            $tabela = $_POST['varTabela'];
            $tipo   = $_POST['varTipo'];
            $id     = $_POST['varId'];

            $query = "select
                        valor
                            from $tabela
                                where id = $id";
            $res   = $conexao->pesquisa($query);
            $row   = mysqli_fetch_assoc($res);

            if($row['valor']==""){  //Não tem NADA ainda
                $conexao->pesquisa("update $tabela set valor = '$separadorRegistro' where id = $id");
                echo "1";
            }else{
                $temp = $row['valor'];
                $temp = explode($separadorRegistro, $row['valor']);
                $qtd  = count($temp);

                $temp = addslashes($row['valor']).$separadorRegistro;
                $conexao->pesquisa("update $tabela set log_usuario = '$id_usuario', valor = '$temp' where id = $id");
                echo $qtd;
            }
        break;
        case 'JSON_CARREGA':
            $tipo   = $_POST['varTipo'];
            $id     = $_POST['varId'];
            $nro    = $_POST['varNro'];
            $tabela = $_POST['varTabela'];
            if($nro==0){$nro=1;}

            $query = "select id, valor from $tabela where id = $id";
            $res   = $conexao->pesquisa($query);
            $row   = mysqli_fetch_assoc($res);

            if($row['valor']==""){
                ?>
                <table width="100%">
                    <tr>
                        <td colspan="2"><font size="2px">Nenhum ítem cadastrado no momento...</font></td>
                    </tr>
                </table>
                <?
            }else{
            ?>
                <div class="row" style="text-align:center">
                <center>
                <div class="btn-toolbar margin-bottom-10" style="text-align:center">
                    <div class="btn-group" style="text-align:center">
                        <?
                            $indMed = 1;
                            $temp = explode("|", $row['valor']);
                            $qtd  = count($temp)-1;
                            for($i=1;$i<=$qtd;$i++){
                                if(($i==$nro)){
                                    echo '<button type="button" class="btn blue" onclick="javascript:carrega('.$indMed.')">'.$indMed.'</button>';
                                }else{
                                    echo '<button type="button" class="btn btn-default" onclick="javascript:carrega('.$indMed.')">'.$indMed.'</button>';
                                }
                                $indMed++;
                            }
                        ?>
                    </div>
                </div>
                </center>
                </div><hr>
                <div class="row">
                <table width="100%">
            <?
                $query = "select * from cfg_visitas_campos where id = " .$tipo;
                $res = $conexao->pesquisa($query);
                $row_campo = mysqli_fetch_assoc($res);
                //$json = formata_utf8_decode($row_campo['selecao_valores']);
                $json = formata_utf8_encode($row_campo['selecao_valores']);
                $json = str_replace("<br />","",$json);
                $obj = json_decode($json);
                $linhas = $obj->linhas;
                $colunas = $obj->colunas;


                /*
                //Pego o vetor do valor (BD) e jogo para as variáveis para a exibição
                $valores = $row['valor'];
                $valores = explode($separadorRegistro, $valores);
                */

                for($linha=0;$linha<count($colunas);$linha++){  //Aqui percorro os campos que estão no selecao_Valores
                        $varValorBanco = $row['valor'];

                        $valor_buscado = explode("|",$row['valor']);
                        $valor_buscado = $valor_buscado[$nro-1];

                        $valor_buscado = explode("§", $valor_buscado);
                        $valor_buscado = $valor_buscado[$linha]; //Aqui pego exatamente o valor do campo
                        //$valor_buscado = htmlspecialchars($valor_buscado[$linha], ENT_QUOTES, 'UTF-8');

                        if (strpos($valor_buscado, "'") !== false) {
                            $valor_buscado = htmlspecialchars($valor_buscado, ENT_QUOTES, 'UTF-8');
                        }




                        /*$valor_buscado = explode("=", $valor_buscado);
                        $valor_buscado = $valor_buscado[1];*/

                        //}
                    ?>
                            <tr>
                                <td class="classe_item"><span style="font-size: 0.7em"><?=formata_utf8_decode($colunas[$linha]->titulo_br);?></span></td>
                                <td style="vertical-align:middle" class="item_json"><span style="font-size: 0.8em">
                                <?
                                    echo $row_campo['valor'] ."<br>";

                                    $campoID = $row_campo['id'];
                                    $mascara = trim($row_campo['formato']);
                                    $TIPO_CAMPO = $colunas[$linha]->tipo;
                                    $indice = $linha+1;

                                    if($TIPO_CAMPO=="texto"){    //SE o tipo for texto
                                        $keyUp .= 'mascara(this, "'.$colunas[$linha]->formato.'");';
                                        echo "<input
                                                    width='100%'
                                                    type='text'
                                                    onchange='javascript:salvarMudando(".$row['id'].",".$nro.");'
                                                    style='text-align: center;'
                                                    class='form-control campoNivel1'
                                                    id='form_".$id."§".$indice."'
                                                    name='form_".$id."§".$indice."'
                                                    value='".$valor_buscado."'
                                                onkeyUp='$keyUp';
                                        >";
                                    }
                                    if($TIPO_CAMPO=="caixa"){    //SE o tipo for caixa de texto
                                        echo "<textarea
                                                rows='3'
                                                onchange='javascript:salvarMudando(".$row['id'].",".$nro.");'
                                                style='width:100%'
                                                class='form-control campoNivel1'
                                                id='form_".$id."§".$indice."'
                                                name='form_".$id."§".$indice."'
                                            >".str_replace('<br />','',$valor_buscado)."
                                            </textarea>";
                                    }
                                    if( ($TIPO_CAMPO=="select")||($TIPO_CAMPO=="select_coluna")){    //SE o tipo for data
                                        $opcoes = utf8_decode($colunas[$linha]->valores);
                                        $opcoes = explode($separadorRegistro, $opcoes);

                                        echo "<select
                                                class='form-control campoNivel1'
                                                onchange='javascript:salvarMudando(".$row['id'].",".$nro.");'
                                                id='form_".$id."§".$indice."'
                                                name='form_".$id."§".$indice."'>";
                                        echo "      <option value=''></option>";
                                        for($iCombo=0;$iCombo<count($opcoes);$iCombo++){
                                            $titulo = $opcoes[$iCombo];
                                            echo "<option value='".$titulo."'";
                                            if($valor_buscado==$titulo){
                                                echo " selected ";
                                            }
                                            echo ">".$titulo."</option>";
                                        }
                                        $combo = "";
                                        echo '</select>';
                                    }
                                    if($TIPO_CAMPO=="JSON"){
                                        echo "
											<div id='divJsonItems'>
												<div style='text-align:right;padding-bottom:10px;'><button type='button' class='btn blue' onclick='javascript:carregaLinhhaJson()'><i class='fa fa-plus-square-o' aria-hidden='true'></i></button></div>";
										$varValoresJson = $valor_buscado;
										$varValoresJson = explode($separadorCampoJSON, $varValoresJson);
										for($i=0;$i<count($varValoresJson);$i++){
											//echo "i->" . count($varValoresJson) . "<br>";
											echo "<div class='input-group' style='padding-bottom:10px;'>
                                                <textarea
                                                    rows='2'
                                                    class='form-control jsonText'
                                                    onchange='javascript:salvarMudando(".$row['id'].",".$nro.");'
                                                    id='json_".$i."'>".$varValoresJson[$i]."</textarea>";

                                            echo "
												<span class='input-group-btn' style='padding-left:10px;'>
													<button
														type='button'
														class='btn '
														id='btnRemover_".$i."'
														onclick='javascript:removeLinhhaJson(this);'><i class='fa fa-minus-square-o' aria-hidden='true'></i></button>
												</span>

											</div>";
										}

										echo "
											</div>
										";

                                    }
                                ?>
                                </td>
                            </tr>
                    <?
                }
                ?>
                </table>
                <br>
                <div style="text-align:center">
                    <button type="button" class="btn btn-default blue" onclick="javascript:salvar(<?=$row['id'];?>,<?=$nro;?>);">Salvar</button>
                    <button type="button" class="btn btn-default red" onclick="javascript:deletar(<?=$row['id'];?>,<?=$nro;?>);">Deletar registro</button>
                </div>

                <?
            }
            ?>
                </table>
                </div>
            <?

        break;
        case 'JSON_SAVE':
            $tabela     = $_POST['varTabela'];
            $id         = $_POST['varId'];
            $nro        = $_POST['varNro'];
            $varValores = addslashes($_POST['varValores']);
            $varJson    = addslashes($_POST['varJson']);
            $sql_campo = "";


            $varValores = explode("&", $varValores);
            $indValores = 0;
            foreach($varValores as $valor){
                $valor = explode("=", $valor);
                $campo = $valor[0];
                $valor =formata_utf8_decode($valor[1]);


                if($indValores==0){
                    $sql_campo = $valor;
                }else{
                    $sql_campo .= $separadorCampo.$valor;
                }
                $indValores++;
            }
            if($varJson!=""){
                $sql_campo .= $separadorCampo.formata_utf8_decode($varJson);
            }
            $sql_campo = str_replace("Â", "", $sql_campo);

            //Pegar o valor atual no banco de dados
            $query       = "select valor from $tabela where id = $id";
            $res         = $conexao->pesquisa($query);
            $row         = mysqli_fetch_assoc($res);
            $valor_atual = addslashes($row['valor']);

            $valor_atual = explode($separadorRegistro, $valor_atual);
            $valor_atual[$nro-1] = $sql_campo;

            $sql_campo = implode($separadorRegistro, $valor_atual);


            $query = "update
                        $tabela
                            set log_usuario = '$id_usuario',
                            valor = '$sql_campo' where id = $id";
            $conexao->pesquisa($query);
            echo $query;
        break;
        case 'JSON_DELETA':
            $tabela = $_POST['varTabela'];
            $id = $_POST['varId'];
            $nro = $_POST['varNro'];

            $query = "select valor from $tabela where id = $id";
            $res = $conexao->pesquisa($query);
            $row = mysqli_fetch_assoc($res);
            $valor_atual = addslashes($row['valor']);

            $valor_atual = explode($separadorRegistro, $valor_atual);
            unset($valor_atual[$nro-1]);
            $sql_campo = implode($separadorRegistro, $valor_atual);

            $query = "update
                        $tabela
                            set log_usuario = '$id_usuario',
                            valor = '$sql_campo' where id = $id";
            $conexao->pesquisa($query);
            echo $query;

        break;
        default:
            echo "Erro: Ação não encontrada";
        break;
    }