<?php
    include_once "mensageiro.class.php";
    class EnvioWhats{
        public function __construct(){}
        public function enviar($paraNro, $titulo, $mensagem){
            $paraNro = "5519981552838";
            $varMensageiro = new Mensageiro();
            if($paraNro!=""){
                $telefones = explode(",",$paraNro);
                require_once(__DIR__."/../vendor/autoload.php");
                for($iTel=0;$iTel<count($telefones);$iTel++){
                    $client = new \GuzzleHttp\Client();
                    $client = new \GuzzleHttp\Client();
                    $texto 			= $titulo . ". " . $mensagem;
                    $varMensageiro->enviaWhats($telefones[$iTel], $texto);
                }
            }
        }
    }