<?
	header('Content-Type:text/html; charset=ISO-8859-1');
	header("Pragma: no-cache");
	session_start();
	include_once __DIR__."/conexao.class.php";
	include_once __DIR__."/usuario.class.php";
	include_once __DIR__."/../funcoes.php";

	function formataNome($temp){
		$temp = explode(".",$temp);
		if(count($temp)==1){
			$final = $temp[0];
		}else{
			if(count($temp)==2){
			$nome_temp = $temp[0];
			$extensao  = $temp[1];
			$nome_temp = str_replace(".", "_", $nome_temp);
			$nome_temp = str_replace(",", "_", $nome_temp);
			$final     = $nome_temp . "." . $extensao;
			}else{
				$tam = count($temp);
				$nome = "";
				for($i=0;$i<$tam-1;$i++){
					$nome .= $temp[$i];
					if($i<$tam-2){$nome .= "_";}
				}
				$extensao = $temp[$tam-1];
				$nome_temp = str_replace(".", "_", $nome);
				$nome_temp = str_replace(",", "_", $nome);
				$final     = $nome_temp . "." . $extensao;
			}
		}
		return $final;
	}
	function qtdArquivos($id){
		$conexao = new Conexao();
		$qtd_nivel = 0;
		$query = "select count(id) as qtd
					from arq_arquivos
					where projeto_id = ".$_SESSION['projeto']."
					and grupo_id = " . $id;
		$result = $conexao->pesquisa($query);
		$row = mysqli_fetch_assoc($result);
		$qtd_nivel = $row['qtd'];
		$qtd_sub = 0;

		$query = "select * from arq_tmf where pai = " . $id;
		$result = $conexao->pesquisa($query);
		while($row = mysqli_fetch_assoc($result)){
			$idFilho = $row['id'];
			$qtd_sub = $qtd_sub + qtdArquivos($idFilho);
		}
		return $qtd_nivel + $qtd_sub;
	}
	class Meta{
		public $id;
		public $arquivo;
		public $zona;
		public $sessao;
		public $artefato;
		public $data;
		public $emissor;
		public $dono;
		public $documento;
		public $meta;

		function __construct($arquivo){
			$varConexao = new Conexao();
			$query  = "select * from arq_meta where arquivo = " . $arquivo;
			$resArq = $varConexao->pesquisa($query);
			if(mysqli_num_rows(($resArq))>0){
				$rowArq = mysqli_fetch_assoc($resArq);
				$this->id 		= $rowArq['id'];
				$this->arquivo	= $rowArq['arquivo'];
				$this->zona		= $rowArq['zona'];
				$this->sessao	= $rowArq['sessao'];
				$this->artefato	= $rowArq['artefato'];
				$this->data		= $rowArq['data'];
				$this->emissor	= $rowArq['emissor'];
				$this->dono		= $rowArq['dono'];
				$this->documento		= $rowArq['documento'];
				$this->meta		= $rowArq['meta'];
			}
		}

	}
	class File{
		public $id;
		public $projeto_id;
		public $centro;
		public $nome;
		public $extensao;
		public $arquivo;
		public $descricao;
		public $tamanho;
		public $data;
		public $hora;
		public $grupo_id;
		public $usuario;
		public $status_id;
		public $status_data;
		public $status_motivo;
		public $versao;
		public $status_usuario;
		public $comentarios;
		public $versao_anterior;
		public $data_lis;
		public $comentario_revisor_status;
		public $comentario_revisor;


		public $conexao;

		function __construct($id){
			$this->conexao = new Conexao();

			if($id>0){
				$query 		= "select * from arq_arquivos where id = " . $id;
				$resFile 	= $this->conexao->pesquisa($query);
				$rowFile	= mysqli_fetch_assoc($resFile);

				$this->id                        = $id;
				$this->projeto_id                = $rowFile['projeto_id'];
				$this->nome                      = $rowFile['nome'];
				$this->arquivo                   = $rowFile['arquivo'];
				$this->extensao                  = $rowFile['extensao'];
				$this->descricao                 = $rowFile['descricao'];
				$this->tamanho                   = $rowFile['tamanho'];
				$this->data                      = $rowFile['data'];
				$this->hora                      = $rowFile['hora'];
				$this->data_lis                  = $rowFile['data_lis'];
				$this->grupo_id                  = $rowFile['grupo_id'];
				$this->usuario                   = new Usuario($rowFile['usuario']);
				$this->status_id                 = $rowFile['status_id'];
				$this->status_data               = $rowFile['status_data'];
				$this->status_usuario            = new Usuario($rowFile['status_usuario']);
				$this->status_motivo             = $rowFile['status_motivo'];
				$this->versao                    = $rowFile['versao'];
				$this->versao_anterior           = $rowFile['versao_anterior'];
				$this->centro                    = $rowFile['centro'];
				$this->comentarios               = $rowFile['comentarios'];
				$this->comentario_revisor_status = $rowFile['comentario_revisor_status'];
				$this->comentario_revisor        = $rowFile['comentario_revisor'];
			}
		}
		function getStatus(){
			if($this->status_id==0){return "Não validado";}
			if($this->status_id==1){return "Validado";}
			if($this->status_id==2){return "Deletado";}
			if($this->status_id==3){return "Rejeitado";}
		}
		function inserir(){
			$varConecta = new Conexao();
			$extensao = $this->nome;
			$extensao = explode(".", $extensao);
			$extensao = $extensao[count($extensao)-1];
			$this->extensao = $extensao;
			$query = "insert into arq_arquivos (projeto_id,
												centro,
												descricao,
												grupo_id,
												nome,
												extensao,
												arquivo,
												tamanho,
												data,
												data_lis,
												hora,
												usuario,
												versao,
												versao_anterior)
						values (
												'".$this->projeto_id."',
												'".$this->centro."',
												'".$this->descricao."',
												'".$this->grupo_id."',
												'".$this->nome."',
												'".$this->extensao."',
												'".$this->arquivo."',
												'".$this->tamanho."',
												'".$this->data."',
												'".$this->data_lis."',
												'".$this->hora."',
												'".$this->usuario."',
												'".$this->versao."',
												'".$this->versao_anterior."'
						);";
			$varConecta->pesquisa($query);
			$query = "select id from arq_arquivos where
													nome = '".$this->nome."'
													and arquivo = '".$this->arquivo."'
													and usuario = '".$this->usuario."'";
			$res = $varConecta->pesquisa($query);
			$row = mysqli_fetch_assoc($res);
			return $row['id'];
		}
		function registraAcao($acao){
			$varConexao = new Conexao();
			$query = "insert into arq_acoes
							(arquivo_id, acao, data, usuario_id ) values
							(".$this->id.",'$acao',now(),'".$_SESSION['user_id']."')";
			$varConexao->pesquisa($query);
		}
		function valida($comentario, $senha){
			$conexao = new Conexao();
			$query = "select * from usu_usuario where id = " . $_SESSION['user_id'] . " and senha = md5('".$senha."')";
			$resSenha = $conexao->pesquisa($query);
			if(mysqli_fetch_assoc($resSenha)==0){
				echo "ERRO";
			}else{
				$rowSenha = mysqli_fetch_assoc($resSenha);
				$query = "update arq_arquivos set status_id = '1', status_usuario = '".$_SESSION['user_id']."', status_data = '".date("YmdHi")."', status_motivo = '$comentario' where id = " . $this->id;
				$conexao->pesquisa($query);
				$query = "insert into arq_acoes (arquivo_id, acao, data, usuario_id )
								values (".$this->id.",'VALIDADO',now(),".$_SESSION['user_id'].")";
				$conexao->pesquisa($query);
				echo "OK";

				$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "tmf@phartrials.com.br", "PHARTRIALS", 1);
				$varProjeto = new Projeto($_SESSION['projeto']);

				$message = utf8_encode(file_get_contents('../modelos/modelo_aprovado.html'));
				$message = str_replace('%usuario%', $_SESSION['nome_usuario'],$message);
				$message = str_replace('%localizacao%', utf8_encode(retorna_caminho_completo($this->grupo_id,1)."<br>"."|->".$this->nome),$message);

				$message = str_replace('%arquivo%', $this->nome,$message);
				$message = str_replace('%tamanho%', tamanhoArquivo($this->tamanho),$message);
				$message = str_replace('%data_hora%', date('d/m/Y H:i'),$message);
				$message = str_replace('%nome_usuario%', $rowSenha['nome'],$message);
				$message = str_replace('%projeto%', utf8_encode($varProjeto->nome),$message);
				$varEmail->enviar("[" . $varProjeto->nome . "] - Arquivo APROVADO", $message);
			}
		}
		function salvaComentario($comentario){
			$conexao = new Conexao();
			$query = "update arq_arquivos set comentarios = '$comentario' where id = " . $this->id;
			echo $query;
			$conexao->pesquisa($query);
		}
		function renomeiaArquivo($nome){
			$conexao = new Conexao();
			$query = "update arq_arquivos set nome = '$nome' where id = " . $this->id;
			$conexao->pesquisa($query);
			$query = "insert into arq_acoes (arquivo_id, acao, data, usuario_id )
								values (".$this->id.",'RENOMEADO',now(),".$_SESSION['user_id'].")";
				$conexao->pesquisa($query);
		}
		function desvalida($comentario, $senha){
			$conexao = new Conexao();
			$query = "select * from usu_usuario where id = " . $_SESSION['user_id'] . " and senha = md5('".$senha."')";
			$resSenha = $conexao->pesquisa($query);
			if(mysqli_fetch_assoc($resSenha)==0){
				echo "ERRO";
			}else{
				$query = "update arq_arquivos set status_id = '0',
							status_usuario = '".$_SESSION['user_id']."',
							status_data = '".date("YmdHi")."',
							status_motivo = '$comentario' where id = " . $this->id;
				$conexao->pesquisa($query);
				$query = "insert into arq_acoes (arquivo_id, acao, data, usuario_id )
								values (".$this->id.",'DESVALIDADO',now(),".$_SESSION['user_id'].")";
				$conexao->pesquisa($query);
				echo "OK";
			}
		}
		function exibeLinha($grupo, $mostraNovaVersao, $divGrupo=0, $divVersao=1){
			$formatacao = "font-size:12px; vertical-align:middle;".$grupo;
			if(($divGrupo!=0)&&($divVersao>1)){
				$divGrupo = "grupo_".$divGrupo. " ";
			}else{
				$divGrupo = "";
			}
			if($divVersao==1){
				//$cssDisplay = "display: block";
			}
			if($divVersao>1){
				$cssDisplay = "display: none";
			}
			$classe= "";
		?>
			<tr class="tamanho_fonte <?=$divGrupo;?>"
				data-toggle="tooltip"
				title="<?=$this->descricao;?>"
				data-toggle="tooltip"
				title="<?=$this->descricao;?>"
				style="<?=$cssDisplay;?>">
				<td>
					<a href="javascript:abreRenomear(<?=$this->id;?>);" data-toggle="tooltip" class="btn btn-xs btn-outline black btn-xs green-seagreen" title="Renomear"> <i class="fa fa-exchange "></i></a>
				</td>
				<td style="<?=$formatacao;?>" class="<?=$classe;?>"><?if($this->status_id==2){echo '<strike>';}?><?=utf8_decode($this->nome);?><?if($this->status_id==2){echo '</strike>';}?></td>
				<td style="<?=$formatacao;?>" class="<?=$classe;?>"><?if($this->status_id==2){echo '<strike>';}?><center><?=tamanhoArquivo($this->tamanho);?></center><?if($this->status_id==2){echo '</strike>';}?></td>
				<td style="<?=$formatacao;?>" class="<?=$classe;?>"><?if($this->status_id==2){echo '<strike>';}?><center><?=$this->data;?> <?=$this->hora;?></center><?if($this->status_id==2){echo '</strike>';}?></td>
				<td style="<?=$formatacao;?>" class="<?=$classe;?>"><?if($this->status_id==2){echo '<strike>';}?><center><?=$this->versao;?></center><?if($this->status_id==2){echo '</strike>';}?></td>
				<td style="<?=$formatacao;?>" class="<?=$classe;?>"><?if($this->status_id==2){echo '<strike>';}?><?=$this->usuario->nome;?><?if($this->status_id==2){echo '</strike>';}?></td>
				<td style="<?=$formatacao;?>" class="<?=$classe;?>">
					<center>
						<?
						if($this->status_id==0){
						echo '<span class="label label-sm label-info" style="font-size: 8pt;"> Não conferido </span>';
						}
						if($this->status_id==1){
							$frase = "Validado por ". $this->status_usuario->nome . " em " . NOVO_LIStoDATA_HORA($this->status_data);
							echo '<span class="label label-sm label-success" style="font-size: 8pt;" data-toggle="tooltip" title="'.$frase.'"> Aprovado </span>';
						}
						if($this->status_id==2){
							$frase = "Deletado por ". $this->status_usuario->nome . " em " . NOVO_LIStoDATA_HORA($this->status_data) . " - MOTIVO: " . utf8_decode($this->status_motivo);;
							echo '<span class="label label-sm label-danger" style="font-size: 8pt;" data-toggle="tooltip" title="'.$frase.'"> DELETADO </span>';
						}
						if($this->status_id==3){
							$frase = "Rejeitado por ". $this->status_usuario->nome . " em " . NOVO_LIStoDATA_HORA($this->status_data) . " - MOTIVO: " . utf8_decode($this->status_motivo);
							echo '<span class="label label-sm label-danger" style="font-size: 8pt;" data-toggle="tooltip" title="'.$frase.'"> REJEITADO </span>';
						}
						?>
					</center>
				</td>
				<td style="text-align:right !important;<?=$formatacao;?>" ><right>
					<?
						if($this->status_id!=2){
					?>
						<a href="arq_action.php?act=D&acw=<?=$this->id."&aci=".$this->id;?>" data-toggle="tooltip" class="btn btn-xs  btn-outline btn-circle blue btn-sm blue" title="Download"> <i class="fa fa-download"></i></a>
					<?
					}
					?>
					<?
						if($this->status_id!=2){
					?>

						<span class="btn btn-xs btn-outline btn-circle red btn-sm red" title="Deletar arquivo" data-toggle="modal" data-target="#static_modal" href="modal_deleta.php?id=<?=$this->id;?>"> <i class="fa fa-remove"></i></span>
					<?
					}
					?>
					<a href="javascript:abreHistorico(<?=$this->id;?>);" data-toggle="tooltip" class="btn btn-xs btn-outline btn-circle black btn-sm yellow" title="Histórico"> <i class="fa fa-history"></i></a>
					<?if($this->status_id==0){?><a data-toggle="tooltip" href="javascript:validaSenha(<?=$this->id;?>, <?=$this->grupo_id;?>);"  class="btn btn-xs btn-outline btn-circle green btn-sm blue" title="Aprovar arquivo"> <i class="fa fa-thumbs-o-up"></i></a><?}?>
					<?if($this->status_id==1){?><a data-toggle="tooltip" href="javascript:desvalidaSenha(<?=$this->id;?>, <?=$this->grupo_id;?>);"  class="btn btn-xs btn-outline btn-circle red btn-sm red" title="Desaprovar arquivo"> <i class="fa fa-thumbs-o-down"></i></a><?}?>
					<?
						if(($this->status_id!=1)&&($this->status_id!=2)&&($this->status_id!=3)){
					?>
						<span class="btn btn-xs btn-outline btn-circle red btn-sm red" title="Rejeitar arquivo" data-toggle="modal" data-target="#static_modal" href="modal_negou.php?id=<?=$this->id;?>"> <i class="fa fa-thumbs-o-down"></i></span>
					<?
						}
					?>
					<?if($mostraNovaVersao==true){
						if($this->status_id!=2){?><a href="javascript:novaVersao(<?=$this->id;?>, '<?=$this->nome;?>', <?=$this->versao;?>)" data-toggle="tooltip" class="btn btn-xs btn-outline btn-circle black btn-sm blue" title="Fazer upload de uma NOVA versão"> <i class="fa fa-database"></i></a><?}?>
					<?
					}
						$cor = $this->temComentarios();
					?>
					<a href="javascript:abreComentario(<?=$this->id;?>);" data-toggle="tooltip" class="btn btn-xs btn-outline btn-circle black btn-sm <?=$cor;?>" title="Comentários"> <i class="fa fa-comment-o"></i></a>
					<?php
						//Se nao tiver metadado, fica em vermelho
						//Se tiver mas nao todos, fica em amarelo
						//Se tiver tudo, fica em verde.
						$cor = $this->temMetaDados();
					?>
					<a href="javascript:metaDados(<?=$this->id;?>);" data-toggle="tooltip" class="btn btn-xs btn-outline btn-circle black btn-sm <?=$cor;?>" title="Meta Dados"> <i class="fa fa-book" aria-hidden="true"></i></i></a>
					<?php
						//Se nao tiver metadado, fica em vermelho
						//Se tiver mas nao todos, fica em amarelo
						//Se tiver tudo, fica em verde.
						$cor = $this->temRevisaoAberta();
					?>
					<a data-toggle="modal"
						data-target="#modalHistorico"
						href="modal_tmf_revisor.php?id=<?=$this->id;?>"
						class="btn btn-xs btn-outline btn-circle black btn-sm <?=$cor;?>"
						title="Comentários do revisor">
						<i class="fa fa-comment" aria-hidden="true"></i></a>
				 </right>
				</td>
			</tr>
		<?
		}
		function temRevisaoAberta(){
			$varConexao = new Conexao();
			$query = "select
						comentario_revisor_status
							from arq_arquivos
						where id = " . $this->id;
			$res   = $varConexao->pesquisa($query);
			$row = mysqli_fetch_assoc($res);
			if($row['comentario_revisor_status']=="0"){
				return " green-seagreen ";
			}else{
				return " red " . $query;
			}
		}
		function temMetaDados(){
			$varConexao = new Conexao();
			$query = "select * from arq_meta where arquivo = " . $this->id;
			//return $query;
			$res   = $varConexao->pesquisa($query);
			if(mysqli_num_rows($res)==0){
				return  " red ";
			}else{
				$row = mysqli_fetch_assoc($res);
				if( ($row['zona']==0)&&($row['sessao']==0)&&($row['artefato']==0)&&($row['classificacao']==0)&&($row['tipo']==0)&&($row['documento']=="0")&&($row['data']=="")&&($row['dono']=="")&&($row['meta']=="") ){ return " red ";}
				if( ($row['zona']==0)||($row['sessao']==0)||($row['artefato']==0)||($row['classificacao']==0)||($row['tipo']==0)||($row['documento']=="0")||($row['data']=="")||($row['dono']=="")||($row['meta']=="") ){ return " yellow ";}else{ return " green-seagreen";}
			}
		}
		function temComentarios(){
			if($this->comentarios==""){
				return  " green-seagreen ";
			}else{
				return  " yellow ";
			}
		}
		function exibeGrupo($grupo){
			$formatacao = "font-size:11px; vertical-align:middle;";
		?>
			<tr class="tamanho_fonte grupo borda_topo"  style="border-top:1pt solid black !important" data-toggle="tooltip" title="<?=$this->descricao;?>">
				<td class="borda_topo" style="border-top:1pt solid black !important; <?=$formatacao;?>"><center><a href="javascript:mostraGrupoVersao(<?=$this->id;?>)"><i class="fa fa-plus-circle"></i></center></a></td>

				<td colspan="3"><font size="1"><i>(Clique em <i class="fa fa-plus-circle"></i> para ver as versões obsoletas)</i></font><br>
				</td>
				<td colspan="4"class="borda_topo"   style="border-top:1pt solid black !important; <?=$formatacao;?>"></td>


			</tr>
			<div name="div_grupo_<?=$this->id;?>" id="" style="display:none;">
				<?
					$query = "select arq_arquivos.*
									from arq_arquivos
									where  ( (arq_arquivos.id = ".$this->id.") or ( versao_anterior = ".$this->id."))
									order by arq_arquivos.versao desc, id desc ";
					echo $query;
					$resV = $this->conexao->pesquisa($query);
					$countVersao = 1;
					while($rowV = mysqli_fetch_assoc($resV)){
						if($countVersao==2){
							echo '<div id="div_versao_'.$this->id.'" style="">';
						}
						$arqV = new File($rowV['id']);
						if($countVersao==1){$exibeUp = true;}else{$exibeUp=false;}
						$arqV->exibeLinha("background-color: #F5F7FA !important;", $exibeUp, $this->id, $countVersao);
						$countVersao++;
					}
				?>
			</div>
			<tr style="line-height: 0px; font-size: 1px;">
				<td colspan="8" style="border-top:1pt solid black !important; line-height: 0px; font-size: 1px;"></td>
			</tr>
		<?
		}
		function deleta($arquivo, $motivo){
			$conexao = new Conexao();
			$query = "insert into arq_acoes (arquivo_id, acao, data, usuario_id, motivo)
						values (".$this->id.",'DELETADO',now(),".$_SESSION['user_id']."'".$motivo."')";
			$resIns = $conexao->pesquisa($query);
			$query = "update arq_arquivos set
							status_id = 2,
							status_motivo = '$motivo',
							status_usuario = ".$_SESSION['user_id'].",
							status_data = '".date('YmdHi')."' where id = " . $this->id;
			$resIns = $conexao->pesquisa($query);
			echo $this->grupo_id;
		}
		function rejeita($motivo){
			$conexao = new Conexao();
			$query = "insert into arq_acoes (arquivo_id, acao, data, usuario_id )
						values (".$this->id.",'REJEITADO',now(),".$_SESSION['user_id'].")";
			$resIns = $conexao->pesquisa($query);
			$query = "update arq_arquivos set
							status_id = 3,
							status_usuario = ".$_SESSION['user_id'].",
							status_data = '".date('YmdHi')."',
							status_motivo = '".$motivo."'
							where id = " . $this->id;
			$resIns = $conexao->pesquisa($query);

			//Mandar email avisando que foi rejeitado
			//Pegar quem é o id/email que fez o upload
			//$idUp =

			$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "tmf@phartrials.com.br", "PHARTRIALS", 1);
			$varProjeto = new Projeto($_SESSION['projeto']);

			$message = utf8_encode(file_get_contents('../modelos/modelo_rejeitado.html'));
			$message = str_replace('%usuario%', $_SESSION['nome_usuario'],$message);
			$message = str_replace('%localizacao%', utf8_encode(retorna_caminho_completo($this->grupo_id,1)."<br>"."|->".$this->nome),$message);
			$tamanho = 0;
			$message = str_replace('%arquivo%', $this->nome,$message);
			$message = str_replace('%tamanho%', tamanhoArquivo($tamanho),$message);
			$message = str_replace('%data_hora%', date('d/m/Y H:i'),$message);
			$message = str_replace('%nome_usuario%', $_SESSION['nome_usuario'],$message);
			$message = str_replace('%comentario%', $motivo,$message);
			$message = str_replace('%projeto%', utf8_encode($varProjeto->nome),$message);
			$varEmail->enviar("[" . $varProjeto->nome . "] - Arquivo REJEITADO", $message);


			echo "OK";
		}
	}
	class Node{
		public $nodeId;
		public $totalArquivos;

		public $nome;
		public $nomeOriginal;
		public $nome_en;
		public $pai;
		public $ordem;
		public $log_usuario;
		public $id_tipo_arquivo;
		public $label;
		public $status;
		public $comentario;
		public $conexao;


		function __construct($id){
			if($id>0){
				$query = "select * from arq_tmf where id = " . $id;
				$this->conexao = new Conexao;
				$resNode = $this->conexao->pesquisa($query);
				$rowNode = mysqli_fetch_assoc($resNode);
				$this->nodeId 			= $id;
				$this->nome   			= $rowNode['nome'];
				$this->nomeOriginal		= $rowNode['nome'];
				$this->nome_en 			= $rowNode['nome_en'];
				$this->pai				= $rowNode['pai'];
				$this->ordem			= $rowNode['ordem'];
				$this->log_usuario 		= $rowNode['log_usuario'];
				$this->id_tipo_arquivo	= $rowNode['id_tipo_arquivo'];
				$this->label			= $rowNode['nome'];
				$this->status			= $rowNode['status'];
				$this->comentario		= $rowNode['comentario'];

				if($this->pai=="0"){$this->pai = "#";}
				$this->totalArquivos = qtdArquivos($id);

				$this->nome = "[".$this->totalArquivos."] - " . $this->nome;
			}
		}
		function classCor(){
			if($this->status==0){return "#FFFFFF";}
			if($this->status==1){return "#FFC0CB";}
			if($this->status==2){return "#48D1CC";}
			if($this->status==3){return "Não aplicável";}
			if($this->status==5){return "#f72516";}
		}
		function status(){
			if($this->status==0){return "Vazio";}
			if($this->status==1){return "Incompleto";}
			if($this->status==2){return "OK";}
			if($this->status==5){return "Not - OK";}
			if($this->status==3){return "Não aplicável";}
			if($this->status==4){return "Not - ISO";}
		}
		function montaJson(){
			$varState = '"state" : {"opened" : false}';
			//$varState = '"state" : {"opened" : true}';
			$link = '"a_attr":{"href":"'.$this->nodeId.'"}';
			if($this->totalArquivos>0){
				$icon = 'fa fa-folder fa-fw';
			}else{
				$icon = '';
			}
			$nome = $this->ordem . '. '. $this->nome;
			return '{"id":"'.$this->nodeId.'", "icon":"'.$icon.'", "parent":"'.$this->pai.'", "text":"'.$nome.'", '.$varState.', '.$link.'}';
		}
		function renomeia($nome){
			$query = "update arq_tmf set nome = '$nome', log_usuario = ".$_SESSION['user_id']." where id = " .$this->nodeId;
			$this->conexao = new Conexao;
			$resRen = $this->conexao->pesquisa($query);
			echo "OK";
		}
		function novo($nome){
			$query = "insert into arq_tmf (nome, log_usuario, pai) values ('$nome', '".$_SESSION['user_id']."', '".$this->nodeId."');";
			$this->conexao = new Conexao;
			$resRen = $this->conexao->pesquisa($query);
			echo "OK";
		}
		function deleta(){
			$query = "delete from arq_tmf where id = " . $this->nodeId;
			$this->conexao = new Conexao;
			$resRen = $this->conexao->pesquisa($query);
			echo "OK";
		}
		function listaArquivos(){
		?>
			<div class="col-md-12 fundo_branco" >
				<div class="portlet">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-bars"></i> <?="<b>".$this->label."</b>]";?>
						</div>
						<div class="inputs" style="text-align: right">
							<div class="portlet-input input-inline input-small">
								<div class="input-icon right">
									<i class="icon-magnifier" id="btnPesquisaFile" onclick="pesquisaArquivo(1, document.getElementById('txtPesquisaFile').value);"></i>
									<input
										type="text"
										class="form-control input-circle"
										placeholder="Pesquisa..."
										id="txtPesquisaFile"
										>
								</div>
							</div>
						</div>
					</div>
					<div class="portlet-body">
						<div class="row">
							<div class="col-md-8">
								<div class="row">
									<label class="col-md-2 control-label"><span class="labelPasta">Comentários da pasta</span></label>
									<div class="col-md-8">
										 <textarea class="form-control" id="txtComentario" rows="4"><?=utf8_decode($this->comentario);?></textarea>
									</div>
									<div class="col-md-2">
										<br><br><br>
										<a class="btn dark btn-outline btn-sm" href="javascript:salvaComentarioPasta(<?=$this->nodeId?>)"> Salvar</a>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<p style="text-align:left"><span class="labelPasta">Status da pasta:</span></p>
							</div>
							<div class="col-md-2">
								<select class="form-control"
										id="form_status_diretorio"
										onchange="javascript:mudouStatusDir(this.value, <?=$this->nodeId;?>);"
										style="text-align: center">
										<option value="0" <?if($this->status==0){ echo "selected ";}?>>Vazio</option>
										<option value="1" <?if($this->status==1){ echo "selected ";}?>>Incompleto</option>
										<option value="2" <?if($this->status==2){ echo "selected ";}?>>OK</option>
										<option value="5" <?if($this->status==5){ echo "selected ";}?>>Not OK</option>
										<option value="3" <?if($this->status==3){ echo "selected ";}?>>Não aplicável</option>
										<option value="4" <?if($this->status==4){ echo "selected ";}?>>not - ISO</option>
								</select>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-9">

								<div id="dZUpload" class="dropzone" style="border-style: dashed">
									  <div class="dz-default dz-message">
										 <center>Solte os arquivos aqui.<br />
										 <span class="note needsclick">(Os arquivos serão subidos automaticamente)</span></center>
									  </div>


								</div>

							</div>
							<div class="col-md-2">
								<a  href="javascript:modalUpload(<?=$this->nodeId;?>, '<?=$this->label;?>');"
									class="btn btn-outline btn-circle blue btn-sm blue">
										<i class="fa fa-cloud-upload"></i> Upload de arquivo </a><font color="white">LUIS</font>
							</div>
							<div class="col-md-1"></div>
						</div>
						<div id="divArquivosLista">
							<div class="table-scrollable table-condensed">
								<table class="table table-advance table-hover table-borderless tamanho_fonte table-condensed cabecalho" name="tree" id="tree">
									<thead>
										<th></th>
										<th align="left" class="arquivos_cabecalho"><b><?=traduzir("Arquivo");?> </b><a href="javascript:sort('nome');"><i class="fa fa-sort" aria-hidden="true"></i></th>
										<th align="center" class="arquivos_cabecalho"><center><b><?=traduzir("Tamanho");?> </center></b></th>
										<th class="arquivos_cabecalho"><b><center><?=traduzir("Data de Upload");?></center></b></th>
										<th class="arquivos_cabecalho"><b><center><?=traduzir("Versão");?></center></b></th>
										<th class="arquivos_cabecalho"><b><?=traduzir("Usuário de Upload");?></b></th>
										<th class="arquivos_cabecalho"><b><center><?=traduzir("Status");?>
											<select onchange="filtraArquivos(<?=$this->nodeId;?>, this.value);">
												<option value="TODOS">TODOS</option>
												<option value="Não conferido">Não conferido</option>
												<option value="Aprovado">Aprovado</option>
												<option value="Deletado">Deletado</option>
												<option value="Rejeitado">Rejeitado</option>
											</select>
											</b></center>
										</th>
										<th class="arquivos_cabecalho"><b><center><?=traduzir("Ações");?></b></center></th>
									</thead>
									<tbody>
										<?
											$varConexao = new Conexao();
											//Listagem dos arquivos das versoes
											$query 	= "select id from arq_arquivos
															where versao = 1
																and grupo_id = " . $this->nodeId . "
																	order by status_id, data_lis ";
											$resArq = $varConexao->pesquisa($query);
											$count = 0;
											if(mysqli_num_rows($resArq)>0){
												while($rowArq = mysqli_fetch_assoc($resArq)){
													$count++;
													if( mysqli_num_rows($resArq) == $count){ $classe = 'borda_fim';}else{$classe="";}
													$arquivo = new File($rowArq['id']);
													$query 	= "select id from arq_arquivos
																	where versao_anterior = " . $arquivo->id;
													$resVer = $varConexao->pesquisa($query);
													if(mysqli_num_rows($resVer)==0){
														$arquivo->exibeLinha($classe, true);
													}else{
														$arquivo->exibeGrupo("grupo");
													}
												}
											}else{
												echo "	<tr>
															<td colspan='6'>Nenhum arquivo a ser exibido...</td>
														</tr>";
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
			</div>
		<?
		}
		function listaArquivosInd(){
		?>
							<div class="table-scrollable table-condensed">
								<table class="table table-advance table-hover table-borderless tamanho_fonte table-condensed cabecalho" name="tree" id="tree">
									<thead>
										<th></th>
										<th align="left" class="arquivos_cabecalho"><b><?=traduzir("Arquivo");?></b></th>
										<th align="center" class="arquivos_cabecalho"><center><b><?=traduzir("Tamanho");?></center></b></th>
										<th class="arquivos_cabecalho"><b><center><?=traduzir("Data de Upload");?></center></b></th>
										<th class="arquivos_cabecalho"><b><center><?=traduzir("Versão");?></center></b></th>
										<th class="arquivos_cabecalho"><b><?=traduzir("Usuário de Upload");?></b></th>
										<th class="arquivos_cabecalho"><b><center><?=traduzir("Status");?></b></center></th>
										<th class="arquivos_cabecalho"><b><center><?=traduzir("Ações");?></b></center></th>
									</thead>
									<tbody>
										<?
											//Listagem dos arquivos das versoes
											$query 	= "select id from arq_arquivos where versao = 1 and grupo_id = " . $this->nodeId . " order by status_id, data_lis ";
											$resArq = $this->conexao->pesquisa($query);
											$count = 0;
											if(mysqli_num_rows($resArq)>0){
												while($rowArq = mysqli_fetch_assoc($resArq)){
													$count++;
													if( mysqli_num_rows($rowArq) == $count){ $classe = 'borda_fim';}else{$classe="";}
													$arquivo = new File($rowArq['id']);

													$query 	= "select id from arq_arquivos where versao_anterior = " . $arquivo->id;
													$resVer = $this->conexao->pesquisa($query);
													if(mysqli_num_rows($resVer)==0){
														$arquivo->exibeLinha($classe, true);
													}else{
														$arquivo->exibeGrupo("grupo");
													}
												}
											}else{
												echo "	<tr>
															<td colspan='6'>Nenhum arquivo a ser exibido...</td>
														</tr>";
											}
										?>
									</tbody>
								</table>
							</div>

		<?
		}
	}
	class Tmf{
		public $projetoId;
		public $totalArquivos;

		public $conexao; //Ponteiro para a conexao

		function __construct($id){
			if($id>0){
				$this->conexao = new Conexao();
				$query = "select count(*) as qtd from arq_arquivos where projeto_id = " . $_SESSION['projeto'];
				if($_SESSION['centro']!=40){
					$query .= " and centro = " . $_SESSION['centro'];
				}
				$resArq = $this->conexao->pesquisa($query);
				$rowArq = mysqli_fetch_assoc($resArq);
				$this->totalArquivos = $rowArq['qtd'];
			}
		}
		function retornaTotal($status){
			$this->conexao = new Conexao();
			$query = "select count(*) as qtd from arq_arquivos where projeto_id = " . $_SESSION['projeto'];
			if($_SESSION['centro']!=40){
				$query .= " and centro = " . $_SESSION['centro'];
			}
			if($status!=""){
				$query .= " and status_id = " . $status;
			}
			$resArq = $this->conexao->pesquisa($query);
			$rowArq = mysqli_fetch_assoc($resArq);
			$temp = $rowArq['qtd'];
			if($temp==""){$temp = 0;}
			return $temp;
		}
		function getMetaZonaTotal($meta){
			$this->conexao = new Conexao();
			$query 	= "SELECT count(arq_meta.id) as qtd FROM arq_meta
							inner join arq_arquivos on arq_meta.arquivo = arq_arquivos.id
								where arq_arquivos.projeto_id = ".$_SESSION['projeto']."
									and zona = " . $meta;
			$resQtd = $this->conexao->pesquisa($query);
			$rowQtd = mysqli_fetch_assoc($resQtd);
			$temp   = $rowQtd['qtd'];
			if($temp==""){$temp = 0;}
			return $temp;
		}
		function montaTree($entrada){
			if($entrada==null){
				$labelPrincipal = "TODOS OS ARQUIVOS";
			}else{
				$labelPrincipal = "Filtro: " . $entrada;
			}

			$final = '[{"id":"0", "parent":"#", "text":"'.$labelPrincipal.'"}';
			if($entrada==null){
				$query = "select id from arq_tmf where pai = 0 and projeto = " . $_SESSION['projeto'];
				if( ($_SESSION['centro']!=40) and ($_SESSION['centro']!=0) ){	//Arqui tem centro escolhido. Mostrar só o dele
					$query .= " and centro = " . $_SESSION['centro'];
				}
				$query .= " order by ordem";
			}else{
				$query = "select id from arq_tmf
							where nome like ()
							and pai = 0
							and projeto = " . $_SESSION['projeto'];
				if( ($_SESSION['centro']!=40) and ($_SESSION['centro']!=0) ){	//Arqui tem centro escolhido. Mostrar só o dele
					$query .= " and centro = " . $_SESSION['centro'];
				}
				$query .= " order by ordem";
			}

			$conexao = new Conexao();
			$resPai = $conexao->pesquisa($query);
			$nodes = array();
			while($rowPai = mysqli_fetch_assoc($resPai)){
				$node = new Node($rowPai['id']);
				$nodes[] = $node->montaJson();
				//Nivel2
				$query = "select * from arq_tmf where pai = " . $rowPai['id'];
				$resNvl2 = $conexao->pesquisa($query);
				while($rowNvl2 = mysqli_fetch_assoc($resNvl2)){
					$node = new Node($rowNvl2['id']);
					$nodes[] = $node->montaJson();
					//Nivel3
					$query = "select * from arq_tmf where pai = " . $rowNvl2['id'];
					$resNvl3 = $conexao->pesquisa($query);
					while($rowNvl3 = mysqli_fetch_assoc($resNvl3)){
						$node = new Node($rowNvl3['id']);
						$nodes[] = $node->montaJson();
						//Nivel4
						$query = "select * from arq_tmf where pai = " . $rowNvl3['id'];
						$resNvl4 = $conexao->pesquisa($query);
						while($rowNvl4 = mysqli_fetch_assoc($resNvl4)){
							$node = new Node($rowNvl4['id']);
							$nodes[] = $node->montaJson();
							//Nivel5
							$query = "select * from arq_tmf where pai = " . $rowNvl4['id'];
							$resNvl5 = $conexao->pesquisa($query);
							while($rowNvl5 = mysqli_fetch_assoc($resNvl5)){
								$node = new Node($rowNvl5['id']);
								$nodes[] = $node->montaJson();
							}

						}
					}
				}
			}
			for($i=0;$i<count($nodes);$i++){$final .= ",".$nodes[$i];}
			$final .= ']';
			echo $final;

		}
	}

