<?php
	include_once "class/conexao.class.php";
	$conexao = new Conexao();
?>
	<!--<script src="//code-sa1.jivosite.com/widget/KaCBeSXQXY" async></script>-->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
	

			<div class="page-wrapper-row">
                <div class="page-wrapper-bottom right" align="right">
                    <div class="page-footer">
                        <div class="container-fluid" align="right">
							<span align="right">
								<?=traduzir("Data / Hora de login");?> :&nbsp;
								<a href="#" ><?=$_SESSION['horario_sessao'];?></a>
                            </span>
							<br>
							<?=traduzir("Sessão expira em");?> : <a href="#" ><span id="countdown" name="countdown"></span></a>
                        </div>
                    </div>
                    <div class="scroll-to-top">
                        <i class="icon-arrow-up"></i>
                    </div>
                </div>
            </div>

<?php
	    	$query = "select * from usu_pagina_atual where sessao = " . $_SESSION['sessao'];
	    	$res_se = $conexao->pesquisa($query);
	    	if(mysqli_num_rows($res_se)<1) {
	    		$query = "insert into usu_pagina_atual (sessao, pagina) values (".$_SESSION['sessao'].",'".str_replace("/", "",$_SERVER['REQUEST_URI'])."')";
	    	}else{
				$query = "update usu_pagina_atual set pagina = '".str_replace("/", "",$_SERVER['REQUEST_URI'])."' where sessao = " . $_SESSION['sessao'];
	    	}
	    	$conexao->pesquisa($query);
			unset($_SESSION['ult_pagina']);
		    $_SESSION['ult_pagina'] = str_replace("/", "",$_SERVER['REQUEST_URI']);
?>


<script>

	function mudou_projeto(id){
		mostraCarregando();
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				removeCarregando()
				document.location = "inicial.php";
			}
		};
		xmlhttp.open("POST","ajax.php?act=mudou_projeto&id="+id);
		xmlhttp.send();
	}
	function carrega_projeto(){

		varUrl = "php_php/php_sistema.php?act=CARREGA_PROJETO";

		$.ajax({
			url: varUrl,
			type: "POST",
			async : false,
			success: function(response, textStatus, jqXHR) {
				document.getElementById("sistema_projeto").innerHTML = response;
			}
		});
	}
	function muda_linguagem(lingua){
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.location = "inicial.php";
			}
		};
		xmlhttp.open("POST","ajax.php?act=MUDOU_LINGUAGEM&lingua="+lingua);
		xmlhttp.send();
	}
	function muda_pagina(entrada){
		$.blockUI({ timeout: 1800, message: '<img src="imagens/carregando.gif" width="100">Aguarde, carregando informações...', css: {
			border: 'none',
			padding: '15px',
			backgroundColor: '#000',
			'-webkit-border-radius': '10px',
			'-moz-border-radius': '10px',
			opacity: .5,
			color: '#fff'
		} });
		document.location = entrada;
	}
	function mostraLoading(mensagem){
		$.blockUI({ timeout: 900, message: '<img src="imagens/carregando.gif" width="100">'+mensagem, css: {
			border: 'none',
			padding: '15px',
			backgroundColor: '#000',
			'-webkit-border-radius': '10px',
			'-moz-border-radius': '10px',
			opacity: .5,
			color: '#fff'
		} });
	}
	function mostraMensagem(mensagem){
		$.blockUI({ timeout: 9000, message: '<img src="imagens/carregando.gif" width="100">'+mensagem, css: {
			border: 'none',
			padding: '15px',
			backgroundColor: '#000',
			'-webkit-border-radius': '10px',
			'-moz-border-radius': '10px',
			opacity: .5,
			color: '#fff'
		} });
	}

	console.log("Rodape carregado");
	carrega_projeto();


</script>


