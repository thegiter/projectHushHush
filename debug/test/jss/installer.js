navigator.serviceWorker.register('/debug/test/sw.js').then(function() {
    console.log('CLIENT: service worker registration complete.');
}, function() {
    console.log('CLIENT: service worker registration failure.');
});

window.addEventListener('load', function () {
    document.body.textContent = 'installer text.';
});
