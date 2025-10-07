import { base_url } from './config.js';
import { GifLoader, fetchAsyncPost, mAux } from './global.js';

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

function addManualProduct(e) {
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
}

function getStockActual(e) {
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
}

function addProductAccept(e) {
    e.preventDefault();
    let cantidad = document.getElementById("qty_product_add").value;
    let unitario = mAux.get('unit');
    let codigo = mAux.get('codigo');
 
    agregar(codigo, "", unitario, cantidad);
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

export {
    selProdPreSale,
    searchProduct,
    prevAgregar,
    addManualProduct,
    getStockActual,
    addProductAccept
}