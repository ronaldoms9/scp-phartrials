<?
	session_start();
	
	class Treinamento{
		public $id;
		public $nome;
		public $descricao;
		
		
		function __construct($id){
			if($id>0){
				$conexao = new Conexao();
				$query 	= "select * from tre_treinamentos where id = " . $id;
				$resTre = $conexao->pesquisa($query);
				$rowTre = mysqli_fetch_assoc($resTre);
				
				$this->id 			= $id;
				$this->nome 		= $rowTre['treinamento'];
				$this->descricao 	= $rowTre['descricao'];
			}
		}
		
		function exibeLista(){
			$conexao = new Conexao();
			$query 		= "select * from tre_treinamentos order by id";
			$resTreinamento 	= $conexao->pesquisa($query);
			echo '<li class="list-group-item">
					<div class="btn-group btn-group-justified">
						<a href="javascript:novoTreinamento();" class="btn btn-default"> <i class="fa fa-plus"></i></a>						
					</div>
				  </li>';
			while($rowTreinamento = mysqli_fetch_assoc($resTreinamento)){
				echo '<li class="list-group-item">
							<a href="javascript:selecionaTreinamento('.$rowTreinamento["id"].');">
								'.$rowTreinamento["id"].'- '. utf8_encode($rowTreinamento["treinamento"]) . '
							</a>
							<a href="javascript:deletaTreinamento('.$rowTreinamento["id"].');"><i class="fa fa fa-minus float-right" alt="Excluir"></i></a>
						</li>';				
			}
		}
		function exibeTelas(){
			$conexao = new Conexao();
			$query 		= "select * from trei_treinamentos_telas where pesquisa = ".$this->id." order by id";
			$resTreinamento 	= $conexao->pesquisa($query);
			echo '<li class="list-group-item">
					<div class="btn-group btn-group-justified">
						<a href="javascript:novoTreinamento();" class="btn btn-default"> <i class="fa fa-plus"></i></a>						
					</div>
				  </li>';
			while($rowTreinamento = mysqli_fetch_assoc($resTreinamento)){
				echo '<li class="list-group-item">
							<a href="javascript:selecionaTreinamento('.$rowTreinamento["id"].');">
								'.$rowTreinamento["id"].'- '. utf8_encode($rowTreinamento["nome"]) . '
							</a>
							<a href="javascript:deletaTreinamento('.$rowTreinamento["id"].');"><i class="fa fa fa-minus float-right" alt="Excluir"></i></a>
						</li>';				
			}
		}
	}
?>