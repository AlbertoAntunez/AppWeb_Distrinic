<div class="container">  

    <?php
    if(!empty($product)): foreach($product as $row):
        $desc = $row["descripcion"];
        $desc = ($desc == "") ? "" : $desc; 
    ?>
    
    <div class="row p-10 fProduct">
        <div class="col-xs-4"><label>Código</label></div>
        <div class="col-xs-8"><input type="text" class="form-control" placeholder="Código" readonly="readonly" value="<?php echo $row["idArticulo"];?>"></div>
    </div>

    <div class="row p-10 fProduct">
        <div class="col-xs-4"><label>Descripción</label></div>
        <div class="col-xs-8">
            <textarea rows="4" type="text" class="form-control" placeholder="Código" readonly="readonly"><?php echo $row["descripcion"];?></textarea>
        </div>
    </div>

    <div class="row p-10 fProduct">
        <div class="col-xs-4"><label>Alic Iva</label></div>
        <div class="col-xs-8"><input type="text" class="form-control" placeholder="Iva" readonly="readonly" value="<?php echo $row["iva"];?>"></div>
    </div>

    <?php
    for($i=1; $i <=10; $i++){
    ?>
    <div class="row p-10 fProduct">
        <div class="col-xs-4"><label>Precio <?=$i;?></label></div>
        <div class="col-xs-8"><input type="text" class="form-control" placeholder="Precio" readonly="readonly" value="<?php echo $row["precio" . $i];?>"></div>
    </div>
    <?php
    }
    ?>
    

    <?php
    endforeach; else:    
    endif;
    ?>
        
  

</div>