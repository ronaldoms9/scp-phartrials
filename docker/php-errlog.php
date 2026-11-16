<?php
/**
 * SOMENTE PARA TESTES DE MIGRACAO (nao usar em producao).
 * O app chama error_reporting(~E_ALL), escondendo tudo. Um set_error_handler e invocado
 * mesmo assim, entao registramos warnings/deprecations/fatais em /tmp/php-errors.log.
 * Ativacao: auto_prepend_file=/var/www/html/docker/php-errlog.php
 */
(function () {
	$log = '/tmp/php-errors.log';
	$names = array(
		E_ERROR => 'E_ERROR', E_WARNING => 'E_WARNING', E_NOTICE => 'E_NOTICE',
		E_DEPRECATED => 'E_DEPRECATED', E_USER_ERROR => 'E_USER_ERROR',
		E_USER_WARNING => 'E_USER_WARNING', E_USER_NOTICE => 'E_USER_NOTICE',
		E_USER_DEPRECATED => 'E_USER_DEPRECATED', E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
		E_PARSE => 'E_PARSE', E_CORE_ERROR => 'E_CORE_ERROR', E_COMPILE_ERROR => 'E_COMPILE_ERROR',
	);
	$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '-';
	$w = function ($level, $msg, $file, $line) use ($log, $names, $uri) {
		$t = isset($names[$level]) ? $names[$level] : $level;
		@file_put_contents($log, "$t|$uri|$file:$line|$msg\n", FILE_APPEND);
	};
	set_error_handler(function ($no, $str, $file, $line) use ($w) {
		$w($no, $str, $file, $line);
		return true;
	});
	set_exception_handler(function ($e) use ($w) {
		$w(E_ERROR, 'Uncaught ' . get_class($e) . ': ' . $e->getMessage(), $e->getFile(), $e->getLine());
		http_response_code(500);
	});
	register_shutdown_function(function () use ($w) {
		$e = error_get_last();
		if ($e && in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR), true)) {
			$w($e['type'], $e['message'], $e['file'], $e['line']);
		}
	});
})();
