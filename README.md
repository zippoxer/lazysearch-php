# Full-Text Search for PHP with [lazysearch](http://lazysearch.zippo.io)
Empower your PHP application with powerful full-text search.

## Installation

```
composer require zippoxer/lazysearch
```

## Usage

Insert a document to folder `people`:

```php
$client = new Lazysearch\Client('your-api-key');

$client->people->put('1', [
    'name' => 'David',
    'age' => 35,
    'about' => 'I like pizza.'
]);
```

Search it:

```php
$results = $client->people->search('pizza');

foreach($results['hits'] as $person) {
    echo $person->name;
}
```

Advanced search:

```php
$results = $client->people->search('davi', [
    // Leave empty for standard query. Other options are: 'prefix', 'phrase' and 'advanced'.
    'queryType' => 'prefix',

    // Which field in the documents to search? Default is '*' (all fields).
    'field' => 'name',

    // Which fields from the documents to return? Default is '*' (all fields).
    'fields' => 'name,age,country',

    // How to sort the results? default is by _score descending (most relevant first).
    // Examples:
    // * 'age' sorts by field 'age' ascending
    // * '-age' sorts by field 'age' descending (reverse order)
    // * '-age,register_date' sorts by field 'age' descending,
    //   then by field 'register_date'
    'sort' => 'age',

    // Allow a specified number of typos in the search query. Default is 0 (no typos allowed).
    'typos' => 1,

    // Highlight matched words with HTML <mark> tags. Default is false.
    'highlight' => true,

    // Pagination.
    'page' => 2, // Default is 1.
    'hitsPerPage' => 50 // Default is 10.
]);

foreach($results['hits'] as $person) {
    echo $person->name;
}
```
