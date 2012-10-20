<?php

namespace Kunststube\Rison;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'RisonDecoder.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'RisonEncoder.php';

function rison_decode($string) {
	try {
		$r = new RisonDecoder($string);
		return $r->decode();
	} catch (\InvalidArgumentException $e) {
		trigger_error($e->getMessage(), E_USER_WARNING);
		return false;
	} catch (RisonParseErrorException $e) {
		trigger_error(sprintf('%s (in "%s")', $e->getMessage(), $e->getRison()), E_USER_WARNING);
		return false;
	}
}

function rison_encode($value) {
	try {
		$r = new RisonEncoder($value);
		return $r->encode();
	} catch (\InvalidArgumentException $e) {
		trigger_error($e->getMessage(), E_USER_WARNING);
		return false;
	}
}