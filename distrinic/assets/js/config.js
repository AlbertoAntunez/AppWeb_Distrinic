// 'use strict'
const base_url = "https://www.alfagestion.com.ar/distrinic/";
/*
if('serviceWorker' in navigator){
    navigator.serviceWorker.register('./sw.js')
    .then(reg => console.log('Registro de SW exitoso', reg))
    .catch(err => console.warn('Error al registrar SW',err))
  }*/


// // nombre de la coleccion de datos
// function local2json(name){
//   // asignamos un valor o recuperamos datos almacenados
//   let DB = (localStorage.getItem(name))?JSON.parse(localStorage.getItem(name)):[];

//   /* metodos */
//   return{
//     // obtener todos los datos de la coleccion
//     get : ()=>{
//       return DB;
//     },
//     // ingresar nuevos datos
//     push  : (obj)=>{
//       DB.push(obj);
//       localStorage.setItem(name,JSON.stringify(DB));
//     },
//     // ingresar una nueva coleccion
//     set : (colection)=>{
//       DB = colection;
//       localStorage.setItem(name,JSON.stringify(DB));
//     },
//     // eliminar todos los datos de la coleccion
//     delete  : ()=>{
//       DB = [];
//       localStorage.setItem(name,JSON.stringify(DB));
//     }
//   }
// }


export {
  base_url
}