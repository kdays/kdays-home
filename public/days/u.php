<?php
require("global.php");

$uid = query("uid", "int");
ObHeader("/user/". $uid. "/");