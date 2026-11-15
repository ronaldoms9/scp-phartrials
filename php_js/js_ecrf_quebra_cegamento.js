function popup(url){
    params  = 'width=700';
    params += ', height=500';
    params += ', top=0, left=0'
    params += ', fullscreen=yes';

    newwin=window.open(url,'windowname4', params);
    if (window.focus) {newwin.focus()}
    return false;
}

function valida(){

    //varUrl = "../php_php/php_ecrf_variaveis_calculadas.php?act=GERAR";
    varUrl = "php_php/php_ecrf_quebra_cegamento.php?act=QUEBRA";
    varParticipante = document.getElementById("form_participante").value;
    varMotivo       = document.getElementById("form_motivo").value.trim();
    varSenha        = document.getElementById("form_senha").value.trim();
    if(varParticipante==""){alert("Por favor, selecione o participante que deseja quebrar o cegamento!");return;}
    if(varMotivo==""){alert("Por favor, informe o motivo da quebra do cegamento");return;}
    if(varSenha==""){alert("Campo senha não preenchido. Por favor, revise as informações");return;}
    mostraCarregando();
    var formData = {participante: varParticipante,
                    motivo: varMotivo,
                    senha: varSenha}
    $.ajax({
        url: varUrl,
        type: "POST",
        data : formData,
        async : true,
        success: function(response, textStatus, jqXHR) {
            console.log(response);
            var resp = JSON.parse(response);
			if(resp.resp=="OK"){
                popup('rep_recibo_quebra.php?centro='+resp.centro+'&paciente='+resp.participante+'&id='+resp.id);
            }else{
                removeCarregando();

                if(resp.tipo=="SENHA"){
                    alert("Senha digitada não confere. Por favor refaça a operação");
                    return;
                }
                if(resp.tipo=="USER"){
                    alert("Usuário não habilitado a efetuar a quebra de cegamento. Operação cancelada.");
                    return;
                }
                if(resp.tipo=="VA"){
                    alert("O formulário [(VA) AVALIAÇÃO DA IMUNOGENICIDADE PÓS-VACINAÇÃO] do CRF desse participante não está preenchido. A quebra de cegamento somente será realizada após o preenchimento desse formulário.");
                    return;
                }
                alert("Erro ao realizar a quebra de cegamento. Operação cancelada");

            }
            removeCarregando();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            removeCarregando();
        }
    });
}