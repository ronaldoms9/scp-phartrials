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
                <div class="col-xl-4">
					<a href="#" class="box">
						<div class="box-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="text-dark fw-700 h2 mb-2 mt-5"><?=$varProjeto->getCRFTotalCampos();?></div>
									<div class="fs-16">Total de Campos</div>
								</div>
							</div>
						</div>
					</a>
				</div>
                <div class="col-xl-4">
					<a href="#" class="box">
						<div class="box-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="text-dark fw-700 h2 mb-2 mt-5"><?=$varProjeto->getCRFTotalCamposMonitorados(); ?></div>
									<div class="fs-16">Monitorados</div>
								</div>
							</div>
						</div>
					</a>
				</div>
                <!-- Total de participantes Selecionados -->
                <div class="col-xl-4">
					<a href="#" class="box">
						<div class="box-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<div class="text-dark fw-700 h2 mb-2 mt-5"><?=$varProjeto->getCRFTotalCampos()-$varProjeto->getCRFTotalCamposMonitorados();?></div>
									<div class="fs-16">Não monitorados</div>
								</div>
							</div>
						</div>
					</a>
				</div>


				<div class="col-xl-5 col-12">
					<div class="box">
						<div class="box-header">
							<h4 class="box-title">Monitorados</h4>
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
							<h4 class="box-title">Campos x Centro</h4>
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
        category: "Não monitorados",
        value: <?=$varProjeto->getCRFTotalCampos()-$varProjeto->getCRFTotalCamposMonitorados(); ?>
    }, {
        category: "Monitorados",
        value: <?=$varProjeto->getCRFTotalCamposMonitorados(); ?>
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


        // Create root element
        // https://www.amcharts.com/docs/v5/getting-started/#Root_element
        var root = am5.Root.new("chartPorCentro");


        // Set themes
        // https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([
        am5themes_Animated.new(root)
        ]);


        // Create chart
        // https://www.amcharts.com/docs/v5/charts/xy-chart/
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
        panX: false,
        panY: false,
        paddingLeft: 0,
        wheelX: "panX",
        wheelY: "zoomX",
        layout: root.verticalLayout
        }));


        // Add legend
        // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
        var legend = chart.children.push(
        am5.Legend.new(root, {
            centerX: am5.p50,
            x: am5.p50
        })
        );
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
                echo "{centro: '".$row['apelido']."', total: ".$varProjeto->getCRFTotalCampos($row['id']).", monitorado: ".$varProjeto->getCRFTotalCamposMonitorados($row['id'])."},";
            }
        ?>
        ];


        // Create axes
        // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
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


        // Add series
        // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
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

        // Make stuff animate on load
        // https://www.amcharts.com/docs/v5/concepts/animations/
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

        makeSeries("Total de campos", "total");
        makeSeries("Monitorados", "monitorado");

        // Make stuff animate on load
        // https://www.amcharts.com/docs/v5/concepts/animations/
        chart.appear(1000, 100);
}); // end am5.ready()

</script>
</body>
</html>
