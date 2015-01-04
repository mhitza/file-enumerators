<?php

namespace FileEnumerators\Reader;

use FileEnumerators\Reader\Transformer\TransformerInterface;

class Line implements ReaderInterface {
  
  /** @var FileEnumerators\Reader\Transformer\TransformerInterface */
  protected $transformer;
  
  public function __construct(TransformerInterface $transformer = null) {
    $this->transformer = $transformer;
  }
  
  
  public function open($filepath) {
    return fopen($filepath, 'r');
  }
  
  public function close($handle) {
    return fclose($handle);
  }
  
  public function consume($handle) {
    while(false !== ($line = fgets($handle))) {
      if(false === is_null($this->transformer)) {
        yield $this->transformer->apply($line);
      }
      else {
        yield $line;
      }
    }
  }
}