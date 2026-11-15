<?php
    session_start();
    include_once "../class/conexao.class.php";
    include_once "../funcoes.php";


    $varConexao = new Conexao();


    $act = $_GET['act'];
    switch($act){
        case 'GERAR_NRO_EVENTO':
            $query = "select max(CAST(nro_evento as INTEGER)) as nro_evento from adv_evento where projeto = ".$_SESSION['projeto'];
            $res = $varConexao->pesquisa($query);
            $row = mysqli_fetch_assoc($res);
            $temp = $row['nro_evento'];
            if($temp==""){$temp = 0;}
            echo $temp + 1;
        break;
        case 'LISTAGEM':
            $participante = $_POST['participante'];
            $grupo        = $_POST['grupo'];
            $periodo      = $_POST['periodo'];
            $gravidade    = $_POST['gravidade'];
            $status       = $_POST['status'];

            $query = "select
                            *
                                from adv_evento
                            where
                                projeto = ".$_SESSION['projeto'];

            if($participante != ""){
                $query .= " and nro_paciente = ".$participante;
            }
            if($periodo != ""){
                $query .= " and periodo = '".formata_utf8_decode($periodo)."'";
            }
            if($gravidade != ""){
                $query .= " and classificado_grave = '".formata_utf8_decode($gravidade)."'";
            }
            if($status != ""){
                $arrCurso = array('', '1 - Em recuperação');;
                if($status=="1"){
                    $query .= " and desfecho in ('".implode("','", $arrCurso)."')";
                }else{
                    $query .= " and desfecho not in ('".implode("','", $arrCurso)."')";
                }
            }
            if($grupo != ""){
                $query .= " and grupo = '".$grupo."'";
            }
            $query .= " order by  CAST(nro_evento as INTEGER)";
            $res = $varConexao->pesquisa($query);
            if(mysqli_num_rows($res)>0){
                while($row = mysqli_fetch_assoc($res)){
                    $status   = getParticipanteStatus($row['nro_paciente'], $row['codigo_paciente']);
                    $pacGrupo = getParticipanteGrupo($row['nro_paciente'], $row['codigo_paciente']);
                    if($grupo!=""){
                        if($pacGrupo!=$grupo){
                            continue;
                        }
                    }
                    ?>
                    <tr>
                        <td class="tabela-texto">
                            <a href="javascript:editarEvento(<?=(int)$row['id'];?>);"><img src="imagens/ico_editar.jpg" width="15" alt="Editar" title="Editar informações"></a>
                        </td>
                        <td class="tabela-texto"><center><?=$row['nro_evento'];?></center></td>
                        <td class="tabela-texto"><center><?=$row['grupo'];?></center></td>
                        <td class="tabela-texto"><center><?=$row['periodo'];?></center></td>
                        <td class="tabela-texto"><center><?=$row['nro_paciente'];?></center></td>
                        <td class="tabela-texto"><center><?=$row['codigo_paciente'];?></center></td>
                        <td class="tabela-texto"><center><?=$status;?></center></td>
                        <td class="tabela-texto"><?=$row['nome'];?></td>
                        <td class="tabela-texto"><?=$row['grau'];?></td>
                        <td class="tabela-texto"><?=$row['classificado_grave'];?></td>
                        <td class="tabela-texto"><center><?=$row['data_inicio'];?></center></td>
                        <td class="tabela-texto"><?=$row['relacao'];?></td>
                        <td class="tabela-texto"><?=$row['acao_tomada'];?></td>
                        <td class="tabela-texto"><?=$row['desfecho'];?></td>
                        <td class="tabela-texto"><center><?=$row['data_termino'];?></center></td>
                        <td class="tabela-texto"><?=$row['descricao'];?></td>
                        <td class="tabela-texto">
                            <a href="javascript:excluir(<?=$row['id'];?>,'<?=$row['nome'];?>');"><img src="imagens/ico_excluir.png" width="15" alt="Excluir" title="Excluir registro"></a>
                            <a href="javascript:editarEvento(<?=(int)$row['id'];?>);"><img src="imagens/ico_editar.jpg" width="15" alt="Editar" title="Editar informações"></a>
                        </td>
                    </tr>
                    <?php
                }
            }else{
                echo '<tr><td colspan="13">Nenhum evento adverso encontrado</td></tr>';
            }
        break;
        case 'SALVAR':
            $tabela = "adv_evento";
            $campos = array(
                "nro_evento",
                "centro",
                "nro_paciente",
                "hora_inicio",
                "hora_fim",
                "relatado_patrocinador",
                "relatado_cep",
                "descricao",
                "acao_descricao",
                "tipo_relato",
                "nome",
                "grau",
                "data_inicio",
                "criterio",
                "relacao",
                "causa_alternativa",
                "pre_existente",
                "acao_tomada",
                "desfecho",
                "data_termino",
                "data_abertura",
                "classificado_grave",
                "codigo_paciente",
                "grupo",
                "periodo",
                "nro_evento_participante",
                "previsibilidade",
                "hora_abertura",
                "desfecho_outros"
            );
            $opc = $_POST['opc'];
            $id  = (int)$_POST['id'];
            $valores = array();
            for($i=0; $i<count($campos); $i++){
                $valor = isset($_POST['form_'.$campos[$i]]) ? $_POST['form_'.$campos[$i]] : '';
                $valor = str_replace("'", "''", formata_utf8_decode($valor));
                $valores[$campos[$i]] = $valor;
            }
            if(($opc=='A') && ($id>0)){
                $sql_campos = "";
                $pega = 0;
                for($i=0; $i<count($campos); $i++){
                    if($pega>0){$sql_campos .= ", ";}else{$pega++;}
                    $sql_campos .= $campos[$i] . " = '" . $valores[$campos[$i]] . "'";
                }
                $query = "update $tabela set " . $sql_campos . " where id = ".$id;
            }else{
                $sql_campos = "";
                $sql_valores = "";
                $pega = 0;
                for($i=0; $i<count($campos); $i++){
                    if($pega>0){$sql_campos .= ", "; $sql_valores .= ", ";}else{$pega++;}
                    $sql_campos  .= $campos[$i];
                    $sql_valores .= "'".$valores[$campos[$i]]."'";
                }
                $query = "insert into $tabela (projeto, $sql_campos) values (".(int)$_SESSION['projeto'].", $sql_valores);";
            }
            $ok = $varConexao->pesquisa($query);
            if($ok===false){
                echo "ERRO";
            }else{
                echo "OK";
            }
        break;
    }