<?php
	if($_GET['act']=="GERA"){
		$variaveis = $_POST['variaveis'];
		$variaveis = explode(";", $variaveis);
		$nome 	   = $_POST['nome'];
		$tabela	   = $_POST['tabela'];

$saida = "class $nome{
";
for($i=0;$i<count($variaveis)-1;$i++){
	$saida .= "	public $" . trim($variaveis[$i]) . ";
";
}
$saida .= "

	public function __construct(){
		";
for($i=0;$i<count($variaveis)-1;$i++){
	//$saida .= "	$this->" . trim($variaveis[$i]) . " = @rowInic['" . trim($variaveis[$i]) . "']";
}
$saida .= "
	}
";
$saida .= "}
		";

	}

?>
<html>
	<body>
		<center><h1 style="color: #5e9ca0;">Criador de classe PHP!</h1>
		<h2 style="color: #2e6c80;">Como usar:</h2></center>
		<p>Colocar todas as vari&aacute;veis, separadas por ponto e v&iacute;rgula, no campo abaixo.</p>
		<p>Clicar no bot&atilde;o <span style="background-color: #2b2301; color: #fff; display: inline-block; padding: 3px 10px; font-weight: bold; border-radius: 5px;">GERAR</span> para gerar o c&oacute;digo.</p>
		<p>&nbsp;</p>
		<form class="form-horizontal" role="form" action="cria_class.php?act=GERA" method="post" id="form_info" name="form_info">

			<table style="border-collapse: collapse; width: 100%;" border="1">
				<tbody>
					<tr>
						<td style="width: 28.5512%; text-align: right;">Nome da Classe:</td>
						<td style="width: 71.4488%;"><input type="text" name="nome" value="Pedido"></td>
					</tr>
					<tr>
						<td style="width: 28.5512%; text-align: right;">Vari&aacute;veis:</td>
						<td style="width: 71.4488%;"><textarea name="variaveis" style="width: 670px; height: 192px;" rows="10">
TPED_EMITENT_1;
TPED_NRONOTA_1;
TPED_SERNOTA_1;
TPED_SITUACA_1;
TPED_ESPNOTA_1;
TPED_CLIENTE_1;
TPED_SIGLEST_1;
TPED_PUBLICO_1;
TPED_VENDEDO_1;
TPED_SUPERVI_1;
TPED_PAGFRET_1;
TPED_USUARIO_1;
TPED_DATPEDI_1;
TPED_TIPONOT_1_O1;
TPED_DEPARTA_1;
TPED_SUBDEPT_1;
TPED_CONDPGT_1;
TPED_DATEMIS_1;
TPED_EMISDAT_1;
TPED_DATPROC_1;
TPED_DATSAID_1;
TPED_HORSAID_1;
TPED_NOMECLI_1;
TPED_TOTNOTA_1;
TPED_TOTMERC_1;
TPED_TRIBUTA_1;
TPED_NAOTRIB_1;
TPED_DATVENC_1_O1;
TPED_VALVENC_1_O1;
TPED_TIPODOC_1_O1;
TPED_CODCLIE_1_O1;
TPED_VALIDAD_1;
TPED_VALINVE_1;
TPED_NOMEVEN_1;
TPED_NRORDEM_1;
TPED_PLACVEI_1;
TPED_NUMPRIS_1;
TPED_TIPOPED_1;
TPED_PROCDAT_1;
TPED_MERCLIQ_1;
tped_percomi_1;
tped_comisup_1;
tped_transpo_1;
tped_endtran_1;
tped_munitra_1;
tped_uftrans_1;
tped_insctra_1;
tped_ufplaca_1;
tped_consign_1;
tped_notavis_1;
tped_pedido1_1;
tped_pedido2_1;</textarea></td>
					</tr>
					<tr>
						<td style="width: 28.5512%; text-align: center;">Nome da tabela no BD:</td>
						<td style="width: 71.4488%;"><input type="text" name="tabela" value="NOTPED"></td>
					</tr>
				</tbody>
			</table>

			<p style="text-align: center;"></p>

			<p style="text-align: center;">&nbsp;</p>
			<p style="text-align: center;">&nbsp;</p>
			<p style="text-align: center;"><input type="submit" value="GERAR" /></p>
		</form>

		<p>&nbsp;</p>
		<p style="text-align: center;"><textarea style="width: 1024px;" rows="60"><?=$saida;?></textarea></p>
	</body>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.1/themes/dark-hive/jquery-ui.css" crossorigin="anonymous"></script>
	<script>
	</scrpit>

</html>