# SearchEngine

Crawls google.com and google.ae

## Installation

Use composer [composer](https://getcomposer.org/) to install searchengine.

```bash
composer require astro/searchengine
```

## Usage
Create a new test PHP file
```php
<?php 
namespace Astro;

require_once './vendor/autoload.php';

#create new instance
$client = new SearchEngine();

#set search engine (google.com or google.ae)
$client->setEngine("google.ae");

#enter search keywords
$results = $client->search(["amazon","tuna"]);

#print the results
print_r($results);
?>
```
Run your php file.
```bash
php test.php
```

## License
[MIT](https://choosealicense.com/licenses/mit/)
