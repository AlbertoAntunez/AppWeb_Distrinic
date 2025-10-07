<div class="container">
    <?php
        $this->load->view('public/mod-back');
    ?>

    <div class="row">
        <div class="col-xs-12 box-customer">
            <div class="col-xs-2">
                <img alt="stock" src="<?php echo base_url('assets/img/icon/stock.svg');?>" />
            </div>
            <div class="col-xs-10">
                <p class="cod">#<?php echo $codigo;?></p>
                <p><?php echo $productsDescription;?></p>
            </div>
        </div>
    </div>
    
    <div id="list_stock">
    <?php
        if($error){
            echo '
                <p class="text-center bg-danger">
                    '.$error_message.'
                </p>
            ';
        }
        if(!empty($list)): foreach($list as $row):

            switch($row->idDeposito){
                case "@REAL":
                    $depoName = "Actual"; $icon = "info";
                    break;
                case "@COMPROMETIDO":
                    $depoName = "Comprometido"; $icon = "warning";
                    break;
                case "@SUGERIDO":
                    $depoName = "Sugerido"; $icon = "tick";
                    break;
                default:
                    $depoName = $row->idDeposito; $icon = "info";
                    break;
            }
    ?>
        <div class="row box-stock">
            <div class="col-xs-2"><img alt="deposito" src="<?=base_url('assets/img/icon/'.$icon.'.svg');?>"></div>
            <div class="col-xs-7"><?php echo $depoName;?></div>
            <div class="col-xs-3 text-right"><?php echo number_format($row->stock, 2);?></div>
        </div>   
        <?php
    endforeach; else:
        echo "No hay datos para mostrar";
    endif;

    ?>
    </div>

</div>
