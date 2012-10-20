<?php

namespace Kunststube\Rison;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Rison.php';


class RisonEncoder extends Rison {

    protected $value = null;

    protected $encoders = [];

    public function __construct($value) {
        $this->value = $value;
        $this->init();
    }

    protected function init() {
        $this->encoders = [
            'boolean'  => array($this, 'encodeBoolean'),
            'integer'  => array($this, 'encodeInteger'),
            'double'   => array($this, 'encodeDouble'),
            'string'   => array($this, 'encodeString'),
            'array'    => array($this, 'encodeArray'),
            'object'   => array($this, 'encodeObject'),
            'resource' => array($this, 'encodeResource'),
            'null'     => array($this, 'encodeNull'),
        ];

        $this->idOkRegex = "/^[^{$this->notIdstart}{$this->notIdchar}][^{$this->notIdchar}]*\$/";
    }

    public function encode() {
        return $this->encodeValue($this->value);
    }

    protected function encodeValue($value) {
        $type = strtolower(gettype($value));
        if (!isset($this->encoders[$type])) {
            throw new \InvalidArgumentException("Cannot encode value of type $type");
        }
        return call_user_func($this->encoders[$type], $value);
    }

    protected function encodeBoolean($boolean) {
        return $boolean ? '!t' : '!f';
    }

    protected function encodeNull($null) {
        return '!n';
    }

    protected function encodeInteger($integer) {
        return $integer;
    }

    protected function encodeDouble($double) {
        return strtolower(str_replace('+', '', (string)$double));
    }

    protected function encodeResource($resource) {
        throw new \InvalidArgumentException("Cannot encode resource $resource");
    }

    protected function encodeString($string) {
        if ($string === '') {
            return "''";
        }

        if (preg_match($this->idOkRegex, $string)) {
            return $string;
        }

        $string = preg_replace("/['!]/", '!$0', $string);
        return "'$string'";
    }

    protected function encodeArray(array $array) {
        $keys = array_keys($array);
        $isArray = range(0, count($keys) - 1) == $keys;
        if (!$isArray) {
            return $this->encodeObject($array);
        }
        return '!(' . join(',', array_map(array($this, 'encodeValue'), $array)) . ')';
    }

    protected function encodeObject($object) {
        $object = (array)$object;
        ksort($object);
        $encoded = [];

        foreach ($object as $key => $value) {
            $encoded[] = $this->encodeValue($key) . ':' . $this->encodeValue($value);
        }
        return '(' . join(',', $encoded) . ')';
    }

}
