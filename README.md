# File Enumerators
[![Build Status](https://travis-ci.org/mhitza/file-enumerators.svg?branch=master)](https://travis-ci.org/mhitza/file-enumerators)
[![Code Climate](https://codeclimate.com/github/mhitza/file-enumerators/badges/gpa.svg)](https://codeclimate.com/github/mhitza/file-enumerators)
[![Test Coverage](https://codeclimate.com/github/mhitza/file-enumerators/badges/coverage.svg)](https://codeclimate.com/github/mhitza/file-enumerators)

File streaming library (via generators), for line by line readers and CSV parsing (other specializations may come up at some point).

It's important to remember that **generators are forward-only iterators**. For that you should take note that in the example
code I'm calling `enumerate()` inside the `foreach` construct instead of assigning it to a variable, and iterating over that
variable. That is the safe way of iterating over a generator, since `enumerate()` is the `Generator` builder, UNLESS you want
to constrain single passes over the streams, in which case binding the generator to a variable is prefered.

## Install

Available as a [composer package](https://packagist.org/packages/mhitza/file-enumerators), **requires** PHP `>=5.5.0`

```shell
$ composer.phar require mhitza/file-enumerators
```

## Example usage

### Line by line reader

a.k.a. how the classic fgets function usage translates in this library

```php
<?php

use FileEnumerators\Reader\Line as LineReader;

$enumerator = new FileEnumerators\Enumerator('testfile.txt', new LineReader);

foreach($enumerator->enumerate() as $line) {
  echo $line;
}
```

### CSV reader - simple

```php
<?php

use FileEnumerators\Reader\CSV as CSVReader;

$enumerator = new FileEnumerators\Enumerator('datafile.csv', new CSVReader);

foreach($enumerator->enumerate() as $row) {
  echo "ROW\n";
  foreach($row as $column) {
    echo "\t$column";
  }
}
```

### CSV reader - more complex

Consider a CSV file that has 5 columns, yet we are only interested in the **first**, **third** and **fifth** column. Also
we want to have semantically adequate keys for those columns instead of numbers. And maybe our **fifth** has a set of
numbers separated by a dash, that we want to sum up.

```php
<?php

use FileEnumerators\Reader\CSV as CSVReader;
use FileEnumerators\Reader\Transformer\CSV as CSVTransformer;

$transformer = new CSVTransformer();
$transformer->onlyColumns(0,2,4)
            ->columnsToNames([
              0 => "title",
              2 => "something-relevant",
              4 => "user-ratings"
            ])
            ->mapColumn(4, function($value){
              return array_sum(array_map('intval', str_split('-', $value)));
            });
  
$reader = new CSVReader(
  CSVReader::COMMA_DELIMITED,
  $transformer
);

$enumerator = new FileEnumerators\Enumerator('datafile.csv', $reader);

foreach($enumerator->enumerate() as $row) {
  printf("%s %s %d",
    $row['title'],
    $row['something-relevant'],
    $row['user-ratings']
  );
}
```

Or the personally prefered variant where everything is bundled up in a single builder set.

```php
<?php

use FileEnumerators\Reader\CSV as CSVReader;
use FileEnumerators\Reader\Transformer\CSV as CSVTransformer;

$enumerator = new FileEnumerators\Enumerator(
  'datafile.csv',
  new CSVReader(
    CSVReader::COMMA_DELIMITED,
    (new CSVTransformer)
      ->onlyColumns(0,2,4)
      ->columnsToNames([
        0 => "Title",
        2 => "Something relevant",
        4 => "User ratings"
      ])
      ->mapColumn(4, function($value){
        return array_sum(array_map(str_split('-', $value), 'intval'));
      })
  )
);

foreach($enumerator->enumerate() as $row) {
  printf("%s %s %d",
    $row['title'],
    $row['something-relevant'],
    $row['user-ratings']
  );
}
```
