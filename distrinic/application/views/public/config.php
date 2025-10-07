<div class="container">
    <?php
        $this->load->view('public/mod-back');

        $server_api = (!defined('SERVER_API')) ? "" : SERVER_API;

        $modClase = (defined('MODIFICA_CLASEP')) ? MODIFICA_CLASEP : "0";
        $modClase = ($modClase == "1") ? " checked " : " unchecked ";

        $vdorCustomer = (defined('VENDEDOR_CLIENTES_PROPIOS')) ? VENDEDOR_CLIENTES_PROPIOS : "0";
        $vdorCustomer = ($vdorCustomer == "1") ? " checked " : " unchecked ";

        $stockOnlineConsulta = (defined('STOCK_ONLINE_CONSULTAS')) ? STOCK_ONLINE_CONSULTAS : "0";
        $stockOnlineConsulta = ($stockOnlineConsulta == "1") ? " checked " : " unchecked ";

        $artsPorConsulta = (!defined('ARTS_POR_CONSULTA')) ? "" : ARTS_POR_CONSULTA;

        $showImages = (defined('MUESTRA_IMAGENES_PRODUCTOS')) ? MUESTRA_IMAGENES_PRODUCTOS : "0";
        $showImages = ($showImages == "1") ? " checked " : " unchecked ";

        $stkComproMasReal = (defined('STOCK_COMPROMETIDO_MAS_REAL')) ? STOCK_COMPROMETIDO_MAS_REAL : "0";
        $stkComproMasReal = ($stkComproMasReal == "1") ? " checked " : " unchecked ";

        $rutaImagenes = (!defined('RUTA_IMAGENES_PRODUCTOS')) ? "" : RUTA_IMAGENES_PRODUCTOS;

        $noMuestraTotales = (defined('NOMUESTRA_TOTALES_PEDIDOS')) ? NOMUESTRA_TOTALES_PEDIDOS : "0";
        $noMuestraTotales = ($noMuestraTotales == "1") ? " checked " : " unchecked ";

        $usaOffline = (defined('USA_OFFLINE')) ? USA_OFFLINE : "0";
        $usaOffline = ($usaOffline == "1") ? " checked " : " unchecked ";
    ?>
    <div class="row">
        <div class="col-xs-12 text-center"><h3>Configuración</h3><hr> </div>
        
        <div class="col-xs-12">
            <form method="POST" action="<?php echo base_url("inicio/grabarArchivoConfiguracion");?>">
                <div class="form-group">
                    <label for="idWs">Ruta Web Service</label>
                    <input id="idWs" required="required" name="idWs" type="text" class="form-control" value="<?php echo $server_api;?>" placeholder="http://ruta:puerto/api/">
                </div>
                                
                <div class="checkbox">
                    <label>
                    <input type="checkbox" <?php echo $modClase;?> name="cfg_modificaClase"> Permite modificar clase de precio
                    </label>
                </div>

                <div class="checkbox">
                    <label>
                    <input type="checkbox" <?php echo $vdorCustomer;?> name="cfg_vendorCustomerOnly"> Vendedores solo visualizan clientes asignados
                    </label>
                </div>

                <div class="checkbox">
                    <label>
                    <input type="checkbox" <?php echo $stockOnlineConsulta;?> name="cfg_stockOnlineConsultas"> Stock online en consulta de articulos
                    </label>
                    <label>
                    <input type="checkbox" <?php echo $stkComproMasReal;?> name="cfg_stockcomprmasreal"> Stock Comprometido + Real
                    </label>
                    <small style="display:block;" class="bg-danger">Esto puede demorar las consultas</small>
                </div>

                <div class="form-group">
                    <small for="cfg_articulosPorConsulta">Articulos por consulta</small>
                    <input name="cfg_articulosPorConsulta" type="text" class="form-control" value="<?php echo $artsPorConsulta;?>" placeholder="20" style="max-width:20%;">
                </div>
                

                <div class="checkbox">
                    <label>
                    <input type="checkbox" <?php echo $noMuestraTotales;?> name="cfg_noMuestraTotalesPedidos"> No mostrar totales en pedidos
                    </label>
                </div>

                <div class="checkbox">
                    <label>
                    <input type="checkbox" <?php echo $showImages;?> name="cfg_muestraImagenesProductos"> Ver imagenes de articulos
                    </label>
                </div>
                <!-- <div class="form-group">
                    <small for="cfg_rutaImagenesProductos">Ruta de donde tomara las imagenes. Si no se informa toma la local (*.gif)</small>
                    <input name="cfg_rutaImagenesProductos" type="text" class="form-control" value="<?php echo $rutaImagenes;?>" placeholder="">
                </div> -->

                <div class="checkbox">
                    <label>
                    <input type="checkbox" <?php echo $usaOffline;?> name="cfg_usaoffline"> Preparar para trabajo OFFLINE
                    </label>
                </div>

                <input class="form-control btn btn-success" type="submit" value="Aceptar">
            </form>
        </div>

        <div class="col-xs-12 text-center">
            <small>Esta configuración se aplicará a todos los vendedores</small>
        </div>
    </div>
</div>


<?php


?>