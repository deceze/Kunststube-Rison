<?php

namespace Kunststube\Rison;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Rison.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'RisonParseErrorException.php';


class RisonDecoder extends Rison {

    protected $rison  = null,
              $length = 0,
              $index  = 0,
              $eof    = false;

    protected $whitespace = '',
              $idRegex    = null,
              $tokens     = array(),
              $bangs      = array();

    public function __construct($rison) {
        $this->rison  = $rison;
        $this->length = strlen($rison);

        if ($this->length == 0) {
            throw new \InvalidArgumentException('Empty string');
        }

        $this->init();
    }

    protected function init() {
        $this->tokens = array(
            '!' => array($this, 'parseBang'),
            '(' => array($this, 'parseObject'),
            "'" => array($this, 'parseStringLiteral'),
            '-' => array($this, 'parseNumber')
        );
        $this->tokens += array_fill_keys(range(0, 9), $this->tokens['-']);

        $this->bangs = array(
            't' => true,
            'f' => false,
            'n' => null,
            '(' => array($this, 'parseArray')
        );

        $this->idRegex = "/[^{$this->notIdstart}{$this->notIdchar}][^{$this->notIdchar}]*/";
    }

    public function decode() {
        $value = $this->parseValue();
        if ($this->next() !== false) {
            $this->parseError('Invalid syntax');
        }
        return $value;
    }

    protected function parseValue() {
        $c = $this->next();
        if ($c === false) {
            $this->parseError('Unexpected end');
        }

        if (isset($this->tokens[$c])) {
            return call_user_func($this->tokens[$c]);
        }

        $i = $this->index - 1;
        if (!preg_match($this->idRegex, $this->rison, $matches, 0, $i)) {
            $this->parseError("Invalid character '$c'");
        }

        $this->index = $i + strlen($matches[0]);
        return $matches[0];
    }

    protected function parseBang() {
        $c = $this->next();
        if ($c === false) {
            $this->parseError('! at end of string');
        }
        if (!array_key_exists($c, $this->bangs)) {
            $this->parseError("Invalid bang '!$c'");
        }
        if (!is_array($this->bangs[$c])) {
            return $this->bangs[$c];
        }
        return call_user_func($this->bangs[$c]);
    }

    protected function parseObject() {
        $obj = array();

        while (($c = $this->next()) !== ')') {
            if ($obj) {
                if ($c !== ',') {
                    $this->parseError('Missing ","');
                }
            } else if ($c === ',') {
                $this->parseError('Extraneous ","');
            } else {
                $this->index--;
            }

            $key = $this->parseValue();
            if (!$key && $this->eof) {
                return false;
            }

            if ($this->next() !== ':') {
                $this->parseError('Missing ":"');
            }

            $value = $this->parseValue();
            if (!$value && $this->eof) {
                $this->parseError('Unexpected end of string');
            }

            $obj[$key] = $value;
        }

        return $obj;
    }

    protected function parseArray() {
        $array = array();

        while (($c = $this->next()) !== ')') {
            if ($c === false) {
                $this->parseError('Unmatched !(');
            }
            if ($array) {
                if ($c !== ',') {
                    $this->parseError('Missing ","');
                }
            } else if ($c === ',') {
                $this->parseError('Extraneous ","');
            } else {
                $this->index--;
            }

            $value = $this->parseValue();
            if (!$value && $this->eof) {
                $this->parseError('Unexpected end of string');
            }
            $array[] = $value;
        }

        return $array;
    }

    protected function parseStringLiteral() {
        $string = null;

        while (($c = $this->next()) !== "'") {
            if ($c === false) {
                $this->parseError('Unmatched "\'"');
            }
            if ($c === '!') {
                $c = $this->next();
                if (strpos("!'", $c) === false) {
                    $this->parseError("Invalid string escape '!$c'");
                }
            }
            $string .= $c;
        }

        return $string;
    }

    protected function parseNumber() {
        $i              = $this->index;
        $start          = $i - 1;
        $state          = 'int';
        $permittedSigns = '-';
        
        static $transitions = array(
            'int+.'  => 'frac',
            'int+e'  => 'exp',
            'frac+e' => 'exp'
        );

        do {
            $c = substr($this->rison, $i++, 1);
            if ($c === false) {
                break;
            }

            if (ctype_digit($c)) {
                continue;
            }

            if (strpos($permittedSigns, $c) !== false) {
                $permittedSigns = '';
                continue;
            }

            $state = $state . '+' . strtolower($c);
            $state = isset($transitions[$state]) ? $transitions[$state] : false;
            if ($state === 'exp') {
                $permittedSigns = '-';
            }
        } while ($state);

        $this->index = --$i;

        $number = substr($this->rison, $start, $i - $start);
        if ($number === '-') {
            $this->parseError('Invalid number "-"');
        }

        if (!is_numeric($number)) {
            $this->parseError("Invalid number '$number'");
        }

        if (preg_match('/^-?\d+$/', $number)) {
            return (int)$number;
        } else {
            return (float)$number;
        }
    }

    protected function next() {
        do {
            if ($this->index >= $this->length) {
                $this->eof = true;
                return false;
            }
            $c = $this->rison[$this->index++];
        } while (strpos($this->whitespace, $c) !== false);
        return $c;
    }

    protected function parseError($message) {
        throw new RisonParseErrorException($this->rison, $message);
    }

}
