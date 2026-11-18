<?php
// Log global de erros: grava em arquivo, sem exibir na tela
error_reporting(E_ALL);
ini_set('display_errors', 0);          // nunca mostrar ao usuario
ini_set('log_errors', 1);
ini_set('error_log', dirname(__DIR__) . '/logs/php-errors.log');

// Excecoes nao tratadas
set_exception_handler(function ($e) {
	error_log('Uncaught ' . get_class($e) . ': ' . $e->getMessage()
		. ' em ' . $e->getFile() . ':' . $e->getLine()
		. ' | URI: ' . ($_SERVER['REQUEST_URI'] ?? '-'));
	http_response_code(500);
});

// Erros fatais (E_ERROR, E_PARSE...) que o handler normal nao pega
register_shutdown_function(function () {
	$e = error_get_last();
	if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
		error_log("FATAL {$e['message']} em {$e['file']}:{$e['line']} | URI: " . ($_SERVER['REQUEST_URI'] ?? '-'));
	}
});
