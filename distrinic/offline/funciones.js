'use strict'
let base_url = "../";
let mAux = new Map([['unit', ''], ['codigo', '']]);
let mPedidoCab = new Map([['cuenta',''], ['razon', ''], ['fecha', ''], ['idtmp', ''], ['clase', '']]);
let GifLoader = "<img class='center' src='"+base_url+"assets/img/ajax-loader.gif' >";
let imgSyncWait = "<img style='max-width:31px;' class='center' src='"+base_url+"assets/img/icon/timer.svg'>";
let imgSyncOK = "<img class='center' src='"+base_url+"assets/img/icon/tick.svg'>";
let imgSyncError = "<img class='center' src='"+base_url+"assets/img/icon/cancel.svg'>";

//Array para los productos en carrito
let carrito = [];

let colRazon = "";
let noMuestraTotales = false;
let preSale = false;

let colUnitario = "";
let coloff = "";
let coltot = "";
let colDesc = "";
let clase_precio = 1

window.onload = function(){
  $(".loaderWeb").fadeOut("slow");
}
/*
if('serviceWorker' in navigator){
  navigator.serviceWorker.register(base_url+'sw.js')
  .then(reg => console.log('Registro de SW exitoso', reg))
  .catch(err => console.warn('Error al registrar SW',err))
}*/


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
    }else if(e.target.id == 'a_bsq_customers'){
      e.preventDefault();
      searchCustomer();
    }else if(e.target.id == 'a_prod_preSale'){
      e.preventDefault();
      selProdPreSale(e.target);
    }else if(e.target.id == 'a_prod_view'){
      e.preventDefault();
      viewProdModal(e.target);
    }else if(e.target.className == 'del_product_order' || e.target.id == 'del_product_order'){
      delProductOrder(e.target);
    }else if(e.target.className == 'nameCliente' || e.target.className == 'order-fh'){
      del_order(e.target.id);
    }else if(e.target.className == 'item_historyOrder'){
      getHistoryCpte(e.target.name);
    }else if(e.target.className == 'nameCustomer'){
      e.preventDefault();
      select_customer(e.target.id, e.target.textContent, e.target.dataset.clase);
    }
  }
  
});

function select_customer(codigo, razon, clase){
  //alert(codigo + " " + razon);
  
  let f = new Date();
  mPedidoCab.set('cuenta', codigo);
  mPedidoCab.set('razon', razon);
  mPedidoCab.set('fecha', f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());
  mPedidoCab.set('idtmp', parseFloat(f.getDate()) + parseFloat(f.getMonth()+1) + parseFloat(f.getFullYear()) + parseFloat(f.getHours()) + parseFloat(f.getMinutes()) + parseFloat(f.getSeconds()));
  mPedidoCab.set('clase', clase)
  clase_precio = parseInt(clase)
  if(clase_precio == 0) {
    clase_precio = 1
  }

  let selectClasePrecio = document.querySelector('#sel_clase_precio')
  selectClasePrecio.value = clase_precio

  let html = "";

  html = "<a href='javascript:void(0);'' id='sel_cliente_order'>Cambiar Cliente</a>";
  html+= "<p>"+ codigo + " - " + razon + "</p>";

  document.getElementById("resumen_pedido").innerHTML = html;
  $('#modalProductsPreSale').modal('hide');
}

function viewProdModal(element){
  let codigo = element.name;

  let html = "";

  let db = new local2json('articulos');
  let result;
  let x;

  const res = db.get();

  result = filterProdByCod(res[0], codigo);

  if(result == false){      
    alert("El codigo informado no es correcto");
    load_div_order.innerHTML = "";
    return;
  }

  for (x of result) {
    
    html+="<div class='container'>";
    
    html+="<div class='row p-10 fProduct'>";
    html+="    <div class='col-xs-4'><label>Código</label></div>";
    html+="    <div class='col-xs-8'><input type='text' class='form-control' placeholder='Código' readonly='readonly' value='"+x.idArticulo+"'></div>";
    html+="</div>";

    html+="<div class='row p-10 fProduct'>";
    html+="    <div class='col-xs-4'><label>Descripción</label></div>";
    html+="    <div class='col-xs-8'>";
    html+="        <textarea rows='4' type='text' class='form-control' placeholder='Código' readonly='readonly'>"+x.descripcion+"</textarea>";
    html+="    </div>";
    html+="</div>";

    html+="<div class='row p-10 fProduct'>";
    html+="    <div class='col-xs-4'><label>Alic Iva</label></div>";
    html+="    <div class='col-xs-8'><input type='text' class='form-control' placeholder='Iva' readonly='readonly' value='"+x.iva+"'></div>";
    html+="</div>";

    for (let i = 1; i <= 10; i++) {
      html+="<div class='row p-10 fProduct'>";
      html+="    <div class='col-xs-4'><label>Precio "+i+"</label></div>";
      html+="    <div class='col-xs-8'><input type='text' class='form-control' placeholder='Precio' readonly='readonly' value='"+x["precio"+i]+"'></div>";
      html+="</div>";
    }


    html+="</div>"; //container

  }

  document.getElementById("modalProductsPreSale_body").innerHTML = html;

  $('#modalProductsPreSale').modal('show');
}

function selProdPreSale(element){
  //REVISAR
  $('#modalProductsPreSale').modal('hide');
  let load_div_order = document.getElementById("load_div_order");
  load_div_order.innerHTML = GifLoader;
  
  let codigo = element.name;

  if(codigo == ""){
    alert("Debe informar el código del producto.");
    load_div_order.innerHTML = "";
    return;
  }

  prevAgregar(codigo);
}

function searchProduct(){
  //a_bsq_products.addEventListener('click', function(e){
  
    //e.preventDefault();
    let query = document.getElementById("txt_bsq_products").value;
    //var preSale = document.getElementById("preSale").value;
    /*var list_products = document.getElementById("list_products");
    var desc = "";
    var html = "";*/

    //location.href = base_url+"?query="+query+"&preSale="&preSale;
    //return;
    //html = "<img class='center' src='"+base_url+"assets/img/ajax-loader.gif' >";
    //list_products.innerHTML = GifLoader;

    if(preSale){
      let productos = loadFromLocalStorage('articulos', query, true);
      document.getElementById('modalProductsPreSale_body').innerHTML = productos;
    }else{
      loadFromLocalStorage('articulos', query);
    }
    return;
    /*html = "";

    const data = new URLSearchParams("query="+ query+"&preSale="+preSale);

    fetchAsyncPost(base_url+'products/getQueryProducts', data)
    .then(function(responseText){
      list_products.innerHTML = responseText;
    })
    .catch(function(err){
      console.log("Error al realizar la peticion getQueryProducts : " + err);
    });*/
  //}, false);
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
  btn_addProduct_accept.addEventListener('click', function(e){
    e.preventDefault();
    let cantidad = document.getElementById("qty_product_add").value;
    let unitario = mAux.get('unit');
    let codigo = mAux.get('codigo');
    let desc = document.getElementById('desc_product_add').innerHTML;

    agregar(codigo, desc, unitario, cantidad);
  }, false);
}

/*OK*/
function prevAgregar(codigo){
  
  let load_div_order = document.getElementById('load_div_order');
  //let modalProductsPreSale_qty = document.getElementById('modalProductsPreSale_qty');

  let db = new local2json('articulos');
  let result;

  const res = db.get();

  result = filterProdByCod(res[0], codigo);

  if(result == false){      
    alert("El codigo informado no es correcto");
    load_div_order.innerHTML = "";
    return;
  }

  let stock_product_add = document.getElementById('stock_product_add');
  let desc = "";
  let unitario = "";
  let x;

  for (x of result) {
    desc = x.descripcion;

    unitario = precioSegunClase(x)

    mAux.set('unit',unitario);     
    mAux.set('codigo',codigo);

    document.getElementById("id_product_add").innerHTML = "#"+codigo;
    document.getElementById("desc_product_add").innerHTML = desc;
    
    stock_product_add.innerHTML = "";
    load_div_order.innerHTML = "";

    document.getElementById("qty_product_add").value = "";

    $('#modalProductsPreSale_qty').modal('show');
  }
}

function loadCarrito(){
  let mask = "";
  let items = 0;
  let totalFinal = 0;

  const carro = carrito.map(function(p){
    mask+="<div class='row'>";
    mask+="<div class='"+colDesc+"' id='del_product_order' name='"+p.codigo+"'>";
    mask+="<a href='javascript:void(0);' class='del_product_order' name='"+p.codigo+"'>";
    mask+=p.descripcion;
    mask+="</a>";

    mask+="<div class='row detail-order-products'>";
    mask+="<div class='col-xs-2 text-right col-xs-offset-"+coloff+"'><span>"+p.cantidad+"</span></div>";
    mask+="<div class='col-xs-"+colUnitario+" text-right'><span>"+p.unitario+"</span></div>";
    
    if(noMuestraTotales){
      mask+="<div class='col-xs-"+coltot+" text-right'><span>"+(p.cantidad*p.unitario)+"</span></div>";
    }
    
    mask+="</div>";
    mask+="</div>";
    mask+="</div>";
    items++;
    totalFinal+=(p.cantidad*p.unitario);
  });

  document.getElementById('row_detalle_order').innerHTML = mask;
  document.getElementById('cant_items').innerHTML = "Items: " + items;
  document.getElementById('total_order').innerHTML = "$ " + totalFinal;
}

//REVISAR
$('#modalProductsPreSale_qty').on('show.bs.modal', function (e) {
  /*$("#qty_product_add").trigger('focus');
  $("#qty_product_add").focus();*/
  setTimeout(function(){ 
    document.getElementById("qty_product_add").focus();
   }, 1000); 
  
});

function agregar(codigo,desc,unitario,cantidad){

  let cant = parseFloat(cantidad);
  let load_div_order = document.getElementById('load_div_order');
  let list_order_products = document.getElementById('row_detalle_order');
  let existe = false;

  load_div_order.innerHTML = "";
  list_order_products.innerHTML = GifLoader;

  const carro = carrito.map(function(p){
    if(p.codigo === codigo){
      existe = true;
      p.cantidad = parseFloat(p.cantidad) + parseFloat(cantidad); 
    }
  });

  if(!existe){
    carrito.push({
      "codigo": codigo,
      "descripcion": desc,
      "unitario": unitario,
      "cantidad": cantidad
    })
  }

  console.log(carrito);
  loadCarrito();

  $('#modalProductsPreSale_qty').modal('hide');

  document.getElementById("txt_newOrder_prod").value = "";      
  //list_order_products.innerHTML = responseText;
  document.getElementById("txt_newOrder_prod").focus();
  /*
  const data = new URLSearchParams("codigo="+codigo+"&cantidad="+cant+"&unit="+unitario+"&desc="+desc);

  fetchAsyncPost(base_url+'products/addProduct', data)
    .then(function(responseText){

      document.getElementById("qty_product_add").value = "";
      $('#modalProductsPreSale_qty').modal('hide');

      document.getElementById("txt_newOrder_prod").value = "";      
      list_order_products.innerHTML = responseText;
      document.getElementById("txt_newOrder_prod").focus();

    }).catch(function(err){
      console.log("Error al realizar la peticion addProduct : " + err);
    });
    */
}


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
      location.href = base_url+"offline/list_orders.php";
    }else{
      return;
    }
  }, false);
}

let new_order_ok = document.getElementById('new_order_ok');
if(new_order_ok){
  new_order_ok.addEventListener('click', function(e){
    //var idOrder = document.getElementById("idOrderHidden").value;
    //var idcustomer = document.getElementById("idCustomerHidden").value;

    let idcustomer = mPedidoCab.get('cuenta');

    if(idcustomer == ''){
      alert("Debe seleccionar un cliente!");
      return;
    }

    let detalle = [];
    let total = 0;
    let totalFinal = 0;

    let carro = carrito.map(function(p){
      total = parseFloat(p.cantidad) * parseFloat(p.unitario);
      detalle.push({
        "idArticulo": p.codigo,
        "descripcion": p.descripcion,
        "importeUnitario": p.unitario,
        "cantidad": p.cantidad,
        "total": total,
        "transferido": 0
      })
      totalFinal+=total;
      total = 0;
    });

    let db = new local2json('pedidos');
    db.push(
    {
      'idpedido': mPedidoCab.get('idtmp'),
      'codCliente': idcustomer,
      'razonSocial': mPedidoCab.get('razon'),
      'fecha': mPedidoCab.get('fecha'),
      'totalFinal': totalFinal,
      'detalle': detalle,
    }
    );

    location.href = base_url+"offline/list_orders.php";
    /*

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
      });*/
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
    //Cargo los productos de localstorage en el modal
    preSale = true;
    let productos = loadFromLocalStorage('articulos','', true);
    document.getElementById('modalProductsPreSale_body').innerHTML = productos;
    //preSale = false;
    $('#modalProductsPreSale').modal('show');
  }, false);
}

let sel_cliente_order = document.getElementById('sel_cliente_order');
if(sel_cliente_order){
  sel_cliente_order.addEventListener('click', function(e){
    let clientes = loadFromLocalStorage('clientes','', true);
    document.getElementById('modalProductsPreSale_body').innerHTML = clientes;
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
  let desc = prodct.innerText;// document.getElementById("desc_"+prodct.name).innerText;
  let pDelete = confirm("¿Desea eliminar el producto "+desc+" ?");

  let index = 0;

  if(pDelete){
    let rowid = prodct.name;
    //var list_order_products = document.getElementById('row_detalle_order');

    const carro = carrito.map(function(p){
      if(p.codigo === rowid){
        carrito.splice(index ,1);
      }
      index++;
    });

    loadCarrito();
    document.getElementById("txt_newOrder_prod").focus();

    /*
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
      });*/
  }else{
    return;
    }
}

/*
var del_product_order = document.getElementById('del_product_order');
if(del_product_order){
  del_product_order.addEventListener('click', function(e){
    var desc = document.getElementById("desc_"+this.name).innerText;
    var pDelete = confirm("¿Desea eliminar el producto "+desc+" ?");

    if(pDelete){
      var rowid = this.name;
      var list_order_products = document.getElementById('list_order_products');

      const carro = carrito.map(function(p){
        if(p.codigo === rowid){
          carrito.splice(p ,1);
        }
      });

      document.getElementById("txt_newOrder_prod").focus();
      loadCarrito();
      const data = new URLSearchParams("rowid="+rowid);

      fetchAsyncPost(base_url+'inicio/deleteOrder_product', data)
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
  }, false);
}*/

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

    //const data = new URLSearchParams("codigo="+codigo);

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

    if(preSale != 1){
      let list_products = document.getElementById("list_products");
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



function del_order(id){
  let pDelete = confirm("¿Desea eliminar el pedido seleccionado?");

  if(pDelete){

    let x;
    let i;
    let db = new local2json('pedidos');
    const res = db.get();

    let pedidos = [];
    let detalle = [];

    for(x of res){
      if(x.idpedido != id){
        
        detalle = [];

        for(i of x.detalle){
          detalle.push({
            'cantidad': i.cantidad,
            'descripcion': i.descripcion,
            'idArticulo': i.idArticulo,
            'importeUnitario': i.importeUnitario,
            'total': i.total,
            'transferido': i.transferido
          })
        }

        pedidos.push({
          'idpedido': x.idpedido,
          'codCliente': x.codCliente,
          'razonSocial': x.razonSocial,
          'fecha': x.fecha,
          'totalFinal': x.totalFinal,
          'detalle': detalle
        })
      }
    }

    db.set(pedidos);
    loadFromLocalStorage('pedidos','');
  }

}

// nombre de la coleccion de datos
function local2json(name){
  // asignamos un valor o recuperamos datos almacenados
  let DB = (localStorage.getItem(name))?JSON.parse(localStorage.getItem(name)):[];

  /* metodos */
  return{
    // obtener todos los datos de la coleccion
    get : ()=>{
      return DB;
    },
    // ingresar nuevos datos
    push  : (obj)=>{
      DB.push(obj);
      localStorage.setItem(name,JSON.stringify(DB));
    },
    // ingresar una nueva coleccion
    set : (colection)=>{
      DB = colection;
      localStorage.setItem(name,JSON.stringify(DB));
    },
    // eliminar todos los datos de la coleccion
    delete  : ()=>{
      DB = [];
      localStorage.setItem(name,JSON.stringify(DB));
    }
  }
}

function filterCustomer(obj, query) {
  return obj.filter(function(el) {
      return el.razonSocial.toLowerCase().indexOf(query.toLowerCase()) > -1;
  })
}

function filterProd(obj, query) {
  return obj.filter(function(el) {
    return el.descripcion.toLowerCase().indexOf(query.toLowerCase()) > -1;
  })
}

function filterProdByCod(obj, query) {
  return obj.filter(function(el) {
      return el.idArticulo.toLowerCase() == query.toLowerCase();
  })
}

function loadFromLocalStorage(name, filter='', retornar = false){

  //holaaa();

  let db = new local2json(name);
  let mask = "";
  let x;
  let result;
  let list;

  const res = db.get();

  if(filter != ''){
    if(name == 'articulos'){
      result = filterProd(res[0], filter);
    }else if(name == 'clientes'){
      result = filterCustomer(res[0], filter);
    }
  }else{
    if(name == 'pedidos'){
      //Los pedidos no se graban en la posicion 0 como los maestros
      result = res;
    }else{
      result = res[0];
    }
  }

  if(result.length > 50) {
    result = result.slice(0,50)
  }

  if(name == 'articulos' && preSale){
    mask+="<div class='row'>";
    mask+="<div class='col-xs-12'>";
    mask+="<form class='formBusqueda' action=''>";
    mask+="<div class='col-xs-9'>";
    mask+="<input type='text' id='txt_bsq_products' placeholder='descripcion'/>";
    mask+="</div>";
    mask+="<div class='col-xs-3'>";
    mask+="<button id='a_bsq_products'>Buscar</button>";
    mask+="</div>";
    mask+="</form>";
    mask+="</div>";
    mask+="</div>";
  }

  if(name == 'clientes'){
    //mask+="<div class='container'>";
    mask+="  <div class='row'>";
    mask+="      <div class='col-xs-12'>";
    mask+="          <form class='formBusqueda' action=''>";
    mask+="              <div class='col-xs-9'>";
    mask+="                  <input type='text' id='txt_bsq_customers' placeholder='razon social'/>";
    mask+="              </div>";
    mask+="              <div class='col-xs-3'>";
    mask+="                  <button id='a_bsq_customers'>Buscar</button>";
    mask+="              </div>";
    mask+="          </form>";
    mask+="      </div>";
    mask+="  </div>";
  }


  for(x of result){
    if(name == 'articulos'){
      
      mask+="<div class='col-xs-12 f-product'>";
      mask+="<div class='col-xs-2'>";
      mask+="<img alt='barcode' src='../assets/img/icon/barcode.svg' />";
      mask+="</div>";
      
      mask+="<div class='col-xs-10 col-name-product'>";
      mask+="<p class='nameProduct'>";
      
      if(preSale){
        mask+="<a href='javascript:void(0);' id='a_prod_preSale' name='"+x.idArticulo+"' class='product'>"+x.descripcion+"</a></p>";
      }else{
        mask+="<a href='javascript:void(0);' id='a_prod_view' name='"+x.idArticulo+"' class='product'>"+x.descripcion+"</a></p>";
      }

      mask+="<div class='row'>";
      mask+="<div class='col-xs-8 col-id-product'>";
      mask+="<small>#"+x.idArticulo+"</small>";
      mask+="</div>";
      mask+="<div class='col-xs-4 col-price-product'>";
      mask+="<small>$"+precioSegunClase(x)+"</small>";
      mask+="</div></div>";
      mask+="</div></div>";
    }

    if(name == 'pedidos'){
      mask+="<div class='col-xs-12 f-item'>";
      mask+="<a href='javascript:void(0);' class='item'>";
      mask+="<div class='col-xs-2' style='padding:0;'>";
      mask+="<img alt='' src='./assets/img/icon/sent-order.svg'>";
      mask+="</div>";
      mask+="<div class='"+colRazon+" col-name-order' style='padding:0;'>";
      mask+="<p class='nameCliente' id='"+x.idpedido+"'>"+x.razonSocial+"</p>";
      mask+="<small class='order-fh' id='"+x.idpedido+"'>"+x.fecha+"</small>";
      mask+="</div>";

      if(!noMuestraTotales){
        mask+="<div class='col-xs-3 col-total-order'>";
        mask+="<small>$"+x.totalFinal+"</small>";
        mask+="</div>";
      }

      mask+="</a>";
      mask+="</div>"; 
    }

    if(name == 'clientes'){

      mask+="  <div class='row' id='list_customers'>";
      mask+="  <div class='col-xs-12 f-product'>";

      if(preSale){
        mask+="<a href='javascript:void(0);' id='"+x.codigo+"' name='"+x.razonSocial+"' class='selCliente'>";
      }else{
        mask+="<a href='#' class='product'>";
      }

      mask+="          <div class='col-xs-2'>";
      mask+="              <img alt='customer' src='../assets/img/icon/customers.svg' >";
      mask+="          </div>"; 
      mask+="          <div class='col-xs-10 col-name-product'>";
      mask+="              <p class='nameCustomer' data-clase='"+x.claseDePrecio+"' id='"+x.codigo+"' name='"+x.razonSocial+"'>"+x.razonSocial+"</p>";
      mask+="          </div>";
      mask+="          <div class='col-xs-6 col-xs-offset-2 col-id-product'>";
      mask+="              <small>"+x.codigo+"</small>";
      mask+="          </div>            ";
      mask+="      </a>";
      mask+="  </div>";
      mask+="  </div>"; //list_customers
      //mask+="</div>"; //Container
    }
  }

  if(name == 'articulos'){
    list = document.getElementById("list_products");
  }else if(name == 'pedidos'){
    list = document.getElementById("list_orders");
  }

  if(!retornar){
    list.innerHTML = mask;
  }else{
    return mask;
  }

}


function precioSegunClase(x){
  let precio = 0

  if(clase_precio == 1){
    precio = x.precio1
  }else if(clase_precio == 2){
    precio = x.precio2
  }else if(clase_precio == 3){
    precio = x.precio3
  }else if(clase_precio == 4){
    precio = x.precio4
  }else if(clase_precio == 5){
    precio = x.precio5
  }else if(clase_precio == 6){
    precio = x.precio6
  }else if(clase_precio == 7){
    precio = x.precio7
  }else if(clase_precio == 8){
    precio = x.precio8
  }else{
    precio = x.precio1;
  }

  return precio

}

let btn_login = document.getElementById("btn_login");
if(btn_login){
  btn_login.addEventListener('click', function(e){
    e.preventDefault();

    let cod = document.getElementById("codigo_login").value;
    let pass = document.getElementById("pass_login").value;

    if(cod == '' || pass == ''){
      alert("Debe informar codigo y contraseña!");
      return;
    }

    let db = new local2json('vendedores');
    let res = db.get();

    let v;
    for(v of res[0]){
      if(v.idVendedor === cod && v.codigoDeValidacion == pass){
        location.href = "offline/inicio.php";
        return;
      }
    }

    alert("Datos incorrectos!");

  }, false);
}