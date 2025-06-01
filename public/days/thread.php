<?php
require("global.php");

$fid = query("fid", "int");
$page = query("page", "page");

ObHeader("/thread/". $fid. "/" . $page);