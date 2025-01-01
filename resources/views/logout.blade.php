<?php
session_start();
unset($_SESSION['auth_token']);
session_destroy();
echo '<script type="text/javascript">',
        'sessionStorage.removeItem("auth_token");',
        'setTimeout(function() { window.location.href = "/login"; }, 1000);',
        '</script>';
exit;
?>