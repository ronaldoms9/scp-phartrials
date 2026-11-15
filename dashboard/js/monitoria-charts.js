(function () {
    'use strict';

    const PALETTE = [
        0x6366f1, 0x8b5cf6, 0x06b6d4, 0x10b981,
        0xf59e0b, 0xef4444, 0xec4899, 0x14b8a6
    ];

    function fmtMes(nome) {
        const parts = nome.split('-');
        if (parts.length !== 2) return nome;
        const meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        const m = parseInt(parts[1], 10) - 1;
        return (meses[m] || parts[1]) + '/' + parts[0].slice(2);
    }

    function abreviarNome(nome) {
        const partes = nome.trim().split(/\s+/);
        if (partes.length <= 2) return nome;
        return partes[0] + ' ' + partes[partes.length - 1];
    }

    function setupRoot(id) {
        const root = am5.Root.new(id);
        root.setThemes([am5themes_Animated.new(root)]);
        root.numberFormatter.set('numberFormat', '#,###.##');
        return root;
    }

    function styledTooltip(root) {
        const tooltip = am5.Tooltip.new(root, {
            getFillFromSprite: false,
            getStrokeFromSprite: false,
            autoTextColor: false
        });
        tooltip.get('background').setAll({
            fill: am5.color(0x1e1b4b),
            fillOpacity: 0.92,
            stroke: am5.color(0x6366f1),
            strokeOpacity: 0.3,
            cornerRadius: 8
        });
        tooltip.label.setAll({
            fill: am5.color(0xffffff),
            fontSize: 13,
            fontWeight: '500'
        });
        return tooltip;
    }

    function styledGrid(renderer) {
        renderer.grid.template.setAll({
            stroke: am5.color(0xe2e8f0),
            strokeOpacity: 0.8
        });
        renderer.labels.template.setAll({
            fill: am5.color(0x64748b),
            fontSize: 12
        });
    }

    window.MonitoriaCharts = {
        preencherKpis: function (dados) {
            $('#total-estudos').text(dados.totalizadores.estudos);
            $('#total-monitorias').text(dados.totalizadores.monitorias);
            $('#total-monitoras').text(dados.totalizadores.monitoras);
            $('#media-dias').text(dados.totalizadores.media_dias_revisao + ' dias');
            $('#total-revisadas').text(dados.totalizadores.monitorias_revisadas + ' monitorias revisadas');
        },

        criarGraficoMonitoriasPorMonitora: function (dados) {
            const limit = Math.min(dados.length, 12);
            const el = document.getElementById('chart-monitoras-qtd');
            el.style.height = Math.max(280, limit * 48) + 'px';

            const root = setupRoot('chart-monitoras-qtd');
            root.container.set('layout', root.verticalLayout);

            const chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: false,
                    panY: false,
                    wheelX: 'none',
                    wheelY: 'none',
                    paddingLeft: 0
                })
            );

            const yRenderer = am5xy.AxisRendererY.new(root, {
                inversed: true,
                cellStartLocation: 0.15,
                cellEndLocation: 0.85,
                minGridDistance: 20
            });
            styledGrid(yRenderer);

            const yAxis = chart.yAxes.push(
                am5xy.CategoryAxis.new(root, {
                    categoryField: 'nome',
                    renderer: yRenderer
                })
            );

            const xRenderer = am5xy.AxisRendererX.new(root, {});
            styledGrid(xRenderer);
            xRenderer.grid.template.set('visible', false);

            const xAxis = chart.xAxes.push(
                am5xy.ValueAxis.new(root, {
                    renderer: xRenderer,
                    min: 0
                })
            );

            const tooltip = styledTooltip(root);
            tooltip.label.set('text', '{categoryY}\n{valueX} monitorias');

            const series = chart.series.push(
                am5xy.ColumnSeries.new(root, {
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueXField: 'valor',
                    categoryYField: 'nome',
                    tooltip: tooltip
                })
            );

            series.columns.template.setAll({
                height: am5.percent(70),
                cornerRadiusTR: 6,
                cornerRadiusBR: 6,
                strokeOpacity: 0
            });

            series.columns.template.adapters.add('fill', function (fill, target) {
                const idx = series.dataItems.indexOf(target.dataItem);
                return am5.color(PALETTE[idx % PALETTE.length]);
            });

            const chartData = dados.slice(0, 12).map(function (item) {
                return { nome: abreviarNome(item.nome), valor: item.valor };
            });

            yAxis.data.setAll(chartData);
            series.data.setAll(chartData);
            series.appear(1000);
            chart.appear(1000, 100);
        },

        criarGraficoMes: function (dados) {
            const root = setupRoot('chart-tempo-mes');

            const chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: false,
                    panY: false,
                    wheelX: 'none',
                    wheelY: 'none'
                })
            );

            const xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 40 });
            styledGrid(xRenderer);

            const xAxis = chart.xAxes.push(
                am5xy.CategoryAxis.new(root, {
                    categoryField: 'label',
                    renderer: xRenderer
                })
            );

            const yRenderer = am5xy.AxisRendererY.new(root, {});
            styledGrid(yRenderer);

            const yAxis = chart.yAxes.push(
                am5xy.ValueAxis.new(root, {
                    renderer: yRenderer,
                    min: 0
                })
            );

            const series = chart.series.push(
                am5xy.ColumnSeries.new(root, {
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: 'media',
                    categoryXField: 'label',
                    tooltip: styledTooltip(root)
                })
            );

            series.columns.template.setAll({
                cornerRadiusTL: 8,
                cornerRadiusTR: 8,
                strokeOpacity: 0,
                fillGradient: am5.LinearGradient.new(root, {
                    stops: [
                        { color: am5.color(0x818cf8) },
                        { color: am5.color(0x4f46e5) }
                    ],
                    rotation: 90
                })
            });

            series.get('tooltip').label.set('text', '{categoryX}\n{valueY} dias em média\n({qtd} monitorias)');

            const chartData = dados.map(function (item) {
                return {
                    label: fmtMes(item.nome),
                    media: item.media,
                    qtd: item.qtd,
                    nome: item.nome
                };
            });

            xAxis.data.setAll(chartData);
            series.data.setAll(chartData);

            series.appear(1000);
            chart.appear(1000, 100);
        },

        criarGraficoMonitora: function (dados) {
            const root = setupRoot('chart-tempo-monitora');

            const chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: false,
                    panY: false,
                    wheelX: 'none',
                    wheelY: 'none',
                    layout: root.verticalLayout
                })
            );

            const xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 30 });
            styledGrid(xRenderer);
            xRenderer.labels.template.setAll({
                rotation: -40,
                centerY: am5.p50,
                centerX: am5.p100,
                fontSize: 11
            });

            const xAxis = chart.xAxes.push(
                am5xy.CategoryAxis.new(root, {
                    categoryField: 'nome',
                    renderer: xRenderer
                })
            );

            const yRenderer = am5xy.AxisRendererY.new(root, {});
            styledGrid(yRenderer);

            const yAxis = chart.yAxes.push(
                am5xy.ValueAxis.new(root, {
                    renderer: yRenderer,
                    min: 0
                })
            );

            const series = chart.series.push(
                am5xy.ColumnSeries.new(root, {
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: 'media',
                    categoryXField: 'nome',
                    tooltip: styledTooltip(root)
                })
            );

            series.columns.template.setAll({
                cornerRadiusTL: 8,
                cornerRadiusTR: 8,
                strokeOpacity: 0,
                fillGradient: am5.LinearGradient.new(root, {
                    stops: [
                        { color: am5.color(0x34d399) },
                        { color: am5.color(0x059669) }
                    ],
                    rotation: 90
                })
            });

            series.get('tooltip').label.set('text', '{categoryX}\n{valueY} dias em média\n({qtd} monitorias)');

            const chartData = dados.map(function (item) {
                return {
                    nome: abreviarNome(item.nome),
                    media: item.media,
                    qtd: item.qtd
                };
            });

            xAxis.data.setAll(chartData);
            series.data.setAll(chartData);
            series.appear(1000);
            chart.appear(1000, 100);
        },

        criarGraficoFaixas: function (dados) {
            const root = setupRoot('chart-faixas');

            const chart = root.container.children.push(
                am5percent.PieChart.new(root, {
                    innerRadius: am5.percent(55),
                    layout: root.horizontalLayout
                })
            );

            const series = chart.series.push(
                am5percent.PieSeries.new(root, {
                    valueField: 'valor',
                    categoryField: 'nome',
                    alignLabels: true
                })
            );

            series.slices.template.setAll({
                stroke: am5.color(0xffffff),
                strokeWidth: 3,
                cornerRadius: 6,
                tooltipText: '{category}: {value} ({valuePercentTotal.formatNumber("#.0")}%)'
            });

            series.slices.template.adapters.add('fill', function (fill, target) {
                const idx = series.dataItems.indexOf(target.dataItem);
                return am5.color(PALETTE[idx % PALETTE.length]);
            });

            series.labels.template.setAll({
                text: '{category}',
                fontSize: 12,
                fill: am5.color(0x475569)
            });

            series.ticks.template.set('visible', false);

            const legend = chart.children.push(
                am5.Legend.new(root, {
                    centerY: am5.p50,
                    y: am5.p50,
                    layout: root.verticalLayout,
                    marginLeft: 20
                })
            );
            legend.labels.template.setAll({ fontSize: 12, fill: am5.color(0x475569) });
            legend.valueLabels.template.setAll({ fontSize: 12, fill: am5.color(0x64748b) });
            legend.data.setAll(series.dataItems);

            series.data.setAll(dados);
            series.appear(1000, 100);
            chart.appear(1000, 100);
        },

        criarGraficoSerie: function (dados) {
            const root = setupRoot('chart-serie');

            const chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: true,
                    panY: false,
                    wheelX: 'panX',
                    wheelY: 'zoomX',
                    pinchZoomX: true
                })
            );

            const cursor = chart.set('cursor', am5xy.XYCursor.new(root, {
                behavior: 'zoomX'
            }));
            cursor.lineY.set('visible', false);

            const xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 60 });
            styledGrid(xRenderer);

            const xAxis = chart.xAxes.push(
                am5xy.DateAxis.new(root, {
                    baseInterval: { timeUnit: 'day', count: 1 },
                    renderer: xRenderer
                })
            );

            const yRenderer = am5xy.AxisRendererY.new(root, {});
            styledGrid(yRenderer);

            const yAxis = chart.yAxes.push(
                am5xy.ValueAxis.new(root, {
                    renderer: yRenderer,
                    min: 0
                })
            );

            const series = chart.series.push(
                am5xy.SmoothedXLineSeries.new(root, {
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: 'dias',
                    valueXField: 'timestamp',
                    stroke: am5.color(0x6366f1),
                    fill: am5.color(0x6366f1),
                    tooltip: styledTooltip(root)
                })
            );

            series.fills.template.setAll({
                fillOpacity: 0.15,
                visible: true
            });

            series.strokes.template.setAll({
                strokeWidth: 3
            });

            series.bullets.push(function () {
                return am5.Bullet.new(root, {
                    sprite: am5.Circle.new(root, {
                        radius: 4,
                        fill: am5.color(0x6366f1),
                        stroke: am5.color(0xffffff),
                        strokeWidth: 2
                    })
                });
            });

            series.get('tooltip').label.set('text', '{valueY} dias\n{monitora}');

            const chartData = dados.map(function (item) {
                return {
                    timestamp: new Date(item.data + 'T12:00:00').getTime(),
                    dias: item.dias,
                    monitora: abreviarNome(item.monitora)
                };
            });

            series.data.setAll(chartData);
            series.appear(1000);
            chart.appear(1000, 100);
        }
    };
})();
