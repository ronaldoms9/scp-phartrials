<?
	/*Acentuação teste chão*/
	session_start();
	if($_SESSION['valido']<1){echo "<script>window.location.replace('expirado.php');</script>";};
	include_once __DIR__ . "/class/conexao.class.php";
	include_once __DIR__ . "/class/usuario.class.php";
	include_once __DIR__ . "/class/agenda.class.php";
	include_once __DIR__ . "/definicoes.php";
	include_once __DIR__ . "/funcoes.php";
	$varConexao = new Conexao();

	$mes_corrido[1] = "Janeiro";
	$mes_corrido[2] = "Fevereiro";
	$mes_corrido[3] = "Março";
	$mes_corrido[4] = "Abril";
	$mes_corrido[5] = "Maio";
	$mes_corrido[6] = "Junho";
	$mes_corrido[7] = "Julho";
	$mes_corrido[8] = "Agosto";
	$mes_corrido[9] = "Setembro";
	$mes_corrido[10] = "Outubro";
	$mes_corrido[11] = "Novembro";
	$mes_corrido[12] = "Dezembro";

	function str_primeira_letra_maiuscula($entrada){
		$temp_entrada = $entrada;
		$entrada = strtolower($entrada);
		$entrada = explode(" ", $entrada);
		for($i=0;$i<count($entrada);$i++){
			if(substr($entrada[$i],0,1)!="("){
				$entrada[$i] = strtoupper(substr($entrada[$i],0,1)) . substr($entrada[$i],1,strlen($entrada[$i]));
			}else{
				$entrada[$i] = strtoupper(substr($entrada[$i],0,2)) . substr($entrada[$i],2,strlen($entrada[$i]));
			}
		}
		$saida = "";
		for($i=0;$i<count($entrada);$i++){
			$saida .= $entrada[$i] . " ";
		}
		//return trim($saida);
		return $temp_entrada;
	}
	function tirarAcentos($string){
		return preg_replace(array("/(á|à|ã|â|ä)/","/(Ý|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Ý|Ì|Î|Ý)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
	}

	require_once __DIR__ . '/vendor/autoload.php';


	function formatar_texto_bd($entrada){
		return utf8_encode($entrada);
	}

	$stylesheet = "
		.referencia{
			font-size:11pt;
			font-weight: bold;
			text-align: center;
		}
		.paragrafo{
			font-size:10pt;
		}

		.paragrafo1{
			font-size:10pt;
			font-weight: bold;
		}
		.versao_cabecalho{
			font-size:8pt;
			font-weight: bold;
			font-family: Times New Roman;
		}
		.footer_versao{
			font-size:8pt;
			font-weight: bold;
			font-family: Times New Roman;
		}

		.linha_json{
			text-align:center;
			border: 1px solid #000000;
			padding-left: 4px;
			padding-right: 4px;
			padding-top: 6px;
			padding-bottom: 3px;
			white-space:nowrap
		}
		.linha_json_legenda{
			text-align:center;
			font-size:5.0pt;
			font-style: italic;
		}
		.coluna_valor{
			text-align:center;
			font-size:6.0pt;
		}
		.dados_centro{
			font-family: 'Times new Roman';
			font-size: 10pt;
			font-weight: bold;
			line-height: 18px;
		}
		.tabela_recebimento{
			font-family: 'Times new Roman';
			font-size: 10pt;
			line-height: 29px;
			border:1;
			cellspacing:0;
			cellpadding:0;
			border-collapse: collapse;
			border: medium none;
			margin-left: 267.65pt
		}
		.recebimento_interno{
			font-family: 'Times new Roman';
			font-size: 9pt;
		}
		.conteudo_bold{
			font-family: 'Times new Roman';
			font-size: 9pt;
			font-weight:bold;
		}
		.conteudo_normal{
			font-family: 'Times new Roman';
			font-size: 9.5pt;
		}
		.paginacao{
			font-size:8.0pt;
		}
		.rodape{
			font-size:8.0pt;
			text-align:center;
		}
		.justificado{
			text-align: justify;
			letter-spacing: 0;
		}
			.ptFooterNormal{
			font-size: 10.0pt;
			font-weight: normal;
		}
		.ptTexto{
			font-size: 10.0pt;
			font-weight: normal;
		}
		.ptTabelaAzul{
			font-size: 11.0pt;
			font-weight: bold;
			background-color: #DBE5F1;
			color: black;
		}
		.ptTabelaAzulEscuro{
			font-size: 11.0pt;
			font-weight: bold;
			background-color: #8DB3E2;
			color: black;
		}

	";

	$query = "select
					agenda.*,
					cfg_centro.numero as centro_numero,
					cfg_centro.nome as centro_nome,
					cfg_centro.investigador  as centro_investigador,
					cfg_centro.coordenador as centro_coordenador,
					cfg_centro.endereco	as centro_endereco,
					usu_usuario.nome as monitora_nome
				 from agenda
					inner join cfg_centro on agenda.centro = cfg_centro.id
					left join usu_usuario on agenda.monitora = usu_usuario.id
				 where agenda.id = " . $_GET['id'];
	$res_agenda = $varConexao->pesquisa($query);
	$row_agenda = mysqli_fetch_assoc($res_agenda);

	$varAgenda = new Agenda($_GET['id']);
	$varMonitora = new Usuario($row_agenda['monitora']);
	$varRevisor  = new Usuario($row_agenda['revisor']);

	$query 			= "select * from proj_projeto where id = " . $_SESSION['projeto'];
	$res_projeto	= $varConexao->pesquisa($query);
	$row_projeto    = mysqli_fetch_assoc($res_projeto);

	$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/temp', 'debug' => true, 'mode' => 'utf-8', 'margin-top' => 1158, 'margin-header' => 1500 ]);
	$mpdf->setAutoTopMargin = 'stretch';
	$mpdf->setAutoBottomMargin = 'stretch';
	$mpdf->allow_charset_conversion=true;
	$mpdf->WriteHTML($stylesheet,1);

	$cabecalho = '<table border="0" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse;">
		<tr>
			<td style="padding: 13px;" >
                <center><br><img src="imagens/header_documento.png"></center>
            </td>
		</tr>
		</table>
	';
	$rodape = '<table border="0" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse;">
		<tr>
			<td style="padding: 3px; text-align: right;"><span class="ptFooterNormal">Página <b>{PAGENO}</b> de <b>{nbpg}</b></span></td>
		</tr>
		</table>
	';
	$mpdf->SetHTMLFooter($rodape);
	$mpdf->SetHTMLHeader($cabecalho);

	//Referência
	$html = '
			<div align="center"><span class="referencia">
				'.traduzir('AGENDA DE MONITORIA').'
			</span></div>
			';
	$mpdf->WriteHTML($html);

	$html = '<p class="paragrafo"><br><br>'.$_GET['data'].'</p>';
	//
	$html .= '<p class="paragrafo">À(o) ' . formata_utf8_encode($varAgenda->centro->quem_assina == "PI" ? $varAgenda->centro->investigador : $varAgenda->centro->coordenador) . '<br>
								   '.traduzir('Centro').': '.formatar_texto_bd($row_agenda['centro_nome']).'<br>
								   '.traduzir('Endereço').': '.nl2br(formatar_texto_bd($row_agenda['centro_endereco'])).'
	'	;
	$mpdf->WriteHTML($html);
	$html = '
		<p class="conteudo_bold justificado">
			'.traduzir('Ref.').': '.traduzir('Projeto').': "'.formatar_texto_bd($row_projeto['descricao']). '".
		</p>
		<p class="ptTexto">
			'.traduzir('Prezado(a)').',
		</p>
		<p class="ptTexto justificado">
			'.formatar_texto_bd($row_agenda['titulo']);
	$mpdf->WriteHTML($html);

	$html = '<span class="ptTexto">
		<p class="ptTexto">'.traduzir('Durante essa visita, os seguintes itens serão abordados').':</p>
		<p class="ptTexto"><ul>
	';
	$mpdf->WriteHTML($html);

	//Atividades
	$html = '
	<table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td class="ptTabelaAzul" style="padding: 5px;"><center><span class="ptTexto"><b>DATA E HORÁRIO DA<br>MONITORIA</b></span></center></td>
			<td class="ptTabelaAzul" style="padding: 5px;"><center><span class="ptTexto"><b>DOCUMENTOS/PROCEDIMENTOS A SEREM MONITORADOS</b></span></center></td>
        </tr>

	';

	//
	$query = "select * from agenda_atividade where agenda = ".$row_agenda['id'];
	$res_atividade = $varConexao->pesquisa($query);
	while($row_atividade = mysqli_fetch_assoc($res_atividade)){  //Linha de datas
		//Pegar as atividades
		$query = "select
						agenda_atividade.data,
						tab_agenda_atividades.id as id_atv,
						tab_agenda_atividades.atividade,
						agenda_atividade_atv.marcado
						from tab_agenda_atividades
							left join agenda_atividade_atv on tab_agenda_atividades.id = agenda_atividade_atv.id_tab_atividade
								and agenda_atividade_atv.id_atividade = ".$row_atividade['id']."
							left join agenda_atividade on agenda_atividade_atv.id_atividade = agenda_atividade.id
							where tab_agenda_atividades.ativo = 1 and agenda_atividade_atv.marcado = 1";
		$res_atividade_atv = $varConexao->pesquisa($query);
		$qtdAtv = mysqli_num_rows($res_atividade_atv);
		$iAtv = 1;
		while($row_atividade_atv = mysqli_fetch_assoc($res_atividade_atv)){
			$html .= '
				<tr>
			';
			if($iAtv == 1){
				$html .= '<td class="" style="vertical-align: top;padding: 5px;" rowspan="'.$qtdAtv.'" width="210px">
								<center>
									<span class="ptTexto">'.formata_utf8_encode($row_atividade['data']).' ('.formata_utf8_encode($row_atividade['periodo']).')</span>
								</center>
						  </td>';
			}
			$html.=		'<td class="" style="padding: 5px;"><span class="ptTexto">'.formata_utf8_encode($row_atividade_atv['atividade']).'</span></td>
				</tr>
			';
			$iAtv++;
		}
	}


	$html .= '
	</table>
	';
	$mpdf->WriteHTML($html);

	$html =  '
		</ul></p>
		<p class="ptTexto">
			'.traduzir('Pedimos, por gentileza, que os profissionais responsáveis pelos departamentos relacionados acima sejam contatados e informados sobre a visita, assim como a possibilidade de uma  breve visita nas áreas correspondentes').'.
		</p>
		<p class="ptTexto">
			'.traduzir('O(s) monitor(res)').' '.str_replace('<br />', ', ', formatar_texto_bd($row_agenda['presentes'])).' '.traduzir('estarão presentes nas monitorias, podendo ser substituído(s) se necessário').'.
		</p>
		<p class="ptTexto">
			'.traduzir('Caso haja alguma dúvida, estou à disposição no celular ').formatar_texto_bd($row_agenda['contato']).'.
		</p>

		<p class="ptTexto"><br>
		'.traduzir('Atenciosamente').',<br>

		<br>
		________________________________________<br>
		'.formatar_texto_bd($varMonitora->nome).'<br>
		'.traduzir(formatar_texto_bd($varMonitora->funcao)).'
	';
	$mpdf->WriteHTML($html);

	$html =  '
		</ul></p><p></p><br><br>
		<p class="ptTexto"><b><br><br>
			<b>'.traduzir('Confirmação de recebimento').':</b>
		</p>

		<br>
		________________________________________<br>
		'. formata_utf8_encode($varAgenda->centro->quem_assina == "PI" ? $varAgenda->centro->investigador : $varAgenda->centro->coordenador). '<br>
	';
	$mpdf->WriteHTML($html);


	$mpdf->Output();
	$mpdf->charset_in='UTF-8';
?>