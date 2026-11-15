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

		
		chartStatus.dataSource.url = "../php_php/php_dash_18.php?grafico=statusParticipantes&centro=ALL";			
		createSeries('total', 'TOTAL');
		createSeries('active', 'active');
		createSeries('finished', 'finished');
	
	}); 
	
	
	/*PATIENT x SITE */
	var chart = am4core.create("chartdiv", am4charts.PieChart);		
	am4core.ready(function() {
		am4core.useTheme(am4themes_animated);
		chart.dataSource.url = "../php_php/php_dash_18.php?grafico=paciente&centro=ALL";
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
	
	/*PATIENT x TREATAMENT*/
	var chartBraco = am4core.create("chartdivBraco", am4charts.PieChart);		
	am4core.ready(function() {
		am4core.useTheme(am4themes_animated);
		chartBraco.dataSource.url = "../php_php/php_dash_18.php?grafico=pacienteBraco&centro=ALL";
		var pieSeries = chartBraco.series.push(new am4charts.PieSeries());
		pieSeries.dataFields.value = "total";
		pieSeries.dataFields.category = "treatment";
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
		pieSeries.labels.template.radius = 3;
		pieSeries.labels.template.padding(0,0,0,0);
		pieSeries.ticks.template.disabled = true;

		//chartBraco.innerRadius = am4core.percent(30);

		// This creates initial animation
		pieSeries.hiddenState.properties.opacity = 1;
		pieSeries.hiddenState.properties.endAngle = -90;
		pieSeries.hiddenState.properties.startAngle = -90;
		chartBraco.hiddenState.properties.radius = am4core.percent(0);
	});

	
	// Create chart instance
	var chartInclusao = am4core.create("chartdivInclusao", am4charts.XYChart);
	// Set up data source
	chartInclusao.dataSource.url = "../php_php/php_dash_18.php?grafico=pacienteData&centro=ALL";
	// Create axes
	var categoryAxis = chartInclusao.xAxes.push(new am4charts.CategoryAxis());
	categoryAxis.dataFields.category = "data";
	// Create value axis
	var valueAxis = chartInclusao.yAxes.push(new am4charts.ValueAxis());
	// Create series
	var series1 = chartInclusao.series.push(new am4charts.LineSeries());
	series1.dataFields.valueY = "inclusoes";
	series1.dataFields.categoryX = "data";
	series1.name = "Date of inclusion";
	series1.strokeWidth = 3;
	series1.tensionX =1;
	series1.bullets.push(new am4charts.CircleBullet());	
	// Add legend
	chartInclusao.legend = new am4charts.Legend();




	//Dias de hospitalização
	var mainContainer = am4core.create("chartdivDiasHospitalizado", am4core.Container);
	var maleChart = mainContainer.createChild(am4charts.XYChart);
	am4core.ready(function() {
		am4core.useTheme(am4themes_animated);		
		mainContainer.width = am4core.percent(100);
		mainContainer.height = am4core.percent(100);
		mainContainer.layout = "horizontal";

		
		maleChart.paddingRight = 0;
		maleChart.dataSource.url = "../php_php/php_dash_18.php?grafico=hospitalizacao&centro=ALL";
		var maleCategoryAxis = maleChart.yAxes.push(new am4charts.CategoryAxis());
		maleCategoryAxis.dataFields.category = "dias";
		maleCategoryAxis.renderer.grid.template.location = 0;
		maleCategoryAxis.renderer.minGridDistance = 30;
		var maleValueAxis = maleChart.xAxes.push(new am4charts.ValueAxis());
		maleValueAxis.renderer.inversed = true;
		maleValueAxis.min = 0;
		maleValueAxis.max = 60;
		maleValueAxis.strictMinMax = true;
		maleValueAxis.numberFormatter = new am4core.NumberFormatter();
		maleValueAxis.numberFormatter.numberFormat = "#.#";
		// Create series
		var maleSeries = maleChart.series.push(new am4charts.ColumnSeries());
		maleSeries.dataFields.valueX = "Heparin";
		maleSeries.dataFields.valueXShow = "percent";
		maleSeries.calculatePercent = true;
		maleSeries.dataFields.categoryY = "dias";
		maleSeries.interpolationDuration = 1000;
		maleSeries.columns.template.tooltipText = "Heparin, dias{categoryY}: {valueX} ({valueX.percent.formatNumber('#.0')}%)";
		var femaleChart = mainContainer.createChild(am4charts.XYChart);
		femaleChart.paddingLeft = 0;
		femaleChart.dataSource.url = "../php_php/php_dash_18.php?grafico=hospitalizacao&centro=ALL";
		var femaleCategoryAxis = femaleChart.yAxes.push(new am4charts.CategoryAxis());
		femaleCategoryAxis.renderer.opposite = true;
		femaleCategoryAxis.dataFields.category = "dias";
		femaleCategoryAxis.renderer.grid.template.location = 0;
		femaleCategoryAxis.renderer.minGridDistance = 15;
		var femaleValueAxis = femaleChart.xAxes.push(new am4charts.ValueAxis());
		femaleValueAxis.min = 0;
		femaleValueAxis.max = 60;
		femaleValueAxis.strictMinMax = true;
		femaleValueAxis.numberFormatter = new am4core.NumberFormatter();
		femaleValueAxis.numberFormatter.numberFormat = "#.#";
		femaleValueAxis.renderer.minLabelPosition = 0.01;
		var femaleSeries = femaleChart.series.push(new am4charts.ColumnSeries());
		femaleSeries.dataFields.valueX = "Soc";
		femaleSeries.dataFields.valueXShow = "percent";
		femaleSeries.calculatePercent = true;
		femaleSeries.fill = femaleChart.colors.getIndex(4);
		femaleSeries.stroke = femaleSeries.fill;
		femaleSeries.columns.template.tooltipText = "SoC, dias{categoryY}: {valueX} ({valueX.percent.formatNumber('#.0')}%)";
		femaleSeries.dataFields.categoryY = "dias";
		femaleSeries.interpolationDuration = 1000;
	}); 
	
	//Average days
	var chartMediaDias = am4core.create('chartdivDiasMedia', am4charts.XYChart);
	am4core.ready(function() {
		am4core.useTheme(am4themes_animated);		
		chartMediaDias.hiddenState.properties.opacity = 0; 
		chartMediaDias.dataSource.url = "../php_php/php_dash_18.php?grafico=mediaDiasHospitalizado&centro=ALL";
		var categoryAxis = chartMediaDias.xAxes.push(new am4charts.CategoryAxis());
		categoryAxis.renderer.grid.template.location = 0;
		categoryAxis.dataFields.category = "category";
		categoryAxis.renderer.minGridDistance = 40;
		categoryAxis.fontSize = 11;

		var valueAxis = chartMediaDias.yAxes.push(new am4charts.ValueAxis());
		valueAxis.min = 0;
		valueAxis.strictMinMax = true;
		valueAxis.renderer.minGridDistance = 30;
		var series = chartMediaDias.series.push(new am4charts.ColumnSeries());
		series.dataFields.categoryX = "category";
		series.dataFields.valueY = "days";
		series.columns.template.tooltipText = "{valueY.value}";
		series.columns.template.tooltipY = 0;
		series.columns.template.strokeOpacity = 0;
		series.columns.template.adapter.add("fill", function(fill, target) {
		  return chartMediaDias.colors.getIndex(target.dataItem.index);
		});
	}); 
	//End of Average days
	
	var chartMortalidade = am4core.create("chartdiMortalidade", am4charts.XYChart);
	am4core.ready(function() {
		
		am4core.useTheme(am4themes_animated);				
		chartMortalidade.hiddenState.properties.opacity = 0; 		
		chartMortalidade.dataSource.url = "../php_php/php_dash_18.php?grafico=mortalidade&centro=ALL";					
		
		chartMortalidade.colors.step = 2;
		chartMortalidade.padding(0, 0, 0, -50);		

		var categoryAxis = chartMortalidade.xAxes.push(new am4charts.CategoryAxis());
		categoryAxis.dataFields.category = "category";
		categoryAxis.renderer.grid.template.location = 0;
 
		var valueAxis = chartMortalidade.yAxes.push(new am4charts.ValueAxis());
		valueAxis.min = 0;
		valueAxis.max = 100;
		valueAxis.strictMinMax = true;
		valueAxis.calculateTotals = true;
		valueAxis.renderer.minWidth = 50;


		var series1 = chartMortalidade.series.push(new am4charts.ColumnSeries());
		series1.columns.template.width = am4core.percent(80);
		series1.columns.template.tooltipText = "{name}: {valueY.totalPercent.formatNumber('#.00')}";
		series1.name = "Deaths";
		series1.dataFields.categoryX = "category";
		series1.dataFields.valueY = "value1";
		series1.dataFields.valueYShow = "totalPercent";
		series1.dataItems.template.locations.categoryX = 0.5;
		series1.stacked = true;
		series1.tooltip.pointerOrientation = "vertical";

		var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
		bullet1.interactionsEnabled = false;
		bullet1.label.text = "{valueY.totalPercent.formatNumber('#.00')}";
		bullet1.label.fill = am4core.color("#ffffff");
		bullet1.locationY = 0.5;

		var series2 = chartMortalidade.series.push(new am4charts.ColumnSeries());
		series2.columns.template.width = am4core.percent(80);
		series2.columns.template.tooltipText = "{name}: {valueY.totalPercent.formatNumber('#.00')}";
		series2.name = "Alives";
		series2.dataFields.categoryX = "category";
		series2.dataFields.valueY = "value2";
		series2.dataFields.valueYShow = "totalPercent";
		series2.dataItems.template.locations.categoryX = 0.5;
		series2.stacked = true;
		series2.tooltip.pointerOrientation = "vertical";

		var bullet2 = series2.bullets.push(new am4charts.LabelBullet());
		bullet2.interactionsEnabled = false;
		bullet2.label.text = "{valueY.totalPercent.formatNumber('#.00')}";
		bullet2.locationY = 0.5;
		bullet2.label.fill = am4core.color("#ffffff");

		//chartMortalidade.scrollbarX = new am4core.Scrollbar();
		
	});	
		
	//Entubados
	var chartEntubado = am4core.create('chartdivEntubado', am4charts.XYChart);
	am4core.ready(function() {
		am4core.useTheme(am4themes_animated);		
		chartEntubado.hiddenState.properties.opacity = 0; 
		chartEntubado.dataSource.url = "../php_php/php_dash_18.php?grafico=entubados&centro=ALL";
		var categoryAxis = chartEntubado.xAxes.push(new am4charts.CategoryAxis());
		categoryAxis.renderer.grid.template.location = 0;
		categoryAxis.dataFields.category = "category";
		categoryAxis.renderer.minGridDistance = 40;
		categoryAxis.fontSize = 11;

		var valueAxis = chartEntubado.yAxes.push(new am4charts.ValueAxis());
		valueAxis.min = 0;
		valueAxis.strictMinMax = true;
		valueAxis.calculateTotals = true;
		valueAxis.renderer.minGridDistance = 500;
		
		
		var series = chartEntubado.series.push(new am4charts.ColumnSeries());		
		series.dataFields.categoryX = "category";
		series.dataFields.valueY = "days";
		series.columns.template.tooltipText = "{valueY.value}";
		series.columns.template.tooltipY = 0;
		series.columns.template.strokeOpacity = 50;
		series.columns.template.adapter.add("fill", function(fill, target) {
		  return chartEntubado.colors.getIndex(target.dataItem.index);
		});
		
		
	}); 
	//End of Average days
	
	function mudouCentro1(centro){
		chart.dataSource.url = "../php_php/php_dash_18.php?grafico=paciente&centro=" + centro;
		chart.dataSource.load();		
	}
	function mudouCentro2(centro){
		chartBraco.dataSource.url = "../php_php/php_dash_18.php?grafico=pacienteBraco&centro=" + centro;
		chartBraco.dataSource.load();		
	}
	function mudouCentro3(centro){
		mainContainer.maleChart.dataSource.url = "../php_php/php_dash_18.php?grafico=obito&centro=" + centro;
		mainContainer.maleChart.dataSource.load();		
		mainContainer.femaleChart.dataSource.url = "../php_php/php_dash_18.php?grafico=obito&centro=" + centro;
		mainContainer.femaleChart.dataSource.load();			
	}
	function mudouCentro4(centro){		
		chartMediaDias.dataSource.url = "../php_php/php_dash_18.php?grafico=mediaDiasHospitalizado&centro=" + centro;
		chartMediaDias.dataSource.load();		
	}
	function mudouCentro5(centro){
		chartInclusao.dataSource.url = "../php_php/php_dash_18.php?grafico=pacienteData&centro=" + centro;
		chartInclusao.dataSource.load();		
	}