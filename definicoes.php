<?php
	include_once __DIR__ . "/funcoes.php";
	$stylesheet = "
		table {
			overflow: wrap;
		}
		.direita {
		  text-align: right;
		  margin-right: 1em;
		}
		.texto_header_assinatura{
			font-size: 8pt;
		}
		.texto_assinatura{
			font-size: 9pt;
			font-weight: bold;
		}
		.assinatura_imagem{
			width:100%;
			max-width:150px;
		}
		.class_item{
			height: 22.85pt;
			border-left: 1.0pt solid black;
			border-right: 1.0pt solid black;
			border-top: 1.0pt solid black;
			border-bottom: 1.0pt solid black;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			padding-top: 1.5pt;
			padding-bottom: 3.5pt;
			text-align:left;
			//position:relative;
		}
		.class_item_coluna{
			height: 22.85pt;
			border-left: medium none;
			border-right: 1.0pt solid black;
			border-top: 1.0pt solid black;
			border-bottom: 1.0pt solid black;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			padding-top: 1.5pt;
			padding-bottom: 3.5pt;
			text-align:center;
			//position:relative;
		}
		.texto{
			font-size:8.0pt;
			font-weight: bold;
		}
		.texto_ordem{
			font-size:8.0pt;
			font-weight: bold;
		}
		.texto_en{
			font-size:6.0pt;
		}

		.rodape{
			font-size:7.0pt;
			font-weight: bold;
		}
		.texto_caption{
			font-size: 10pt;
			font-weight: bold;
		}
		.texto_conteudo{
			font-size: 8pt;
		}
		.texto_formulario{
			font-size: 9.0pt;
		}
		.texto_formulario_divisao{
			font-size: 8.0pt;
			font-weight: bold;
		}
		.texto_formulario_en{
			font-size: 6.5pt;
			font-style: italic;
		}
		.texto_formulario_nro{
			font-size: 9.0pt;
		}
		.header_formulario{
			padding-top: 4pt;
			padding-bottom: 3pt;
			border-left: 1.0pt solid black;
			border-right: 1.0pt solid black;
			border-top: 1.0pt solid black;
			border-bottom: 1.0pt solid black;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			background: #D9D9D9
		}
		.header_pagina_em_branco{
			padding-top: 4pt;
			padding-bottom: 3pt;
			border-left: 1.0pt solid black;
			border-right: 1.0pt solid black;
			border-top: 1.0pt solid black;
			border-bottom: 1.0pt solid black;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			background: white;
		}
		.cabecalho{
			/*background: #D9D9D9*/
			background: white;
			border-left: 0.0pt solid black;
			border-right: 0.0pt solid black;
			border-top: 0.0pt solid black;
			border-bottom: 0.0pt solid black;
		}
		.cabecalho_1{
			padding-top: 4pt;
			padding-bottom: 3pt;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			/*background: #D9D9D9;*/
			background: white;
			font-size: 7.0pt;
		}
		.cabecalho_2{
			padding-top: -2.0pt;
			padding-bottom: 0pt;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			/*background: #D9D9D9*/
			background: white;
			margin-top: -5pt;
			vertical-align: top;
			font-family: Tahoma, Verdana, Segoe, sans-serif;
			font-size: 7.0pt;
		}


		.divisao{
			border-left: 1.0pt solid black;
			border-right: 1.0pt solid black;
			border-top: 1.0pt solid black;
			border-bottom: 1.0pt solid black;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			padding-top: 4pt;
			padding-bottom: 3pt;
			background: #D9D9D9
		}
		.header_json{
			border: 1.0pt solid windowtext;
			padding-left: 5.4pt;
			padding-right: 5.4pt;
			padding-top: 4pt;
			padding-bottom: 3pt;
			background: #F2F2F2
		}
		.header_json_pt{
			font-size: 6.0pt

		}
		.header_json_en{
			font-size:4.5pt;
			font-style: italic;
			font-weight: normal;
		}
		.header_json_lg{
			font-size:4.0pt;
			font-style: italic;
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
		.paginacao{
			font-size:8.0pt;

		}
		.justificado{
			text-align: justify;
			letter-spacing: 0;
		}
		.centralizado{
			text-align: center;
			letter-spacing: 0;
		}
		.ass_header{
			font-size:10.0pt;
			font-weight: bold;
			padding-top: 4px;
			padding-bottom: 4px;
		}
		.ass_caption{
			font-size:8.0pt;
			font-weight: bold;
			padding-top: 4px;
			padding-bottom: 4px;
		}
		.ass_item{
			font-size:8.0pt;
			font-weight: normal;
			padding-top: 6px;
			padding-bottom: 4px;
		}
		.ass_item_caption{
			font-size:6.0pt;
			font-weight:normal;
			padding-top: 4px;
			padding-bottom: 4px;
		}
		.rodapeVersao{
			font-size:5.0pt;
			font-weight:normal;
			padding-top: 4px;
			padding-bottom: 4px;
		}
		.header_json{
			border: 1.0pt solid windowtext;
			padding-left: 5.4pt;
			padding-right: 5.4pt;
			padding-top: 4pt;
			padding-bottom: 3pt;
			background: #F2F2F2
		}
		.header_json_pt{
			font-size: 8.0pt

		}
		.header_json_en{
			font-size:4.5pt;
			font-style: italic;
			font-weight: normal;
		}
		.header_json_lg{
			font-size:4.0pt;
			font-style: italic;
		}
		.linha_json_preenchido{
			text-align:center;
			border: 1px solid #000000;
			padding-left: 4px;
			padding-right: 4px;
			padding-top: 6px;
			padding-bottom: 3px;
			white-space:nowrap
			font-size: 6.0pt
		}
		.css_texto_json{
			text-align:center;
			font-size: 6.0pt;
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
		.ptHeaderTitulo{
			font-size: 20.0pt;
			font-weight: bold;
		}
		.ptHeaderDestaque{
			font-size: 16.0pt;
			font-weight: bold;
		}
		.ptHeaderNormal{
			font-size: 19.0pt;
			font-weight: normal;
		}
		.ptFooterDestaque{
			font-size: 10.0pt;
			font-weight: bold;
		}
		.ptFooterNormal{
			font-size: 10.0pt;
			font-weight: normal;
		}
		.ptTexto{
			font-size: 11.0pt;
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
		.vertical-lr {
			writing-mode: vertical-lr;
			transform: rotate(180deg);
		}
		.ptTabelaPadding{
			padding-left: 30.5px !important;
			padding-right: 30.5px !important;
			padding-top: 30.5px !important;
			padding-bottom: 30.5px !important;
		}
	";

	$rodape = '
		<table width="100%" style="text-align:center">
		  <tr style="text-align:center">
			<td width="33%" align="center" style="text-align:left">
				<br><br><span class="rodape">Template vr 2.0 - 30/06/2025</span>
			</td>
			<td width="33%" align="center" style="text-align:center">
				<span class="rodape"><b>PHARTRIALS</b><br>
				'.traduzir('Telefone').'   +0055 (19) telefone<br>
				www.phartrials.com.br</span>
			</td>
			<td width="33%" style="text-align:right">
				<br><br><br><span class="paginacao">{PAGENO}/{nbpg}</font>
			</td>
		 </tr>
		</table>
	';
?>