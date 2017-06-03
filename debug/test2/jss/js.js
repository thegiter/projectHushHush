//shpsCmm is the library code I use
shpsCmm.domMgr.domReady().then(function () {
    //get form
    const form = document.getElementsByTagName('form')[0];
    const msgCnr = document.getElementsByClassName('msg-cnr')[0];

    const hdr = document.getElementsByTagName('header')[0];
    const btn = form.getElementsByTagName('button')[0],
    hdg = form.getElementsByTagName('h2')[0],
    p = form.getElementsByTagName('p')[0],
    bd = document.body;

    function initLogin() {
        //toggle login and logout
        if (bd.classList.contains('logged-in')) {
            //switch to logout form
            form.action = 'logout.php';

            new shpsCmm.util.AnimFramePromise(function () {
                btn.textContent = 'Log Out';
                p.textContent = '';
            }).then(function () {
                hdr.style.height = shpsCmm.domMgr.getElmCloneDimension(hdr).h+'px';
            });

            msgCnr.textContent = 'You have logged in.';

            //load content
        } else {
            hdr.style.removeProperty('height');

            //remove content

            //switch to logout form
            form.action = 'login.php';

            btn.textContent = 'Log In';
            hdg.textContent = 'Login';

            p.textContent = '';
        }
    }

    initLogin();

    //handle submit event
    form.addEventListener('submit', function (evt) {
        evt.preventDefault();

        const fd = new FormData(form);

        //for login, no validation is needed, the server will handle pw and un verifications
        //we have html validation in place, we could also check with javascript just to be sure, but very unnecessary

        //to inform this is sent from a script from this domain
        //it can be faked by a hacker if he knows about this
        fd.append('refuri', window.location.hostname);

        //ajax manager
        //undefined to response type, headers, ajax handler
        //true to disable default content-type
        const rqsUrl = form.action;

        shpsCmm.ajaxMgr.createAjax('POST', rqsUrl, fd, undefined, undefined, undefined, true).then(function (xhr) {
            //if success, show logged in,
            //else assume is error msg, and show msg
            const rsp = xhr.responseText;

            if (rsp != 'success') {
                return form.getElementsByTagName('p')[0].textContent = rsp;
            }

            //toggle login and logout
            if (rqsUrl.substr(-9) == 'login.php') {
                //switch to logout form
                hdg.textContent = fd.get('un');
                bd.classList.add('logged-in');
            } else {
                bd.classList.remove('logged-in');
            }

            initLogin();
        });
    });
});
