<?php
require("global.php");

$page = query("page", "page");
$tid = query("tid", "int");
if (!$tid) errorMessage("error tid");

ObHeader("/read/". $tid. "/". $page);