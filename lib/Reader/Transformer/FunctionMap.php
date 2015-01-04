<?php

namespace FileEnumerators\Reader\Transformer;

class FunctionMap implements TransformerInterface {
  
  protected $callback;
  
  /**
   * @param callable $callback Callback to apply on each data
   * @throws \InvalidArgumentException
   */
  public function __construct(callable $callback) {
    if(is_null($callback)) {
      throw new \InvalidArgumentException(
        "FunctionMap callback can not be null"
      );
    }
    
    $this->callback = $callback;
  }
  
  
  public function apply($value) {
    // XXX: PHP doesn't like $this->callback($value)
    $callback = $this->callback;
    return $callback($value);
  }
}
