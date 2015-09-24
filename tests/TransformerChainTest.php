<?php

use FileEnumerators\Enumerator as Enumerator;
use FileEnumerators\Reader\Line as LineReader;
use FileEnumerators\Reader\Transformer\FunctionMap as FunctionMapTransformer;

class TransformerChainTest extends PHPUnit_Framework_TestCase {
  
  public function testTwoChainedFunctionMapTransformers() {
    $transformers = new FunctionMapTransformer(function($value) {
      return $value.'_first';
    });
    $transformers->chain(new FunctionMapTransformer(function($value){
      return $value.'_second';
    }));
    
    $enumerator = new Enumerator(__DIR__.'/data/line_sample.txt', new LineReader($transformers));
    
    $collector = [];
    foreach($enumerator->enumerate() as $line) {
      $collector[] = $line;
    }
    
    $expected_raw = file(__DIR__.'/data/line_sample.txt');
    $expected = [];
    foreach($expected_raw as $raw_line) {
      $expected[] = $raw_line.'_first_second';
    }
    
    $this->assertEquals(
      $expected,
      $collector,
      "Order should be preserved in transformer chaining"
    );
  }
}