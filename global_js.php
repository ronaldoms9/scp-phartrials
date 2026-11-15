		<div class="modal fade" id="idle-timeout-dialog" data-backdrop="static">
			<div class="modal-dialog modal-small">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Sua sessão será bloqueada por inatividade.</h4>
					</div>
					<div class="modal-body">
						<p>
							<i class="fa fa-warning font-red"></i> Sua sessão será bloqueada em
							<span id="idle-timeout-counter"></span> segundos.</p>
						<p> Você gostaria de continuar com a sessão? </p>
					</div>
					<div class="modal-footer">
						<button id="idle-timeout-dialog-logout" type="button" class="btn dark btn-outline sbold uppercase">Não, sair do sistema</button>
						<button id="idle-timeout-dialog-keepalive" type="button" class="btn green btn-outline sbold uppercase" data-dismiss="modal">SIM, continuar</button>
					</div>
				</div>
			</div>
		</div>
        <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>		      
		<script type="text/javascript" src="assets/global/plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>                
		<script src="js/global.js" type="text/javascript"></script>            
        <script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>				
		<link rel="stylesheet" href="css/sweetalert2.css">		
		<script src="js/funcoes.js" type="text/javascript"></script>
		<script src="js/ui.datepicker-pt-BR.js" type="text/javascript"></script>
		<script src="assets/global/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js" type="text/javascript"></script>
		<script src="js/jquery.bootstrap-growl.min.js" type="text/javascript"></script>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<script src="js/loadingoverlay.min.js" type="text/javascript"></script>
		<script>
			$(document).ready(function() {
				$('.select_pesquisa').select2({theme: 'bootstrap4'});
			});
			function mostraCarregando(){$.LoadingOverlay("show");}
			function removeCarregando(){$.LoadingOverlay("hide");}
		</script>