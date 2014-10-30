<?php

require_once 'inc/config.inc.php';
require_once 'inc/sessao.inc.php';

session_unset();
session_destroy();

header("Location: login.php");