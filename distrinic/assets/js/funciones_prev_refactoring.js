'use strict'

// import { base_url, local2json } from './config.js';
// import { mAux, GifLoader, imgSyncOK, imgSyncWait, imgSyncError, fetchAsyncPost } from './global.js';
// import { selProdPreSale, searchProduct, addManualProduct, getStockActual, addProductAccept } from './products.js';

let mAux = new Map([['unit', ''], ['codigo', '']]);
let GifLoader = "<img class='center' src='"+base_url+"assets/img/ajax-loader.gif' >";
let imgSyncWait = "<img style='max-width:31px;' class='center' src='"+base_url+"assets/img/icon/timer.svg'>";
let imgSyncOK = "<img class='center' src='"+base_url+"assets/img/icon/tick.svg'>";
let imgSyncError = "<img class='center' src='"+base_url+"assets/img/icon/cancel.svg'>";

function getParameterByName(name) {
  name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
  results = regex.exec(location.search);
  return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

window.onload = function(){
  $(".loaderWeb").fadeOut("slow");
}

async function fetchAsyncPost(url, body){
  let response = await fetch(url,{
    method: 'POST',
    body: body
  });
  let data = await response.text();
  return data;
}

document.addEventListener('click', function(e){
  if(e.target){
    if(e.target.id == 'a_bsq_products'){
      e.preventDefault();
      searchProduct();
    }else if(e.target.id == 'a_prod_preSale'){
      e.preventDefault();
      selProdPreSale(e.target);
    }else if(e.target.className == 'del_product_order' || e.target.id == 'del_product_order'){
      delProductOrder(e.target);
    }else if(e.target.className == 'item_historyOrder'){
      getHistoryCpte(e.target.name);
    }else if(e.target.id == 'btn_sync_offline'){
      verificarPedidosOffline();
    }
  }
});

function selProdPreSale(element){
  //REVISAR
  $('#modalProductsPreSale').modal('hide');
  let load_div_order = document.getElementById("load_div_order");
  load_div_order.innerHTML = GifLoader;
  
  let codigo = element.name;
  let clase = document.getElementById('nclaseprecio').value

  if(codigo == ""){
    alert("Debe informar el código del producto.");
    load_div_order.innerHTML = "";
    return;
  }

  prevAgregar(codigo, clase);
}

function agregar(codigo,desc,unitario,cantidad){

  let cant = parseFloat(cantidad);
  let load_div_order = document.getElementById('load_div_order');
  let list_order_products = document.getElementById('list_order_products');

  load_div_order.innerHTML = "";
  list_order_products.innerHTML = GifLoader;

  const data = new URLSearchParams("codigo="+codigo+"&cantidad="+cant+"&unit="+unitario+"&desc="+desc);

  fetchAsyncPost(base_url+'products/addProduct', data)
    .then(function(responseText){

      document.getElementById("qty_product_add").value = "";
      //REVISAR
      $('#modalProductsPreSale_qty').modal('hide');

      document.getElementById("txt_newOrder_prod").value = "";      
      list_order_products.innerHTML = responseText;
      document.getElementById("txt_newOrder_prod").focus();

    }).catch(function(err){
      console.log("Error al realizar la peticion addProduct : " + err);
    });
}

function searchProduct(){

    let query = document.getElementById("txt_bsq_products").value;
    let preSale = document.getElementById("preSale").value;
    let list_products = document.getElementById("list_products");

    list_products.innerHTML = GifLoader;

    const data = new URLSearchParams("query="+ query+"&preSale="+preSale);

    fetchAsyncPost(base_url+'products/getQueryProducts', data)
    .then(function(responseText){
      list_products.innerHTML = responseText;
    })
    .catch(function(err){
      console.log("Error al realizar la peticion getQueryProducts : " + err);
    });

}

let a_bsq_customers = document.getElementById('a_bsq_customers');
if(a_bsq_customers){
  a_bsq_customers.addEventListener('click', function(e){

    e.preventDefault();
    let query = document.getElementById("txt_bsq_customers").value;
    let localidad = document.getElementById("sel_loc").value;
    let preSale = document.getElementById("preSale").value;
    let list_customers = document.getElementById("list_customers");
    let html = ""
    list_customers.innerHTML = GifLoader;
  
    const data = new URLSearchParams("query="+ query+"&localidad="+localidad);

    fetchAsyncPost(base_url+'customers/getQueryCustomers', data)
    .then(function(responseText){
      let registros = eval(responseText);
 
      if(registros == false){      
        list_customers.innerHTML ="<p class='text-center'>Sin resultados</p>";    
        return;
      }
      let razon = "";

      for (let i = 0; i < registros.length; i++) {

        html+="<div class='col-xs-12 f-product'>";
        if(preSale=="1"){
          html+="  <a href='"+base_url+"inicio/newOrder/" + registros[i]["codigo"] + "' class='product'>";
        }else{
          html+="  <a href='"+base_url+"customers/view/" + registros[i]["codigo"] + "' class='product'>";
        }

        razon = registros[i]["razonSocial"];
        if (razon.length > 45){
          razon = razon.substring(0,45) +"...";
        }

        html+="      <div class='col-xs-2'>";
        html+="          <img src='" + base_url + "assets/img/icon/customers.svg'>";
        html+="      </div>";
        html+="      <div class='col-xs-10 col-name-product'>";
        html+="          <p class='nameProduct'>"+razon+"</p>";
        html+="      </div>";
        html+="      <div class='col-xs-6 col-xs-offset-2 col-id-product'>";
        html+="          <small>"+registros[i]["codigo"] + "</small>";
        html+="      </div>";
        html+="  </a>";
        html+="</div>";
      }

      list_customers.innerHTML = html;
    })
    .catch(function(err){
      console.log("Error al realizar la peticion getQueryCustomers : " + err);
    });
  }, false);
}

let btn_new_order = document.getElementById('btn_new_order');
if(btn_new_order){
  btn_new_order.addEventListener('click', function(e){
    e.preventDefault();
    location.href = base_url+"customers/list_customersPreSale";
  }, false);
}

let btn_check_product = document.getElementById('btn_check_product');
if(btn_check_product){
  // btn_check_product.addEventListener('click', addManualProduct , false)
  btn_check_product.addEventListener('click', function(e){
    e.preventDefault();
    let load_div_order = document.getElementById('load_div_order');
    load_div_order.innerHTML = GifLoader;

    let codigo = document.getElementById("txt_newOrder_prod").value;

    if(codigo == ""){
      alert("Debe informar el código del producto.");
      load_div_order.innerHTML = "";
      return;
    }

    prevAgregar(codigo);
  }, false);
}

let btn_getStockActual = document.getElementById('btn_getStockActual');
if(btn_getStockActual){
  // btn_getStockActual.addEventListener('click', getStockActual, false);
  btn_getStockActual.addEventListener('click', function(e){
    e.preventDefault();
    
    let stock_product_add = document.getElementById('stock_product_add');

    stock_product_add.innerHTML = GifLoader+"<small style='display:block;' class='text-center'>Obteniendo stock...</small>";
    const data = new URLSearchParams("codigo="+mAux.get('codigo'));

    fetchAsyncPost(base_url+'products/getStockActual_byFetch', data)
    .then(function(responseText){
      stock_product_add.innerHTML = "Stock Actual : "+responseText;
      document.getElementById("qty_product_add").focus();
    })
    .catch(function(err){
      console.log("Error al realizar la peticion getQueryProducts : " + err);
    });
  }, false);
}

let btn_addProduct_accept = document.getElementById('btn_addProduct_accept');
if(btn_addProduct_accept){
  // btn_addProduct_accept.addEventListener('click', addProductAccept, false);
  btn_addProduct_accept.addEventListener('click', function(e){
    e.preventDefault();
    let cantidad = document.getElementById("qty_product_add").value;
    let unitario = mAux.get('unit');
    let codigo = mAux.get('codigo');
 
    agregar(codigo, "", unitario, cantidad);
  }, false);
}

function prevAgregar(codigo, clase=''){
  
  let load_div_order = document.getElementById('load_div_order');
  const data = new URLSearchParams("codigo="+ codigo+"&clase="+clase);

  fetchAsyncPost(base_url+'products/productExists', data)
    .then(function(responseText){
      let registros = eval(responseText);

      if(registros == false){      
        alert("El codigo informado no es correcto");
        load_div_order.innerHTML = "";
        return;
      }

      let stock_product_add = document.getElementById('stock_product_add');
      let desc = "";
      let unitario = "";

      for (let i = 0; i < registros.length; i++) {
        desc = registros[i]["descripcion"];
        unitario = registros[i]["precio"];

        mAux.set('unit',unitario);     
        mAux.set('codigo',codigo);

        document.getElementById("id_product_add").innerHTML = "#"+codigo;
        document.getElementById("desc_product_add").innerHTML = desc;
        
        stock_product_add.innerHTML = "";
        load_div_order.innerHTML = "";

        document.getElementById("qty_product_add").value = "";
        //REVISAR
        $('#modalProductsPreSale_qty').modal('show');
      }
    }).catch(function(err){
      console.log("Error al realizar la peticion productExists : " + err);
    });
}

//REVISAR
$('#modalProductsPreSale_qty').on('show.bs.modal', function (e) {
  setTimeout(function(){ 
    document.getElementById("qty_product_add").focus();
   }, 1000); 
  
});


let new_order_cancel = document.getElementById('new_order_cancel');
if(new_order_cancel){
  new_order_cancel.addEventListener('click', function(e){
    let idOrder = document.getElementById("idOrderHidden").value;

    let cancel
    if(idOrder>0){
      cancel = confirm("Se perderán los cambios no guardados. ¿Desea salir?");
    }else{
      cancel = confirm("¿Esta seguro que desea cancelar el pedido?");
    }

    if(cancel){
      if(getParameterByName('opcion') == 'ruta'){
        location.href = base_url+"vendors/ruta_diaria"
      }else{
        location.href = base_url+"inicio/list_orders"
      }
    }else{
      return;
    }
  }, false);
}

let new_order_ok = document.getElementById('new_order_ok');
if(new_order_ok){
  new_order_ok.addEventListener('click', function(e){
    let idOrder = document.getElementById("idOrderHidden").value;
    let idcustomer = document.getElementById("idCustomerHidden").value;

    const data = new URLSearchParams("idCustomer="+idcustomer+"&idOrder="+idOrder);

    fetchAsyncPost(base_url+'order/saveOrder', data)
      .then(function(responseText){
        if(responseText == ""){
          location.href = base_url+"inicio/list_orders"
        }else{
          alert("Hubo un error");
        }
      }).catch(function(err){
        console.log("Error al realizar la peticion saveOrder : " + err);
      });
  }, false);
}


let new_order_delete = document.getElementById('new_order_delete');
if(new_order_delete){
  new_order_delete.addEventListener('click', function(e){
    
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
  }, false);
}

let btn_search_prod = document.getElementById('btn_search_prod');
if(btn_search_prod){
  btn_search_prod.addEventListener('click', function(e){
    //REVISAR
    $('#modalProductsPreSale').modal('show');
  }, false);
}

let btn_add_prod = document.getElementById('btn_add_prod');
if(btn_add_prod){
  btn_add_prod.addEventListener('click', function(e){
    //REVISAR
    $('#modalProductsPreSale_qty').modal('show');
  }, false);
}

function delProductOrder(prodct){
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

let btn_start_sync = document.getElementById('btn_start_sync');
if(btn_start_sync){
  btn_start_sync.addEventListener('click', function(e){
    let div_img_categories = document.getElementById('div_img_categories');
    let div_img_vendors = document.getElementById('div_img_vendors');
    let div_img_customers = document.getElementById('div_img_customers');
    let div_img_products = document.getElementById('div_img_products');
    let div_img_families = document.getElementById('div_img_families');
    let div_img_visitas = document.getElementById('div_img_visitas');

    div_img_categories.innerHTML = imgSyncWait;
    div_img_vendors.innerHTML = imgSyncWait;
    div_img_customers.innerHTML = imgSyncWait;
    div_img_products.innerHTML = imgSyncWait;
    div_img_families.innerHTML = imgSyncWait;
    div_img_visitas.innerHTML = imgSyncWait;

    document.getElementById('msgStatus').style.display = "none";
    document.getElementById('btn_sync_close').style.display = "none";
    
    //REVISAR
    $('#modalSync').modal('show');
    StartSync();
  }, false);
}

function StartSync(){
  SyncCategories();
}

function SyncCategories(){
  let div_img_categories = document.getElementById('div_img_categories');
  div_img_categories.innerHTML = GifLoader;

  const data = new URLSearchParams("option=getRubrosD");

  fetchAsyncPost(base_url+'sync/insertSyncDataDB', data)
    .then(function(responseText){
      if(responseText == "OK"){
        div_img_categories.innerHTML = imgSyncOK;
        //loadDbOffline('rubros');
        SyncFamilies();
      }else{
        div_img_categories.innerHTML = imgSyncError;
        showErrorSync(responseText);
      }
    }).catch(function(err){
      console.log("Error al realizar la peticion insertSyncDataDB Rubros : " + err);
    });
}


function SyncFamilies(){ 
  let div_img_families = document.getElementById('div_img_families');
  div_img_families.innerHTML = GifLoader;

  const data = new URLSearchParams("option=getFamiliasD");

  fetchAsyncPost(base_url+'sync/insertSyncDataDB', data)
    .then(function(responseText){
      if(responseText == "OK"){
        div_img_families.innerHTML = imgSyncOK;
        //loadDbOffline('familias');
        SyncVendors();
      }else{
        div_img_families.innerHTML = imgSyncError;
        showErrorSync(responseText);
      }
    }).catch(function(err){
      console.log("Error al realizar la peticion insertSyncDataDB Familias : " + err);
    });
}

function SyncVendors(){
  let div_img_vendors = document.getElementById('div_img_vendors');
  div_img_vendors.innerHTML = GifLoader;

  const data = new URLSearchParams("option=getVendedoresD");

  fetchAsyncPost(base_url+'sync/insertSyncDataDB', data)
    .then(function(responseText){
      if(responseText == "OK"){
        div_img_vendors.innerHTML = imgSyncOK;
        loadDbOffline('vendedores', SyncVisitas);
        // SyncCustomers();
      }else{
        div_img_vendors.innerHTML = imgSyncError;
        showErrorSync(responseText);
      }
    }).catch(function(err){
      console.log("Error al realizar la peticion insertSyncDataDB Vendedores : " + err);
    });
}

function SyncVisitas(){ 
  let div_img_visitas = document.getElementById('div_img_visitas');
  div_img_visitas.innerHTML = GifLoader;

  const data = new URLSearchParams("option=getVisitasVendedor");

  fetchAsyncPost(base_url+'sync/insertSyncDataDB', data)
    .then(function(responseText){
      if(responseText == "OK"){
        div_img_visitas.innerHTML = imgSyncOK;
        //loadDbOffline('familias');
        loadDbOffline('vendedores', SyncCustomers);
      }else{
        div_img_visitas.innerHTML = imgSyncError;
        showErrorSync(responseText);
      }
    }).catch(function(err){
      console.log("Error al realizar la peticion insertSyncDataDB Visitas : " + err);
    });
}

function SyncCustomers(){
  let div_img_customers = document.getElementById('div_img_customers');
  div_img_customers.innerHTML = GifLoader;

  const data = new URLSearchParams("option=getClientesD");

  fetchAsyncPost(base_url+'sync/insertSyncDataDB', data)
    .then(function(responseText){
      if(responseText == "OK"){
        div_img_customers.innerHTML = imgSyncOK;
        loadDbOffline('clientes', SyncProducts);
        // SyncProducts();
      }else{
        div_img_customers.innerHTML = imgSyncError;
        showErrorSync(responseText);
      }
    }).catch(function(err){
      console.log("Error al realizar la peticion insertSyncDataDB Clientes : " + err);
    });
}

function SyncProducts(){
  let div_img_products = document.getElementById('div_img_products');
  div_img_products.innerHTML = GifLoader;

  const data = new URLSearchParams("option=getArticulosD");

  fetchAsyncPost(base_url+'sync/insertSyncDataDB', data)
    .then(function(responseText){
      if(responseText == "OK"){
        div_img_products.innerHTML = imgSyncOK;
        loadDbOffline('articulos', showSuccessSync);
        // showSuccessSync();
      }else{
        div_img_products.innerHTML = imgSyncError;
        showErrorSync(responseText);
      }
    }).catch(function(err){
      console.log("Error al realizar la peticion insertSyncDataDB Articulos : " + err);
    });
}

function showErrorSync(error){
  let msg = document.getElementById("msgStatus");
  msg.innerText = error;
  msg.className+=" bg-danger";
  document.getElementById('btn_sync_close').style.display = "block";
  document.getElementById('msgStatus').style.display = "block";
}

function showSuccessSync(){
  let msg = document.getElementById("msgStatus");
  msg.innerText = "Datos sincronizados correctamente";
  msg.className+=" bg-success";
  document.getElementById('btn_sync_close').style.display = "block";
  document.getElementById('msgStatus').style.display = "block";
}

let btn_send_orders = document.getElementById('btn_send_orders');
if(btn_send_orders){
  btn_send_orders.addEventListener('click', function(e){

    let div_img_sendOrder = document.getElementById('div_img_sendOrder');
    div_img_sendOrder.innerHTML = GifLoader;
    
    document.getElementById('msgStatus_order').style.display = "none";
    document.getElementById('btn_sendorder_close').style.display = "none";

    //REVISAR
    $('#modalSendOrders').modal('show');

    let msg = document.getElementById("msgStatus_order");
    let resultado = "";
    let clase = "";

    fetchAsyncPost(base_url+'order/sendOrders')
      .then(function(responseText){
        if(responseText == "OK"){
          div_img_sendOrder.innerHTML = imgSyncOK;
          resultado = "Los pedidos se enviaron correctamente";
          clase = " bg-success";
        }else{
          div_img_sendOrder.innerHTML = imgSyncError;
          resultado = responseText;
          clase = " bg-danger";
        }

        msg.innerText = resultado;
        msg.className+= clase;
        document.getElementById('btn_sendorder_close').style.display = "block";
        document.getElementById('msgStatus_order').style.display = "block";

      }).catch(function(err){
        console.log("Error al realizar la peticion sendOrders : " + err);
      });
  }, false);
}

let btn_sendorder_close = document.getElementById('btn_sendorder_close');
if(btn_sendorder_close){
  btn_sendorder_close.addEventListener('click', function(e){
    e.preventDefault();
    location.href = base_url;
  }, false);
}

let a_config_init = document.getElementById('a_config_init');
if(a_config_init){
  a_config_init.addEventListener('click', function(e){
    let clave = prompt("Ingrese la clave", "");

    if(clave == "alfa@"){
      location.href = base_url+"inicio/config";
    }else{
      alert("Acceso denegado.");    
    }
  }, false);
}

let btn_sync_close = document.getElementById('btn_sync_close');
if(btn_sync_close){
  btn_sync_close.addEventListener('click', function(e){
    location.href = base_url;
  }, false);
}

let sel_clase_precio = document.getElementById('sel_clase_precio');
if(sel_clase_precio){
  sel_clase_precio.addEventListener('change', function(e){
    //REVISAR
    $(".loaderWeb").fadeIn("slow");
    let list_order_products = document.getElementById('list_order_products');
    let preSale = document.getElementById("preSale").value;
    let list_products

    if(preSale != 1){
      list_products = document.getElementById("list_products");
    }

    const data = new URLSearchParams("clase="+this.value+"&preSale="+preSale);

    fetchAsyncPost(base_url+'inicio/cambiaClasePrecio', data)
      .then(function(responseText){
        if(preSale == 1){
          //REVISAR
          $("#modalProductsPreSale .modal-body").load(base_url+'inicio/reloadModalProdSale', function (){
            list_order_products.innerHTML = responseText;
            $(".loaderWeb").fadeOut("slow");
          });
        }else{
          list_products.innerHTML = responseText;
          $(".loaderWeb").fadeOut("slow");
        }
      }).catch(function(err){
        console.log("Error al realizar la peticion cambiaClasePrecio : " + err);
      });
  }, false);
}

let btn_search_history = document.getElementById('btn_search_history');
if(btn_search_history){
  btn_search_history.addEventListener('click', function(e){
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
    
  }, false);
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

async function loadDbOffline(name, callback = ""){
  let tabla = name
  const data = new URLSearchParams("name="+tabla);

  fetchAsyncPost(base_url+'sync/load_db_offline', data)
    .then(function(responseText){
      if(responseText != "NO") {
        if(tabla!='rubros'){
          saveTableLocalStorage(tabla, responseText)
          if(callback != ""){
            callback();
          }
        }
      }else{
        if(callback != ""){
          callback();
        }
      }
    }).catch(function(err){
      console.log("Error al realizar la peticion loadDbOffline : " + err);
    });
}

async function saveTableLocalStorage(table, data) {
  await insertToLocalStorage(table, data)
}

function insertToLocalStorage(table, data){

  let status_offline_sync = document.querySelector("#status_offline_sync")
  let datos = JSON.parse(data);
  let database = new local2json(table);

  database.delete()
  // let x;
  let total = datos.length
  // let pos = 0

  status_offline_sync.innerHTML += `${table[0].toUpperCase() + table.slice(1)} Offline ${total}<br>`;
  database.push(datos)

  // for(x of datos){
  //   pos++
  //   status_offline_sync.innerHTML = `Offline ${pos}/${total}`
  //   database.push(x);
  // }
}

function verificarPedidosOffline(){
  let db = new local2json('pedidos');
  const res = db.get();

  let datos_offline = document.getElementById('datos_offline');

  if(!res || res.length == 0){
    datos_offline.innerHTML = " (Sin datos)";
    return;
  }

  $('#modal_syncOffline').modal('show');

  let pedidos = JSON.stringify(res);

  const data = new URLSearchParams("pedidos="+pedidos);

  fetchAsyncPost(base_url+'order/saveOrderOffline', data)
    .then(function(responseText){
      
      if(responseText == 'OK'){
        //Si los grabo bien, borro los pedidos del localstorage
        db.delete();
        datos_offline.innerHTML = " (Sincronizado)";
      }else{
        alert("Hubo un error. Intente mas tarde o comuniquese con el encargado de sistemas.");
      }
      $('#modal_syncOffline').modal('hide');
      location.href = base_url;      
    }).catch(function(err){
      $('#modal_syncOffline').modal('hide');
      console.log("Error al realizar la peticion saveOrderOffline : " + err);
    });
}

function verificaDatosOffline(){
  //necesito que esten vendedores, articulos y clientes como minimo
  let db = new local2json('vendedores');
  let res = db.get();

  let status = document.getElementById('status_offline');

  if(!res || res.length == 0){
    status.innerHTML = "<button type='button' class='btn btn-danger'></button>";
    return;
  }

  db = new local2json('clientes');
  res = db.get();

  if(!res || res.length == 0){
    status.innerHTML = "<button type='button' class='btn btn-danger'></button>";
    return;
  }

  db = new local2json('articulos');
  res = db.get();

  if(!res || res.length == 0){
    status.innerHTML = "<button type='button' class='btn btn-danger'></button>";
    return;
  }

  status.innerHTML = "<button type='button' class='btn btn-success'></button>";
}

/* Cobranzas */
let btn_save_payment = document.querySelector("#btn-save-payment");
if(btn_save_payment) {
  btn_save_payment.addEventListener('click', (e) => {
    let fecha = document.querySelector("#date_new_payment").value
    let cuenta = document.querySelector("#sel_customer_new_payment").value
    let medio_de_pago = document.querySelector("#sel_mp_new_payment").value
    let importe = document.querySelector("#imp_payment").value
    let obs = document.querySelector("#obs_new_payment").value

    let loader = document.querySelector("#loader_new_payment")

    if(fecha == ""){
      alert("Debe informar la fecha!")
      return
    }

    if(cuenta == "") {
      alert("Debe informar la cuenta del cliente!")
      return
    }

    if(medio_de_pago == "") {
      alert("Debe informar el medio de pago!")
      return
    }

    if(importe <= 0) {
      alert("Debe informar el importe a cobrar!")
      return
    }

    loader.innerHTML = GifLoader

    const data = new URLSearchParams("fecha="+fecha+"&cuenta="+cuenta+"&mp="+medio_de_pago+"&importe="+importe+"&obs="+obs);

    fetchAsyncPost(base_url+'payments/save', data)
    .then(function(responseText){
      let res = JSON.parse(responseText)

      if(typeof res.error != 'undefined') {
        if(res.error){
          loader.innerHTML = `<p class="text-center bg-danger">${res.message}</p>`
          return;
        }
      }

      if(res[0].status == 'ok'){        
        location.href = base_url + "payments?message=ok";
      }else{
        alert("Hubo un error. Intente mas tarde o comuniquese con el encargado de sistemas.");
      }
      
    }).catch(function(err){
      console.log("Error al realizar la peticion payments/save : " + err);
    });

  })
}

let btn_search_payments = document.getElementById('btn_search_payments');
if(btn_search_payments){
  btn_search_payments.addEventListener('click', function(e){
    let fhd = document.getElementById('fechaD').value;
    let fhh = document.getElementById('fechaH').value;
    let customer = document.getElementById('sel_customer_history').value;
    let payment_list = document.getElementById('payment_list');

    payment_list.innerHTML = GifLoader + "<small style='display:block;' class='text-center'>Cargando cobranzas</small>";

    const data = new URLSearchParams("idCustomer="+customer+"&fhd="+fhd+"&fhh="+fhh);
    
    fetchAsyncPost(base_url+'payments/searchPayments', data)
      .then(function(responseText){
        payment_list.innerHTML = responseText;
      }).catch(function(err){
        console.log("Error al realizar la peticion searchPayments : " + err);
      });
    
  }, false);
}


let dev_btn_key = document.querySelector('.dev-btn-key')

if(dev_btn_key){
  dev_btn_key.addEventListener('click', (e) => {
    e.preventDefault()

    $('#modal_key').modal('show');
  
  }, false)
}

let btn_solicitar_key = document.querySelector('#btn_solicitar_key')
if(btn_solicitar_key) {
  btn_solicitar_key.addEventListener('click', (e) => {

    let key = document.querySelector('#txt_nro_clave').value
    if(key == '') {
      loader.innerHTML = '<p class="text-center bg-danger">Debe informar la clave</p>';
      return;
    }

    let loader = document.querySelector('.loader_key')
    loader.innerHTML = GifLoader

    const data = new URLSearchParams("key="+key);
    
    fetchAsyncPost(base_url+'developer/getSerie', data)
      .then(function(responseText){
        let res = JSON.parse(responseText)

        if(typeof res.error != 'undefined') {
          if(res.error){
            loader.innerHTML = `<p class="text-center bg-danger">${res.message}</p>`
            return;
          }
        }

        document.querySelector('#txt_nro_serie').value = res.serie
        loader.innerHTML = ''
      }).catch(function(err){
        console.log("Error al realizar la peticion getSerie : " + err);
      });
  }, false)
}


let item_ruta = document.querySelectorAll('.item-ruta')

item_ruta.forEach((item) => {
  item.addEventListener('click', (e) => {
    if(item.matches('.item-display')){
      item.classList.remove('item-display')
    }else{
      oculta_rutas()
      item.classList.add('item-display')
    }
  })
})

const oculta_rutas = (e) => {
  item_ruta.forEach((item) => {
    item.classList.remove('item-display')
  })
}