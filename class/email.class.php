<?

	include_once __DIR__."/../funcoes.php";
	include_once __DIR__."/conexao.class.php";
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require __DIR__.'/../vendor/autoload.php';
	include_once __DIR__."/conexao.class.php";
	$varConexao = new Conexao();
	class EnvioEmail{
		public $deMail;
		public $deNome;
		public $paraMail;
		public $paraNome;
		public $copia;
		function __construct($deMail, $deNome, $paraMail, $paraNome, $copia){
				$this->deMail   = $deMail;
				$this->deNome   = $deNome;
				$this->paraMail = $paraMail;
				$this->paraNome = $paraNome;
				$this->copia    = $copia;

		}
		function enviar($titulo, $msg){

			$mail = new PHPMailer;
			$mail->Timeout       =   20; // set the timeout (seconds)
			$mail->IsSMTP();
			$mail->setLanguage("br");
			$mail->SMTPAuth = true;
			$mail->SMTPDebug = 0;
			$mail->SMTPOptions = array(
				'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					)
			);
			$mail->Port = "2525";
			$mail->Host = "mail.smtp2go.com";
			$mail->Username = "qsbsoft.com.br";
			$mail->Password = "oa2dTvtdc123";
			$mail->setFrom("envio@sistemadepesquisaclinica.com.br", formata_utf8_encode("[SPC] - Sistema de Pesquisa Clínica"));

			$varPara = $this->paraMail;
			$varPara = explode(";", $varPara);
			if(count($varPara)==1){
				$mail->addAddress($this->paraMail, $this->paraNome);
			}else{
				for($i=0;$i<count($varPara);$i++){
					//$mail->addBcc(trim($varPara[$i]), "ALERTA SPC");
					$mail->addCC(trim($varPara[$i]), "ALERTA SPC");
				}
			}
			//$varPara = "eduqsb@gmail.com";
			//$mail->addAddress($varPara, "Luis Bueno");
			if($this->paraMail!="eduqsb@gmail.com"){$mail->addBcc("eduqsb@gmail.com", "Luis Bueno");}

			$mail->Subject=	"[SPC-PT] - " . formata_utf8_encode($titulo);
			$mail->msgHTML($msg);
			$mail->isHTML(true);
			$mail->CharSet = 'UTF-8';
			$mail->send();
			unset($mail);
		}
	}

