<?php



session_start();

require_once 'core/database.php';
require_once 'core/verification.php';
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'core/route.php';
//require_once 'core/recaptchalib.php';

$verification = new Verification();
$verification->admin_access();

Route::start();

?>