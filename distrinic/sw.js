;
//asignar un nombre y version al cache
const CACHE_NAME = 'v1_cache_alfa',
urlsToCache = [
    './assets/img/favicon.png',
    './assets/img/ajax-loader.gif',
    './assets/img/logo.png',
    './assets/img/pwa/icon_512.png',
]

//Durante la fase de instalacion, generalmente se almacena en cache los activos estaticos
self.addEventListener('install', e => {
    e.waitUntil(
        caches.open(CACHE_NAME)
        .then(cache => {
            return cache.addAll(urlsToCache)
            .then(() => self.skipWaiting())
        })
        .catch(err => console.log("Fallo registro de cache'", err))
    )
})

//Una vez que se instala el SW, se activa y busca los recursos para hacer que funcion sin conexion
self.addEventListener('activate', e => {
    const cacheWhiteList = [ CACHE_NAME ]

    e.waitUntil(
        caches.keys()
        .then(cachesNames => {
            cachesNames.map(cachesName => {
                //Eliminamos lo que ya no se necesita en cache
                if(cacheWhiteList.indexOf(cachesName) === -1){
                    return caches.delete(cachesName)
                }
            })
        })
        .then(() => self.clients.claim())
    )
})

//Cuando el navegador recupera una url
self.addEventListener('fetch', e => {
    //Responder ya qsea con el objeto en cache o continuar y buscar la url real
    e.respondWith(
        caches.match(e.request)
        .then(res => {
            if(res){
                //recuperando del cache
                return res
            }
            //Recuperar de la peticion a la url
            return fetch(e.request)
        })
    )
})