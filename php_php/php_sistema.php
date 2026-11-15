<?php
    include_once "../seguranca.php";
    session_start();

    $varConexao = new Conexao();
    $conexao    = new Conexao();

    $act = strtoupper($_GET['act']);


    switch($act){
        case 'CARREGA_PROJETO':
            echo "Projeto selecionado: ".$_SESSION['projeto'];

            echo "<option value=''>ESCOLHA O PROJETO</option>";
            //if($_SESSION['centro']==40){
                //Aqui coloco os Protocolos

                /*echo '<optgroup label="PROTOCOLOS">';
                    $query = "select * from proj_projeto where tipo = 'PROT' order by numero";
                    $res_p = $conexao->pesquisa($query);
                    while($row_p = mysqli_fetch_assoc($res_p)){
                        echo "<option value='".$row_p['id']."' ";
                        if(isset($_SESSION['projeto'])){if($_SESSION['projeto']==$row_p['id']){ echo " selected "; }}
                        echo ">";
                        if($_SESSION['user_id']=="1"){echo "[".$row_p['id']."]";}
                        echo $row_p['numero']. " - " . $row_p['nome']."</option>";
                    }
                echo '</optgroup>';
                //Aqui coloco os Relatórios
                echo '<optgroup label="RELATÓRIOS">';
                    $query = "select * from proj_projeto where tipo = 'REL' order by numero";
                    $res_p = $conexao->pesquisa($query);
                    while($row_p = mysqli_fetch_assoc($res_p)){
                        echo "<option value='".$row_p['id']."' ";
                        if(isset($_SESSION['projeto'])){if($_SESSION['projeto']==$row_p['id']){ echo " selected "; }}
                        echo ">";
                        if($_SESSION['user_id']=="1"){echo "[".$row_p['id']."]";}
                        echo $row_p['numero']. " - " . $row_p['nome']."</option>";
                    }
                echo '</optgroup>'; */
                //Aqui eu coloco os estudos de BIO
                echo '<optgroup label="BIOEQUIVALÊNCIA">';
                    $query = "select * from proj_projeto where tipo is null or tipo = 'BIO' order by numero";
                    $res_p = $conexao->pesquisa($query);
                    while($row_p = mysqli_fetch_assoc($res_p)){
                        echo "<option value='".$row_p['id']."' ";
                        if(isset($_SESSION['projeto'])){if($_SESSION['projeto']==$row_p['id']){ echo " selected "; }}
                        echo ">";
                        if($_SESSION['user_id']=="1"){echo "[".$row_p['id']."]";}
                        echo $row_p['numero']. " - " . $row_p['nome']." - ".$row_p['codigo']."</option>";
                    }
                echo '</optgroup>';
                echo '<optgroup label="BIODISPONIBILIDADE">';
                    $query = "select * from proj_projeto where tipo is null or tipo = 'BIOD' order by numero";
                    $res_p = $conexao->pesquisa($query);
                    while($row_p = mysqli_fetch_assoc($res_p)){
                        echo "<option value='".$row_p['id']."' ";
                        if(isset($_SESSION['projeto'])){if($_SESSION['projeto']==$row_p['id']){ echo " selected "; }}
                        echo ">";
                        if($_SESSION['user_id']=="1"){echo "[".$row_p['id']."]";}
                        echo $row_p['numero']. " - " . $row_p['nome']." - ".$row_p['codigo']."</option>";
                    }
                echo '</optgroup>';

                //Aqui coloco os de pesquisa clínica
                echo '<optgroup label="PESQ CLÍNICA">';
                    $query = "select * from proj_projeto where tipo = 'PESQ' order by numero";
                    $res_p = $conexao->pesquisa($query);
                    while($row_p = mysqli_fetch_assoc($res_p)){
                        echo "<option value='".$row_p['id']."' ";
                        if(isset($_SESSION['projeto'])){if($_SESSION['projeto']==$row_p['id']){ echo " selected "; }}
                        echo ">";
                        if($_SESSION['user_id']=="1"){echo "[".$row_p['id']."]";}
                        echo $row_p['numero']. " - " . $row_p['nome']."</option>";
                    }
                echo '</optgroup>';
                //Aqui coloco os de pesquisa clínica
                /*echo '<optgroup label="PAPERs">';
                    $query = "select * from proj_projeto where tipo = 'PAPER' order by numero";
                    $res_p = $conexao->pesquisa($query);
                    while($row_p = mysqli_fetch_assoc($res_p)){
                        echo "<option value='".$row_p['id']."' ";
                        if(isset($_SESSION['projeto'])){if($_SESSION['projeto']==$row_p['id']){ echo " selected "; }}
                        echo ">";
                        if($_SESSION['user_id']=="1"){echo "[".$row_p['id']."]";}
                        echo $row_p['numero']. " - " . $row_p['nome']."</option>";
                    }
                echo '</optgroup>';
                */


            //}else{
               /*
               echo "<option value=''>ESCOLHA O PROJETO</option>";
                $query = "select distinct
                            proj_projeto.* from proj_projeto
                            inner join usu_estudo_usuario on proj_projeto.id = usu_estudo_usuario.estudo
                                where usu_estudo_usuario.usuario = " . $_SESSION['user_id'] . "
                            order by numero
                        ";
                $res_p = $conexao->pesquisa($query);
                while($row_p = mysqli_fetch_assoc($res_p)){
                    echo "<option value='".$row_p['id']."' ";
                    if($_SESSION['projeto']==$row_p['id']){ echo " selected "; }
                    echo ">";
                    if($_SESSION['user_id']=="1"){echo "[".$row_p['id']."]";}
                    echo $row_p['numero']. " - " . $row_p['nome']."</option>";
                }
                */
            //}


        break;
    }