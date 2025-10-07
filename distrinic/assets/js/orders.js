import { base_url } from './config.js';
import { getParameterByName, fetchAsyncPost, GifLoader } from './global.js';

function newOrder(e) {
    e.preventDefault();
    location.href = base_url+"customers/list_customersPreSale";
}

function newOrderCancel(e) {
    let idOrder = document.getElementById("idOrderHidden").value;
    let idcustomer = document.getElementById("idCustomerHidden").value;

    let cancel

    if(idOrder>0){
        cancel = confirm("Se perderán los cambios no guardados. ¿Desea salir?");
    }else{
        cancel = confirm("¿Esta seguro que desea cancelar el pedido?");
    }

    if(cancel){
        if(getParameterByName('opcion') == 'ruta'){
            location.href = base_url+"vendors/ruta_diaria?cuenta="+idcustomer
        }else{
            location.href = base_url+"inicio/list_orders"
        }
    }else{
        return;
    }
}

function newOrderSave() {
    let idOrder = document.getElementById("idOrderHidden").value;
    let idcustomer = document.getElementById("idCustomerHidden").value;

    const data = new URLSearchParams("idCustomer="+idcustomer+"&idOrder="+idOrder);

    fetchAsyncPost(base_url+'order/saveOrder', data)
      .then(function(responseText){
        if(responseText == ""){
            if(getParameterByName('opcion') == 'ruta'){
                location.href = base_url+"vendors/ruta_diaria?cuenta="+idcustomer
            } else {
                location.href = base_url+"inicio/list_orders"
            }
        }else{
          alert("Hubo un error");
        }
      }).catch(function(err){
        console.log("Error al realizar la peticion saveOrder : " + err);
      });
}

function newOrderDelete() {
    let cdelete = confirm("¿Está seguro que desea eliminar el pedido? No se podrá recuperar.");
    let idOrder = document.getElementById("idOrderHidden").value;

    const data = new URLSearchParams("idOrder="+idOrder);

    if(cdelete){
      fetchAsyncPost(base_url+'order/deleteOrder', data)
        .then(function(responseText){
          if(responseText == ""){
            location.href = base_url+"inicio/list_orders"
          }else{
            alert("Hubo un error");
          }
        }).catch(function(err){
          console.log("Error al realizar la peticion deleteOrder : " + err);
        });
    }else{
      return;
    }
}

function deleteProductOrder(prodct) {
    let desc = prodct.innerText;
    let pDelete = confirm("¿Desea eliminar el producto "+desc+" ?");

    if(pDelete){
        let rowid = prodct.name;
        let list_order_products = document.getElementById('list_order_products');

        const data = new URLSearchParams("rowid="+rowid);

        fetchAsyncPost(base_url+'order/deleteOrder_product', data)
        .then(function(responseText){
            if(responseText != "ERROR"){
            list_order_products.innerHTML = responseText;
            document.getElementById("txt_newOrder_prod").focus();
            }else{
            alert("Hubo un error");
            }
        }).catch(function(err){
            console.log("Error al realizar la peticion deleteOrder_product : " + err);
        });
    }else{
        return;
    }
}


// function sendOrders() {
//     let div_img_sendOrder = document.getElementById('div_img_sendOrder');
//     div_img_sendOrder.innerHTML = GifLoader;
    
//     document.getElementById('msgStatus_order').style.display = "none";
//     document.getElementById('btn_sendorder_close').style.display = "none";

//     //REVISAR
//     $('#modalSendOrders').modal('show');

//     let msg = document.getElementById("msgStatus_order");
//     let resultado = "";
//     let clase = "";

//     fetchAsyncPost(base_url+'order/sendOrders')
//       .then(function(responseText){
//         if(responseText == "OK"){
//           div_img_sendOrder.innerHTML = imgSyncOK;
//           resultado = "Los pedidos se enviaron correctamente";
//           clase = " bg-success";
//         }else{
//           div_img_sendOrder.innerHTML = imgSyncError;
//           resultado = responseText;
//           clase = " bg-danger";
//         }

//         msg.innerText = resultado;
//         msg.className+= clase;
//         document.getElementById('btn_sendorder_close').style.display = "block";
//         document.getElementById('msgStatus_order').style.display = "block";

//       }).catch(function(err){
//         console.log("Error al realizar la peticion sendOrders : " + err);
//       });
// }

function selClasePrecios(value){
    $(".loaderWeb").fadeIn("slow");
    // let list_order_products = document.getElementById('list_order_products');
    let preSale = document.getElementById("preSale").value;
    let list_products
    
    if(preSale != 1){
      list_products = document.getElementById("list_products");
    }else{
      list_products = document.getElementById('list_order_products');
    }

    const data = new URLSearchParams("clase="+value+"&preSale="+preSale);

    fetchAsyncPost(base_url+'inicio/cambiaClasePrecio', data)
      .then(function(responseText){
        if(preSale == 1){
          //REVISAR
          $("#modalProductsPreSale .modal-body").load(base_url+'inicio/reloadModalProdSale', function (){
            list_products.innerHTML = responseText;
            $(".loaderWeb").fadeOut("slow");
          });
        }else{
          list_products.innerHTML = responseText;
          $(".loaderWeb").fadeOut("slow");
        }
      }).catch(function(err){
        console.log("Error al realizar la peticion cambiaClasePrecio : " + err);
      });
}

function searchHistory(){
    let fhd = document.getElementById('fechaD').value;
    let fhh = document.getElementById('fechaH').value;
    let customer = document.getElementById('sel_customer_history').value;
    let history_list = document.getElementById('history_list');

    history_list.innerHTML = GifLoader + "<small style='display:block;' class='text-center'>Cargando pedidos</small>";

    const data = new URLSearchParams("idCustomer="+customer+"&fhd="+fhd+"&fhh="+fhh);
    
    fetchAsyncPost(base_url+'order/searchOrderHistory', data)
      .then(function(responseText){
        history_list.innerHTML = responseText;
      }).catch(function(err){
        console.log("Error al realizar la peticion searchOrderHistory : " + err);
      });
}


function getHistoryCpte(key){

    //REVISAR
    $(".loaderWeb").fadeIn("slow");
  
    let modalHistoryDetail_body = document.getElementById('modalHistoryDetail_body');
    const data = new URLSearchParams("key="+key);
  
    fetchAsyncPost(base_url+'order/getHistoryCpte', data)
      .then(function(responseText){
        //REVISAR
        modalHistoryDetail_body.innerHTML = responseText;
        $('#modalHistoryDetail').modal('show');
        $(".loaderWeb").fadeOut("slow");
        
      }).catch(function(err){
        console.log("Error al realizar la peticion getHistoryCpte : " + err);
      });
  }

export {
    newOrder,
    newOrderCancel,
    newOrderSave,
    newOrderDelete,
    deleteProductOrder,
    selClasePrecios,
    searchHistory,
    getHistoryCpte
}