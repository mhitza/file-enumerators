# File Enumerators

File streaming library (via generators), for line by line readers and CSV parsing (XML may come at some point too).

## Install

Available as a composer package named `mhitza/file-enumerators`, *requires* PHP `>=5.5.0`

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

### CSV reader

Consider a CSV file that has 5 columns, yet we are only interested in the *first*, *third* and *fifth* column. Also
we want to have semantically adequate keys for those columns instead of numbers. And maybe our *fifth* has a set of
numbers separated by a dash, that we want to sum up.

```php
<?php

use FileEnumerators\Reader\CSV as CSVReader;
use FileEnumerators\Reader\Transformer\CSV as CSVTransformer;

$enumerator = new FileEnumerators\Enumerator(
  'datafile.csv',
  new CSVReader(
    CSVReader::COMMA_DELIMITED,
    (new CSVTransformer)
      ->onlyColumns(1,3,5)
      ->columnsToNames([
        1 => "Title",
        2 => "Something relevant",
        5 => "User ratings"
      ])
      ->mapColumn(5, function($value){
        return array_sum(array_map(str_split('-', $value), 'intval'));
      })
  )
);
```