<?php
    defined('root') or die;
    
    require_once root.'debug/test2/lib/rsp.php';

    //start session
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        die(new Err('Secure script error'));
    }
/* not sure how this works, this breaks sessions when enabled
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();

    session_set_cookie_params(
        $cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        true,//secure
        true//httponly
    );
*/
    session_start();
?>
