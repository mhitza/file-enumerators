<?php

namespace FileEnumerators;

class Enumerator {
  
  /** @var FileEnumerators\Reader\ReaderInterface */
  protected $reader;
  
  public function __construct($filepath, Reader\ReaderInterface $reader) {
    $this->filepath = $filepath;
    $this->reader = $reader;
  }
  
  
  public function enumerate() {
    $handle = $this->reader->open($this->filepath);
    
    foreach($this->reader->consume($handle) as $entity) {
      yield $entity;
    }
    
    $this->reader->close($handle);
  }
}
