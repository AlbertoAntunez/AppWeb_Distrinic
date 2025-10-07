import { base_url } from "./config.js";
import { fetchAsyncPost, GifLoader } from "./global.js";

function savePayment() {
  let fecha = document.querySelector("#date_new_payment").value;
  let cuenta = document.querySelector("#sel_customer_new_payment").value;
  let medio_de_pago = document.querySelector("#sel_mp_new_payment").value;
  let importe = document.querySelector("#imp_payment").value;
  let obs = document.querySelector("#obs_new_payment").value;
  let tc = document.querySelector("#sel_tipocb").value;

  let loader = document.querySelector("#loader_new_payment");

  if (fecha == "") {
    alert("Debe informar la fecha!");
    return;
  }

  if (cuenta == "") {
    alert("Debe informar la cuenta del cliente!");
    return;
  }

  if (medio_de_pago == "") {
    alert("Debe informar el medio de pago!");
    return;
  }

  if (importe <= 0) {
    alert("Debe informar el importe a cobrar!");
    return;
  }

  loader.innerHTML = GifLoader;

  const data = new URLSearchParams(
    "fecha=" +
      fecha +
      "&cuenta=" +
      cuenta +
      "&mp=" +
      medio_de_pago +
      "&importe=" +
      importe +
      "&obs=" +
      obs +
      "&tc=" +
      tc
  );

  fetchAsyncPost(base_url + "payments/save", data)
    .then(function (responseText) {
      let res = JSON.parse(responseText);

      if (typeof res.error != "undefined") {
        if (res.error) {
          loader.innerHTML = `<p class="text-center bg-danger">${res.message}</p>`;
          return;
        }
      }

      if (res.status == "ok") {
        location.href = base_url + "payments?message=ok";
      } else {
        alert("Hubo un error. Intente mas tarde o comuniquese con el encargado de sistemas.");
      }
    })
    .catch(function (err) {
      console.log("Error al realizar la peticion payments/save : " + err);
    });
}

function searchPayments() {
  let fhd = document.getElementById("fechaD").value;
  let fhh = document.getElementById("fechaH").value;
  let customer = document.getElementById("sel_customer_history").value;
  let payment_list = document.getElementById("payment_list");

  payment_list.innerHTML =
    GifLoader + "<small style='display:block;' class='text-center'>Cargando cobranzas</small>";

  const data = new URLSearchParams("idCustomer=" + customer + "&fhd=" + fhd + "&fhh=" + fhh);

  fetchAsyncPost(base_url + "payments/searchPayments", data)
    .then(function (responseText) {
      payment_list.innerHTML = responseText;
    })
    .catch(function (err) {
      console.log("Error al realizar la peticion searchPayments : " + err);
    });
}

function deletePayment(id) {
  if (id == 0) {
    return;
  }
  let del = confirm("Esta seguro que desea eliminar la cobranza? No se podrá recuperar.");

  if (del) {
    const data = new URLSearchParams("id=" + id);

    fetchAsyncPost(base_url + "payments/delete", data)
      .then(function (responseText) {
        location.href = base_url + "payments?message=delete";
      })
      .catch(function (err) {
        console.log("Error al realizar la peticion delete payment : " + err);
      });
  }
}

export { savePayment, searchPayments, deletePayment };
