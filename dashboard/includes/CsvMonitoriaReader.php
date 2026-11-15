<?php

class CsvMonitoriaReader
{
    private string $csvPath;
    private string $cachePath;

    public function __construct(string $csvPath, string $cachePath)
    {
        $this->csvPath = $csvPath;
        $this->cachePath = $cachePath;
    }

    public function getDados(): array
    {
        if ($this->cacheValido()) {
            $json = file_get_contents($this->cachePath);
            $dados = json_decode($json, true);
            if (is_array($dados)) {
                return $dados;
            }
        }

        $dados = $this->processarCsv();
        $this->salvarCache($dados);

        return $dados;
    }

    private function cacheValido(): bool
    {
        if (!file_exists($this->cachePath) || !file_exists($this->csvPath)) {
            return false;
        }

        return filemtime($this->cachePath) >= filemtime($this->csvPath);
    }

    private function salvarCache(array $dados): void
    {
        $dir = dirname($this->cachePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents(
            $this->cachePath,
            json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    private function processarCsv(): array
    {
        $monitorias = [];
        $projetos = [];
        $usuarios = [];

        $fp = fopen($this->csvPath, 'r');
        if ($fp === false) {
            throw new RuntimeException('Não foi possível abrir o arquivo CSV.');
        }

        $secao = null;
        $headers = [];

        while (($row = fgetcsv($fp)) !== false) {
            if ($row === [null] || count($row) === 0) {
                continue;
            }

            if (($row[0] ?? '') === 'id') {
                if ($this->isHeaderMonitoria($row)) {
                    $secao = 'monitoria';
                    $headers = $row;
                } elseif ($this->isHeaderProjeto($row)) {
                    $secao = 'projeto';
                    $headers = $row;
                } elseif ($this->isHeaderUsuario($row)) {
                    $secao = 'usuario';
                    $headers = $row;
                } else {
                    $secao = null;
                    $headers = [];
                }
                continue;
            }

            if ($secao === null || count($headers) === 0) {
                continue;
            }

            $registro = $this->mapearLinha($headers, $row);

            if ($secao === 'monitoria') {
                $monitorias[] = $registro;
            } elseif ($secao === 'projeto') {
                $projetos[$registro['id']] = $registro;
            } elseif ($secao === 'usuario') {
                $usuarios[$registro['id']] = $registro;
            }
        }

        fclose($fp);

        return $this->agregarDados($monitorias, $projetos, $usuarios);
    }

    private function isHeaderMonitoria(array $row): bool
    {
        return ($row[0] ?? '') === 'id'
            && ($row[1] ?? '') === 'ativo'
            && ($row[2] ?? '') === 'nro_visita';
    }

    private function isHeaderProjeto(array $row): bool
    {
        return ($row[0] ?? '') === 'id'
            && ($row[1] ?? '') === 'numero'
            && ($row[2] ?? '') === 'nome'
            && ($row[3] ?? '') === 'descricao'
            && ($row[4] ?? '') === 'patrocinador';
    }

    private function isHeaderUsuario(array $row): bool
    {
        return ($row[0] ?? '') === 'id'
            && ($row[1] ?? '') === 'usuario'
            && ($row[2] ?? '') === 'nome'
            && ($row[3] ?? '') === 'funcao';
    }

    private function mapearLinha(array $headers, array $row): array
    {
        $registro = [];
        $total = min(count($headers), count($row));

        for ($i = 0; $i < $total; $i++) {
            $registro[$headers[$i]] = $row[$i];
        }

        return $registro;
    }

    private function agregarDados(array $monitorias, array $projetos, array $usuarios): array
    {
        $estudos = [];
        $monitoriasPorMonitora = [];
        $tempoPorMes = [];
        $tempoPorMonitora = [];
        $faixasPrazo = [
            '0-2 dias' => 0,
            '3-5 dias' => 0,
            '6-10 dias' => 0,
            '11-20 dias' => 0,
            '21+ dias' => 0,
        ];
        $serieTemporal = [];

        $totalMonitorias = 0;
        $totalComRevisao = 0;
        $somaDias = 0;

        foreach ($monitorias as $mon) {
            $projetoId = trim((string) ($mon['projeto'] ?? ''));
            $monitoraId = trim((string) ($mon['monitora'] ?? ''));

            if ($projetoId === '' || $projetoId === '0') {
                continue;
            }

            $nomeEstudo = $projetos[$projetoId]['nome'] ?? ('Estudo #' . $projetoId);
            $monitoraNome = $usuarios[$monitoraId]['nome'] ?? ('Monitora #' . $monitoraId);

            $estudos[$projetoId] = $nomeEstudo;
            $totalMonitorias++;

            if (!isset($monitoriasPorMonitora[$monitoraNome])) {
                $monitoriasPorMonitora[$monitoraNome] = 0;
            }
            $monitoriasPorMonitora[$monitoraNome]++;

            $dataVisita = $this->parseData($mon['data'] ?? '');
            if ($dataVisita === null) {
                $dataVisita = $this->parseData($mon['data_caption'] ?? '');
            }

            $dataRevisao = $this->parseData($mon['rel_revisado_data'] ?? '');
            if ($dataVisita === null || $dataRevisao === null) {
                continue;
            }

            $dias = (int) floor(($dataRevisao->getTimestamp() - $dataVisita->getTimestamp()) / 86400);
            if ($dias < 0) {
                continue;
            }

            $totalComRevisao++;
            $somaDias += $dias;

            $mes = $dataVisita->format('Y-m');
            if (!isset($tempoPorMes[$mes])) {
                $tempoPorMes[$mes] = ['soma' => 0, 'qtd' => 0];
            }
            $tempoPorMes[$mes]['soma'] += $dias;
            $tempoPorMes[$mes]['qtd']++;

            if (!isset($tempoPorMonitora[$monitoraNome])) {
                $tempoPorMonitora[$monitoraNome] = ['soma' => 0, 'qtd' => 0];
            }
            $tempoPorMonitora[$monitoraNome]['soma'] += $dias;
            $tempoPorMonitora[$monitoraNome]['qtd']++;

            if ($dias <= 2) {
                $faixasPrazo['0-2 dias']++;
            } elseif ($dias <= 5) {
                $faixasPrazo['3-5 dias']++;
            } elseif ($dias <= 10) {
                $faixasPrazo['6-10 dias']++;
            } elseif ($dias <= 20) {
                $faixasPrazo['11-20 dias']++;
            } else {
                $faixasPrazo['21+ dias']++;
            }

            $serieTemporal[] = [
                'data' => $dataVisita->format('Y-m-d'),
                'dias' => $dias,
                'monitora' => $monitoraNome,
                'estudo' => $nomeEstudo,
                'visita' => trim((string) ($mon['nro_visita'] ?? '')),
            ];
        }

        ksort($tempoPorMes);
        usort($serieTemporal, static fn(array $a, array $b): int => strcmp($a['data'], $b['data']));

        arsort($monitoriasPorMonitora);

        $mediaGeral = $totalComRevisao > 0 ? round($somaDias / $totalComRevisao, 1) : 0;

        return [
            'totalizadores' => [
                'estudos' => count($estudos),
                'monitorias' => $totalMonitorias,
                'monitoras' => count($monitoriasPorMonitora),
                'media_dias_revisao' => $mediaGeral,
                'monitorias_revisadas' => $totalComRevisao,
            ],
            'monitorias_por_monitora' => $this->formatarLista($monitoriasPorMonitora),
            'tempo_medio_por_mes' => $this->formatarMedia($tempoPorMes),
            'tempo_medio_por_monitora' => $this->formatarMedia($tempoPorMonitora),
            'faixas_prazo' => $this->formatarLista($faixasPrazo),
            'serie_temporal' => $serieTemporal,
            'gerado_em' => date('d/m/Y H:i:s'),
        ];
    }

    private function formatarLista(array $valores): array
    {
        $lista = [];
        foreach ($valores as $nome => $qtd) {
            $lista[] = ['nome' => (string) $nome, 'valor' => (int) $qtd];
        }

        return $lista;
    }

    private function formatarMedia(array $grupos): array
    {
        $lista = [];
        foreach ($grupos as $nome => $info) {
            if ($info['qtd'] === 0) {
                continue;
            }
            $lista[] = [
                'nome' => (string) $nome,
                'media' => round($info['soma'] / $info['qtd'], 1),
                'qtd' => (int) $info['qtd'],
            ];
        }

        usort($lista, static fn(array $a, array $b): int => strcmp($a['nome'], $b['nome']));

        return $lista;
    }

    private function parseData(string $valor): ?DateTime
    {
        $valor = trim($valor);
        if ($valor === '' || strtoupper($valor) === 'NULL') {
            return null;
        }

        if (preg_match('/^\d{8}$/', $valor)) {
            $data = DateTime::createFromFormat('Ymd', $valor);
            return $data ?: null;
        }

        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})(?:\s+(\d{1,2}):(\d{2}))?/', $valor, $m)) {
            $dia = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            $mes = str_pad($m[2], 2, '0', STR_PAD_LEFT);
            $formato = isset($m[4]) ? 'd/m/Y H:i' : 'd/m/Y';
            $texto = isset($m[4])
                ? "{$dia}/{$mes}/{$m[3]} {$m[4]}:{$m[5]}"
                : "{$dia}/{$mes}/{$m[3]}";
            $data = DateTime::createFromFormat($formato, $texto);
            return $data ?: null;
        }

        return null;
    }
}
