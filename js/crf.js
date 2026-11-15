	var retorno = "";
	function popup(url){
		params  = 'width=700';
		params += ', height=500';
		params += ', top=0, left=0'
		params += ', fullscreen=yes';

		newwin=window.open(url,'windowname4', params);
		if (window.focus) {newwin.focus()}
		return false;
	}
	function data_atual(){
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth() + 1; //January is 0!
		var yyyy = today.getFullYear();
		if (dd < 10) {
		dd = '0' + dd;
		}
		if (mm < 10) {
		mm = '0' + mm;
		}
		today = dd + '/' + mm + '/' + yyyy;
		return today;
	}
	function isNumber(x) {
		if(typeof x == 'number'){
			return true;
		}else{
			return false;
		}
	}
	function DataToLIS(entrada){
		try{
			if(entrada.length==10){
				entrada = entrada.split("/");
				return entrada[2] + entrada[1] +entrada[0];
			}else{return "";}
		}catch(err){}
	}
	function mascara(t, mask){
		 var i = t.value.length;
		 var saida = mask.substring(1,0);
		 var mascara = mask.substring(i)
		 if(event.keyCode!=8){
			if(t.value.substr(i-1,1)!=mask.substr(i,1)){
				if (mascara.substring(0,1) != saida){
					t.value += mascara.substring(0,1);
				}
			}
		 }
	}

	function valida(valor,min,max,nome,campo){
		campo.style.backgroundColor ="";
		if(min>0){
			if((parseInt(valor)<parseInt(min))&&(parseInt(valor)>0)){
				campo.style.backgroundColor ="#ffe6e6";
				exibe_mensagem("ATENÇÃO", "Valor do campo "+nome+" é menor que o valor mínimo permitido", "error");
			}
		}
		if(max>0){
			if((parseInt(valor)>parseInt(max))&&(parseInt(valor)>0)){
				exibe_mensagem("ATENÇÃO", "Valor do campo "+nome+" é maior que o valor máximo permitido", "error");
				campo.style.backgroundColor ="#ffe6e6";
			}
		}
	}
	function critica_campo_obrigatorio(){
		var existe = false;
		var all = document.getElementsByClassName("obrigatorio");
		for(i=0;i<all.length;i++){
			try {
				if(all[i].value==""){
					if(all[i].disabled == false){
						all[i].style.border="1.5px solid #db6969";
						existe = true;
					}
				}
			}catch(err){}
		}
		return existe;
	}
	function validaTexto2(){
		const todosElementos = document.getElementsByTagName('*');
		const elementosComTextoId = [];

		for (let i = 0; i < todosElementos.length; i++) {
			const idCampo = todosElementos[i].getAttribute('condtext2');
			const textoId = todosElementos[i].getAttribute('data-campo');
			const nomeOrig = todosElementos[i].getAttribute('v2nomecampo')
			if (textoId && textoId.trim() !== '') {
				let cond 	= todosElementos[i].getAttribute('condtext2');
				let text1 	= todosElementos[i].getAttribute('texto1');
				let text2 	= todosElementos[i].getAttribute('texto2');

				nomeVariavel = "divTexto_"+nomeOrig;
				document.getElementById(nomeVariavel).innerHTML = text1;

				cond = cond.split(";");
				cond1 = cond[0];
				cond2 = cond[1];
				cond3 = cond[2];
				cond4 = cond[3];
				nomeVarTxt = cond[4];
				valor = retornaValorNome(cond2);

				if(text2!==""){
					nomeVariavel = "divTexto_"+nomeVarTxt;
					if(valor==cond4){
						document.getElementById(nomeVariavel).innerHTML = text2;
					}
				}

			}
		}
	}
	function retorna_valor_field1_POR_NOME(entrada){
		var divRetorno = document.getElementById("divFormularioRetorno");
		var todos = divRetorno.getElementsByTagName("*");
		for(z=0;z<todos.length;z++){
			campo = todos[z].getAttribute("v2nomecampo");
			if( campo!="null") {
				if(campo==entrada){
					if(todos[z].type!="checkbox"){
						var valor = "";
						valor = String(todos[z].value);
						return valor;
					}else{
						if(todos[z].checked==true){ retorno = todos[z].value; }
					}
				}
			}
		}
	}
	function retorna_valor_field1(entrada){
		var divRetorno = document.getElementById("divFormularioRetorno");
		var todos = divRetorno.getElementsByTagName("*");
		for(z=0;z<todos.length;z++){
			campo = todos[z].id;
			if(typeof campo !== "undefined") {
				//Alterado em 07/03/2019
				//campo = todos[z].getAttribute("data-campo");
				if(campo=="form_"+entrada){
					if(todos[z].type!="checkbox"){
						var valor = "";
						valor = String(todos[z].value);
						return valor;
					}else{
						if(todos[z].checked==true){ retorno = todos[z].value; }
					}
				}
			}
		}
	}
	function retornaValorNome(entrada){
			var todos = document.getElementsByTagName("*");
			for(z=0;z<todos.length;z++){
				campo = todos[z].getAttribute("v2NomeCampo");
				if(typeof campo !== "undefined") {
					//Alterado em 07/03/2019
					//campo = todos[z].getAttribute("data-campo");
					if(campo==entrada){
					  if(todos[z].type!="checkbox"){
						  var valor = "";
						  valor = String(todos[z].value);
						  return valor;
					  }else{
						   if(todos[z].checked==true){ retorno = todos[z].value; }
					  }
					}
				}
			}
	}
	function valida_e_numerico(n) {
		valor = n;
		valor = valor.replace(/[\,]/g , ".");
		return !isNaN(parseFloat(valor)) && isFinite(valor);
	}
	function valida_dados(tipo){
		if(tipo=="SALVA"){
			verificaAssinatura();
		}else{
			validaPreRandomizacao();
		}
	}
	function Verifica_Hora(valor){
		estado = true;
		if(valor!=""){
			Hora = valor;
			hrs = (Hora.substring(0,2));
			min = (Hora.substring(3,5));
			if ((hrs < 00 ) || (hrs > 23) || ( min < 00) ||( min > 59)){ estado = false; }
			if(valida_e_numerico(hrs)==false){ estado = false;}
			if(valida_e_numerico(min)==false){ estado = false;}
			if (Hora == ""){ estado = false; }
		}
		return estado;
	}
	function validaData(stringData)	{
	   /******** VALIDA DATA NO FORMATO DD/MM/AAAA *******/
	   var volta = true;
		   if(stringData!=""){
		   var regExpCaracter = /[^\d]/;     //Expressão regular para procurar caracter não-numérico.
		   var regExpEspaco = /^\s+|\s+$/g;  //Expressão regular para retirar espaços em branco.
		   if(stringData.length != 10){volta = false;}
		   splitData = stringData.split('/');
		   if(splitData.length != 3){volta = false;}
		   /* Retira os espaços em branco do início e fim de cada string. */
		   try{
			   splitData[0] = splitData[0].replace(regExpEspaco, '');
			   splitData[1] = splitData[1].replace(regExpEspaco, '');
			   splitData[2] = splitData[2].replace(regExpEspaco, '');
		   }catch(e){
			   volta = false;
		   }
		   if ((splitData[0].length != 2) || (splitData[1].length != 2) || (splitData[2].length != 4)){volta = false;}
		   /* Procura por caracter não-numérico. EX.: o "x" em "28/09/2x11" */
		   if (regExpCaracter.test(splitData[0]) || regExpCaracter.test(splitData[1]) || regExpCaracter.test(splitData[2])){volta = false;}
		   dia = parseInt(splitData[0],10);
		   mes = parseInt(splitData[1],10)-1; //O JavaScript representa o mês de 0 a 11 (0->janeiro, 1->fevereiro... 11->dezembro)
		   ano = parseInt(splitData[2],10);
		   var novaData = new Date(ano, mes, dia);

		   if ((novaData.getDate() != dia) || (novaData.getMonth() != mes) || (novaData.getFullYear() != ano)){volta = false;}else{volta = true;}
	   }
	   return volta;
	}
	function valida_campos_onBlur(entrada){
		var tipo = "";
		//Verifico se o tipo de validação é numérica e valido
		tipo = entrada.getAttribute("data-tipo-validar");
		if(tipo!=""){
			if(tipo=="numero"){
				if((!valida_e_numerico(entrada.value))&&(entrada.value.length>0)){
					exibe_mensagem("ATENÇÃO", "Valor digitado inválido. Por favor, revise as informações.", "error");
					entrada.style.border="1.5px solid #db6969";
					entrada.value = "";
				}
			}
			if(tipo=="horario"){
				if(Verifica_Hora(entrada.value)==false){
					exibe_mensagem("ATENÇÃO", "Hora digitada inválida.", "error");
					entrada.style.border="1.5px solid #db6969";
				}
			}
			if(tipo=="data"){
				if(validaData(entrada.value)==false){
					exibe_mensagem("ATENÇÃO", "Data digitada inválida.", "error");
					entrada.style.border="1.5px solid #db6969";
				}
			}
		}

		qtd_decimo = entrada.getAttribute("data-qtd-decimal");
		if(qtd_decimo!=""){
			   //Vejo o tipo do campo. Se for numero seguir um procedimento. Se nao for, outro
			   if(tipo=="numero"){
				   valor = entrada.value;
				   valor = valor.split(",");
				   resto = "";
				   if((valor.length>1)&&(valor.length!=undefined)){
							inicio = valor[1].length;
							resto = valor[1];
				   }else{inicio=0}
				   for(i=inicio;i<qtd_decimo;i++){
						resto = resto + "0";
				   }
				   entrada.value = valor[0]+","+resto;
			   }
			   if(tipo=="horario"){
				   valor = entrada.value;
				   valor = valor.split(":");
				   if((valor.length>1)&&(valor.length!=undefined)){
							inicio = valor[1].length;
							hrs = valor[0];
							min = valor[1];
				   }else{inicio=0}
				   if(entrada.value!=""){
						if(inicio<2){
						   exibe_mensagem("ATENÇÃO", "Formato do horário inválido. Por favor, reveja as informações.", "error");
						   entrada.style.border="1.5px solid #db6969";
						   entrada.value = "";
						}else{

							if ((hrs < 00 ) || (hrs > 23) || ( min < 00) ||( min > 59)){
								exibe_mensagem("ATENÇÃO", "Formato do horário inválido. Por favor, reveja as informações.", "error");
								entrada.style.border="1.5px solid #db6969";
								entrada.value = "";
							}
						}
				   }
			   }
		}
	}
	function valida_condicional_vermelho(entrada, tipo, exibe){
		//Tipo = 1, Change - 2, Inicio
		//Alterado em 16/08/2016
		//Se o valor de entrada for igual ao do condicional (errado), deixar em vermelho
		if(entrada!=null){
			var condicional_vermelho = "";
			condicional_vermelho = entrada.getAttribute("data-condicional-vermelho");
			if(condicional_vermelho!=""){
				condicional_vermelho = condicional_vermelho.split(";");
				if(condicional_vermelho[0]=="="){
					if(entrada.value==condicional_vermelho[1]){
						var condicional_alerta = "";
						condicional_alerta = entrada.getAttribute("data-condicional-vermelho-alerta");
						if((condicional_alerta!="")&&(tipo==1)){ if(exibe == 1){exibe_mensagem("ATENÇÃO", condicional_alerta, "");}}
						entrada.style.border="3px solid #660000";
					}
				}
			}
		}else{
			var all = document.getElementsByTagName("*");
			for(i=0;i<all.length;i++){
				var condicional_vermelho = "";
				condicional_vermelho = all[i].getAttribute("data-condicional-vermelho");
				if(condicional_vermelho!=null){
					condicional_vermelho = condicional_vermelho.split(";");
					if(condicional_vermelho[0]=="="){
						if(all[i].value==condicional_vermelho[1]){
							var condicional_alerta = "";
							condicional_alerta = all[i].getAttribute("data-condicional-vermelho-alerta");
							if((condicional_alerta!="")&&(tipo==1)){ if(exibe == 1){exibe_mensagem("ATENÇÃO", condicional_alerta, "");}}
							all[i].style.border="3px solid #660000";
						}
					}
				}
			}
		}
	}
	function alertas(campo){		//Exibo alerta baseado nas regras condicionais do campo
		/*
			REGRA
				TIPO  = DATA,
				CAMPO = CAMPO, mas aqui já não me interessa
				OPERADOR = Operador da comparação
				MENSAGEM = Mensagem de alerta
				VALOR;1 - Sim;Verificar desvio de GCP
		*/
		if(campo.value!=""){
			regras = campo.getAttribute("data-condicional-alerta");
			if(regras!=""){
				regras = regras.split("|");
				for(i=0;i<regras.length;i++){
					regra = regras[i];
					regra = regra.split(";");
					if(regra[0]=="VALOR"){
						//VALOR;=;2 - Não;*Por favor, verificar desvio de protocolo*
						dataOriginal 	= campo.value;
						dataComparador 	= regra[1];
						if(dataOriginal==dataComparador){
							temp = campo.id;
							temp = temp.split("_");
							temp = temp[1];
							document.getElementById("divMsg_"+temp).innerHTML = "<i>*"+regra[2]+"*</i>";
							return;
						}
					}
					if(regra[0]=="IDADE"){
						dataOriginal = campo.value;
						if(campo.value!=""){
							dataOriginal = dataOriginal.split("/");
							data_atual = new Date();
							mes = data_atual.getMonth()+1;
							if(mes<10){mes = "0" + mes;}
							data_atual =  data_atual.getDate() + "-" + mes + "-" + data_atual.getFullYear();
							data_atual = data_atual.split("-");
							data_atual = data_atual[2] + "-" + data_atual[1] + "-" + data_atual[0];
							idade_atual = calcularDiferencaAnos(dataOriginal[2] + "-" + dataOriginal[1] + "-" + dataOriginal[0], data_atual);
							if(idade_atual>0){
								if(regra[2]=="<"){
									if(idade_atual<regra[1]){
										campo.style.border="3px solid #660000";
										swal({
											title: "ATENÇÃO!!",
											text: regra[3],
											icon: "error",
											buttons: true,
											dangerMode: false,
											html: true
										});
									}
								}
								if(regra[2]==">"){
									if(idade_atual>regra[1]){
										campo.style.border="3px solid #660000";
										swal({
											title: "ATENÇÃO!!",
											text: regra[3],
											icon: "error",
											buttons: true,
											dangerMode: false,
											html: true
										});
									}
								}
							}
						}
					}
					if(regra[0]=="NUMERO"){
						datoOriginal = campo.value;
						datoOriginal = datoOriginal.replace(",", ".");
						if(campo.value!=""){
							comparado = regra[1];
							comparado = comparado.replace(",", ".");
							if(regra[2]=="<"){
								if(parseFloat(datoOriginal)<parseFloat(comparado)){
									campo.style.border="3px solid #660000";
									swal({
										title: "ATENÇÃO!!",
										text: regra[3],
										icon: "error",
										buttons: true,
										dangerMode: false,
										html: true
									});
								}
							}
							if(regra[2]==">"){
								if(parseFloat(datoOriginal)>parseFloat(comparado)){
									campo.style.border="3px solid #660000";
									swal({
										title: "ATENÇÃO!!",
										text: regra[3],
										icon: "error",
										buttons: true,
										dangerMode: false,
										html: true
									});
								}
							}
						}
					}
				}
			}
		}

	}
	function conferencia(){
		var all = document.getElementsByTagName("*");
		for(i=0;i<all.length;i++){
			campo = all[i].name;
			if((typeof campo!== "undefined")&&(campo.substr(0,6)!="ANCORA")) {
				if(campo.substr(0,5)=="form_"){
					campo1 = document.getElementById(campo).value;
					nomecampo2 = "formD_" + campo.substr(5, 100);
					try{
						campo2 = document.getElementById(nomecampo2).value;
						if(campo1!=campo2){
							document.getElementById(campo).style.border="4px solid #db6969";
						}
					}catch(err){}
				}
			}
		}
	}
	function valida_data(entrada, tipo){
		entrada.style.border="";
		tipo = entrada.getAttribute("data-tipo");
		if(tipo=="texto"){
			e_horario = entrada.getAttribute("e_horario");
			if(e_horario=="on"){
				hora = entrada.value.split(":");
				if(hora[0]>23){
					exibe_mensagem("ATENÇÃO", "Horário digitado inválido. A hora não pode ser maior que 23 horas", "error");
					entrada.value = "";
					entrada.focus;
				}
				if(hora[1]>59){
					exibe_mensagem("ATENÇÃO", "Horário digitado inválido. O minuto não pode ser maior que 59 minutos", "error");
					entrada.value = "";
					entrada.focus;
				}
				if(hora[1]<0){
					exibe_mensagem("ATENÇÃO", "Horário digitado inválido. O minuto não pode ser menor que 0 minutos", "error");
					entrada.value = "";
					entrada.focus;
				}
				if(hora[1].length>2){
					exibe_mensagem("ATENÇÃO", "Horário digitado inválido.", "error");
					entrada.value = "";
					entrada.focus;
				}
				if(hora[0]<0){
					exibe_mensagem("ATENÇÃO", "Horário digitado inválido. A hora não pode ser menor que 0 horas", "error");
					entrada.value = "";
					entrada.focus;
				}
			}
		}
		if(tipo=="data"){
			//Valido a questão da data de assinatura do TCLE
			termo = entrada.getAttribute("data-termo");
			data_termo = entrada.getAttribute("data-data_termo");
			if((termo=="on")&&(data_termo!="")){   //Se tiver que criticar com a data de termo
			  data_atual = entrada.value.split("/");
			  data_atual = data_atual[2] + data_atual[1] + data_atual[0];
			  if(data_atual<data_termo){
				  entrada.style.border="1.5px solid #db6969";
				  exibe_mensagem("ATENÇÃO", "Atenção. Data preenchida é menor do que a data da assinatura do TCLE", "error");
				  entrada.value = "";
				  entrada.focus;
			  }else{
				entrada.style.border="";
			  }
			}

			menor_igual = entrada.getAttribute("data-igual-menor");
			maior_igual = entrada.getAttribute("data-igual-maior");
			if(menor_igual=="on"){
				data_atual = new Date();
				mes = data_atual.getMonth()+1;
				if(mes<10){mes = "0" + mes;}
				vAno = data_atual.getFullYear();
				vDia = data_atual.getDate();
				if(vDia<10){vDia = "0" + vDia;}
				data_atual =  "" + vAno + mes + vDia;
				data_digitada = entrada.value.split("/");
				data_digitada = data_digitada[2] + data_digitada[1] + data_digitada[0];
				if((data_atual<data_digitada)&&(data_digitada!="")&&(data_digitada!="NaN")){
					entrada.style.border="1.5px solid #db6969";
					exibe_mensagem("ATENÇÃO", "Data de preenchimento não pode ser posterior à data de hoje. Por favor, revise as informações", "error");
					entrada.value = "";
					entrada.focus;
				}
			}
			if(maior_igual=="on"){
				data_atual = new Date();
				mes = data_atual.getMonth()+1;
				if(mes<10){mes = "0" + mes;}
				vAno = data_atual.getFullYear();
				vDia = data_atual.getDate();
				if(vDia<10){vDia = "0" + vDia;}
				data_atual =  "" + vAno + mes + vDia;
				data_digitada = entrada.value.split("/");
				data_digitada = data_digitada[2] + data_digitada[1] + data_digitada[0];
				if((data_atual>data_digitada)&&(data_digitada!="")){
					entrada.style.border="1.5px solid #db6969";
					exibe_mensagem("ATENÇÃO", "Data de preenchimento não pode ser anterior à data de hoje. Por favor, revise as informações", "error");
					entrada.value = "";
					entrada.focus;
				}
			}
			menor_termo = entrada.getAttribute("data-menor-termo");
			if((menor_termo=="on")&&(data_termo!="")){   //Se tiver que criticar com a data de termo
				data_atual = entrada.value.split("/");
				data_atual = data_atual[2] + data_atual[1] + data_atual[0];
				if(data_atual>data_termo){
					entrada.style.border="1.5px solid #db6969";
					exibe_mensagem("ATENÇÃO", "Data de preenchimento não pode ser superior a data de assinatura do TCLE", "error");
					entrada.value = "";
					entrada.focus;
				}else{ entrada.style.border=""; }
			}
			alerta_menor_termo = entrada.getAttribute("data-alerta-termo");
			if((alerta_menor_termo=="on")&&(data_termo!="")){   //Se tiver que criticar com a data de termo
				data_atual = entrada.value.split("/");
				data_atual = data_atual[2] + data_atual[1] + data_atual[0];
				if(data_atual<data_termo){
					exibe_mensagem("ATENÇÃO", "Data da visita é menor do que a data da assinatura do TCLE", "error");
				}
			}
			dezoito_anos = entrada.getAttribute("data-dezoito-anos");
			if(dezoito_anos=="on"){
				data_digitada = entrada.value.split("/");
				data_atual = new Date();
				mes = data_atual.getMonth()+1;
				if(mes<10){mes = "0" + mes;}
				data_atual =  data_atual.getDate() + "-" + mes + "-" + data_atual.getFullYear();
				idade_atual = calcularDiferencaAnos(data_digitada[2] + "-" + data_digitada[1] + "-" + data_digitada[0],data_atual);
				if(idade_atual<12){
					entrada.style.border="1.5px solid #db6969";
					exibe_mensagem("ATENÇÃO", "Idade mínima para partipar do estudo é de 12 anos. Por favor, revise as informações", "error");
					entrada.value = "";
					entrada.focus;
				}
				/*if(idade_atual>60){
				entrada.style.border="1.5px solid #db6969";
				exibe_mensagem("ATENÇÃO", "Idade máxima para partipar do estudo é de 60 anos. Por favor, revise as informações", "error");
				entrada.value = "";
				entrada.focus;
				}*/
			}
		}
	}
	function NumerOnly(){
		if((event.keyCode < 47 || event.keyCode > 57 )&& event.keyCode!=44 && event.keyCode!=32 ){
			event.returnValue = false;
		}
	}
	function Onlychars(){
		if((event.keyCode >= 48 && event.keyCode <= 57 )&&event.keyCode!= 44 ){
			event.returnValue = false;
		}
	}
	function calcula_dupla_digitacao(entrada){
		/*var camposALL = document.getElementsByTagName("*");
		for(iCamposALL=0;iCamposALL<camposALL.length;iCamposALL++){
		try {
			if(camposALL[iCamposALL].getAttribute("data-dupla_digitacao_campo")!=""){
				var valor_original = camposALL[iCamposALL];
				valor_dupla_dig= document.getElementById(camposALL[iCamposALL].getAttribute("data-dupla_digitacao_campo"));
				//valor_dupla_dig.style.border="";
				if(typeof camposALL[iCamposALL].value !== "undefined") {
					if(valor_original.value!=valor_dupla_dig){
					valor_dupla_dig.style.border="3px solid #660000";
					}
				}
			}
		}catch(err){}
		}*/
	}
	function validaComparadoCampo(campo,evento){
		//Só vai ser executado ao saior do campo (onBlur)
		let inputValue = campo.value;

		tipo = "";
		tipo = campo.getAttribute("data-calc-tipo");
		var destino = campo;
		retorno = "";
		if(tipo!=null){
			//20250421 - Depende do valor ser maior que um campo específico.
			//Só funciona para campos do mesmo formulário
			if(tipo=="MAIOR_IGUAL"){
				//Aqio busco o valor atual
				campo1 = campo.getAttribute("data-calc-field1");
				campo1 = retorna_valor_field1_POR_NOME(campo1);
				if(campo1==""){
					//Se campo 1 estiver vazio, já reclamo de data
					destino.style.border="1.5px solid #db6969";
					exibe_mensagem("ATENÇÃO", "Campo de referência não preenchido. Por favor, revisar as informações", "error");
					destino.value = "";
					destino.focus;
				}else{
					if(evento=="input"){
						if(campo.getAttribute("tipo-de-campo")=="data"){ //Se for data
							if(campo.value.length==10){
								campo1 = campo1.split("/");
							campo1 = campo1[2] + campo1[1] + campo1[0];
							campo2 = campo.value.split("/");
							campo2 = campo2[2] + campo2[1] + campo2[0];
							if(campo2<campo1){
								destino.style.border="2.5px solidrgb(121, 4, 4)";
								exibe_mensagem("ATENÇÃO", "Esse campo não pode ser menor que a data de referência. Por favor, revisar as informações", "error");
								destino.value = "";
								destino.focus;
								}
							}
						}
						if(campo.getAttribute("tipo-de-campo")=="texto"){ //Se for data
							if(parseInt(campo.value,10)<parseInt(campo1,10)){
								destino.style.border="2.5px solidrgb(121, 4, 4)";
								exibe_mensagem("ATENÇÃO", "Esse campo não pode ser menor que a data de referência. Por favor, revisar as informações", "error");
								destino.value = "";
								destino.focus;
								}
						}
					}
				}
			}

		}
	}
	function calcula_campo(){
		var divRetorno = document.getElementById("divFormularioRetorno");
		var camposALL = divRetorno.getElementsByTagName("*");
		for(iCamposALL=0;iCamposALL<camposALL.length;iCamposALL++){
			try {
				if(camposALL[iCamposALL].getAttribute("data-obrigatorio")=="on"){
					camposALL[iCamposALL].style.border="";
					if(camposALL[iCamposALL].value==""){
						if(camposALL[iCamposALL].disabled == false){
							camposALL[iCamposALL].style.border="1.5px solid #db6969";
						}
					}
					if((camposALL[iCamposALL].getAttribute("data-condicional-vermelho")==camposALL[iCamposALL].value)&&(camposALL[iCamposALL].value!="")){
						if(camposALL[iCamposALL].disabled == false){
							if(camposALL[iCamposALL].getAttribute("data-condicional-alerta")!=""){
								camposALL[iCamposALL].style.border="3px solid #660000";
							}
						}
					}
				}
			}catch(err){}
		}
		var all = document.getElementsByTagName("*");
		if(processa_mudanca==true){
			for(i=0;i<all.length;i++){
				var nome = all[i].name;
				if (typeof nome != 'undefined') {
					tipo = "";
					tipo = all[i].getAttribute("data-calc-tipo");
					var destino = all[i];
					retorno = "";
					if(tipo!=null){
						if(tipo=="AREA"){
							campo1 = all[i].getAttribute("data-calc-field1-id");
							campo2 = all[i].getAttribute("data-calc-field2-id");
							campo1 = retorna_valor_field1(campo1);
							campo2 = retorna_valor_field1(campo2);
							retorno = "";

							try{
								if( (campo1!="")&&(campo2!="")){
									peso = campo2;
									altura = campo1;
									altura = altura.replace(",", ".");
									peso = peso.replace(",",".");
									area = altura * peso;
									area = area.toFixed(2);
									destino.value = area;
								}
							}catch(err){}
						}
						if(tipo=="IMC"){
							campo1 = all[i].getAttribute("data-calc-field1-id");
							campo2 = all[i].getAttribute("data-calc-field2-id");
							campo1 = retorna_valor_field1(campo1);
							campo2 = retorna_valor_field1(campo2);
							retorno = "";
							try{
								if( (campo1!="")&&(campo2!="")){
									peso = campo2;
									altura = campo1;
									altura = altura.replace(",", ".");
									peso = peso.replace(",",".");
									imc = peso / (altura*altura);
									//imc = imc * 10000;
									imc = imc.toFixed(2);
									//imc = parseFloat(imc.toFixed(2));
									//if(imc>1){
										destino.value = imc;
									//}else{
									//	exibe_mensagem("ATENÇÃO", "Campo ALTURA ou PESO com problemas. Por favor, revisar as informações", "error");
									//	destino.value = "";
									//}
								}
							}catch(err){}
						}
						if(tipo=="Idade"){
							//try{
								campo1 = all[i].getAttribute("v2Campo1");
								campo2 = all[i].getAttribute("data-calc-field2_valor");
								var campo1_valor = retornaValorNome(campo1);
								//var campo2_valor = retornaValorNome(campo2);
								var campo2_valor = campo2;
								if(campo2=="!HOJE"){
									campo2_valor = data_atual();
									date2 = data_atual();
								}else{
									date2 = campo2;
									date2 = date2.split("/");
									date2 = date2[2] + "-" + date2[1] + "-" + date2[0];
								}
								if((campo1_valor!="")&&(date2!="")){
									date1 = campo1_valor;
									//if (typeof date1 === 'undefined') { return; }
									date1 = date1.split("/");
									date1 = date1[2] + "-" + date1[1] + "-" + date1[0];

									campo1_valor = campo1_valor.split("/");
									Idade = calcularDiferencaAnos(date1, date2);
									if (Number.isInteger(Idade)) {
										destino.value = Idade;
									}
									//if(destino.value=="NaN"){										destino.value = "";									}
								}
							//}catch(err){}
						}
						if(tipo=="%"){
							campo1 = all[i].getAttribute("data-calc-field1");
							campo2 = all[i].getAttribute("data-calc-field2");
							campo1 = retorna_valor_field1(campo1);
							campo1 = retorno;
							campo2 = retorna_valor_field1(campo2);
							campo2 = retorno;
							retorno = "";
							try{
								x = (campo2*100)/campo1;
								x = parseFloat(x.toFixed(2));
								destino.value = x + "%";
							}catch(err){}
						}
					}
				}
			}
			processa_mudanca = true;
		}
	}
	function validaBisnaga(campo){
		if(campo.getAttribute("data-calc-tipo")=="BISNAGA"){
			mostraCarregando();
			valorCampo = campo.value;
			var dados = {idCampo: campo.id,
						valorCampo: campo.value
			}
			$.ajax({
				url: "php_php/php_est_estoque.php?act=VALIDA_BISNAGA",
				dataType: 'html',
				type: 'POST',
				data: dados,
				beforeSend: function() {
				},
				complete: function() {
					removeCarregando();
				},
				success: function(data, textStatus) {
					console.log("retorno-bisnaga: " + data);
					if(data=="NAO_LOCALIZADO"){
						let pai = campo.parentNode;
						let varCampo = campo;
						let varSpan = document.createElement("span");
						pai.innerHTML = "";
						pai.appendChild(varCampo);
						varSpan.style.color = "red";
						varSpan.innerHTML = "<b>*ATENÇÃO! BISNAGA NÃO LOCALIZADA</b>";
						pai.appendChild(varSpan);
					}
					if(data=="BISNAGA_JA_UTILIZADA"){
						let pai = campo.parentNode;
						let varCampo = campo;
						let varSpan = document.createElement("span");
						pai.innerHTML = "";
						pai.appendChild(varCampo);
						varSpan.style.color = "red";
						varSpan.innerHTML = "<b>*ATENÇÃO! BISNAGA JÁ UTILIZADA</b>";
						pai.appendChild(varSpan);
					}
					if(data=="FALHA"){
						let pai = campo.parentNode;
						let varCampo = campo;
						let varSpan = document.createElement("span");
						pai.innerHTML = "";
						pai.appendChild(varCampo);
						varSpan.style.color = "red";
						varSpan.innerHTML = "<b>*ATENÇÃO! ESSA BISNAGA NÃO CORRESPONDE AO TRATAMENTO DO PARTICIPANTE</b>";
						pai.appendChild(varSpan);
					}
					if(data=="OK"){
						let pai = campo.parentNode;
						let varCampo = campo;
						let varSpan = document.createElement("span");
						pai.innerHTML = "";
						pai.appendChild(varCampo);
						//varSpan.style.color = "red";
						//varSpan.innerHTML = "<b>*ATENÇÃO! ESSA BISNAGA NÃO CORRESPONDE AO TRATAMENTO DO PARTICIPANTE</b>";
						//pai.appendChild(varSpan);
					}
				},
				error: function(xhr,er) {
					alert("ERRO! " + xhr.status + ' - ' + xhr.statusText + 'Tipo de erro: ' + er);
					removeCarregando();
				}
			});
		}
	}
	function calcularDiferencaAnos(data1, data2) {
		/*//AAAA-MM-DD
		const dt1 = new Date(data1);
		const dt2 = new Date(data2);
		let anos = dt2.getFullYear() - dt1.getFullYear();

		if (dt2.getMonth() < dt1.getMonth() ||
			(dt2.getMonth() === dt1.getMonth() && dt2.getDate() < dt1.getDate())) {
			anos--;
		}
*/
		let nascimento = new Date(data1);
		let base = new Date(data2);

		// Calcula diferença de anos
		let anos = base.getFullYear() - nascimento.getFullYear();

		// Ajusta caso ainda não tenha feito aniversário no ano da data base
		let mes = base.getMonth() - nascimento.getMonth();
		if (mes < 0 || (mes === 0 && base.getDate() < nascimento.getDate())) {
			anos--;
		}

		if(anos<0){
			return anos*-1;
		}else{
			return anos;
		}

	}

	function v2HabilitaCondicional(onde){
		if(onde=="PAGINA"){
			//Loop em todos os campos do formulário
			try{
				//var all = document.getElementsByTagName("*");
				var divRetorno = document.getElementById("divFormularioRetorno");
				var all = divRetorno.getElementsByTagName("*");
				for(i=0;i<all.length;i++){
					if( (all[i].name!="undefined") && (all[i].getAttribute("status")!="disabled") ) {
						try{
							campoAtualNome 		  = all[i].getAttribute("v2NomeCampo");
							campoAtualId   		  = all[i].name;
							campoAtualCondicional = all[i].getAttribute("v2Condicional");

							temHabilitado = false;
							//tentar fazer o condicional aqui
							var itens = campoAtualCondicional.split('|');
							qtsOr = itens.length;
							var itens = campoAtualCondicional.split('&');
							qtdAnd = itens.length;

							if(qtdAnd>1){
								var itens = campoAtualCondicional.split('&');
								tempHabilitaCampo = false;
								itens.forEach(function(item, index) {
									condicionalItem = item;
									if((condicionalItem!=null)&&(condicionalItem!="")){
										condicional 	= condicionalItem.split(";");
										campoBuscaTipo  = condicional[0];
										campoBuscaNome 	= condicional[1];
										campoBuscaCompa  = condicional[2];
										campoBuscaValor = condicional[3];
										//Loop em todos os campos ativos novamente
										var todos = divRetorno.getElementsByTagName("*");
										for(z=0;z<todos.length;z++){
											var destino = all[i]
											destino.disabled = true;
											if(campoBuscaNome==todos[z].getAttribute("v2NomeCampo")){
												campoBuscalTipo 		  = todos[z].getAttribute("data-tipo");
												valor = todos[z].value;	//Valor atual do campo
												if(campoBuscaTipo=="IF"){
													if(campoBuscaCompa=="!="){
														if(campoBuscaValor==valor){
															tempHabilitaCampo = true;
														}
													}
													if(campoBuscaCompa=="="){
														if(campoBuscaValor!=valor){
															tempHabilitaCampo = true;
														}
													}
												}
											}
										}
										all[i].disabled = tempHabilitaCampo;
									}
								});
							}else{

									temHabilitado = false;
									var itens = campoAtualCondicional.split('|');
									itens.forEach(function(item, index) {
										condicionalItem = item;
										if((condicionalItem!=null)&&(condicionalItem!="")){
											condicional 	= condicionalItem.split(";");
											campoBuscaTipo  = condicional[0];
											campoBuscaNome 	= condicional[1];
											campoBuscaCompa  = condicional[2];
											campoBuscaValor = condicional[3];
											//Loop em todos os campos ativos novamente
											var todos = divRetorno.getElementsByTagName("*");
											for(z=0;z<todos.length;z++){
												if(campoBuscaNome==todos[z].getAttribute("v2NomeCampo")){
													campoBuscalTipo 		  = todos[z].getAttribute("data-tipo");
													valor = todos[z].value;	//Valor atual do campo
													var destino = all[i];
													if(campoBuscaTipo=="IF"){
														if(campoBuscaCompa=="!="){
															if(campoBuscaValor!=valor){
																if(habilitado==true){
																	destino.disabled = false;
																	temHabilitado = true;
																}else{
																	if(temHabilitado==false){
																		destino.disabled = true;
																	}
																}
															}
														}
														if(campoBuscaCompa=="="){
															if(campoAtualId!="form_pagina"){
																if(campoBuscaValor==valor){
																	if(habilitado==true){
																		destino.disabled = false;
																		temHabilitado = true;
																	}
																}else{
																	if(temHabilitado==false){
																		destino.disabled = true;
																	}
																}
															}else{
																if(campoBuscaValor==valor){
																	destino.style.display = 'block';
																}else{
																	destino.style.display = 'none';
																}
															}

														}
														if(campoBuscaCompa=="<>"){
															if(campoAtualId!="form_pagina"){
																if(valor==""){
																	destino.disabled = true;
																}else{
																	if(campoBuscaValor==valor){
																		destino.disabled = true;
																		temHabilitado = true;
																	}else{
																		if(temHabilitado==false){
																		destino.disabled = false;
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									});

							}


							/*Vejo se é readOnly*/
							if(all[i].getAttribute("data-readOnly")=="on"){
								all[i].disabled = true;
							}
						}catch(err){}
					}
				}
			}catch(err){}
		}
	}
	function HabilitaMonitorado(){
		//Loop em todos os campos do formulário
		try{
			var all = document.getElementsByTagName("*");
			for(i=0;i<all.length;i++){
				try{
					monitorado 		  = all[i].getAttribute("monitoria-status");
					if(monitorado==1){
						all[i].disabled = true;
					}
				}catch(err){}
			}
		}catch(err){}

	}
	function carrega_condicional(onde){
		if(onde=="PAGINA"){
			try{
				var divRetorno = document.getElementById("divFormularioRetorno");
				var all = divRetorno.getElementsByTagName("*");
				for(i=0;i<all.length;i++){
					try{
						condicional = "";
						condicional_valor = "";
						condicional_id = "";
						condicional = all[i].getAttribute("data-condicional");
						condicional_id = all[i].getAttribute("data-condicional-id");
						if(condicional_id!=null){
						//Busco o valor do condicional_id na página em questão
						var todos = divRetorno.getElementsByTagName("*");
							for(z=0;z<todos.length;z++){
								if("form_"+condicional_id==todos[z].name){
									valor = todos[z].value;
									var destino = all[i];
									condicional = condicional.split(";");
									//pego tipo condicional
									tipo = condicional[0];
									condicao = condicional[2];
									valor_a_ser = condicional[3];
									if(todos[z].getAttribute("status")!="disabled"){
										if(tipo=="IF"){
											if(condicao=="!="){
												if((valor_a_ser!=valor)||(campo=="undefined")){
													if(habilitado==true){
														destino.disabled = false;

													}
												}else{
													destino.disabled = true;
												}
											}
											if(condicao=="="){
												if(destino.id!="form_pagina"){
													if(valor_a_ser==valor){
														if(habilitado==true){
															destino.disabled = false;
														}
													}else{
														destino.disabled = true;
													}
												}else{
													if(valor_a_ser==valor){
														destino.style.display = 'block';
													}else{
														destino.style.display = 'none';
													}
												}
											}
											if(condicao=="<>"){
												if(valor==""){
													destino.disabled = true;
												}else{
													if(valor_a_ser==valor){
														if(habilitado==false){
															destino.disabled = true;
														}
													}else{
														destino.disabled = false;
													}
												}
											}
										}
									}
								}
							}
						}
					}catch(err){}
				}
			}catch(err){}
		}else{		//se for INICIO
			try{
				var divRetorno = document.getElementById("divFormularioRetorno");
				var all = divRetorno.getElementsByTagName("*");
				for(i=0;i<all.length;i++){
					try{
						condicional = "";
						condicional = all[i].getAttribute("data-condicional");
						condicional_valor = all[i].getAttribute("data-condicional-valor");

						var destino = all[i];
						if( (condicional!=null)&&(condicional!="") ){
							condicional = condicional.split(";");
							//pego tipo condicional
							tipo = condicional[0];
							campo = condicional[1];
							condicao = condicional[2];
							valor = condicional[3];
							campo = condicional_valor;
							if(tipo=="IF"){
								if(condicao=="!="){
									if((campo!=valor)||(campo=="undefined")){
										if(habilitado==true){
											if(destino.getAttribute("data-tipo")!="leitura"){
												destino.disabled = false;
											}
										}
									}else{
									}
								}
								if(condicao=="="){

									if(destino.id!="form_pagina"){
										if(campo==valor){
											if(habilitado==true){
												if(destino.getAttribute("data-tipo")!="leitura"){
													destino.disabled = false;
												}
											}
										}else{
										}
									}else{
										if(campo==valor){
											destino.style.display = 'block';
										}else{
											destino.style.display = 'none';
										}
									}
								}
								if(condicao=="<>"){
									if(valor==""){
										destino.disabled = true;
									}else{
										if(valor_a_ser==valor){
											if(habilitado==false){
												destino.disabled = true;
											}
										}else{
											destino.disabled = false;
										}
									}
								}
							}
						}
					}catch(err){}
				}
			}catch(err){}
		}
	}
	function deletar_query(campo, id){
		if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				$('#modalQuerie').modal('hide');
			}
		};
		xmlhttp.open("POST","ajax.php?act=DELETAR_QUERY&campo="+id);
		xmlhttp.send();
	}
	function nova_query(campo, centro){
		var valor = document.getElementById("query_nova").value;
		if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				$('#modalQuerie').modal('hide');
			}
		};
		xmlhttp.open("POST","ajax.php?act=SALVAR_NOVA_QUERY&campo="+campo+"&valor="+valor+"&centro="+centro);
		xmlhttp.send();
	}
	function salvar_query(campo, id){
		var valor = document.getElementById("query_"+campo).value;
		if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				$('#modalQuerie').modal('hide');
			}
		};
		xmlhttp.open("POST","ajax.php?act=SALVAR_MUDANCA_QUERY&campo="+id+"&valor="+valor);
		xmlhttp.send();
	}
	function muda_erro(campo){
		if (window.XMLHttpRequest) {
			document.getElementById("retorno_erro"+campo).innerHTML = '<img class="icone" src="imagens/carregando.gif" width="30">'
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				retorno = this.responseText;
				var tratado = retorno.split("|");
				document.getElementById("retorno_erro"+campo).innerHTML = tratado[2];
				exibe_notificacao(tratado[0].trim(), tratado[1]);
				if(tratado[3]=="1"){	//Desabilita
					try{
						//document.getElementById("form_"+campo).disabled = true;
					}catch(err){
					}
				}else{ //Habilita
					try{
						//document.getElementById("form_"+campo).disabled = false;
					}catch(err){
					}
				}

			}
		};
		xmlhttp.open("POST","ajax.php?act=MUDA_ERRO&campo="+campo);
		xmlhttp.send();
	};
	function muda_valor_campo(id, valor){
		/*if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
			}
		};
		xmlhttp.open("POST","ajax.php?act=MUDA_VALOR&campo="+id+"&valor="+valor);
		xmlhttp.send();			*/
	}
	function verificaEn(){
		var divRetorno = document.getElementById("divFormularioRetorno");
		var all = divRetorno.getElementsByTagName("*");
		for(i=0;i<all.length;i++){
			try{
				condicional = "";
				condicional = all[i].getAttribute("data-enfinal");
				if(condicional=="1"){
					all[i].disabled = true;
				}
			}catch(err){}
		}
	}
	function recibo(centro, paciente){
		popup('rep_recibo.php?centro='+centro+'&paciente='+paciente);
	}
	function sleep(ms) {
	  return new Promise(resolve => setTimeout(resolve, ms));
	}
	function exibe_notificacao(tipo, mensagem){
		if(tipo=="OK"){
			$.bootstrapGrowl(mensagem, {
				ele: 'body', // which element to append to
				type: 'info', // (null, 'info', 'danger', 'success')
				offset: {from: 'top', amount: 20}, // 'top', or 'bottom'
				align: 'right', // ('left', 'right', or 'center')
				width: 290, // (integer, or 'auto')
				delay:1000, // Time while the message will be displayed. It's not equivalent to the *demo* timeOut!
				allow_dismiss: true, // If true then will display a cross to close the popup.
				stackup_spacing: 10 // spacing between consecutively stacked growls.
			});
		}
		if(tipo=="FALHA"){
			$.bootstrapGrowl(mensagem, {
				ele: 'body', // which element to append to
				type: 'danger', // (null, 'info', 'danger', 'success')
				offset: {from: 'top', amount: 20}, // 'top', or 'bottom'
				align: 'right', // ('left', 'right', or 'center')
				width: 290, // (integer, or 'auto')
				delay:2000, // Time while the message will be displayed. It's not equivalent to the *demo* timeOut!
				allow_dismiss: true, // If true then will display a cross to close the popup.
				stackup_spacing: 10 // spacing between consecutively stacked growls.
			});
		}
	}
	$(document).ready(function() {
		formmodified=0;
		$('form *').change(function(){
			formmodified=1;
		});
		window.onbeforeunload = confirmExit;
		function confirmExit() {
			if (formmodified == 1) {
				return "É possível que as alterações não sejam salvas. Você tem certeza que quer sair dessa página?";
			}
		}
		$("input[name='commit']").click(function() {
			formmodified = 0;
		});
	});
	function qtdMaxCaracteres(campo, maximo) {
		if (campo.value.length > maximo) {
			campo.value = campo.value.substring(0, 3);
		}
	}
