<?php

function ObHeader($url) {
	header( "HTTP/1.1 301 Moved Permanently" );
	Header("Location: https://bbs2.kdays.net". $url);
}

function query($parameterName, $filter = NULL) {
	$value = isset($_GET[$parameterName]) ? $_GET[$parameterName] : NULL;

	if ($filter == "int") {
		return (int) $value;
	} elseif ($filter == 'page') {
		if (!is_numeric($value) || $value < 1) {
			$value = 1;
		}

		return $value;
	}

	return $value;
}

function errorMessage($msg) {
	throw new \Exception($msg);
}
