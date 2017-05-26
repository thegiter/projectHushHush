window.addEventListener('load', function () {
    const d = document.createElement('div');

    d.textContent = 'non cached text';

    document.body.appendChild(d);
});
