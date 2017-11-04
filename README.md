# urbis-php
[UrbIS®©](http://bric.brussels/en/our-solutions/urbis-solutions) comprises a set of geographical databases of the Brussels-Capital Region and online services enabling the convenient use of these data.

urbis-php gives developpers a tool to use it easily with PHP.


## Install

The tool only requires **PHP 7.0+** and **guzzlehttp/guzzle**.

Use it via composer + packagist: https://packagist.org/packages/ici-brussels/urbis-php

## Usage

```php
$urbis = new Urbis_Geoloc();

// Find relevant address according to input
/* Option 1: Structured */
$urbis->getAddress_Structured("rue américaine", "25", "1060", "fr");
*/

// get array with latitude/longitude
$result = $urbis->getGeographicalLocation();
print_r($result);
/*
Array
(
    [lat] => 50.824197276153
    [lon] => 4.3554360355926
)
*/
```

## Credits ##
- Created by Bruno Veyckemans ([ici Bruxelles](https://ici.brussels/)). All comments and suggestions welcome !
- Realized by means of Brussels UrbIS®© - CIRB
