<!DOCTYPE html>
<html lang="en">

<head>
 	<!-- CSS -->
	<link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/main.css" rel="stylesheet" type="text/css">
	<link href="assets/css/my-custom-styles.css" rel="stylesheet" type="text/css">

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400italic,400,600,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,300italic,400italic,700,400,300' rel='stylesheet' type='text/css'>
	<!-- FAVICONS -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/repute144x144.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/repute114x114.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/repute72x72.png">
	<link rel="apple-touch-icon-precomposed" href="assets/ico/repute57x57.png">
	<link rel="shortcut icon" href="assets/ico/favicon.png">
</head>
<body>
	<!-- WRAPPER -->
	<div class="wrapper">
        <br><br>

        <div class="page-content">
			<div class="container">
					</div>
                    <div class="col-md-2">
                    	   <center><h2 class="page-title pull-left">INCLUSÃO DE PARTICIPANTE</h2></center>
                    	   <br><br><br>
                        <div name="div_numero" id="div_numero" style="-moz-border-radius:7px;-webkit-border-radius:7px; border-radius:7px;border: 1px solid #888;" class="form-group">
                             <table width="100%" class="table">
                                 <tr>
                                     <td align="right"><right><h4>Participante alocado com o número</h4></right></td>
                                     <td width="50"></td>
                                     <td width="200"><h4><b><?=str_pad($_GET['seq'],3,"000",STR_PAD_LEFT);?>S</b></h4></td>
                                 </tr>
                                 <tr>
                                     <td align="right"><h4>Centro</h4></p></td>
                                     <td width="50"></td>
                                     <td width="200"><h4><b><?=$_GET['centro'];?></b></h4></td>
                                 </tr>

                             </table>
                        </div>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
	<script src="assets/js/jquery-2.1.1.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
