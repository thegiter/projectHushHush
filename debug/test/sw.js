//because service worker is scoped to itself, we don't need to create a scope.
const VER = 1, NAME = 'test',
FILES = [
  '/debug/test/index.html',
  '/shared/csss/default.css',
  '/debug/test/jss/installer.js'
];

const cacheName = NAME+VER;

self.addEventListener('install', function(evt) {console.log('WORKER: install event in progress.');
    evt.waitUntil(caches.open(cacheName).then(function(cache) {
        return cache.addAll(FILES);
    }).then(function() {
        console.log('WORKER: install completed');
    }));
});

self.addEventListener('activate', function(evt) {console.log('WORKER: activate event in progress.');
    evt.waitUntil(caches.keys().then(function (keys) {
        return Promise.all(keys.map(function (key) {
            if (key !== cacheName) {
                return caches.delete(key);
            }
        }));
    }).then(function() {
        console.log('WORKER: activate completed.');
    }));
});

self.addEventListener('fetch', function(evt) {console.log('WORKER: fetch event in progress.');
    /* Similar to event.waitUntil in that it blocks the fetch event on a promise.
     Fulfillment result will be used as the response, and rejection will end in a
     HTTP response indicating failure.
    */
    evt.respondWith(caches.match(evt.request).then(function(cached) {
        if (cached) {
            return cached;
        }

        return fetch(evt.request).then(function (rsp) {
            return rsp;
        }, function (err) {console.log('WORKER: error fetch from network.');
            return new Response(`<h1>
                Service Unavailable
            </h1>`, {
                status: 503,
                statusText: 'Service Unavailable',
                headers: new Headers({
                  'Content-Type': 'text/html'
                })
            });
        });

/*        fetch(evt.request).then(fetchedFromNetwork, unableToResolve)
          console.log('WORKER: fetch event', cached ? '(cached)' : '(network)', evt.request.url);
        return cached || networked;

        function fetchedFromNetwork(response) {
         const copy = response.clone();

          console.log('WORKER: fetch response from network.', event.request.url);

          caches.open(cacheName).then(function (cache) {
              cache.put(evt.request, copy);

              console.log('WORKER: fetch response stored in cache.', event.request.url);
          });

          // Return the response so that the promise is settled in fulfillment.
          return response;
      }*/
    }));
});
