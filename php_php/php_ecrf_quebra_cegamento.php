<?php
    session_start();
    include_once "../class/conexao.class.php";
    $varConexao = new Conexao();

    if($_GET['act']=="QUEBRA"){
        $varParticipante = $_POST['participante'];
        $varMotivo       = $_POST['motivo'];
        $varSenha        = $_POST['senha'];


        //Verificar primeiramente se a senha esta correta
        $query      =   "select * from usu_usuario where senha = md5('$varSenha');";
        $resSenha   =   $varConexao->pesquisa($query);
        if(mysqli_num_rows($resSenha)==0){  //A senha esta incorreta. Retorno o erro
            $response = array(
                "resp"          => "ERRO",
                "tipo" 	    => "SENHA"
            );
            $array = array_map( 'htmlentities' , $response );
            $json = html_entity_decode( json_encode( $array ) );
            echo $json;
            exit;
        }else{
            $rowUsuario = mysqli_fetch_array($resSenha);
            if( ($rowUsuario['tipo_usuario']!="22")&&($rowUsuario['tipo_usuario']!="1")&&($rowUsuario['tipo_usuario']!="7")){
                $response = array(
                        "resp"          => "ERRO",
                        "tipo" 	    => "USER"
                );
                $array = array_map( 'htmlentities' , $response );
                $json = html_entity_decode( json_encode( $array ) );
                echo $json;
                exit;
            }
        }
        $query   = "select * from crf_paciente_pre_rand where id = " . $varParticipante;
        $resPart = $varConexao->pesquisa($query);
        if(mysqli_num_rows($resPart)>0){
            include_once "../class/participante.class.php";
            $rowPart = mysqli_fetch_assoc($resPart);

            $varCentro          = $rowPart['centro'];
            $varPart    = $rowPart['nro_triagem'];

            $varParticipante = new Participante($varCentro, $varPart);

            //Verificar se o formulário de VA esta preenchido como sim
            $varRealizada = $varParticipante->buscaValor("VA_REALIZADA");
            if($varRealizada!="1 - Sim"){
                $response = array(
                    "resp"          => "ERRO",
                    "tipo" 	    => "VA"
                    );
                    $array = array_map( 'htmlentities' , $response );
                    $json = html_entity_decode( json_encode( $array ) );
                    echo $json;
                    exit;
            }


            $dataHoraLis = date("YmdHi");
            $query = "insert into crf_quebra_cegamento (participante, centro, usuario, datahora, datahora_lis, motivo) values
                (
                    '".$varPart."',
                    '".$varCentro."',
                    '".$_SESSION['user_id']."',
                    '".date("d/m/Y H:i")."',
                    '".$dataHoraLis."',
                    '".$varMotivo."'
            )";
            $varConexao->pesquisa($query);
            $query = "select id from crf_quebra_cegamento where participante = '$varPart' and centro = '$varCentro' and usuario = '".$_SESSION['user_id']."' and datahora_lis = '$dataHoraLis'";
            $resPart = $varConexao->pesquisa($query);
            $rowPart = mysqli_fetch_assoc($resPart);
            $response = array(
                    "resp"          => "OK",
                    "centro" 	    => $varCentro,
                    "participante"  => $varPart,
                    "id"            => $rowPart['id']
            );
            $array = array_map( 'htmlentities' , $response );
            $json = html_entity_decode( json_encode( $array ) );
            echo $json;

        }
    }

