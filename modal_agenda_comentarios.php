<?
	include "seguranca.php";
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
		<meta charset="utf-8" />
		<meta http-equiv="Cache-control" content="public">
        <title>SPC - Sistema de pesquisa clínica</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #3 for " name="description" />
        <meta content="" name="author" />
		<link href="plugins/font/css/font-awesome.css" rel="stylesheet">
		<link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/global/css/geral.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<script src="js/sweetalert2.all.min.js"></script>
		<link rel="shortcut icon" href="favicon.ico" />
		<style>
			.datepicker {
				z-index: 99999 !important;
			}
		</style>
	</head>
	<body>
			<div class="modal-content" id="myModal">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Comentários do revisor</h4>
				</div>
				<div class="modal-body">
                    <div  style="height: 500px;
                                overflow-y: scroll;">
                        <table id="tblComentarios" class="table table-striped table-bordered table-sm">
                            <thead>
                                <th>Formulário</th>
                                <th>Campo</th>
                                <th>Valor do Campo</th>
                                <th>Comentário do revisor</th>
                                <th><center>Status</center></th>
                            </thead>
                            <?
                                $query = "SELECT agenda.id,
                                            cfg_visitas.nome as form_nome,
                                            cfg_visitas_campos.texto as campo_nome,
                                            crf_agenda_campos.valor,
                                            crf_agenda_campos.comentario_revisor,
                                            crf_agenda_campos.comentario_revisor_status
                                        FROM agenda
                                            inner join crf_agenda_forms on agenda.id = crf_agenda_forms.agendamento
                                            inner join cfg_visitas on crf_agenda_forms.form_id = cfg_visitas.id
                                            inner join crf_agenda_campos on crf_agenda_forms.id = crf_agenda_campos.form_id
                                            inner join cfg_visitas_campos on crf_agenda_campos.campo_id = cfg_visitas_campos.id
                                            where agenda.id = ".$_GET['id']."
                                                and crf_agenda_campos.comentario_revisor <> ''";
                                $resComentarios = $conexao->pesquisa($query);
                                while($rowComentarios = mysqli_fetch_assoc($resComentarios)){
                                    ?>
                                    <tr>
                                        <td><?=$rowComentarios['form_nome'];?></td>
                                        <td><?=$rowComentarios['campo_nome'];?></td>
                                        <td><?=$rowComentarios['valor'];?></td>
                                        <td><?=nl2br($rowComentarios['comentario_revisor']);?></td>
                                        <td><center><?=$rowComentarios['comentario_revisor_status'];?></center></td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </table>
                    </div>

				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn dark btn-outline">Fechar</button>
				</div>
			</div>
	</body>
	<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="assets/global/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="js/global.js" type="text/javascript"></script>
	<script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="css/sweetalert2.css">
	<script src="js/funcoes.js" type="text/javascript"></script>
	<script src="js/jquery.idletimeout.js" type="text/javascript"></script>
	<script src="js/jquery.idletimer.js" type="text/javascript"></script>
	<script src="js/paginas/idle.js" type="text/javascript"></script>
	<script src="js/ui.datepicker-pt-BR.js" type="text/javascript"></script>
	<script src="js/moment.min.js"></script>
	<script src="plugin/js/fullcalendar.min.js"></script>
	<script src="plugin/js/locale/pt-br.js"></script>
	<script src="js/funcoes.js"></script>

</html>