
<div class=" login-p">
    <div class="login-h">
        <div class="row">
            <div class="col-xs-12 text-center div-img-login">
                <a href="<?php echo base_url();?>">
                    <img alt="alfalogo" src="<?php echo base_url('assets/img/logo.png');?>">
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <h4 class="text-center">Login</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 text-center">
                <form id="form_login" method="POST" action="<?php echo base_url('login');?>">
                    <input type="number" required="required" name="codigo" id="codigo_login" placeholder="Codigo">
                    <br />
                    <input type="password" required="required" name="password" id="pass_login" placeholder="password">
                    <br />
                    <input id="btn_login" type="submit" value="Ingresar">
                </form>

            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <?php
                
                if(!isset($_GET['status'])){                
                    $status = "";
                }else{
                    $status = $_GET['status'];
                }

                if($status == "ERROR"){
                ?>
                <p class="bg-danger text-center">Datos incorrectos</p>
                <?php
                }
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 text-center">
                <?php
                    if($version < 1){
                        $version .= " BETA";
                    }
                ?>      
                <small>V. <?php echo $version;?></small> 
                   
                <div id="res_update">
                    <small>
                        Comprobando actualización
                        <img alt="loader" class='center' src='<?php echo base_url();?>assets/img/ajax-loader.gif'>
                    </small>
                </div>      
            </div>
        </div>
    </div>

</div>

<script>
    var icod = document.getElementById('codigo_login');
    var ipas = document.getElementById('pass_login');
    var ibtn = document.getElementById('btn_login');
    var divUpdate = document.getElementById("res_update");

    icod.disabled = true;
    ipas.disabled = true;
    ibtn.disabled = true;
                    
    var res = "";

    var r = new XMLHttpRequest();
    r.open("POST", "./inicio/verificarActualizacionLogin", true);
    r.setRequestHeader('Content-Type', 'application/xml')
    r.onreadystatechange = function () {
        if (r.readyState != 4 || r.status != 200) return;
        switch(r.responseText){
            case 0:
                res = "Error al actualizar. Recargue la aplicación.";
                break;
            case 1:
                res = "Aplicación actualizada";
                break;
            default:
                break;
        }

        divUpdate.innerHTML = "<small>" + res + "</small>";
        icod.disabled = false;
        ipas.disabled = false;
        ibtn.disabled = false;
    };
    r.send();
</script>