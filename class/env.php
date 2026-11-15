<?php
/**
 * Carrega variaveis do arquivo .env (raiz do projeto) uma unica vez.
 * Uso: env('DB_HOST'), env('DB_PORT', '3306')
 * Ordem de busca: .env  ->  variavel de ambiente real (getenv)  ->  $default
 */
if (!function_exists('env')) {
	function env($key, $default = null) {
		static $vars = null;
		if ($vars === null) {
			$vars = array();
			$file = dirname(__DIR__) . '/.env';
			if (is_readable($file)) {
				foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
					$line = trim($line);
					if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) { continue; }
					list($k, $v) = explode('=', $line, 2);
					$k = trim($k);
					$v = trim($v);
					$q = substr($v, 0, 1);
					if (strlen($v) >= 2 && ($q === '"' || $q === "'") && substr($v, -1) === $q) {
						$v = substr($v, 1, -1);
					}
					$vars[$k] = $v;
				}
			}
		}
		if (array_key_exists($key, $vars)) { return $vars[$key]; }
		$e = getenv($key);
		return ($e !== false) ? $e : $default;
	}
}
