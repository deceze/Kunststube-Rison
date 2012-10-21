<?php

use Kunststube\Rison as R;

require_once 'Rison/RisonEncoder.php';


class RisonEncoderTest extends PHPUnit_Framework_TestCase {

	public function testArrays() {
		$r = new R\RisonEncoder(array('foo', 'bar'));
		$this->assertEquals('!(foo,bar)', $r->encode());
	}

	public function testObjects() {
		$r = new R\RisonEncoder(array('foo' => 'bar'));
		$this->assertEquals('(foo:bar)', $r->encode());
	}

	public function testSimpleObject() {
		$php   = ['a' => 0, 'b' => 1];
		$rison = '(a:0,b:1)';
		
		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testComplexObject() {
		$php   = ['a' => 0, 'b' => 'foo', 'c' => '23skidoo'];
		$rison = "(a:0,b:foo,c:'23skidoo')";

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testTrue() {
		$php   = true;
		$rison = '!t';

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testFalse() {
		$php   = false;
		$rison = '!f';

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testNull() {
		$php   = null;
		$rison = '!n';

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testEmptyString() {
		$php   = '';
		$rison = "''";

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function test0() {
		$php   = 0;
		$rison = '0';

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function test1_5() {
		$php   = 1.5;
		$rison = '1.5';

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testMinus3() {
		$php   = -3;
		$rison = '-3';

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function test1e30() {
		$php   = 1e+30;
		$rison = '1e30';

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function test1eMinus30() {
		$php   = 1e-30;
		$rison = '1e-30';

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testA() {
		$php   = 'a';
		$rison = 'a';

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function test0a() {
		$php   = '0a';
		$rison = "'0a'";

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testAbcDef() {
		$php   = 'abc def';
		$rison = "'abc def'";

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testEmptyObject() {
		$php   = [];
		$rison = '()';

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testSingleObject() {
		$php   = ['a' => 0];
		$rison = '(a:0)';

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testComplexQuoteObject() {
		$php   = ['id' => null, 'type' => '/common/document'];
		$rison = '(id:!n,type:/common/document)';

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testPrimitiveTypeArray() {
		$php   = [true, false, null, ''];
		$rison = "!(!t,!f,!n,'')";

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testMinusH() {
		$php   = '-h';
		$rison = "'-h'";

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testAThroughZ() {
		$php   = 'a-z';
		$rison = 'a-z';

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testWow() {
		$php   = 'wow!';
		$rison = "'wow!!'";

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testDomainDotCom() {
		$php   = 'domain.com';
		$rison = 'domain.com';

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testUserAtDomainDotCom() {
		$php   = 'user@domain.com';
		$rison = "'user@domain.com'";

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function test10Dollars() {
		$php   = 'US $10';
		$rison = "'US $10'";

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testCant() {
		$php   = "can't";
		$rison = "'can!'t'";

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testControlF() {
		$php   = 'Control-F: ';
		$rison = "'Control-F: '";

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testUnicode() {
		$php   = 'Unicode: ௫';
		$rison = "'Unicode: ௫'";

		$this->assertEquals($rison, R\rison_encode($php));
	}

	public function testAllTypesNested() {
		$php   = array('foo' => 'bar', 'baz' => array(1, 12e40, 0.42, array('a' => true, false, null)));
		$rison = '(baz:!(1,1.2e41,0.42,(a:!t,0:!f,1:!n)),foo:bar)';

		$this->assertEquals($rison, R\rison_encode($php));
	}

}