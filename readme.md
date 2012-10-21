Kunststube\Rison encoder and decoder for PHP
============================================

Rison is a compact data format optimized for URIs, a slight variation of JSON. See http://mjtemplate.org/examples/rison.html for more information and other implementations.

Requires PHP 5.4 (mostly because of the simplified array syntax).

Usage
-----

### Object oriented ###

    require_once 'Rison/RisonDecoder.php';

    use Kunststube\Rison;

    $rison = '(baz:!(1,1.2e41,0.42,(a:!t,0:!f,1:!n)),foo:bar)';

    try {
        $decoder = new Rison\RisonDecoder($rison);
        $data = $decoder->decode();
        var_dump($data);
    } catch (Rison\RisonParseErrorException $e) {
        echo $e->getMessage(), ' in string: ', $e->getRison();
    } catch (InvalidArgumentException $e) {
        echo $e->getMessage();
    }

### Procedural/convenience wrapper ###

    require_once 'Rison/rison_functions.php';

    $rison = '(baz:!(1,1.2e41,0.42,(a:!t,0:!f,1:!n)),foo:bar)';
    $data  = Kunststube\Rison\rison_decode($rison);
    var_dump($data);

Information
-----------

Version: 0.9  
Author:  David Zentgraf  
Contact: rison@kunststube.net  
License: Public Domain
