<div class="modal fade" id="modal_key" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Solicitud de Serie</h5>
			</div>
			<div class="modal-body" id="modal_key_body">

				<div class="form-group">
					<label for="txt_nro_clave">Ingrese la clave</label>
					<input class="form-control" type="text" id="txt_nro_clave" name="txt_nro_clave" value="">
				</div>

				<button class="btn btn-success" id="btn_solicitar_key">Solicitar</button>

				<div class="loader_key"></div>
				<br>
				<div class="form-group">
					<label for="txt_nro_serie">Serie generado</label>
					<input class="form-control" type="text" id="txt_nro_serie" name="txt_nro_serie" value="">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
			</div>
		</div>
	</div>
</div>