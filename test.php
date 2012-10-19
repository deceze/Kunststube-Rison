<?php

require_once 'rison.php';

use Kunststube\Rison\Rison;

$tests = array(
	'!t',
	'!f',
	'!n',
	'123',
	'test',
	'(foo:bar)',
	'!(foo)',
	'!(!f)',
	'-123',
	'-12.124',
	'12e12',
	"'string literal'",
	"!('漢字')",
	'-a'
);

foreach ($tests as $test) {
	echo $test, PHP_EOL;
	$r = new Rison($test);
	var_dump($r->decode());
	echo PHP_EOL;
}