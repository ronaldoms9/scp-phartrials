<?
	 header('Content-Type:text/html; charset=ISO-8859-1');
?>
<!DOCTYPE html>
<head>
        <title><?=$titulo;?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #3 for " name="description" />
        <meta content="" name="author" />
        <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' rel='stylesheet'/>
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/layouts/layout3/css/custom.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/css/geral.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="assets/ico/favicon.png">
<script>
        function login(){
                 window.location.href="index.php";
        }
</script>

<div class="container">
	<div class="row clearfix"><BR><BR><BR><BR>
		<div class="col-md-12 column">
			<div class="row clearfix">
				<div class="col-md-2 column">
				</div>
				<div class="col-md-8 column">
                   <div class="panel panel-danger">
						<div class="panel-heading">
							<h3 class="panel-title">
								Sua sessão expirou por inatividade
							</h3>
						</div>
						<div class="panel-body">
							Por ter ficado muito tempo sem usar o sistema, sua sessão expirou.
							<div>
								Por favor, faça login novamente usando o botão abaixo.
							</div>
						</div>
						<div class="panel-footer">
                             <button onclick="login();" type="button" class="btn btn-lg btn-danger">EFETUAR LOGIN</button>
						</div>
					</div>
				</div>
				<div class="col-md-2 column">
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>

</html>
