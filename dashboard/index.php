<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Monitoria</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="css/monitoria.css" rel="stylesheet">
</head>
<body>
    <div id="loading" class="loading-overlay">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <p>Processando dados de monitoria...</p>
        </div>
    </div>

    <header class="dashboard-header">
        <div class="container-fluid px-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <h1>Dashboard de Monitoria</h1>
                    <p>Volume de visitas e tempo até revisão dos relatórios</p>
                </div>
                <div class="header-badge">
                    <span class="dot"></span>
                    <span id="info-atualizacao">Carregando...</span>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid px-4">
        <!-- KPIs -->
        <div class="row g-3 kpi-row">
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card">
                    <div class="kpi-icon indigo"><i class="bi bi-journal-medical"></i></div>
                    <div class="kpi-body">
                        <div class="kpi-label">Estudos</div>
                        <div class="kpi-value indigo" id="total-estudos">—</div>
                        <div class="kpi-desc">Estudos distintos com monitoria registrada</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card">
                    <div class="kpi-icon emerald"><i class="bi bi-clipboard2-check"></i></div>
                    <div class="kpi-body">
                        <div class="kpi-label">Monitorias</div>
                        <div class="kpi-value emerald" id="total-monitorias">—</div>
                        <div class="kpi-desc">Total de visitas (nro_visita + projeto)</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card">
                    <div class="kpi-icon sky"><i class="bi bi-people"></i></div>
                    <div class="kpi-body">
                        <div class="kpi-label">Monitoras</div>
                        <div class="kpi-value sky" id="total-monitoras">—</div>
                        <div class="kpi-desc">Profissionais com monitorias no período</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card">
                    <div class="kpi-icon amber"><i class="bi bi-clock-history"></i></div>
                    <div class="kpi-body">
                        <div class="kpi-label">Tempo médio</div>
                        <div class="kpi-value amber" id="media-dias">—</div>
                        <div class="kpi-desc"><span id="total-revisadas">—</span></div>
                    </div>
                </div>
            </div>
        </div>

        <main class="dashboard-main">
            <!-- Monitorias por monitora -->
            <div class="section-block">
                <div class="chart-panel">
                    <div class="chart-panel-header">
                        <div>
                            <h2 class="section-title">Monitorias por monitora</h2>
                            <p class="section-subtitle">Distribuição de visitas por profissional</p>
                        </div>
                        <span class="panel-tag">Volume</span>
                    </div>
                    <div class="chart-panel-body">
                        <div id="chart-monitoras-qtd" class="chart-container sm"></div>
                    </div>
                </div>
            </div>

            <!-- Tempo de revisão -->
            <div class="row g-3 section-block">
                <div class="col-lg-6">
                    <div class="chart-panel">
                        <div class="chart-panel-header">
                            <div>
                                <h2 class="section-title">Tempo médio por mês</h2>
                                <p class="section-subtitle">Dias entre a visita e rel_revisado_data</p>
                            </div>
                            <span class="panel-tag">Prazo</span>
                        </div>
                        <div class="chart-panel-body">
                            <div id="chart-tempo-mes" class="chart-container"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-panel">
                        <div class="chart-panel-header">
                            <div>
                                <h2 class="section-title">Tempo médio por monitora</h2>
                                <p class="section-subtitle">Média de dias até revisão por profissional</p>
                            </div>
                            <span class="panel-tag">Prazo</span>
                        </div>
                        <div class="chart-panel-body">
                            <div id="chart-tempo-monitora" class="chart-container"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Análise complementar -->
            <div class="row g-3">
                <div class="col-lg-5">
                    <div class="chart-panel">
                        <div class="chart-panel-header">
                            <div>
                                <h2 class="section-title">Faixas de prazo</h2>
                                <p class="section-subtitle">Distribuição do tempo até revisão</p>
                            </div>
                            <span class="panel-tag">Distribuição</span>
                        </div>
                        <div class="chart-panel-body">
                            <div id="chart-faixas" class="chart-container"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="chart-panel">
                        <div class="chart-panel-header">
                            <div>
                                <h2 class="section-title">Evolução temporal</h2>
                                <p class="section-subtitle">Dias até revisão ao longo do tempo — arraste para navegar</p>
                            </div>
                            <span class="panel-tag">Tendência</span>
                        </div>
                        <div class="chart-panel-body">
                            <div id="chart-serie" class="chart-container lg"></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="js/monitoria-charts.js"></script>
    <script>
        am5.ready(function () {
            $.getJSON('api.php')
                .done(function (dados) {
                    MonitoriaCharts.preencherKpis(dados);
                    MonitoriaCharts.criarGraficoMonitoriasPorMonitora(dados.monitorias_por_monitora);
                    MonitoriaCharts.criarGraficoMes(dados.tempo_medio_por_mes);
                    MonitoriaCharts.criarGraficoMonitora(dados.tempo_medio_por_monitora);
                    MonitoriaCharts.criarGraficoFaixas(dados.faixas_prazo);
                    MonitoriaCharts.criarGraficoSerie(dados.serie_temporal);
                    $('#info-atualizacao').text('Atualizado em ' + dados.gerado_em);
                    $('#loading').addClass('hidden');
                    setTimeout(function () { $('#loading').remove(); }, 500);
                })
                .fail(function (xhr) {
                    var msg = 'Erro ao carregar dados.';
                    if (xhr.responseJSON && xhr.responseJSON.erro) {
                        msg = xhr.responseJSON.erro;
                    }
                    $('#loading .loading-box').html(
                        '<p style="color:#fca5a5;margin-bottom:1rem">' + msg + '</p>' +
                        '<button onclick="location.reload()" class="btn btn-light btn-sm">Tentar novamente</button>'
                    );
                });
        });
    </script>
</body>
</html>
