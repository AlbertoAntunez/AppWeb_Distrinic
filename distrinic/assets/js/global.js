import { base_url } from './config.js'

let mAux = new Map([['unit', ''], ['codigo', '']]);
const GifLoader = "<img class='center' src='" + base_url + "assets/img/ajax-loader.gif' >";
const imgSyncWait = "<img style='max-width:31px;' class='center' src='" + base_url + "assets/img/icon/timer.svg'>";
const imgSyncOK = "<img class='center' src='" + base_url + "assets/img/icon/tick.svg'>";
const imgSyncError = "<img class='center' src='" + base_url + "assets/img/icon/cancel.svg'>";

async function fetchAsyncPost(url, body) {
  let response = await fetch(url, {
    method: 'POST',
    body: body
  });
  let data = await response.text();
  return data;
}

// nombre de la coleccion de datos
function local2json(name) {
  // asignamos un valor o recuperamos datos almacenados
  let DB = (localStorage.getItem(name)) ? JSON.parse(localStorage.getItem(name)) : [];

  /* metodos */
  return {
    // obtener todos los datos de la coleccion
    get: () => {
      return DB;
    },
    // ingresar nuevos datos
    push: (obj) => {
      DB.push(obj);
      localStorage.setItem(name, JSON.stringify(DB));
    },
    // ingresar una nueva coleccion
    set: (colection) => {
      DB = colection;
      localStorage.setItem(name, JSON.stringify(DB));
    },
    // eliminar todos los datos de la coleccion
    delete: () => {
      DB = [];
      localStorage.setItem(name, JSON.stringify(DB));
    }
  }
}

function getParameterByName(name) {
  name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
  return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function getKey() {
  let key = document.querySelector('#txt_nro_clave').value
  if (key == '') {
    loader.innerHTML = '<p class="text-center bg-danger">Debe informar la clave</p>';
    return;
  }

  let loader = document.querySelector('.loader_key')
  loader.innerHTML = GifLoader

  const data = new URLSearchParams("key=" + key);

  fetchAsyncPost(base_url + 'developer/getSerie', data)
    .then(function (responseText) {
      let res = JSON.parse(responseText)

      if (typeof res.error != 'undefined') {
        if (res.error) {
          loader.innerHTML = `<p class="text-center bg-danger">${res.message}</p>`
          return;
        }
      }

      document.querySelector('#txt_nro_serie').value = res.serie
      loader.innerHTML = ''
    }).catch(function (err) {
      console.log("Error al realizar la peticion getSerie : " + err);
    });
}

export {
  mAux,
  GifLoader,
  imgSyncError,
  imgSyncWait,
  imgSyncOK,
  fetchAsyncPost,
  local2json,
  getParameterByName,
  getKey
}