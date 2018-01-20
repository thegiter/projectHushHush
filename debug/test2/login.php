<?php
    if (!defined('root')) {
        define('root', '../../');
    }

    //make sure it's from my javascript
    require_once root.'debug/test2/lib/ajax_chk.php';

    //chk if user name match
    require_once root.'debug/test2/lib/db.php';

    $conn = new mysqli(Db::HOST, Db::UN, Db::PW, Db::DB);

    if ($stmt = $conn->prepare('
        SELECT *
        FROM Users
        WHERE un = ?
        LIMIT 1
    ')) {
        $stmt->bind_param('s', $_POST['un']);  // Bind un to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();

        // get variables from result.
        $stmt->bind_result($un, $pw);
        $stmt->fetch();

        //if user exist
        require_once root.'debug/test2/lib/rsp.php';

        if ($stmt->num_rows != 1) {
            die(Err::LOGIN_ERR_MSG);
        }

        //chk if pw match
        //encryption code here
        //check encrypted pw vs pw from db
        if ($_POST['pw'] !== $pw) {
            die(Err::LOGIN_ERR_MSG);
        }

        require_once root.'debug/test2/lib/start_ses.php';
        $_SESSION['un'] = $un;//un is unique

        echo Succ::MSG;
    }
?>
