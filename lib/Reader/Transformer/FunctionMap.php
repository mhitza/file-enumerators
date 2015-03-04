<?php

namespace FileEnumerators\Reader\Transformer;

class FunctionMap implements TransformerInterface {
  
  protected $callback;
  
  /**
   * @param callable $callback Callback to apply on each data
   * @throws \InvalidArgumentException
   */
  public function __construct(callable $callback) {
    $this->callback = $callback;
  }
  
  
  public function apply($value) {
    // Note: PHP doesn't like $this->callback($value)
    $callback = $this->callback;
    return $callback($value);
  }
}
