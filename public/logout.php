<?php
require_once __DIR__ . '/../src/Auth/Session.php';

use Auth\Session;

Session::start();
Session::destroy();

header('Location: /index.php');
exit;