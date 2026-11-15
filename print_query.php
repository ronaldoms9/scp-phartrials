<?
	include_once "seguranca.php";
	
	$query = "select * from vw_query_completa where query_id = " . $_GET['cod'];
	$res   = $conexao->pesquisa($query);
	$row   = mysqli_fetch_assoc($res);
?>
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns="http://www.w3.org/TR/REC-html40">

<head>
	<link href='https://fonts.googleapis.com/css?family=Libre Barcode 39' rel='stylesheet'>
<style>
	
<!--
 p.MsoNormal
	{mso-style-parent:"";
	margin-bottom:.0001pt;
	font-size:12.0pt;
	font-family:"Times New Roman","serif";
	margin-left:0cm; margin-right:0cm; margin-top:0cm}
table.MsoTableGrid
	{border:1.0pt solid black;
	font-size:10.0pt;
	font-family:"Times New Roman","serif"}
 table.MsoNormalTable
	{mso-style-parent:"";
	font-size:11.0pt;
	font-family:"Calibri","sans-serif";
	}
-->
</style>
<!--[if !mso]>
<style>
v\:*         { behavior: url(#default#VML) }
o\:*         { behavior: url(#default#VML) }
.shape       { behavior: url(#default#VML) }
</style>
<![endif]-->
<link rel="File-List" href="new_page_1_files/filelist.xml">
<!--[if gte mso 9]>
<xml><o:shapedefaults v:ext="edit" spidmax="1027"/>
</xml><![endif]-->
</head>

<body>

<table class="MsoTableGrid" border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: medium none">
	<tr>
		<td width="168" rowspan="2" valign="top" style="width: 125.8pt; border: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
			<p class="MsoHeader"><img border=0 width=154 height=92 src="logo_qsbsoft.png" >
		</td>
		<td width="529" colspan="3" valign="top" style="width: 397.0pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.0pt solid windowtext; border-bottom: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
		<p class="MsoHeader" align="center" style="text-align:center"><b>
		<span style="font-family:&quot;Arial Black&quot;,&quot;sans-serif&quot;">
		FORMULÁRIO DE ESCLARECIMENTO DE DADOS</span></b> (<i>Query</i>)</td>
	</tr>
	<tr>
		<td width="132" valign="bottom" style="width: 98.95pt; border-left: medium none; border-right: medium none; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
		<p class="MsoHeader" align="center" style="margin-bottom:2.0pt;text-align:center">
		<span style="font-size: 7.0pt; font-family: Garamond,serif">
		Questionamento Automático</span></td>
		<td width="144" style="width: 108.0pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
		<p class="MsoHeader" align="right" style="text-align:right">
		Questionamento Nº</td>
		<td width="253" style="width: 190.05pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
			<p class="MsoHeader" align="center" style="text-align:center" valign="center">
			<center>
				<span style="font-size:42.0pt;font-family:'Libre Barcode 39'; font-size: 50px">*<?=$row['query_nro']?>*</span><br>				
				<span style="font-size:42.0pt;font-family:'Arial'; font-size: 14px"><b><?=$row['query_nro']?></b></span>
			</center>
		</td>
	</tr>
</table>
<br>

<table class="MsoTableGrid" border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: medium none">
	<tr>
		<td width="83" valign="top" style="border:1.0pt solid windowtext; width: 62.2pt; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 3pt; padding-bottom: 3pt; position:relative" align="right">
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><font size="3">CENTRO:</font></p></td>
		<td valign="top" style="border:1.0pt solid windowtext; width: 270px; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 3pt; padding-bottom: 3pt; position:relative">
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal" align="center"><font size="4"><?=$row['query_centro'];?></font></td>
		<td valign="top" style="border:1.0pt solid windowtext; width: 114px;  padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 3pt; padding-bottom: 3pt; position:relative" align="right">
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><font size="3">PARTICIPANTE:</font></td>
		<td valign="top" style="width: 254px; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.0pt solid windowtext; border-bottom: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 3pt; padding-bottom: 3pt; position:relative">
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal" align="center"><font size="4"><b><?=$row['nro_participante'];?></b></font></td>
	</tr>
	<tr>
		<td style="position: relative; border: 1px solid #000000; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" align="right">
		<font size="3">PÁGINA DO CRF:</font></td>
		<td style="position: relative; border: 1px solid #000000; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
		<?=$row['FORMULARIO_NOME'];?></td>
		<td style="position: relative; border: 1px solid #000000; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" align="right">
		<font size="3">CAMPO:</font></td>
		<td style="position: relative; border: 1px solid #000000; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
		<?=$row['TEXTO_CAMPO'];?></td>
	</tr>
</table>

<p class="MsoNormal" style="line-height: normal; margin-bottom: .0001pt">&nbsp;</p>
<table class="MsoTableGrid" border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: medium none">
	<tr height="200">
		<td width="697" colspan="2" valign="top" style="width: 522.8pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: 1.0pt solid windowtext; border-bottom: medium none; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
			<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
	  normal"><b><u>QUESTIONAMENTO</u></b>:&nbsp;&nbsp;&nbsp; <span style="font-size: 8.0pt">( 
			Descrição do Achado / Problema &nbsp;&nbsp;<b>E</b> &nbsp;&nbsp;Questionamento / Solicitação 
			de Esclarecimento decorrente )</span></p>
			<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
	  normal">&nbsp;</p>
			<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
	  normal"><font face="tahoma" size="3"><?=$row['query_pergunta'];?></font></p>
		
		</td>
	</tr>
	<tr>
		<td width="366" valign="top" style="width: 274.25pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</td>
		<td width="331" valign="top" style="width: 248.55pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.0pt solid windowtext; border-bottom: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">Responsável pelo Questionamento:</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 8.0pt">(Nome/Rubrica, Data)</span></p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><centeR><b><?=$row['responsavel_questionamento'];?></b><br>
	<?
		$data = $row['query_data_hora'];
		$data = substr($data, 8,2) . "/" . substr($data, 5,2). "/" . substr($data, 0,4);
		echo $data;
	?></center>
  </p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</td>
	</tr>
	<tr>
		<td width="697" colspan="2" valign="top" style="width: 522.8pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: medium none; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><b><u>RESPOSTA</u></b>:&nbsp;&nbsp;&nbsp; <span style="font-size: 8.0pt">(Correção, 
		&nbsp;Esclarecimento ou Justificativa fornecida pelo Investigador ou 
		Responsável)</span></p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" align="right" style="margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal">&nbsp;</td>
	</tr>
	<tr>
		<td width="366" valign="top" style="width: 274.25pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" align="center" style="margin-bottom:2.0pt;text-align:center;
  line-height:normal">
		<span style="font-size: 8.0pt; font-family: Garamond,serif">Se o espaço 
		for insuficiente, utilize o verso deste formulário.</span></td>
		<td width="331" valign="top" style="width: 248.55pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.0pt solid windowtext; border-bottom: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">Responsável pela Resposta:</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 8.0pt">(Nome/Rubrica, Data)</span></p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</p>
		<p class="MsoNormal" style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal">&nbsp;</td>
	</tr>
</table>
<p class="MsoNormal" style="line-height: normal; margin-bottom: .0001pt">&nbsp;</p>
<table class="MsoTableGrid" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: medium none; margin-left: -.25pt">
	<tr>
		<td width="366" colspan="2" valign="top" style="width: 274.5pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: 1.0pt solid windowtext; border-bottom: medium none; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
		<p class="MsoFooter">Reservado <span style="font-size:8.0pt">(Controle)</span></td>
		<td width="331" colspan="2" valign="top" style="width: 248.3pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: 1.0pt solid windowtext; border-bottom: medium none; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
		<p class="MsoFooter">Resposta Implementada por</td>
	</tr>
	<tr>
		<td width="366" colspan="2" valign="top" style="width: 274.5pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
		
		<p class="MsoFooter"><span style="font-size: 8.0pt">&nbsp;</span></p>
		<p class="MsoFooter"><span style="font-size: 8.0pt">&nbsp;</span></p>
		<p class="MsoFooter">&nbsp;</td>
		<td width="331" colspan="2" valign="top" style="width: 248.3pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
		<p class="MsoFooter"><span style="font-size: 8.0pt">(Nome/Rubrica, Data)</span></td>
	</tr>
	<tr>
		<td width="144" style="width: 108.0pt; border: medium none; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
		<p class="MsoFooter">
		<span style="font-size: 9.0pt; font-family: 'Times New Roman',serif">
		Estudo GDN 018-16</span></td>
		<td width="444" colspan="2" style="width: 333.0pt; border: medium none; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
		<p class="MsoFooter" align="center" style="text-align:center">
		<span style="font-size: 8.0pt; font-family: 'Arial Narrow',sans-serif">
		Formulário&nbsp; v1.0 </span></td>
		<td width="109" style="width: 81.8pt; border: medium none; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
		<p class="MsoFooter" align="right" style="text-align:right">Página 1/1</td>
	</tr>
</table>

</body>