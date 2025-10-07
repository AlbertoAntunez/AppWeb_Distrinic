import { base_url } from './config.js';
import { fetchAsyncPost, GifLoader } from './global.js';

function searchCustomer(e) {
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
}


export {
    searchCustomer
}