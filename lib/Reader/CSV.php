<?php

namespace FileEnumerators\Reader;

use FileEnumerators\Reader\Transformer\TransformerInterface;

class CSV implements ReaderInterface {
  
  const TAB_DELIMITED = "\t";
  const COMMA_DELIMITED = ",";
  
  
  protected $delimiter;
  /** @var FileEnumerators\Reader\Transformer\TransformerInterface */
  protected $transformer;
  
  
  /**
   * @param string $delimiter Field delimiter (ONE CHARACTER ONLY) OR use CSV::TAB_DELIMITED / CSV::COMMAN_DELIMITED
   * @param FileEnumerators\Reader\Transformer\TransformerInterface
   */
  public function __construct($delimiter = CSV::COMMA_DELIMITED, TransformerInterface $transformer = null) {
    $this->delimiter = $delimiter;
    $this->transformer = $transformer;
  }
  
  
  public function open($filepath) {
    return fopen($filepath, 'r');
  }
  
  public function close($handle) {
    return fclose($handle);
  }
  
  public function consume($handle) {
    while(false !== ($row = fgetcsv($handle, 0, $this->delimiter))) {
      if(false === is_null($this->transformer)) {
        yield $this->transformer->apply($row);
      }
      else {
        yield $row;
      }
    }
  }
}

