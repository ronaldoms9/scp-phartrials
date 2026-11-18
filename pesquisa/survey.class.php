<?php	
	header ('Content-type: text/html; charset=UTF-8');
	session_start();
	
	class Campo{
		public $formId;
		public $id;
		
		public $ordem;
		public $tipo;
		public $nome;
		public $texto;
		public $texto_en;
		public $formato;
		public $legenda;
		public $obrigatorio;
		public $so_numero;
		public $so_letra;
		public $so_leitura;
		public $selecao_valores;
		
		
		function __construct($id){			
			//Verifico se o $id é null. Se for, crio um zerado
			if($id<1){
				$this->formId = "";				
				$this->id	  = "";
			}else{
				$conexao = new Conexao();
				$query 		= "select * from pesq_campos where id = " .$id;				
				
				$resCampo 	= $conexao->pesquisa($query);				
				$rowCampo	= mysqli_fetch_assoc($resCampo);
				
				$this->id	= $id;
				$this->nome	= $rowCampo['nome'];
				$this->ordem	= $rowCampo['ordem'];
				$this->tipo	= $rowCampo['tipo'];
				$this->nome	= $rowCampo['nome'];
				$this->texto	= $rowCampo['texto'];
				$this->texto_en	= $rowCampo['texto_en'];
				$this->formato	= $rowCampo['formato'];
				$this->legenda	= $rowCampo['legenda'];
				$this->obrigatorio	= $rowCampo['obrigatorio'];				
				$this->selecao_valores	= $rowCampo['selecao_valores'];
			}
		}
		function cadastroPesquisa($pesquisa){
		}
		function montaPesquisaHtml($valor){
			$html ="
				<tr>					
					<td class='tabela-body'>".utf8_encode($this->texto)."</td>
					<td style='vertical-align:middle' width='*'>
				";
			if($this->tipo=="numero"){    //SE o tipo for número
				$html .= "<input autocomplete='new-password' autocomplete='off' width='100%' type='number'
							class='form-control controle' 
							id='form_".$this->id."' 							
							value='$valor' >";
			}
			if($this->tipo=="texto"){    //SE o tipo for texto
				$html .= "<input autocomplete='new-password' autocomplete='off' width='100%'
							class='form-control controle' 
							id='form_".$this->id."' 
							value='$valor'>";
			}
			if($this->tipo=="caixa"){    //SE o tipo for caixa de texto
				$html .= "<textarea rows='6' 
					   style='width:100%' 
					   class='form-control' 
					   id='form_".$this->id."' 
					   name='".$this->id."'>".str_replace('<br />','',$valor)."</textarea>";
			}			
			if($this->tipo=="select"){    //SE o tipo for select
				$opcoes = "";
				$opcoes = str_replace("<br />","%",utf8_encode($this->selecao_valores));
				$opcoes = str_replace("\n","",$opcoes);
				$html .= "<select 
						class='form-control controle' >";
				$html .="      <option value=''></option>";
				$combo = "";
				$temp  = "";																										
				for($z=0;$z<strlen($opcoes);$z++){
				   if(substr($opcoes,$z,1)!="%"){$temp.=substr($opcoes,$z,1);}
				   else{
						$temp=trim($temp);
						$temp_selected = "";
						if(trim($valor)==$temp){ $temp_selected = " selected "; }
						$html .= '<option '.$temp_selected.' value="'.$temp.'">'.$temp.'</option>';
						$temp="";
				   }
				}
				if(strlen($temp)>0){																											   
						$temp=trim($temp);
						$temp_selected = "";
						if(trim($valor)==$temp){ $temp_selected = " selected "; }
						$html .= '<option '.$temp_selected.' value="'.$temp.'">'.$temp.'</option>';
						$temp="";
				}
				$html .= '</select>';
			}
			if($this->tipo=="checkbox"){    //SE o tipo for Check-Box
			   $opcoes = "";
			   $opcoes = str_replace("<br />","%",utf8_encode($this->selecao_valores));
			   $opcoes = str_replace("\n","",$opcoes);			   
			   $temp = "";
			   $html .= "<left>";
			   for($z=0;$z<strlen($opcoes);$z++){
				   if(substr($opcoes,$z,1)!="%"){$temp.=substr($opcoes,$z,1);}
				   else{
					   $html .= "<input type='checkbox' id='chec_".$this->id."[]' name='chec_".$this->id."[]' 
						class='btn-group-xs btn-default' value='".$temp."' ";
					   $tags  = $valor;
					   $termo = trim($temp);
					   $pattern = '/' . $termo . '/';//Padrão a ser encontrado na string $tags
					   if (preg_match($pattern, $tags)) {$html .= " checked ";} 																													   
					   $html .= "> " .$temp ."<br>";
					   $html .= "<input type='hidden' id='chec_".$this->id."_H' name='chec_".$this->id."_H' value='DELETAR' ";
					   if (preg_match($pattern, $tags)) {$html .= " checked ";} 
					   $html .= "> ";
					   $temp="";
				   }
			   }
			   if(strlen($temp)>0){
				   $temp =trim($temp);
				   $html .= "<input type='checkbox' id='chec_".$this->id."[]' name='chec_".$this->id."[]' 
				   class='btn-group-xs btn-default' value='".$temp."' ";
				   $tags  = $valor;
				   $termo = trim($temp);
				   $pattern = '/' . $termo . '/';//Padrão a ser encontrado na string $tags
				   if (preg_match($pattern, $tags)) {$html .= " checked ";} 																													   
				   $html .= "> ". $temp;
				   $html .= "<input type='hidden' id='chec_".$this->id."_H' name='chec_".$this->id."_H' value='DELETAR' ";
				   if (preg_match($pattern, $tags)) {$html .= " checked ";} 	
				   $html .= "> ";
			   }
			   $html .= "</left>";
			   
			   $html .= "<center></center>";
			}																								
																											
																											
			$html .=  "				
					</td>
				</tr>
				
				";
			echo $html;
		}
		function listaCampos($formId){
			echo '<li class="list-group-item">
					<div class="btn-group btn-group-justified">
						<a href="javascript:novoCampo();" class="btn btn-default"> <i class="fa fa-plus"></i></a>						
					</div>
				  </li>';
			$conexao 	= new Conexao();
			$query		= "select * from pesq_campos 
							where formulario = " . $formId . " order by id";
			$resCampo	= $conexao->pesquisa($query);
			while ($rowCampo	= mysqli_fetch_assoc($resCampo)){
				echo '	<li class="list-group-item">
							<a href="javascript:selecionaCampo('.$rowCampo["id"].');">
								'.$rowCampo["id"].'- '. utf8_encode($rowCampo["texto"]) . '
							</a>
							<a href="javascript:deletaCampo('.$rowCampo["id"].');"><i class="fa fa fa-minus float-right" alt="Excluir"></i></a>
						</li>';		
			}
		}
		
		function insereCampo(){		
			$query = "
						insert into pesq_campos ( 
							formulario, 
							nome, 
							texto, 
							texto_en, 
							ordem, 
							tipo, 
							obrigatorio,
							selecao_valores, 
							formato, 
							legenda
							)values
							(
							'".$this->formId."',
							'".$this->nome."',
							'".$this->texto."',
							'".$this->texto_en."',
							'".$this->ordem."',
							'".$this->tipo."',
							'".$this->obrigatorio."',
							'".$this->selecao_valores."',
							'".$this->formato."',
							'".$this->legenda."'		
							)
					 ";
			$query = utf8_decode($query);
			$conexao = new Conexao();
			$conexao->pesquisa($query);
			echo "OK";
		}
		
	}
	
	class Pesquisa{
		public $id;
		public $nome;
		
		public $conexao;
		
		function __construct($id){
			
			//Verifico se o $id é null. Se for, crio um zerado
			if($id<1){
				$this->nome = "";
				$this->id   = "";
			}else{
				$conexao = new Conexao();
				$query 		= "select * from pesq_pesquisa where id = " .$id;				
				$resPesq 	= $conexao->pesquisa($query);
				$rowPesq	= mysqli_fetch_assoc($resPesq);
				$this->id	= $rowPesq['id'];
				$this->nome	= $rowPesq['nome'];
			}
		}
		function exibeLista(){
			$conexao = new Conexao();
			$query 		= "select * from pesq_pesquisa order by id";
			$resPesq 	= $conexao->pesquisa($query);
			echo '<li class="list-group-item">
					<div class="btn-group btn-group-justified">
						<a href="javascript:novaPesquisa();" class="btn btn-default"> <i class="fa fa-plus"></i></a>						
					</div>
				  </li>';
			while($rowPesq = mysqli_fetch_assoc($resPesq)){
				echo '<li class="list-group-item">
							<a href="javascript:selecionaPesq('.$rowPesq["id"].');">
								'.$rowPesq["id"].'- '. utf8_encode($rowPesq["nome"]) . '
							</a>
							<a href="javascript:deletaPesq('.$rowPesq["id"].');"><i class="fa fa fa-minus float-right" alt="Excluir"></i></a>
						</li>';				
			}
		}
		function exibeFormularios(){
			$conexao 	= new Conexao();
			$query		= "select * from pesq_formulario where pesquisa = " . $this->id . " order by id";
			$resForm	= $conexao->pesquisa($query);
			echo '<li class="list-group-item">
					<div class="btn-group btn-group-justified">
						<a href="javascript:novoFormulario();" class="btn btn-default"> <i class="fa fa-plus"></i></a>						
					</div>
				  </li>';
			while ($rowForm	= mysqli_fetch_assoc($resForm)){
				echo '	<li class="list-group-item">
							<a href="javascript:selecionaFormulario('.$rowForm["id"].');">
								'.$rowForm["id"].'- '. utf8_encode($rowForm["nome"]) . '
							</a>
							<a href="javascript:deletaForm('.$rowForm["id"].');"><i class="fa fa fa-minus float-right" alt="Excluir"></i></a>
						</li>';		
			}				
		}
		function cadastraFormulario($nome){
			if($nome==""){echo "ERRO";}else{
				$id = $_GET['pesquisaid'];
				$query = "insert into pesq_formulario (pesquisa, nome) values ('$id', '".utf8_decode($nome)."');";
				$conexao = new Conexao();
				$conexao->pesquisa($query);
				echo "OK";
			}
		}
		function deletaFormulario($id){
			$conexao = new Conexao();
			$query = "select pesquisa from pesq_formulario where id  = " . $id;
			$resPesq = $conexao->pesquisa($query);
			$rowPesq = mysqli_fetch_assoc($resPesq);
			$pesquisa = $rowPesq['pesquisa'];
			$query = "delete from pesq_formulario where id = " . $id;			
			$conexao->pesquisa($query);
			echo $pesquisa;			
		}
		function cadastra($nome){
			$pesquisaId = $_GET['pesquisaid'];
			if(nome!=""){
				$conexao 	= new Conexao();
				$query 		= "insert into pesq_pesquisa (nome) values ('".utf8_decode($nome)."');";
				$conexao->pesquisa($query);
				echo "OK";
			}else{echo "ERRO";}
		}
		function deletaPesquisa(){
			$conexao = new Conexao();
			$query = "delete from pesq_pesquisa where id = " . $this->id;
			echo $query;
			$conexao->pesquisa($query);
			echo "OK";
			
		}
		function vinculaPesquisa($varCentro){
			$idCentro = $varCentro;
			
			$conexao 	= new Conexao();
			$query		= "select * from pesq_formulario where pesquisa = " . $this->id . " order by id";
			$resForm	= $conexao->pesquisa($query);
			$link 		= rand(156,58574) . rand(8889,26699887) . "Zjg" . rand(235,98989965574);
			while($rowForm = mysqli_fetch_assoc($resForm)){	//Listar formulários da pesquisa			
				$query = "insert into pesq_centro_formulario (centro, formulario, link) values ('".$idCentro."', '".$rowForm['id']."', '$link');";
				$conexao->pesquisa($query);
				$query = "select id from pesq_centro_formulario where centro = '$idCentro' and formulario = '".$rowForm['id']."'";
				$resF = $conexao->pesquisa($query);
				$rowF = mysqli_fetch_assoc($resF);
				$idFormulario = $rowF['id'];
				
				//Campos
				$query = "select * from pesq_campos where formulario = " . $rowForm['id'];
				$resC  =  $conexao->pesquisa($query);
				while($rowC = mysqli_fetch_assoc($resC)){
					$idCampo = $rowC['id'];
					$query = "insert into pesq_centro_campo (formulario, campo) values ('$idFormulario', '$idCampo');";
					$conexao->pesquisa($query);
				}
			}
		}
		function retornaLink($centro, $pesquisa){
			$query = "select 	
						DISTINCT(pesq_centro_formulario.link) as link
						from pesq_centro_formulario
							inner join pesq_formulario on pesq_centro_formulario.formulario = pesq_formulario.id 
							inner join pesq_pesquisa on pesq_formulario.pesquisa = pesq_pesquisa.id
							where pesq_centro_formulario.centro = ".$centro."
								and pesq_pesquisa.id = ".$pesquisa."
					";
			$conexao = new Conexao();			
			$resP = $conexao->pesquisa($query);
			$rowP = mysqli_fetch_assoc($resP);
			return $rowP['link'];
		}
		function listaPesquisas($varCentro){
			//include "conexao.class.php";
			$conexao = new Conexao();			
			$query = "SELECT
						distinct pesq_pesquisa.id,
                        pesq_pesquisa.nome						
						from pesq_centro_formulario 
							inner join pesq_formulario on pesq_centro_formulario.formulario = pesq_formulario.id
                            inner join pesq_pesquisa on pesq_formulario.pesquisa = pesq_pesquisa.id
								where pesq_centro_formulario.centro = " . $varCentro;
			$resF  = $conexao->pesquisa($query);			
			while($rowF = mysqli_fetch_assoc($resF)){
				?>
					<tr class="lista_formularios" 
						onclick="" 
						style="cursor: pointer;font-family: 'Abel';font-size: 22px;">																					
						<td class="lista_formularios">							
							<?php   								 
								echo utf8_encode($rowF['nome']);
							?>
						</td>
						<td>
							<center><a href="javascript:selecionaPesq(<?=$rowF['id'];?>, <?=$varCentro;?>);"><i class="fa fa-arrow-circle-right" aria-hidden="true" alt="Visualizar perguntas/respostas"></i></a> | 
							<a href="javascript:enviaEmail(<?=$rowF['id'];?>, <?=$varCentro;?>);"><i class="fa fa-envelope-open-o" aria-hidden="true" alt="Enviar pesquisa por email"></i></a></center>
						</td>
					</tr>
				<?php			
			}
		}
		function listaFormularios($varFormulario, $varCentro){
			//include "conexao.class.php";
			$conexao = new Conexao();			
			$query = "select
						pesq_centro_formulario.id,
						pesq_formulario.nome
						from pesq_centro_formulario
							inner join pesq_formulario on pesq_centro_formulario.formulario = pesq_formulario.id
							where 
									pesq_centro_formulario.centro = " . $varCentro . "
											order by pesq_centro_formulario.id";
			$resF  = $conexao->pesquisa($query);			
			while($rowF = mysqli_fetch_assoc($resF)){
				?>
					<tr class="lista_formularios" 
						onclick="" 
						style="cursor: pointer;font-family: 'Abel';font-size: 22px;">																					
						<td class="lista_formularios">							
							<?php   								 
								echo utf8_encode($rowF['nome']);
							?>
						</td>
						<td>
							<center><a href="javascript:selecionaForm(<?=$rowF['id'];?>);"><i class="fa fa-arrow-circle-right" aria-hidden="true" alt="Visualizar perguntas/respostas"></i></a>
						</td>
					</tr>
				<?php			
			}
		}
		function listaCampos($idFormulario){
			$query = "select
				pesq_centro_campo.id, 
				pesq_centro_campo.valor,
				pesq_campos.id as campoId
					from pesq_centro_campo
						inner join pesq_campos on pesq_centro_campo.campo = pesq_campos.id
						where pesq_centro_campo.formulario = " . $idFormulario;
			$conexao 	= new Conexao();
			$resC		= $conexao->pesquisa($query);
			echo utf8_encode("
				<table width='100%' class='table table-striped' style='font-family: Abel;font-size: 22px;'>
					<thead>
						<th class='tabela-head' width='10%'>Ítem</th>																																									
						<th class='tabela-head' ><center>Valor</center></th>																																																												
						<th class='tabela-head' width='10%'><center></center></th>
					</thead>
				<tbody>");
			while($rowC = mysqli_fetch_assoc($resC)){
				$varCampo = new Campo($rowC['campoId']);
				$varCampo->montaPesquisaHtml($rowC['valor']);
			}
			echo "</tbody>
				  </table>";				
		}
	}
	
