<?php
    if(!isset($_SESSION)){session_start();}if($_SESSION['valido']<1){echo "<script>window.location.replace('expirado.php');</script>";};
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
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <style>
        #chartdiv {
        width: 100%;
        height: 500px;
    }
    </style>
  </head>

<body class="layout-top-nav light-skin theme-primary ">
<div class="wrapper"><br>
  <div class="content-wrapper">
	  <div class="container">
		<section class="content">
			<div class="row">

                <!-- Aqui vou começar os quadrados de informações -->
                <!-- Total de participantes triados -->
                <div class="col-xl-3">
					<a href="#" class="box">
						<div class="box-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="text-dark fw-700 h2 mb-2 mt-5"><?=$varProjeto->getTotalEventos();?></div>
									<div class="fs-16">Total de Eventos</div>
								</div>
							</div>
						</div>
					</a>
				</div>
                <div class="col-xl-3">
					<a href="#" class="box">
						<div class="box-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="text-dark fw-700 h2 mb-2 mt-5"><?=$varProjeto->getTotalEventosGraves(); ?></div>
									<div class="fs-16">Total de Eventos GRAVES</div>
								</div>
							</div>
						</div>
					</a>
				</div>
                <!-- Total de participantes Selecionados -->
                <div class="col-xl-3">
					<a href="#" class="box">
						<div class="box-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="text-dark fw-700 h2 mb-2 mt-5"><?=$varProjeto->getTotalCampoPorNome("EA#_DESFECHO","");?></div>
									<div class="fs-16">Em andamento</div>
								</div>
							</div>
						</div>
					</a>
				</div>
                <!-- Total de participantes falha de seleção -->
                <div class="col-xl-3">
					<a href="#" class="box">
						<div class="box-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="text-dark fw-700 h2 mb-2 mt-5"><?= $varProjeto->getTotalCampoPorNome("EA#_DESFECHO","1 - Fatal/Óbito")+$varProjeto->getTotalCampoPorNome("EA#_DESFECHO","2 - Não recuperado / não resolvido")+$varProjeto->getTotalCampoPorNome("EA#_DESFECHO","3 - Recuperado / Resolvido")+$varProjeto->getTotalCampoPorNome("EA#_DESFECHO","4 - Recuperado / Resolvido com sequelas")+$varProjeto->getTotalCampoPorNome("EA#_DESFECHO","5 - Recuperando / Resolvendo")+$varProjeto->getTotalCampoPorNome("EA#_DESFECHO","6 - Desconhecido"); ?></div>
									<div class="fs-16">Finalizados</div>
								</div>
							</div>
						</div>
					</a>
				</div>
				<div class="col-xl-5 col-12">
					<div class="box">
						<div class="box-header">
							<h4 class="box-title">Eventos x Status</h4>
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
							<h4 class="box-title">Eventos x Centro</h4>
						</div>
						<div class="box-body py-0">
							<div id="chart4" class="h-350 w-p100">
                                <div id="chartPorCentro" style="width: 100%; height: 100%;"></div>
                            </div>
						</div>
					</div>
				</div>

		</section>
	  </div>
  </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    am5.ready(function() {
    var root = am5.Root.new("chartdiv");
    root.setThemes([
        am5themes_Animated.new(root)
    ]);
    var chart = root.container.children.push(
        am5percent.PieChart.new(root, {
        layout: root.verticalLayout
        })
    );
    var series = chart.series.push(
        am5percent.PieSeries.new(root, {
            name: "Série",
            categoryField: "category",
            valueField: "value"
        })
    );
    series.states.create("hidden", {
    endAngle: -90
    });
    series.labels.template.set("text", "{value}"); // Mostra o valor bruto
    series.data.setAll([{
        category: "Em andamento",
        value: <?=$varProjeto->getTotalCampoPorNome("EA#_EM_ANDAMENTO","1 - Sim")+$varProjeto->getTotalCampoPorNome("EAG_EV_DAT_AND","1 - Sim")+$varProjeto->getTotalCampoPorNome("EA#_EM_ANDAMENTO","")+$varProjeto->getTotalCampoPorNome("EAG_EV_DAT_AND",""); ?>
    }, {
        category: "Finalizados",
        value: <?=$varProjeto->getTotalCampoPorNome("EA#_EM_ANDAMENTO","2 - Não")+$varProjeto->getTotalCampoPorNome("EAG_EV_DAT_AND","2 - Não"); ?>
    }]);
    series.appear(1000, 1);
    var legend = chart.children.push(
        am5.Legend.new(root, {
        centerX: am5.percent(50),
        x: am5.percent(50),
        layout: root.horizontalLayout
        })
    );
    legend.data.setAll(series.dataItems);
    }); // end am5.ready()
    // Eventos x Centro
    am5.ready(function() {
        var root = am5.Root.new("chartPorCentro");
        root.setThemes([
            am5themes_Animated.new(root)
        ]);
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            panX: true,
            panY: true,
            wheelX: "panX",
            wheelY: "zoomX",
            pinchZoomX: true,
            paddingLeft:0,
            paddingRight:1
        }));
        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
        var xRenderer = am5xy.AxisRendererX.new(root, {
            minGridDistance: 30,
            minorGridEnabled: true
        });
        xRenderer.labels.template.setAll({
            rotation: -90,
            centerY: am5.p50,
            centerX: am5.p100,
            paddingRight: 15
        });
        xRenderer.grid.template.setAll({
            location: 1
        })
        var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
            maxDeviation: 0.3,
            categoryField: "centro",
            renderer: xRenderer,
            tooltip: am5.Tooltip.new(root, {})
        }));
        var yRenderer = am5xy.AxisRendererY.new(root, {
            strokeOpacity: 0.1
        })
        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            maxDeviation: 0.3,
            renderer: yRenderer
        }));
        var series = chart.series.push(am5xy.ColumnSeries.new(root, {
        name: "Queries",
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: "value",
        sequencedInterpolation: true,
        categoryXField: "centro",
        tooltip: am5.Tooltip.new(root, {
            labelText: "{valueY}"
        })
        }));

        series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
        series.columns.template.adapters.add("fill", function (fill, target) {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
        });

        series.columns.template.adapters.add("stroke", function (stroke, target) {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
        });


        var data = [
        <?php
            $query = "select id,
                        apelido
                        from cfg_centro
                        where id > 0";
            if($_SESSION['centro']!="40"){
                $query .= " and id = ".$_SESSION['centro'];
            }
            $query .= " order by numero";
            $res = $conexao->pesquisa($query);
            while($row = mysqli_fetch_assoc($res)){
                $totalEventos = intval($varProjeto->getTotalEventos($row['id']))+intval($varProjeto->getTotalEventosGraves($row['id']));
                //$totalEventos = intval($varProjeto->getTotalEventos($row['id']));
                echo "{centro: '".$row['apelido']."', value: ".$totalEventos."},\n";
            }
        ?>
        ];
        xAxis.data.setAll(data);
        series.data.setAll(data);
        series.appear(1000);
        chart.appear(1000, 100);
        }); // end am5.ready()
</script>
</body>
</html>
