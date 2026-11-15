	baseUrl = "php_php/";

	function filtro(){
		mostraCarregando();
		document.getElementById("tabela_retorno").innerHTML = "<img src='imagens/carregando.gif' width='85'>Carregando...";
		varUrl = baseUrl + "php_mon_fin_cobranca.php?act=PESQUISA_GERACAO";

		var formData = {}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				removeCarregando();
				document.getElementById("tabela_retorno").innerHTML = response;
			},
			error: function (jqXHR, textStatus, errorThrown) {
				removeCarregando();
			}
		});
	}
	function cobranca(){
		mostraCarregando();
		varUrl = baseUrl + "php_mon_fin_cobranca.php?act=GERA_COBRANCA";
		var formData = {}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				removeCarregando();
				document.location = "mon_fin_cobranca.php";
			},
			error: function (jqXHR, textStatus, errorThrown) {
				removeCarregando();
			}
		});
	}