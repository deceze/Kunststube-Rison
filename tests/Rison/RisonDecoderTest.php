<?php

use Kunststube\Rison as R;

require_once 'RisonDecoder.php';
require_once 'rison_functions.php';


class RisonDecoderTest extends PHPUnit_Framework_TestCase {

	public function testArraysOOP() {
		$r = new R\RisonDecoder('!(foo,bar)');
		$this->assertEquals(array('foo', 'bar'), $r->decode());
	}

	public function testObjectsOOP() {
		$r = new R\RisonDecoder('(foo:bar)');
		$this->assertEquals(array('foo' => 'bar'), $r->decode());
	}

	public function testSimpleObject() {
		$php   = ['a' => 0, 'b' => 1];
		$rison = '(a:0,b:1)';
		$json  = '{"a":0,"b":1}';
		
		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testComplexObject() {
		$php   = ['a' => 0, 'b' => 'foo', 'c' => '23skidoo'];
		$rison = "(a:0,b:foo,c:'23skidoo')";
		$json  = '{"a":0,"b":"foo","c":"23skidoo"}';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testTrue() {
		$php   = true;
		$rison = '!t';
		$json  = 'true';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testFalse() {
		$php   = false;
		$rison = '!f';
		$json  = 'false';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testNull() {
		$php   = null;
		$rison = '!n';
		$json  = 'null';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testEmptyString() {
		$php   = '';
		$rison = "''";
		$json  = '""';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function test0() {
		$php   = 0;
		$rison = '0';
		$json  = '0';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function test1_5() {
		$php   = 1.5;
		$rison = '1.5';
		$json  = '1.5';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testMinus3() {
		$php   = -3;
		$rison = '-3';
		$json  = '-3';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function test1e30() {
		$php   = 1e+30;
		$rison = '1e30';
		$json  = '1e+30';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function test1eMinus30() {
		$php   = 1e-30;
		$rison = '1e-30';
		$json  = '1e-30';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testA() {
		$php   = 'a';
		$rison = 'a';
		$json  = '"a"';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function test0a() {
		$php   = '0a';
		$rison = "'0a'";
		$json  = '"0a"';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testAbcDef() {
		$php   = 'abc def';
		$rison = "'abc def'";
		$json  = '"abc def"';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testEmptyObject() {
		$php   = [];
		$rison = '()';
		$json  = '{}';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testSingleObject() {
		$php   = ['a' => 0];
		$rison = '(a:0)';
		$json  = '{"a":0}';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testComplexQuoteObject() {
		$php   = ['id' => null, 'type' => '/common/document'];
		$rison = '(id:!n,type:/common/document)';
		$json  = '{"id":null,"type":"/common/document"}';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testEmptyArray() {
		$php   = [];
		$rison = '!()';
		$json  = '[]';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testPrimitiveTypeArray() {
		$php   = [true, false, null, ''];
		$rison = "!(!t,!f,!n,'')";
		$json  = '[true,false,null,""]';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testMinusH() {
		$php   = '-h';
		$rison = "'-h'";
		$json  = '"-h"';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testAThroughZ() {
		$php   = 'a-z';
		$rison = 'a-z';
		$json  = '"a-z"';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testWow() {
		$php   = 'wow!';
		$rison = "'wow!!'";
		$json  = '"wow!"';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testDomainDotCom() {
		$php   = 'domain.com';
		$rison = 'domain.com';
		$json  = '"domain.com"';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testUserAtDomainDotCom() {
		$php   = 'user@domain.com';
		$rison = "'user@domain.com'";
		$json  = '"user@domain.com"';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function test10Dollars() {
		$php   = 'US $10';
		$rison = "'US $10'";
		$json  = '"US $10"';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testCant() {
		$php   = "can't";
		$rison = "'can!'t'";
		$json  = '"can\'t"';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testControlF() {
		$php   = 'Control-F: ';
		$rison = "'Control-F: '";
		$json  = '"Control-F: \u0006"';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testUnicode() {
		$php   = 'Unicode: ௫';
		$rison = "'Unicode: ௫'";
		$json  = '"Unicode: ௫"';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	public function testAllTypesNested() {
		$php   = array('foo' => 'bar', 'baz' => array(1, 12e40, 0.42, array('a' => true, false, null)));
		$rison = '(foo:bar,baz:!(1,12e40,0.42,(a:!t,0:!f,1:!n)))';
		$json  = '{"foo":"bar","baz":[1,12e40,0.42,{"a":true,"0":false,"1":null}]}';

		$this->assertEquals($php, R\rison_decode($rison));
		$this->assertEquals(json_decode($json, true), R\rison_decode($rison));
	}

	/**
	 * @expectedException Kunststube\Rison\RisonParseErrorException
	 */
	public function testTwoLiterals() {
		$r = new R\RisonDecoder('foo bar');
		$r->decode();
	}

}
