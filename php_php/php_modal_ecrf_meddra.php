<?php
    //
	include_once "../class/conexaoBancos.class.php";

    if($_GET['act']=="LISTAGEM_TOTAL2"){
        $final = '<div id="html1">
                    <ul>
                        <li>Root node 1</li>
                        <li>Root node 2</li>
                    </ul>
                </div>';
        echo $final;
    }
    if($_GET['act']=="RETORNA_ID"){
        $conexaoBanco = new ConexaoBancos("bancos_meddra");
        //$query  = "select * from md_hierarchy where id = " . $_POST['varId'];
        $query = "SELECT md_hierarchy.pt_code,
                    md_hierarchy.hlt_code,
                    md_hierarchy.hlgt_code,
                    md_hierarchy.soc_code,
                    md_hierarchy.pt_name,
                    md_hierarchy.hlt_name,
                    md_hierarchy.soc_name,
                    md_hierarchy.soc_abbrev,
                    md_hierarchy_en.pt_name as pt_name_en,
                    md_hierarchy_en.hlt_name as hlt_name_en,
                    md_hierarchy_en.soc_name as soc_name_en,
                    md_hierarchy_en.soc_abbrev as soc_abbrev_en
                        FROM md_hierarchy
                        left join md_hierarchy_en on md_hierarchy.pt_code = md_hierarchy_en.pt_code
                            and md_hierarchy.hlt_code = md_hierarchy_en.hlt_code
                            and md_hierarchy.hlgt_code = md_hierarchy_en.hlgt_code
                            and md_hierarchy.soc_code = md_hierarchy_en.soc_code
                        where md_hierarchy.id = " . $_POST['varId'];
        $resLin = $conexaoBanco->pesquisa($query);
        $rowLin = mysqli_fetch_assoc($resLin);

        $query = "select llt_code, llt_name as nome from llt where llt_code = '".$_POST['lltCode']."'";
        $resLLT = $conexaoBanco->pesquisa($query);
        $rowLLT = mysqli_fetch_assoc($resLLT);
        $lltNome = $rowLLT['nome'];

        $query = "select llt_code, llt_name as nome from llt_en where llt_code = '".$_POST['lltCode']."'";
        $resLLT = $conexaoBanco->pesquisa($query);
        $rowLLT = mysqli_fetch_assoc($resLLT);
        $lltNomeEn = $rowLLT['nome'];

        $response = array(
            "resp"  => "OK",
            "llt_code" 	=> $rowLLT['llt_code'],
            "nome" => strtoupper(utf8_encode($lltNome)),
            "pt_code"  => $rowLin['pt_code'],
            "pt_name"  => utf8_encode($rowLin['pt_name']),
            "hlt_code" => $rowLin['hlt_code'],
            "soc_code" => $rowLin['soc_code'],
            "soc_name" => utf8_encode($rowLin['soc_name']),
            "llt_name_en"   => utf8_encode($lltNomeEn),
            "pt_name_en"    => utf8_encode($rowLin['pt_name_en']),
            "soc_name_en"   => utf8_encode($rowLin['soc_name_en']),
        );
        //$array = array_map( 'htmlentities' , $response );
        $json = html_entity_decode( json_encode( $response ) );
        echo $json;
    }
    if($_GET['act']=="RETORNA_PT"){
        $conexaoBanco = new ConexaoBancos("bancos_meddra");
        $meddra = $_POST['meddra'];
        $query  = "select pt_name from vw_completo where pt_code = '$meddra'";
        $resPT  = $conexaoBanco->pesquisa($query);
        $rowPT = mysqli_fetch_assoc($resPT);
        echo $rowPT['pt_name'];
    }
    if($_GET['act']=="RETORNA_SOC_NAME"){
        $conexaoBanco = new ConexaoBancos("bancos_meddra");
        $meddra = $_POST['meddra'];
        $query  = "select descricao from soc where codigo = '$meddra'";
        $resPT  = $conexaoBanco->pesquisa($query);
        $rowPT = mysqli_fetch_assoc($resPT);
        echo $rowPT['descricao'];
    }

	if($_GET['act']=="LISTAGEM_TOTAL"){
		$chave = utf8_decode($_POST['chave']);
        $comAcentos = array('�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', 'O', '�', '�', '�');
        $semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U');
        //$chave = str_replace($comAcentos, $semAcentos, $chave);
		$conexaoBanco = new ConexaoBancos("bancos_meddra");
        $query = 'SELECT * FROM vw_completo where UPPER(vw_completo.llt_name) like("%'.$chave.'%");';
        $resTem = $conexaoBanco->pesquisa($query);
        $html = "<div id='html1'>
                    <ul>";
        if(mysqli_num_rows($resTem)==0){
            ?>
                <tr>
                    <td colspan="4">Nenhum registro encontrado...</td>
                </tr>
            <?php
        }else{
            if($chave==""){
            ?>
                <tr>
                    <td colspan="4">Por favor, selecione um nome para pesquisar...</td>
                </tr>
            <?php
            }else{
                $query = 'SELECT DISTINCT vw_completo.soc_code, vw_completo.soc_name
                            FROM vw_completo
                                where llt_name like ("%'.$chave.'%")
                                    order by soc_name
                ';
                $resSOC = $conexaoBanco->pesquisa($query);
                while($rowSOC = mysqli_fetch_assoc($resSOC)){
                    $html .= "<li>[soc]
                                ".$rowSOC['soc_code']." - ".$rowSOC['soc_name']."
                            ";
                        $query 	= 'SELECT distinct hlt_code, hlt_name
                                        FROM vw_completo
                                            where llt_name like ("%'.$chave.'%")
                                                and soc_code = "'.$rowSOC['soc_code'].'"';
                        $resGrp = $conexaoBanco->pesquisa($query);
                        if(mysqli_num_rows($resGrp)>0){
                            $subCategoria = array();
                            $html .= "
                                    <ul>";
                            while($rowGrp = mysqli_fetch_assoc($resGrp)){
                                $html .= "
                                            <li>[hlt]".$rowGrp['hlt_code'] . " - " . $rowGrp['hlt_name']."

                                ";
                                $query = "SELECT distinct llt_code, llt_name
                                            FROM vw_completo
                                                where llt_name like ('%".$chave."%') and hlt_code = '".$rowGrp['hlt_code']."';";
                                $resPPT = $conexaoBanco->pesquisa($query);
                                $html .= "<ul>";
                                while($rowLLT = mysqli_fetch_assoc($resPPT)){
                                    $html .= "
                                            <li>[llt]".$rowLLT['llt_code'] . " - " . $rowLLT['llt_name'];
                                    $lltCode = $rowLLT['llt_code'];
                                    $query = "SELECT distinct id, pt_code, pt_name
                                                FROM vw_completo
                                                    where llt_code = '".$rowLLT['llt_code']."'
                                                        and hlt_code = '".$rowGrp['hlt_code']."'

                                                    ;";
                                    $resPPT = $conexaoBanco->pesquisa($query);
                                    $html .= "<ul>";
                                    while($rowPPT = mysqli_fetch_assoc($resPPT)){
                                        $lnkSocCode = $rowSOC['soc_code'];
                                        $lnkSocName = $rowSOC['soc_name'];
                                        $lnkPTCode  = $rowPPT['pt_code'];
                                        $lnkPTName  = $rowPPT['pt_name'];
                                        $id         = $rowPPT['id'];
                                        //$html .= '<li data-jstree={"icon":"jstree-file"} onclick=javascript:selecionaRetornoModalMEDDRA("'.$lnkSocCode.'","'.$lnkPTCode.'")>[pt]'.$lnkPTCode.' - ' . $lnkPTName.'</li>';
                                        $html .= '<li data-jstree={"icon":"jstree-file"} onclick=javascript:selecionaRetornoModalMEDDRA('.$id.',"'.trim($lltCode).'");>[pt]'.$lnkPTCode.' - ' . $lnkPTName.'</li>';
                                    }
                                    $html .= "
                                        </ul>
                                    </li>";
                                }
                                $html .= "</ul>
                                </li>";
                            }
                            $html .= "</ul>";
                        }
                    $html .= '</li>';
                }
                $html .= "
                                </ul>
                            </div>";
            }
        }
		echo $html;
	}