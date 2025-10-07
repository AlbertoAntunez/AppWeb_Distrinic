"use strict";

import { base_url } from "./config.js";
import { getKey } from "./global.js";

import {
  selProdPreSale,
  searchProduct,
  addManualProduct,
  getStockActual,
  addProductAccept,
} from "./products.js";

import {
  newOrder,
  newOrderCancel,
  newOrderDelete,
  newOrderSave,
  deleteProductOrder,
  selClasePrecios,
  searchHistory,
  getHistoryCpte,
} from "./orders.js";
import { initSync, sendMovements } from "./sync.js";
import { savePayment, searchPayments, deletePayment } from "./payments.js";
import { searchCustomer } from "./customers.js";

import { startDraw, stopDraw, drawLine, cleanSignature, saveTask, dibujarTmp } from "./tareas.js";

window.onload = function () {
  $(".loaderWeb").fadeOut("slow");
};

document.addEventListener("click", function (e) {
  if (e.target) {
    if (e.target.id == "a_bsq_products") {
      e.preventDefault();
      searchProduct();
    } else if (e.target.id == "a_prod_preSale") {
      e.preventDefault();
      selProdPreSale(e.target);
    } else if (e.target.className == "del_product_order" || e.target.id == "del_product_order") {
      deleteProductOrder(e.target);
    } else if (e.target.className == "item_historyOrder") {
      getHistoryCpte(e.target.name);
    } else if (e.target.id == "btn_sync_offline") {
      verificarPedidosOffline();
    } else if (e.target.id == "btn_getStockActual") {
      getStockActual(e);
    } else if (e.target.id == "btn_addProduct_accept") {
      addProductAccept(e);
    } else if (e.target.id == "btn_check_product" || e.target.id == "img_btn_check_product") {
      addManualProduct(e);
    } else if (e.target.id == "btn_new_order") {
      newOrder(e);
    } else if (e.target.id == "new_order_cancel" || e.target.id == "img_new_order_cancel") {
      newOrderCancel();
    } else if (e.target.id == "new_order_ok" || e.target.id == "img_new_order_ok") {
      newOrderSave();
    } else if (e.target.id == "new_order_delete" || e.target.id == "img_new_order_delete") {
      newOrderDelete();
    } else if (e.target.id == "btn_start_sync") {
      initSync();
    } else if (e.target.id == "btn_send_orders") {
      sendMovements(); //sendOrders();
    } else if (e.target.id == "btn-save-payment") {
      savePayment();
    } else if (e.target.id == "btn_search_payments") {
      searchPayments();
    } else if (e.target.id == "a_bsq_customers") {
      searchCustomer(e);
      // } else if (e.target.id == "sel_clase_precio") {
      //   selClasePrecios(e.target.value);
    } else if (e.target.id == "btn_sync_close") {
      location.href = base_url;
    } else if (e.target.id == "btn_sendorder_close") {
      e.preventDefault();
      location.href = base_url;
    } else if (e.target.id == "btn_search_prod" || e.target.id == "img_btn_search_prod") {
      $("#modalProductsPreSale").modal("show");
    } else if (e.target.id == "btn_add_prod") {
      $("#modalProductsPreSale_qty").modal("show");
    } else if (e.target.id == "btn_search_history") {
      searchHistory();
    } else if (e.target.id == "btn_solicitar_key") {
      getKey();
    } else if (e.target.id == "btn-save-task") {
      saveTask();
    } else if (e.target.id == "dibujar") {
      dibujarTmp();
    } else if (e.target.className == "item_payment") {
      deletePayment(e.target.id);
    }
  }
});

document.addEventListener("change", function (e) {
  if (e.target.id == "sel_clase_precio") {
    selClasePrecios(e.target.value);
  }
});

/*Abrir modal para ingresar cantidad de articulos en pedido */
$("#modalProductsPreSale_qty").on("show.bs.modal", function (e) {
  setTimeout(function () {
    document.getElementById("qty_product_add").focus();
  }, 1000);
});

/* Abrir configuracion */
let a_config_init = document.getElementById("a_config_init");
if (a_config_init) {
  a_config_init.addEventListener(
    "click",
    function (e) {
      let clave = prompt("Ingrese la clave", "");

      if (clave == "alfa@") {
        location.href = base_url + "inicio/config";
      } else {
        alert("Acceso denegado.");
      }
    },
    false
  );
}

/* Mostrar modal para generar nro serie */
let dev_btn_key = document.querySelector(".dev-btn-key");
if (dev_btn_key) {
  dev_btn_key.addEventListener(
    "click",
    (e) => {
      e.preventDefault();
      $("#modal_key").modal("show");
    },
    false
  );
}

/* Acordion de rutas preexistentes */
let item_ruta = document.querySelectorAll(".item-ruta");
item_ruta.forEach((item) => {
  item.addEventListener("click", (e) => {
    if (item.matches(".item-display")) {
      item.classList.remove("item-display");
    } else {
      oculta_rutas();
      item.classList.add("item-display");
    }
  });
});

const oculta_rutas = (e) => {
  item_ruta.forEach((item) => {
    item.classList.remove("item-display");
  });
};

/* Para firma */
// Eventos raton
let miCanvas = document.querySelector("#pizarra");
if (miCanvas) {
  miCanvas.addEventListener("mousedown", startDraw, false);
  miCanvas.addEventListener("mousemove", drawLine, false);
  miCanvas.addEventListener("mouseup", stopDraw, false);

  // Eventos pantallas táctiles
  miCanvas.addEventListener("touchstart", startDraw, false);
  miCanvas.addEventListener("touchmove", drawLine, false);

  const btnClearFirma = document.querySelector("#btn_clear_firma");
  btnClearFirma.addEventListener("click", cleanSignature, false);
}
