Kunststube\Rison encoder and decoder for PHP
============================================

Rison is a compact data format optimized for URIs, a slight variation of JSON.

JSON:

    {"a":0,"b":"foo","c":"23skidoo"}

URI-encoded JSON:

    %7B%22a%22:0,%22b%22%3A%22foo%22%2C%22c%22%3A%2223skidoo%22%7D

Rison:

    (a:0,b:foo,c:'23skidoo')

URI-encoded Rison:

    (a:0,b:foo,c:'23skidoo')

See http://mjtemplate.org/examples/rison.html for more information and other implementations.

Usage
-----

### Procedural/convenience wrapper ###

```php
require_once 'Rison/rison_functions.php';

$data = array('foo', 'bar' => array('baz'));

// encoding
$rison = Kunststube\Rison\rison_encode($data);
var_dump($rison);

// decoding
$data = Kunststube\Rison\rison_decode($rison);
var_dump($data);
```

### Object oriented ###

```php
require_once 'Rison/RisonEncoder.php';
require_once 'Rison/RisonDecoder.php';

use Kunststube\Rison;

$data = array('foo', 'bar' => array('baz'));

// encoding
try {
    $encoder = new Rison\RisonEncoder($data);
    $rison   = $encoder->encode();
    var_dump($rison);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage();
}

// decoding
try {
    $decoder = new Rison\RisonDecoder($rison);
    $data    = $decoder->decode();
    var_dump($data);
} catch (Rison\RisonParseErrorException $e) {
    echo $e->getMessage(), ' in string: ', $e->getRison();
} catch (InvalidArgumentException $e) {
    echo $e->getMessage();
}
```

PSR-0
-----

The repository is organized so its contents can be dumped into a folder `Kunststube/Rison/` and the naming be PSR-0 compliant.

Information
-----------

Version: 0.92  
Author:  David Zentgraf  
Contact: rison@kunststube.net  
License: Public Domain
