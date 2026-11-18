<?php
    include_once __DIR__."/../class/conexao.class.php";
    $varConexao = new Conexao();

            $idProjeto = 464;

            $arrFases = array("Inicial", "Follow-up", "Dieta", "Pós");


			$query = "select * from agenda_check_fase where projeto = 0 order by id";
			$resFases = $this->conexao->pesquisa($query);
			while($rowFases = mysqli_fetch_assoc($resFases)){

				$query = "select * from agenda_check_fase where projeto = ".$idProjeto." and item = '".$rowFases['item']."'";
				$resFaseNova = $this->conexao->pesquisa($query);
				$rowFaseNova = mysqli_fetch_assoc($resFaseNova);
				$periodoNovo = $rowFaseNova['id'];

				$query = "select * from agenda_check where periodo = '".$rowFases['id']."' and projeto = '0'";
				echo $query . "<br>";
				$resFase = $this->conexao->pesquisa($query);
				if(mysqli_num_rows($resFase)>0){//Deu certo o add
					while($rowFase = mysqli_fetch_assoc($resFase)){
						$query = "insert into agenda_check (projeto, periodo, ordem, nome, label, mascara, opcoes, exporta, calculo, calculo_tipo, calculo_campos, valor_minimo, valor_maximo, valor, valor6) values (
							'".$idProjeto."',
							'".$periodoNovo."',
							'".$rowFase['ordem']."',
							'".$rowFase['nome']."',
							'".$rowFase['label']."',
							'".$rowFase['mascara']."',
							'".$rowFase['opcoes']."',
							'".$rowFase['exporta']."',
							'".$rowFase['calculo']."',
							'".$rowFase['calculo_tipo']."',
							'".$rowFase['calculo_campos']."',
							'".$rowFase['valor_minimo']."',
							'".$rowFase['valor_maximo']."',
							'',
							''
						);";
						echo $query."<br>";
						$this->conexao->pesquisa($query);
					}
				}
			}
			//Aqui já gerou. Vou arrumar o campo dos calculos
			//Aqui da Idade
			$query = "select id from agenda_check where projeto = ".$idProjeto." and nome = 'Idade'";
			$resIdade = $this->conexao->pesquisa($query);
			$rowIdade = mysqli_fetch_assoc($resIdade);
			$varIdade = $rowIdade['id'];
			$query = "select id from agenda_check where projeto = ".$idProjeto." and nome = 'Data da internação'";
			$resIdade = $this->conexao->pesquisa($query);
			$rowIdade = mysqli_fetch_assoc($resIdade);
			$varDataInternacao = $rowIdade['id'];
			$query = "select id from agenda_check where projeto = ".$idProjeto." and nome = 'Data de Nascimento'";
			$resIdade = $this->conexao->pesquisa($query);
			$rowIdade = mysqli_fetch_assoc($resIdade);
			$varDataNascimento = $rowIdade['id'];
			$query = "update agenda_check set calculo_campos = '".$varDataInternacao.",".$varDataNascimento."' where id = ".$varIdade;
			$this->conexao->pesquisa($query);
			//Aqui da IMC
			$query = "select id from agenda_check where projeto = ".$idProjeto." and nome = 'IMC'";
			$resIdade = $this->conexao->pesquisa($query);
			$rowIdade = mysqli_fetch_assoc($resIdade);
			$varIMC = $rowIdade['id'];
			$query = "select id from agenda_check where projeto = ".$idProjeto." and nome = 'Peso'";
			$resIdade = $this->conexao->pesquisa($query);
			$rowIdade = mysqli_fetch_assoc($resIdade);
			$varPeso = $rowIdade['id'];
			$query = "select id from agenda_check where projeto = ".$idProjeto." and nome = 'Altura'";
			$resIdade = $this->conexao->pesquisa($query);
			$rowIdade = mysqli_fetch_assoc($resIdade);
			$varAltura = $rowIdade['id'];
			$query = "update agenda_check set calculo_campos = '".$varAltura.",".$varPeso."' where id = ".$varIMC;
			$this->conexao->pesquisa($query);