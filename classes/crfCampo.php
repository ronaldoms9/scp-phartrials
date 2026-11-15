<?

	class crfCampo {
		public campoNome = "";
		public campoMonitorado = false;
		
		/*function __construct($nome) {
			$this->campoNome = $nome;
		}*/
		
		function setValor($atributo, $valor){
			$this->$atributo = $valor;
		}
		
		
	}

