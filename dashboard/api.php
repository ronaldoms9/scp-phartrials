<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/includes/CsvMonitoriaReader.php';

try {
    $csvPath = __DIR__ . '/pt_monitoria.csv';
    $cachePath = __DIR__ . '/cache/dados_monitoria.json';

    if (!file_exists($csvPath)) {
        http_response_code(404);
        echo json_encode(['erro' => 'Arquivo pt_monitoria.csv não encontrado.']);
        exit;
    }

    $reader = new CsvMonitoriaReader($csvPath, $cachePath);
    echo json_encode($reader->getDados(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['erro' => $e->getMessage()]);
}
