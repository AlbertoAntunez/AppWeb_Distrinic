<div class="modal fade" id="modal_getRubros" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
      	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title" id="exampleModalLongTitle">Seleccione los rubros</h5>
      </div>
      <div class="modal-body" id="modal_getRubros_body">
        <div class="row">    
      	<?php
      	foreach($rubros as $row){
			echo '
			<div class="col-md-6 col-xs-6">
				<div class="checkbox">
	                <label>
	                <input type="checkbox" name="cfg_modificaClase">'.$row->descripcion.'
	                </label>
	            </div>
            </div>
			';
		}
      	?>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-secondary">Aceptar</button>
      </div>
    </div>
  </div>
</div>