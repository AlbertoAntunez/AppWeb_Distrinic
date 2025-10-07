import { GifLoader, fetchAsyncPost } from "./global.js";
import { base_url } from "./config.js";
//======================================================================
// VARIABLES
//======================================================================
let miCanvas = document.querySelector("#pizarra");

let lineas = [];
let correccionX = 0;
let correccionY = 0;
let pintarLinea = false;
// Marca el nuevo punto
let nuevaPosicionX = 0;
let nuevaPosicionY = 0;

if (miCanvas) {
  let posicion = miCanvas.getBoundingClientRect();
  correccionX = posicion.x;
  correccionY = posicion.y;

  miCanvas.width = screen.width; //200;
  miCanvas.height = 300;
}
//======================================================================
// FUNCIONES
//======================================================================

function dibujarTmp() {
  let imagen = document.querySelector("#imagen");

  fetchAsyncPost(base_url + "task/temp")
    .then(function (responseText) {
      let res = JSON.parse(responseText);
      //   imageObj.src =
      // imagen.src = res.base[0].imagen;

      let ctx = miCanvas.getContext("2d");
      let img = new Image();
      let firma = res.base[0].imagen.replaceAll(" ", "+");

      console.log(typeof firma);
      img.src = firma;
      img.onload = () => {
        ctx.drawImage(img, 0, 0);
      };
    })
    .catch(function (err) {
      console.log("Error al realizar la peticion task/save : " + err);
    });
}

/**
 * Funcion que empieza a dibujar la linea
 */
function startDraw() {
  pintarLinea = true;
  lineas.push([]);
}

/**
 * Funcion que guarda la posicion de la nueva línea
 */
function guardarLinea() {
  lineas[lineas.length - 1].push({
    x: nuevaPosicionX,
    y: nuevaPosicionY,
  });
}

/**
 * Funcion dibuja la linea
 */
function drawLine(event) {
  event.preventDefault();
  if (pintarLinea) {
    let ctx = miCanvas.getContext("2d");
    // Estilos de linea
    ctx.lineJoin = ctx.lineCap = "round";
    ctx.lineWidth = 5;
    // Color de la linea
    ctx.strokeStyle = "#000";
    // ctx.strokeStyle = "#fff";
    // Marca el nuevo punto
    if (event.changedTouches == undefined) {
      // Versión ratón
      nuevaPosicionX = event.layerX;
      nuevaPosicionY = event.layerY;
    } else {
      // Versión touch, pantalla tactil
      nuevaPosicionX = event.changedTouches[0].pageX - correccionX;
      nuevaPosicionY = event.changedTouches[0].pageY - correccionY;
    }
    // Guarda la linea
    guardarLinea();
    // Redibuja todas las lineas guardadas
    ctx.beginPath();
    lineas.forEach(function (segmento) {
      ctx.moveTo(segmento[0].x, segmento[0].y);
      segmento.forEach(function (punto, index) {
        ctx.lineTo(punto.x, punto.y);
      });
    });
    ctx.stroke();
  }
}

/**
 * Funcion que deja de dibujar la linea
 */
function stopDraw() {
  pintarLinea = false;
  guardarLinea();
}

/**
 * Para limpiar firma
 */
function cleanSignature() {
  let ctx = miCanvas.getContext("2d");
  lineas = [];
  ctx.clearRect(0, 0, miCanvas.width, miCanvas.height);
}

function saveTask() {
  let firma = miCanvas.toDataURL("image/png");
  let fecha = document.querySelector("#date_new_task").value;
  let cuenta = document.querySelector("#sel_customer_new_task").value;
  let obs = document.querySelector("#obs_new_task").value;
  let service = document.querySelector("#sel_service_new_task").value;

  let imagen = document.querySelector("#imagenFirma");

  let loader = document.querySelector("#loader_new_task");

  if (fecha == "") {
    alert("Debe informar la fecha!");
    return;
  }

  if (cuenta == "") {
    alert("Debe informar la cuenta del cliente!");
    return;
  }

  imagen.value = firma;

  loader.innerHTML = GifLoader;
  const data = new URLSearchParams(
    "fecha=" +
      fecha +
      "&cuenta=" +
      cuenta +
      "&firma=" +
      imagen.value +
      "&obs=" +
      obs +
      "&service=" +
      service
  );

  fetchAsyncPost(base_url + "task/save", data)
    .then(function (responseText) {
      let res = JSON.parse(responseText);

      if (typeof res.error != "undefined") {
        if (res.error) {
          loader.innerHTML = `<p class="text-center bg-danger">${res.message}</p>`;
          return;
        }
      }

      if (res.status == "ok") {
        location.href = base_url + "task?message=ok";
      } else {
        alert("Hubo un error. Intente mas tarde o comuniquese con el encargado de sistemas.");
      }
    })
    .catch(function (err) {
      console.log("Error al realizar la peticion task/save : " + err);
    });
}

export { startDraw, drawLine, stopDraw, cleanSignature, saveTask, dibujarTmp };
