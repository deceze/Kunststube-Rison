<?php

use Kunststube\Rison\RisonDecoder;

require_once 'Rison/RisonDecoder.php';


class RisonDecoderTest extends PHPUnit_Framework_TestCase {

	public function testArrays() {
		$r = new RisonDecoder('!(foo,bar)');
		$this->assertEquals(array('foo', 'bar'), $r->decode());
	}

	public function testObjects() {
		$r = new RisonDecoder('(foo:bar)');
		$this->assertEquals(array('foo' => 'bar'), $r->decode());
	}

}