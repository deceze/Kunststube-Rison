<?php

require_once 'rison_functions.php';

use Kunststube\Rison;

$tests = [
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
	"(foo:bar,baz:!('Bawam bam'),ber:!n)",
	'-a'
];

// foreach ($tests as $test) {
// 	echo $test, PHP_EOL;
// 	var_dump(Rison\rison_decode($test));
// 	echo PHP_EOL;
// }


$tests = [
	'Foo',
	true,
	false,
	null,
	'Bar baz',
	['foo', 'bar', 'baz bam'],
	new stdClass,
	['blah' => 'bim', 'bom', 'foo'],
	['meh', 'das' => 'beh', 'obj' => new stdClass]
];

foreach ($tests as $test) {
	var_dump($test);
	var_dump(Rison\rison_encode($test));
	echo PHP_EOL;
}