<?php
    defined('root') or die;
    /**
     * Response Objects
     */
    class Err {
        const LOGIN_ERR_MSG = 'Wrong username or password.';

        private $msg;

        function __construct($msg) {
            $this->msg = $msg;
        }

        function __toString() {
            return $this->msg;
        }
    }

    /**
     *
     */
    class Succ {
        const MSG = 'success';
    }
?>
