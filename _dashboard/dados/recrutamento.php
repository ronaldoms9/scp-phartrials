<?php
    session_start();
    include_once '../class/conexao.class.php';
    include_once '../class/projeto.class.php';
    $varProjeto = new Projeto(249);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title> Dashboard</title>
	<link rel="stylesheet" href="css/vendors_css.css">
	<link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Responsive.js"></script>

  </head>

<body class="layout-top-nav light-skin theme-primary ">
<div class="wrapper"><br>
  <div class="content-wrapper">
	  <div class="container">
		<section class="content">
			<div class="row">

                <!-- Aqui vou começar os quadrados de informações -->
                <!-- Total de participantes triados -->
                <div class="col-xl-2">
					<a href="#" class="box">
						<div class="box-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="text-dark fw-700 h2 mb-2 mt-5"><?= $varProjeto->getTotalTriados(); ?></div>
									<div class="fs-16">Triado(s) (TCLE)</div>
								</div>
							</div>
						</div>
					</a>
				</div>
                <!-- Total de participantes Selecionados -->
                <div class="col-xl-2">
					<a href="#" class="box">
						<div class="box-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="text-dark fw-700 h2 mb-2 mt-5"><?= $varProjeto->getTotalEmSelecao(); ?></div>
									<div class="fs-16">Em seleção</div>
								</div>
							</div>
						</div>
					</a>
				</div>
                <!-- Total de participantes falha de seleção -->
                <div class="col-xl-2">
					<a href="#" class="box">
						<div class="box-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="text-dark fw-700 h2 mb-2 mt-5"><?= $varProjeto->getTotalNaoSelecionados(); ?></div>
									<div class="fs-16">Falha de seleção</div>
								</div>
							</div>
						</div>
					</a>
				</div>
                <!-- Total de participantes selecionado e randomizado -->
                <div class="col-xl-2">
					<a href="#" class="box">
						<div class="box-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="text-dark fw-700 h2 mb-2 mt-5"><?= $varProjeto->getTotalSelecionados(); ?></div>
									<div class="fs-16">Selecionados</div>
								</div>
							</div>
						</div>
					</a>
				</div>
                <!-- Total de participantes descontinuados -->
                <div class="col-xl-2">
					<a href="#" class="box">
						<div class="box-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="text-dark fw-700 h2 mb-2 mt-5"><?= $varProjeto->getTotalDescontinuados(); ?></div>
									<div class="fs-16">Descontinuados</div>
								</div>
							</div>
						</div>
					</a>
				</div>
                <!-- Total de participantes Finalizados-->
                <div class="col-xl-2">
					<a href="#" class="box">
						<div class="box-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="text-dark fw-700 h2 mb-2 mt-5"><?= $varProjeto->getTotalFinalizados(); ?></div>
									<div class="fs-16">Finalizados</div>
								</div>
							</div>
						</div>
					</a>
				</div>


				<div class="col-xl-5 col-12">
					<div class="box">
						<div class="box-header">
							<h4 class="box-title">Recrutamento</h4>
						</div>
						<div class="box-body py-0">
							<div id="chart3" class="h-350 w-p100">
                            <div id="chartdiv" style="width: 100%; height: 100%;"></div>
                            </div>
						</div>
					</div>
				</div>
				<div class="col-xl-7 col-12">
					<div class="box">
						<div class="box-header">
							<h4 class="box-title">Participantes incluídos ao longo do tempo</h4>
						</div>
						<div class="box-body py-0">
							<div id="chart4" class="h-350 w-p100">
                                <div id="chartdivXsemana" style="width: 100%; height: 100%;"></div>
                            </div>
						</div>
					</div>
				</div>

		</section>
	  </div>
  </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>


    $(document).ready(function() {
        am5.ready(function() {
            var root = am5.Root.new("chartdiv");
            root.setThemes([
            am5themes_Animated.new(root)
            ]);
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: false,
                panY: false,
                paddingLeft: 0,
                wheelX: "panX",
                wheelY: "zoomX",
                layout: root.verticalLayout
            }));
            var legend = chart.children.push(
                am5.Legend.new(root, {
                    centerX: am5.p50,
                    x: am5.p50
                })
            );

            var data =[
                <?php
                    $varConexao = new Conexao();
                    $query = "select
                                    id,
                                    apelido
                                        from cfg_centro";
                    if($_SESSION['centro']!=40){
                        $query .= " where id = ".$_SESSION['centro'];
                    }
                    $query .= "                    order by numero";
                    $resCentro = $varConexao->pesquisa($query);
                    while($rowCentro = mysqli_fetch_assoc($resCentro)){
                        $recrutado = $varProjeto->getTotalTriados($rowCentro['id']);
                        $selecionado = $varProjeto->getTotalSelecionados($rowCentro['id']);
                        echo "{\"centro\": \"".$rowCentro['apelido']."\", \"recrutados\": ".$recrutado.", \"selecionados\": ".$selecionado."},";
                    }
                ?>
            ]

            var xRenderer = am5xy.AxisRendererX.new(root, {
                cellStartLocation: 0.1,
                cellEndLocation: 0.9,
                minorGridEnabled: true
            })
            var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: "centro",
                renderer: xRenderer,
                tooltip: am5.Tooltip.new(root, {})
            }));
            xRenderer.grid.template.setAll({
                location: 1
            })
            xAxis.data.setAll(data);
            var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                renderer: am5xy.AxisRendererY.new(root, {
                    strokeOpacity: 0.1
                })
            }));
            function makeSeries(name, fieldName) {
            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: name,
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: fieldName,
                categoryXField: "centro"
            }));

            series.columns.template.setAll({
                tooltipText: "{name}, {categoryX}:{valueY}",
                width: am5.percent(90),
                tooltipY: 0,
                strokeOpacity: 0
            });

            series.data.setAll(data);
            series.appear();
            series.bullets.push(function () {
                return am5.Bullet.new(root, {
                locationY: 0,
                sprite: am5.Label.new(root, {
                    text: "{valueY}",
                    fill: root.interfaceColors.get("alternativeText"),
                    centerY: 0,
                    centerX: am5.p50,
                    populateText: true
                })
                });
            });
            legend.data.push(series);
            }

            makeSeries("Recrutados", "recrutados");
            makeSeries("Selecionados", "selecionados");

            chart.appear(1000, 100);
        });


    });
</script>

<script>
    am5.ready(function() {
        var root = am5.Root.new("chartdivXsemana");
        const myTheme = am5.Theme.new(root);
        myTheme.rule("AxisLabel", ["minor"]).setAll({
            dy:1
        });
        root.setThemes([
            am5themes_Animated.new(root),
            myTheme,
            am5themes_Responsive.new(root)
        ]);
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            panX: false,
            panY: false,
            wheelX: "panX",
            wheelY: "zoomX",
            paddingLeft:0
        }));
        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
            behavior: "zoomX"
        }));
        //cursor.lineY.set("visible", false);
        var date = new Date();
        date.setHours(0, 0, 0, 0);
        var value = 100;
        function generateData() {
            value = Math.round((Math.random() * 10 - 5) + value);
            am5.time.add(date, "day", 1);
            //console.log(date.getTime());
            return {
                date: date.getTime(),
                value: value
            };
        }
        function generateDatas(count) {
            var data = [];
            for (var i = 0; i < count; ++i) {
                data.push(generateData());
            }
            return data;
        }
        function stringParaData(dataString) {
            console.log("Entrada->"+dataString);
            const [ano, mes, dia] = dataString.split('-');
            // Note que o mês no objeto Date é baseado em 0 (janeiro = 0)
            varData = new Date(ano, mes - 1, dia);
            varData.setHours(0, 0, 0, 0);
            return varData.getTime();
        }
        var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
            maxDeviation: 0,
            baseInterval: {
                timeUnit: "day",
                count: 1
            },
            renderer: am5xy.AxisRendererX.new(root, {
                minorGridEnabled:true,
                minorLabelsEnabled:true
            }),
            tooltip: am5.Tooltip.new(root, {})
        }));
        xAxis.set("minorDateFormats", {
            "day":"dd",
            "month":"MM"
        });
        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            renderer: am5xy.AxisRendererY.new(root, {})
        }));
        var series = chart.series.push(am5xy.ColumnSeries.new(root, {
            name: "Series",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            valueXField: "date",
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}"
            })
        }));
        series.columns.template.setAll({ strokeOpacity: 0 });
        <?php
            echo "var data = [";
            $query = "SELECT CONCAT(SUBSTR(data_hora, 1, 4), SUBSTR(data_hora, 6, 2), SUBSTR(data_hora, 9, 2)) as data_lis,
                            SUBSTR(data_hora ,1, 10) as data_inc,
                            count(*) as qtd
                                FROM crf_paciente_pre_rand
                                    group by 1,2
                                    order by 1";
            $resDatas = $varConexao->pesquisa($query);
            while($rowDatas = mysqli_fetch_assoc($resDatas)){
                echo "{\"date\": stringParaData('".$rowDatas['data_inc']."'), \"value\": ".$rowDatas['qtd']."},";
            }
            echo "];";
        ?>

        //var data = generateDatas(30);
        /*var data = [
            {
                "date": "2025-01-01",
                "value": 100
            },
            {
                "date": "2025-01-02",
                "value": 200
            },
            {
                "date": "2025-01-03",
                "value": 300
            }
        ];*/
        console.log(data);
        series.data.setAll(data);
        series.appear(1000);
        chart.appear(1000, 100);
    });


</script>

</body>
</html>
