import { base_url } from "./config.js";
import { GifLoader, imgSyncOK, imgSyncWait, imgSyncError, fetchAsyncPost } from "./global.js";

function initSync() {
  let div_img_categories = document.getElementById("div_img_categories");
  let div_img_vendors = document.getElementById("div_img_vendors");
  let div_img_customers = document.getElementById("div_img_customers");
  let div_img_products = document.getElementById("div_img_products");
  let div_img_families = document.getElementById("div_img_families");
  let div_img_visitas = document.getElementById("div_img_visitas");
  let div_img_mp = document.getElementById("div_img_mp");
  let div_img_servicios = document.getElementById("div_img_servicios");

  div_img_categories.innerHTML = imgSyncWait;
  div_img_vendors.innerHTML = imgSyncWait;
  div_img_customers.innerHTML = imgSyncWait;
  div_img_products.innerHTML = imgSyncWait;
  div_img_families.innerHTML = imgSyncWait;
  div_img_visitas.innerHTML = imgSyncWait;
  div_img_mp.innerHTML = imgSyncWait;
  div_img_servicios.innerHTML = imgSyncWait;

  document.getElementById("msgStatus").style.display = "none";
  document.getElementById("btn_sync_close").style.display = "none";

  //REVISAR
  $("#modalSync").modal("show");
  SyncCategories();
}

function SyncCategories() {
  let div_img_categories = document.getElementById("div_img_categories");
  div_img_categories.innerHTML = GifLoader;

  const data = new URLSearchParams("option=getRubrosD");

  fetchAsyncPost(base_url + "sync/insertSyncDataDB", data)
    .then(function (responseText) {
      if (responseText == "OK") {
        div_img_categories.innerHTML = imgSyncOK;
        //loadDbOffline('rubros');
        SyncFamilies();
      } else {
        div_img_categories.innerHTML = imgSyncError;
        showErrorSync(responseText);
      }
    })
    .catch(function (err) {
      console.log("Error al realizar la peticion insertSyncDataDB Rubros : " + err);
    });
}

function SyncFamilies() {
  let div_img_families = document.getElementById("div_img_families");
  div_img_families.innerHTML = GifLoader;

  const data = new URLSearchParams("option=getFamiliasD");

  fetchAsyncPost(base_url + "sync/insertSyncDataDB", data)
    .then(function (responseText) {
      if (responseText == "OK") {
        div_img_families.innerHTML = imgSyncOK;
        //loadDbOffline('familias');
        SyncVendors();
      } else {
        div_img_families.innerHTML = imgSyncError;
        showErrorSync(responseText);
      }
    })
    .catch(function (err) {
      console.log("Error al realizar la peticion insertSyncDataDB Familias : " + err);
    });
}

function SyncVendors() {
  let div_img_vendors = document.getElementById("div_img_vendors");
  div_img_vendors.innerHTML = GifLoader;

  const data = new URLSearchParams("option=getVendedoresD");

  fetchAsyncPost(base_url + "sync/insertSyncDataDB", data)
    .then(function (responseText) {
      if (responseText == "OK") {
        div_img_vendors.innerHTML = imgSyncOK;
        loadDbOffline("vendedores", SyncVisitas);
        // SyncCustomers();
      } else {
        div_img_vendors.innerHTML = imgSyncError;
        showErrorSync(responseText);
      }
    })
    .catch(function (err) {
      console.log("Error al realizar la peticion insertSyncDataDB Vendedores : " + err);
    });
}

function SyncVisitas() {
  let div_img_visitas = document.getElementById("div_img_visitas");
  div_img_visitas.innerHTML = GifLoader;

  const data = new URLSearchParams("option=getVisitasVendedor");

  fetchAsyncPost(base_url + "sync/insertSyncDataDB", data)
    .then(function (responseText) {
      if (responseText == "OK") {
        div_img_visitas.innerHTML = imgSyncOK;
        //loadDbOffline('familias');
        loadDbOffline("vendedores", SyncCustomers);
      } else {
        div_img_visitas.innerHTML = imgSyncError;
        showErrorSync(responseText);
      }
    })
    .catch(function (err) {
      console.log("Error al realizar la peticion insertSyncDataDB Visitas : " + err);
    });
}

function SyncCustomers() {
  let div_img_customers = document.getElementById("div_img_customers");
  div_img_customers.innerHTML = GifLoader;

  const data = new URLSearchParams("option=getClientesD");

  fetchAsyncPost(base_url + "sync/insertSyncDataDB", data)
    .then(function (responseText) {
      if (responseText == "OK") {
        div_img_customers.innerHTML = imgSyncOK;
        loadDbOffline("clientes", SyncProducts);
        // SyncProducts();
      } else {
        div_img_customers.innerHTML = imgSyncError;
        showErrorSync(responseText);
      }
    })
    .catch(function (err) {
      console.log("Error al realizar la peticion insertSyncDataDB Clientes : " + err);
    });
}

function SyncProducts() {
  let div_img_products = document.getElementById("div_img_products");
  div_img_products.innerHTML = GifLoader;

  const data = new URLSearchParams("option=getArticulosD");

  fetchAsyncPost(base_url + "sync/insertSyncDataDB", data)
    .then(function (responseText) {
      if (responseText == "OK") {
        div_img_products.innerHTML = imgSyncOK;
        loadDbOffline("articulos", SyncMediosPago);
        // showSuccessSync();
      } else {
        div_img_products.innerHTML = imgSyncError;
        showErrorSync(responseText);
      }
    })
    .catch(function (err) {
      console.log("Error al realizar la peticion insertSyncDataDB Articulos : " + err);
    });
}

function SyncMediosPago() {
  let div_img_mp = document.getElementById("div_img_mp");
  div_img_mp.innerHTML = GifLoader;

  const data = new URLSearchParams("option=getMediosPago");

  fetchAsyncPost(base_url + "sync/insertSyncDataDB", data)
    .then(function (responseText) {
      if (responseText == "OK") {
        div_img_mp.innerHTML = imgSyncOK;
        loadDbOffline("mediospago", SyncServicios);
      } else {
        div_img_mp.innerHTML = imgSyncError;
        showErrorSync(responseText);
      }
    })
    .catch(function (err) {
      console.log("Error al realizar la peticion insertSyncDataDB Medios de pago : " + err);
    });
}

function SyncServicios() {
  let div_img_servicios = document.getElementById("div_img_servicios");
  div_img_servicios.innerHTML = GifLoader;

  const data = new URLSearchParams("option=getServicios");

  fetchAsyncPost(base_url + "sync/insertSyncDataDB", data)
    .then(function (responseText) {
      if (responseText == "OK") {
        div_img_servicios.innerHTML = imgSyncOK;
        loadDbOffline("servicios", showSuccessSync);
      } else {
        div_img_servicios.innerHTML = imgSyncError;
        showErrorSync(responseText);
      }
    })
    .catch(function (err) {
      console.log("Error al realizar la peticion insertSyncDataDB Servicios : " + err);
    });
}

function showErrorSync(error) {
  let msg = document.getElementById("msgStatus");
  msg.innerText = error;
  msg.className += " bg-danger";
  document.getElementById("btn_sync_close").style.display = "block";
  document.getElementById("msgStatus").style.display = "block";
}

function showSuccessSync() {
  let msg = document.getElementById("msgStatus");
  msg.innerText = "Datos sincronizados correctamente";
  msg.className += " bg-success";
  document.getElementById("btn_sync_close").style.display = "block";
  document.getElementById("msgStatus").style.display = "block";
}

async function loadDbOffline(name, callback = "") {
  let tabla = name;
  const data = new URLSearchParams("name=" + tabla);

  fetchAsyncPost(base_url + "sync/load_db_offline", data)
    .then(function (responseText) {
      if (responseText != "NO") {
        if (tabla != "rubros") {
          saveTableLocalStorage(tabla, responseText);
          if (callback != "") {
            callback();
          }
        }
      } else {
        if (callback != "") {
          callback();
        }
      }
    })
    .catch(function (err) {
      console.log("Error al realizar la peticion loadDbOffline : " + err);
    });
}

async function saveTableLocalStorage(table, data) {
  await insertToLocalStorage(table, data);
}

function insertToLocalStorage(table, data) {
  let status_offline_sync = document.querySelector("#status_offline_sync");
  let datos = JSON.parse(data);
  let database = new local2json(table);

  database.delete();
  // let x;
  let total = datos.length;
  // let pos = 0

  status_offline_sync.innerHTML += `${
    table[0].toUpperCase() + table.slice(1)
  } Offline ${total}<br>`;
  database.push(datos);

  // for(x of datos){
  //   pos++
  //   status_offline_sync.innerHTML = `Offline ${pos}/${total}`
  //   database.push(x);
  // }
}

function verificarPedidosOffline() {
  let db = new local2json("pedidos");
  const res = db.get();

  let datos_offline = document.getElementById("datos_offline");

  if (!res || res.length == 0) {
    datos_offline.innerHTML = " (Sin datos)";
    return;
  }

  $("#modal_syncOffline").modal("show");

  let pedidos = JSON.stringify(res);

  const data = new URLSearchParams("pedidos=" + pedidos);

  fetchAsyncPost(base_url + "order/saveOrderOffline", data)
    .then(function (responseText) {
      if (responseText == "OK") {
        //Si los grabo bien, borro los pedidos del localstorage
        db.delete();
        datos_offline.innerHTML = " (Sincronizado)";
      } else {
        alert("Hubo un error. Intente mas tarde o comuniquese con el encargado de sistemas.");
      }
      $("#modal_syncOffline").modal("hide");
      location.href = base_url;
    })
    .catch(function (err) {
      $("#modal_syncOffline").modal("hide");
      console.log("Error al realizar la peticion saveOrderOffline : " + err);
    });
}

function verificaDatosOffline() {
  //necesito que esten vendedores, articulos y clientes como minimo
  let db = new local2json("vendedores");
  let res = db.get();

  let status = document.getElementById("status_offline");

  if (!res || res.length == 0) {
    status.innerHTML = "<button type='button' class='btn btn-danger'></button>";
    return;
  }

  db = new local2json("clientes");
  res = db.get();

  if (!res || res.length == 0) {
    status.innerHTML = "<button type='button' class='btn btn-danger'></button>";
    return;
  }

  db = new local2json("articulos");
  res = db.get();

  if (!res || res.length == 0) {
    status.innerHTML = "<button type='button' class='btn btn-danger'></button>";
    return;
  }

  status.innerHTML = "<button type='button' class='btn btn-success'></button>";
}

let resToSend = "";

async function sendMovements() {
  /**
   * Envio los movimientos en este orden
   * Pedidos
   * Cobranzas
   * Tareas
   */
  let msg = document.getElementById("msgStatus_order");

  let div_img_sendOrder = document.getElementById("div_img_sendOrder");
  div_img_sendOrder.innerHTML = GifLoader;

  document.getElementById("msgStatus_order").style.display = "none";
  document.getElementById("btn_sendorder_close").style.display = "none";

  $("#modalSendOrders").modal("show");

  /* Pedidos */
  await sendMovement("order/sendOrders");
  if (resToSend != "ok") {
    showErrorToSend(resToSend);
    return;
  }

  /* Cobranzas */
  await sendMovement("payments/sendPayments");
  if (resToSend != "ok") {
    showErrorToSend(resToSend);
    return;
  }

  /* Tareas */
  await sendMovement("task/sendTasks");
  if (resToSend != "ok") {
    showErrorToSend(resToSend);
    return;
  }

  div_img_sendOrder.innerHTML = imgSyncOK;
  let clase = " bg-success";

  msg.innerText = "Los movimientos se enviaron correctamente";
  msg.className += clase;
  document.getElementById("btn_sendorder_close").style.display = "block";
  document.getElementById("msgStatus_order").style.display = "block";
}

function showErrorToSend(error) {
  let msg = document.getElementById("msgStatus_order");
  let div_img_sendOrder = document.getElementById("div_img_sendOrder");

  div_img_sendOrder.innerHTML = imgSyncError;
  let clase = " bg-danger";

  msg.innerText = error;
  msg.className += clase;
  document.getElementById("btn_sendorder_close").style.display = "block";
  document.getElementById("msgStatus_order").style.display = "block";
}

async function sendMovement(endpoint) {
  resToSend = "";
  await fetchAsyncPost(base_url + endpoint)
    .then(function (responseText) {
      if (responseText == "OK") {
        resToSend = "ok";
      } else {
        resToSend = responseText;
      }
    })
    .catch(function (err) {
      console.log(`Error al realizar la peticion ${endpoint} : ${err}`);
    });
}

export { initSync, sendMovements };
