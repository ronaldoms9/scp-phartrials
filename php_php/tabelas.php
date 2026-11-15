<?php
    session_start();
    include_once '../class/conexao.class.php';
    include_once '../funcoes.php';
    $varConexao = new Conexao();

    switch($_GET['act']){
        case 'GERAL_SALVAR':
            $id                  = $_POST['id'];
            $centro              = $_POST['centro'];
            $participante        = addslashes($_POST['participante']);
            $tipo_desvio         = addslashes($_POST['tipo_desvio']);
            $comentarios         = addslashes($_POST['comentarios']);
            $data                = $_POST['data'];
            $grupo               = $_POST['grupo'];
            $periodo             = $_POST['periodo'];
            $codigo_participante = $_POST['codigo_participante'];

            if($id==0){
                $query = formata_utf8_decode("
                    insert into agenda_desvio_geral (projeto, centro, participante, codigo_participante, tipo_desvio, comentarios, data, grupo, periodo, log_usuario)
                    values (".$_SESSION['projeto'].", '$centro', '$participante', '$codigo_participante', '$tipo_desvio', '$comentarios', '$data', '$grupo', '$periodo', '".$_SESSION['usuario']."');
                ");
            }else{
                $query = formata_utf8_decode("
                    update agenda_desvio_geral set centro = '$centro', participante = '$participante', codigo_participante = '$codigo_participante', tipo_desvio = '$tipo_desvio', comentarios = '$comentarios', data = '$data', grupo = '$grupo', periodo = '$periodo', log_usuario = '".$_SESSION['usuario']."'
                    where id = '$id'
                ");
            }
            $varConexao->pesquisa($query);
            echo $query;
            echo "OK2";
        break;
        case 'GERAL_EXCLUIR':
            $id = $_POST['id'];
            $query = "delete from agenda_desvio_geral where id = '$id'";
            $varConexao->pesquisa($query);
            echo $query;
            echo "OK3";
        break;
        case 'GERAL_LISTA':
            $participante = $_GET['participante'];
            $grupo        = $_GET['grupo'];

            $query = "select
                            agenda_desvio_geral.*,
                            cfg_centro.nome as centro_nome
                        from agenda_desvio_geral
                            left join cfg_centro on agenda_desvio_geral.centro = cfg_centro.id
                            where agenda_desvio_geral.projeto = ".$_SESSION['projeto'];
            if($participante != ''){
                $query .= " and agenda_desvio_geral.participante = '$participante'";
            }
            if($grupo != ''){
                $query .= " and agenda_desvio_geral.grupo = '$grupo'";
            }
            $query .= " order by agenda_desvio_geral.centro, cast(agenda_desvio_geral.participante as INTEGER), agenda_desvio_geral.id";
            $res = $varConexao->pesquisa($query);
            if(mysqli_num_rows($res)==0){
                $data[] = array(
                    "centro"=>"",
                    "data"              => "",
                    "participante"=>"",
                    "codigo_participante"=>"",
                    "grupo"             => "",
                    "periodo"           => "",
                    "tipo_desvio"=>"",
                    "comentarios"=>"",
                    "botao"=>""
                );
                $response = array(
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "data" => $data
                  );
                echo json_encode($response);
            }else{
                while($row = mysqli_fetch_assoc($res)){
                    $link = "javascript:btnRegistro('modal_tab_geral.php?id=".$row['id']."');";
                    $btn = '<a alt="Visualizar CRF" href="'.$link.'" class="btn btn-circle btn-sm blue">
									<i class="fa fa-search" alt="Visualizar CRF" ></i>
								</a>
                                <a alt="Exluir registro" href="javascript:excluir('.$row['id'].');" class="btn btn-circle btn-sm red">
									<i class="fa fa-trash" alt="Exluir registro" ></i>
								</a>
                    ';
                    $data[] = array(
                        "centro"            => formata_utf8_encode($row['centro_nome']),
                        "data"              => "<center>".formata_utf8_encode($row['data'])."</center>",
                        "participante"      => "<center>".formata_utf8_encode($row['participante'])."</center>",
                        "codigo_participante" => "<center>".formata_utf8_encode($row['codigo_participante'])."</center>",
                        "grupo"             => "<center>".formata_utf8_encode($row['grupo'])."</center>",
                        "periodo"           => "<center>".formata_utf8_encode($row['periodo'])."</center>",
                        "tipo_desvio"       => "<center>".formata_utf8_encode($row['tipo_desvio'])."</center>",
                        "comentarios"       => "<center>".formata_utf8_encode($row['comentarios'])."</center>",
                        "botao"             => "$btn"
                    );
                }
                echo json_encode($data);
                return;
            }
        break;
        case 'COLETA_SALVAR':
            $id                = $_POST['id'];
            $centro            = $_POST['centro'];
            $participante      = addslashes($_POST['participante']);
            $codigo_participante = addslashes($_POST['codigo_participante']);
            $horario_coleta    = $_POST['horario_coleta'];
            $horario_previsto  = $_POST['horario_previsto'];
            $horario_realizado = $_POST['horario_realizado'];
            $motivo            = addslashes($_POST['motivo']);
            $data              = $_POST['data'];
            $grupo             = $_POST['grupo'];
            $periodo           = $_POST['periodo'];
            $motivo_outro      = addslashes($_POST['motivo_outro']);

            if($id==0){
                $query = formata_utf8_decode("
                    insert into agenda_desvio_horario (projeto, centro, participante, codigo_participante, horario_coleta, horario_previsto, horario_realizado, motivo, motivo_outro, data, grupo, periodo, log_usuario)
                    values (".$_SESSION['projeto'].", '$centro', '$participante', '$codigo_participante', '$horario_coleta', '$horario_previsto', '$horario_realizado', '$motivo', '$motivo_outro', '$data', '$grupo', '$periodo', '".$_SESSION['usuario']."');
                ");
            }else{
                $query = formata_utf8_decode("
                    update agenda_desvio_horario set centro = '$centro', participante = '$participante', codigo_participante = '$codigo_participante', horario_coleta = '$horario_coleta', horario_previsto = '$horario_previsto', horario_realizado = '$horario_realizado', motivo = '$motivo', motivo_outro = '$motivo_outro', data = '$data', grupo = '$grupo', periodo = '$periodo', log_usuario = '".$_SESSION['usuario']."'
                    where id = '$id'
                ");
            }
            $varConexao->pesquisa($query);
            echo $query;
            echo "OK2";
        break;
        case 'COLETA_EXCLUIR':
            $id = $_POST['id'];
            $query = "delete from agenda_desvio_horario where id = '$id'";
            $varConexao->pesquisa($query);
            echo $query;
            echo "OK3";
        break;
        case 'COLETA_LISTA':
            $participante = $_GET['participante'];
            $grupo        = $_GET['grupo'];
            $periodo      = $_GET['periodo'];

            $query = "
                select
                    agenda_desvio_horario.*,
                        tab_motivo_atraso.motivo as motivo_descricao,
                        cfg_centro.nome as centro_nome
                    from agenda_desvio_horario
                        left join tab_motivo_atraso on agenda_desvio_horario.motivo = tab_motivo_atraso.id
                        left join cfg_centro on agenda_desvio_horario.centro = cfg_centro.id
                        where agenda_desvio_horario.projeto = ".$_SESSION['projeto'];

            if($participante != ''){
                $query .= " and agenda_desvio_horario.participante = '$participante'";
            }
            if($grupo != ''){
                $query .= " and agenda_desvio_horario.grupo = '$grupo'";
            }
            if($periodo != ''){
                $query .= " and periodo = '$periodo'";
            }

            $query .= " order by centro, grupo, periodo, cast(participante as INTEGER),horario_coleta ";

            $res = $varConexao->pesquisa($query);
            if(mysqli_num_rows($res)==0){
                $data[] = array(
                    "centro_nome"=>"",
                    "participante"=>"",
                    "codigo_participante"=>"",
                    "horario_coleta"=>"",
                    "horario_previsto"=>"",
                    "horario_realizado"=>"",
                    "tempo_atraso"      => "",
                    "data"=>"",
                    "grupo"=>"",
                    "periodo"=>"",
                    "motivo"=>"",
                    "botao"=>""
                );
                $response = array(
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "data" => $data
                  );
                echo json_encode($response);
            }else{
                while($row = mysqli_fetch_assoc($res)){
                    $link = "javascript:btnRegistro('modal_tab_devio.php?id=".$row['id']."');";
                    $btn = '<a alt="Visualizar CRF" href="'.$link.'" class="btn btn-circle btn-sm blue">
									<i class="fa fa-search" alt="Visualizar CRF" ></i>
								</a>
                                <a alt="Exluir registro" href="javascript:excluir('.$row['id'].');" class="btn btn-circle btn-sm red">
									<i class="fa fa-trash" alt="Exluir registro" ></i>
								</a>
                    ';
                    $tempo_atraso = retornaDiferencaHorario($row['horario_previsto'], $row['horario_realizado']);
                    if($tempo_atraso > 5){
                        $tempo_atraso = "<center><span style='color: red;'>$tempo_atraso</span></center>";
                    }else{
                        $tempo_atraso = "<center><span style=''>$tempo_atraso</span></center>";
                    }
                    $data[] = array(
                        "centro_nome"       => formata_utf8_encode($row['centro_nome']),
                        "participante"      => "<center>".formata_utf8_encode($row['participante'])."</center>",
                        "codigo_participante" => "<center>".formata_utf8_encode($row['codigo_participante'])."</center>",
                        "horario_coleta"    => "<center>".formata_utf8_encode($row['horario_coleta'])."</center>",
                        "horario_previsto"  => "<center>".formata_utf8_encode($row['horario_previsto'])."</center>",
                        "horario_realizado" => "<center>".formata_utf8_encode($row['horario_realizado'])."</center>",
                        "tempo_atraso"      => "<center>".formata_utf8_encode($tempo_atraso)."</center>",
                        "data"              => "<center>".formata_utf8_encode($row['data'])."</center>",
                        "grupo"             => "<center>".formata_utf8_encode($row['grupo'])."</center>",
                        "motivo"            => formata_utf8_encode($row['motivo_descricao']),
                        "periodo"           => "<center>".formata_utf8_encode($row['periodo'])."</center>",
                        "botao"             => "$btn"
                    );
                }
                echo json_encode($data);
                return;
            }
        break;
        case 'DIETA_SALVAR':
            $id                = $_POST['id'];
            $centro            = $_POST['centro'];
            $participante      = addslashes($_POST['participante']);
            $codigo_participante = addslashes($_POST['codigo_participante']);
            $horario_coleta    = $_POST['horario_coleta'];
            $horario_previsto  = $_POST['horario_previsto'];
            $horario_realizado = $_POST['horario_realizado'];
            $motivo            = addslashes($_POST['motivo']);
            $data              = $_POST['data'];
            $grupo             = $_POST['grupo'];
            $dieta             = $_POST['dieta'];
            if($id==0){
                $query = formata_utf8_decode("
                    insert into agenda_desvio_dieta (projeto, centro, participante, codigo_participante, horario_coleta, horario_previsto, horario_realizado, motivo, data, grupo, dieta, log_usuario)
                    values (".$_SESSION['projeto'].", '$centro', '$participante', '$codigo_participante', '$horario_coleta', '$horario_previsto', '$horario_realizado', '$motivo', '$data', '$grupo', '$dieta', '".$_SESSION['usuario']."');
                ");
            }else{
                $query = formata_utf8_decode("
                    update agenda_desvio_dieta set centro = '$centro', participante = '$participante', codigo_participante = '$codigo_participante', horario_coleta = '$horario_coleta', horario_previsto = '$horario_previsto', horario_realizado = '$horario_realizado', motivo = '$motivo', data = '$data', grupo = '$grupo', dieta = '$dieta', log_usuario = '".$_SESSION['usuario']."'
                    where id = '$id'
                ");
            }
            $varConexao->pesquisa($query);
            echo $query;
            echo "OK2";
        break;
        case 'DIETA_EXCLUIR':
            $id = $_POST['id'];
            $query = "delete from agenda_desvio_dieta where id = '$id'";
            $varConexao->pesquisa($query);
            echo $query;
            echo "OK3";
        break;
        case 'DIETA_LISTA':
            $participante = $_GET['participante'];
            $grupo        = $_GET['grupo'];
            $periodo      = $_GET['periodo'];

            $query = "
                select
                    agenda_desvio_dieta.*,
                    cfg_centro.nome as centro_nome
                    from agenda_desvio_dieta
                        left join cfg_centro on agenda_desvio_dieta.centro = cfg_centro.id
                        where agenda_desvio_dieta.projeto = ".$_SESSION['projeto'];
            if($participante != ''){
                $query .= " and agenda_desvio_dieta.participante = '$participante'";
            }
            if($grupo != ''){
                $query .= " and agenda_desvio_dieta.grupo = '$grupo'";
            }
            if($periodo != ''){
                $query .= " and CAST(agenda_desvio_dieta.horario_coleta AS INT) = '$periodo'";
            }
            $query .= " order by centro, cast(participante as INTEGER), agenda_desvio_dieta.id";
            //echo $query;
            $res = $varConexao->pesquisa($query);
            if(mysqli_num_rows($res)==0){
                $data[] = array(
                    "centro"=>"",
                    "data"=>"",
                    "participante"=>"",
                    "grupo"=>"",
                    "codigo_participante"=>"",
                    "horario_coleta"=>"",
                    "horario_previsto"=>"",
                    "horario_realizado"=>"",
                    "tempo_atraso"      => "",
                    "dieta"=>"",
                    "motivo"=>"",
                    "botao"=>""
                );
                $response = array(
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "data" => $data
                  );
                echo json_encode($response);
            }else{
                while($row = mysqli_fetch_assoc($res)){
                    $link = "javascript:btnRegistro('modal_tab_dieta.php?id=".$row['id']."');";
                    $btn = '<a alt="Visualizar CRF" href="'.$link.'" class="btn btn-circle btn-sm blue">
									<i class="fa fa-search" alt="Visualizar CRF" ></i>
								</a>
                                <a alt="Exluir registro" href="javascript:excluir('.$row['id'].');" class="btn btn-circle btn-sm red">
									<i class="fa fa-trash" alt="Exluir registro" ></i>
								</a>
                    ';
                    $tempo_atraso = retornaDiferencaHorario($row['horario_previsto'], $row['horario_realizado']);
                    $data[] = array(
                        "centro"       => formata_utf8_encode($row['centro_nome']),
                        "data"              => "<center>".$row['data']."</center>",
                        "participante"      => "<center>".$row['participante']."</center>",
                        "grupo"             => "<center>".$row['grupo']."</center>",
                        "codigo_participante" => "<center>".$row['codigo_participante']."</center>",
                        "horario_coleta"    => "<center>".$row['horario_coleta']."</center>",
                        "horario_previsto"  => "<center>".$row['horario_previsto']."</center>",
                        "horario_realizado" => "<center>".$row['horario_realizado']."</center>",
                        "tempo_atraso"      => "<center>$tempo_atraso</center>",
                        "dieta"             => "<center>".formata_utf8_encode($row['dieta'])."</center>",
                        "motivo"            => formata_utf8_encode($row['motivo']),
                        "botao"             => "$btn"
                    );
                }
                echo json_encode($data);
                return;
            }
        break;
     }