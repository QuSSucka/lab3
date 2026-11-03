<?php
require 'bootstrap.php';

use App\Auth;

Auth::logout();
header('Location: login.php');
exit;
