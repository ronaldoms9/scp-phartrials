	var baseUrl = "../php_php/php_dash_metatrial.php?";
	var baseUrl = "php_php/php_dash_metatrial.php?";
	
	
	/*PATIENT x SITE */
	var chart = am4core.create("chartdiv", am4charts.PieChart);		
	am4core.ready(function() {
		am4core.useTheme(am4themes_animated);
		chart.dataSource.url = baseUrl + "grafico=paciente&centro=ALL";
		var pieSeries = chart.series.push(new am4charts.PieSeries());
		pieSeries.dataFields.value = "patients";
		pieSeries.dataFields.category = "centro";
		pieSeries.slices.template.stroke = am4core.color("#fff");
		pieSeries.slices.template.strokeWidth = 2;
		pieSeries.slices.template.strokeOpacity = 1;
		pieSeries.slices.template
		  .cursorOverStyle = [
			{
			  "property": "cursor",
			  "value": "pointer"
			}
		  ];
		pieSeries.alignLabels = false;
		pieSeries.labels.template.bent = true;
		pieSeries.labels.template.radius = 2;
		pieSeries.labels.template.padding(0,0,0,0);
		pieSeries.hiddenState.properties.opacity = 1;
		pieSeries.hiddenState.properties.endAngle = -45;
		pieSeries.hiddenState.properties.startAngle = -90;
		chart.hiddenState.properties.radius = am4core.percent(0);
	});
	
	var chartStatus = am4core.create('chartdivStatus', am4charts.XYChart)
	am4core.ready(function() {
		am4core.useTheme(am4themes_animated);
		
		chartStatus.colors.step = 2;

		chartStatus.legend = new am4charts.Legend()
		chartStatus.legend.position = 'top'
		chartStatus.legend.paddingBottom = 20
		chartStatus.legend.labels.template.maxWidth = 95

		var xAxis = chartStatus.xAxes.push(new am4charts.CategoryAxis())
		xAxis.dataFields.category = 'category'
		xAxis.renderer.cellStartLocation = 0.1
		xAxis.renderer.cellEndLocation = 0.9
		xAxis.renderer.grid.template.location = 0;

		var yAxis = chartStatus.yAxes.push(new am4charts.ValueAxis());
		yAxis.min = 0;

		function createSeries(value, name) {
			var series = chartStatus.series.push(new am4charts.ColumnSeries())
			series.dataFields.valueY = value
			series.dataFields.categoryX = 'category'
			series.name = name
			var bullet = series.bullets.push(new am4charts.LabelBullet())
			bullet.interactionsEnabled = false
			bullet.dy = 30;
			bullet.label.text = '{valueY}'
			bullet.label.fill = am4core.color('#ffffff')

			return series;
		}

		
		chartStatus.dataSource.url = baseUrl + "grafico=statusParticipantes&centro=ALL";			
		createSeries('total', 'TOTAL');
		createSeries('heparin', 'Heparin');
		createSeries('soc', 'Soc');
		createSeries('intub', 'Intubated');
		createSeries('death', 'Death');
		
	}); 
	