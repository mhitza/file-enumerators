<?php

use FileEnumerators\Enumerator as Enumerator;
use FileEnumerators\Reader\Line as LineReader;
use FileEnumerators\Reader\Transformer\FunctionMap as FunctionMapTransformer;


class LineReaderTest extends PHPUnit_Framework_TestCase {
  
  const SAMPLE_FILEPATH = __DIR__.'/data/line_sample.txt';
  
  public function testAgainstFileGetContents() {
    $enumerator = new Enumerator(self::SAMPLE_FILEPATH, new LineReader);
    
    $collector = "";
    foreach($enumerator->enumerate() as $line) {
      $collector .= $line;
    }
    
    $expected = file_get_contents(self::SAMPLE_FILEPATH);
    $this->assertEquals($expected, $collector);
  }
  
  
  public function testReiteration() {
    $enumerator = new Enumerator(self::SAMPLE_FILEPATH, new LineReader);
    
    $collector1 = "";
    foreach($enumerator->enumerate() as $line) {
      $collector1 .= $line;
    }
    
    $collector2 = "";
    foreach($enumerator->enumerate() as $line) {
      $collector2 .= $line;
    }
    
    $this->assertEquals(
      $collector1,
      $collector2,
      "Running twice over the source should yield same values"
    );
  }
  
  
  public function testFunctionMap() {
    $enumerator = new Enumerator(
      self::SAMPLE_FILEPATH,
      new LineReader(
        new FunctionMapTransformer(function($value) {
          return preg_split('/-/', trim($value));
        })
      )
    );
    
    $collector = [];
    foreach($enumerator->enumerate() as $values) {
      $collector[] = $values;
    }
    
    $expected = [
      ["lorem"],
      ["ipsum"],
      ["dolor", "sit"]
    ];
    
    $this->assertEquals(
      $expected,
      $collector,
      "Last value should be an array with two elements"
    );
  }
}
