<?	
	class Centro{
		public $id;		
		public $nome;
		public $cidade;
		public $coordenador;
		public $email;		
		
		function __construct($id){
			$conexao 	= new Conexao();
			$query 	 	= "select * from pesq_centro where id = " . $id;
			$resCentro	= $conexao->pesquisa($query);
			$rowCentro	= mysqli_fetch_assoc($resCentro);
			$this->nome = $rowCentro['nome'];
			$this->cidade = $rowCentro['cidade'];
			$this->coordenador = $rowCentro['coordenador'];
			$this->email = $rowCentro['email'];			
		}
		function cadastra(){
			$query = "insert into pesq_centro (nome, cidade, coordenador, email) values(
				'".$this->nome."',
				'".$this->cidade."',
				'".$this->coordenador."',
				'".$this->email."'
			)";
			$conexao = new Conexao();
			$conexao->pesquisa($query);
			echo $query;
		}
		function listaCentros(){
			$conexao 	= new Conexao();
			$query		=	"select * from pesq_centro order by nome";
			$resCentro	=	$conexao->pesquisa($query);
			while($rowCentro = mysqli_fetch_assoc($resCentro)){
				?>
					<tr>
						<td><center><?=$rowCentro['id'];?></center></td>
						<td><?=$rowCentro['nome'];?></td>
						<td><?=$rowCentro['cidade'];?></td>
						<td><?=$rowCentro['coordenador'];?></td>
						<td><?=$rowCentro['email'];?></td>
						<td><a href="pesq_centro_pesquisas.php?id=<?=$rowCentro['id'];?>"><center><i class="fa fa-search" aria-hidden="true"></i></center></a></td>
					</tr>
				<?
			}
		}

	}	