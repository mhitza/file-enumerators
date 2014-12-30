<?php

namespace FileEnumerators\Reader;

class Line implements ReaderInterface {
  
  public function open($filepath) {
    return fopen($filepath, 'r');
  }
  
  public function close($handle) {
    return fclose($handle);
  }
  
  public function consume($handle) {
    while(false !== ($line = fgets($handle))) {
      yield $line;
    }
  }
}