<?php

use FileEnumerators\Enumerator as Enumerator;
use FileEnumerators\Reader\CSV as CSVReader;
use FileEnumerators\Reader\Transformer\CSV as CSVTransformer;


class CSVReaderTest extends PHPUnit_Framework_TestCase {
  
  const SAMPLE_FILEPATH = __DIR__.'/data/csv_sample_simple.txt';
  
  public function testAgainstFgetcsv() {
    $enumerator = new Enumerator(self::SAMPLE_FILEPATH, new CSVReader);
    
    $rows = [];
    foreach($enumerator->enumerate() as $row) {
      $rows[] = $row;
    }
    
    $rows_expected = $this->readCSV();
    
    $this->assertEquals(
      $rows_expected,
      $rows,
      "Should be the same as using fgetcsv"
    );
  }
  
  
  public function testTransformerOnlyColumnsOne() {
    $transformer = new CSVTransformer;
    $transformer->onlyColumns(0);
    
    $enumerator = new Enumerator(
      self::SAMPLE_FILEPATH,
      new CSVReader(
        CSVReader::COMMA_DELIMITED,
        $transformer
      )
    );
    
    $rows = [];
    foreach($enumerator->enumerate() as $row) {
      $rows[] = $row;
    }
    
    $expected = [
      ["Lorem"],
      ["sit"],
      ["adipiscing"]
    ];
    
    $this->assertEquals(
      $expected,
      $rows,
      "Should have only first column of each row"
    );
  }
  
  
  public function testTransformerOnlyColumnsTwo() {
    $transformer = new CSVTransformer;
    $transformer->onlyColumns(0,2);
    
    $enumerator = new Enumerator(
      self::SAMPLE_FILEPATH,
      new CSVReader(
        CSVReader::COMMA_DELIMITED,
        $transformer
      )
    );
    
    $rows = [];
    foreach($enumerator->enumerate() as $row) {
      $rows[] = $row;
    }
    
    $expected = [
      [0 => "Lorem", 2 => "dolor"],
      [0 => "sit", 2 => "consectetur"],
      [0 => "adipiscing", 2 => "Donec"]
    ];
    
    $this->assertEquals(
      $expected,
      $rows,
      "Should have only first and third column of each row"
    );
    
  }
  
  
  public function testTransformerColumnsToNames() {
    $transformer = new CSVTransformer;
    $transformer->columnsToNames([
      0 => "First",
      1 => "Second",
      2 => "Third"
    ]);
    
    $enumerator = new Enumerator(
      self::SAMPLE_FILEPATH,
      new CSVReader(
        CSVReader::COMMA_DELIMITED,
        $transformer
      )
    );
    
    $columns = [];
    foreach($enumerator->enumerate() as $row) {
      foreach($row as $column_name => $value) {
        $columns[] = $column_name;
      }
    }
    $columns = array_unique($columns);
    
    
    $expected = ["First", "Second", "Third"];
    
    $this->assertEquals(
      $expected,
      $columns,
      "Should have given column names instead of IDs as keys"
    );
    
  }
  
  
  public function testTransformerMapColumn() {
    $transformer = new CSVTransformer;
    $transformer->mapColumn(0, function() { return 1; });
    
    $enumerator = new Enumerator(
      self::SAMPLE_FILEPATH,
      new CSVReader(
        CSVReader::COMMA_DELIMITED,
        $transformer
      )
    );
    
    $rows = [];
    foreach($enumerator->enumerate() as $row) {
      $rows[] = $row;
    }
    
    $expected = [
      [1, "ipsum", "dolor"],
      [1, "amet", "consectetur"],
      [1, "elit", "Donec"]
    ];
    
    $this->assertEquals(
      $expected,
      $rows,
      "First column values should be all the value 1"
    );
  }
  
  
  public function testTransformerAll() {
    $transformer = new CSVTransformer;
    $transformer->mapColumn(0, function() { return 1; });
    $transformer->columnsToNames([
      0 => "First",
      1 => "Second",
      2 => "Third"
    ]);
    $transformer->onlyColumns(0,2);
    
    $enumerator = new Enumerator(
      self::SAMPLE_FILEPATH,
      new CSVReader(
        CSVReader::COMMA_DELIMITED,
        $transformer
      )
    );
    
    $rows = [];
    foreach($enumerator->enumerate() as $row) {
      $rows[] = $row;
    }
    
    
    $expected = [
      ["First" => 1, "Third" => "dolor"],
      ["First" => 1, "Third" => "consectetur"],
      ["First" => 1, "Third" => "Donec"]
    ];
    
    $this->assertEquals(
      $expected,
      $rows,
      "Transformations should work well togheter :)"
    );
  }
  
  protected function readCSV() {
    $handle = fopen(self::SAMPLE_FILEPATH, 'r');
    $rows_expected = [];
    while(false !== ($row = fgetcsv($handle))) {
      $rows_expected[] = $row;
    }
    
    fclose($handle);
    
    return $rows_expected;
  }
}