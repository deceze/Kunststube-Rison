<?php

use Kunststube\Rison\RisonEncoder;

require_once 'Rison/RisonEncoder.php';


class RisonEncoderTest extends PHPUnit_Framework_TestCase {

	public function testArrays() {
		$r = new RisonEncoder(array('foo', 'bar'));
		$this->assertEquals('!(foo,bar)', $r->encode());
	}

	public function testObjects() {
		$r = new RisonEncoder(array('foo' => 'bar'));
		$this->assertEquals('(foo:bar)', $r->encode());
	}

}