<?php
    if (!defined('root')) {
        define('root', '../../');
    }

    //make sure it's from my javascript
    require_once root.'debug/test2/lib/ajax_chk.php';

    require_once root.'debug/test2/lib/rsp.php';

    require_once root.'debug/test2/lib/start_ses.php';
    unset($_SESSION['un']);
    
    echo Succ::MSG;
?>
