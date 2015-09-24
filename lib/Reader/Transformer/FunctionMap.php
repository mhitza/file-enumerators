<?php

namespace FileEnumerators\Reader\Transformer;

class FunctionMap implements TransformerInterface {
  
  protected $callback;
  /** @var TransformerInterface */
  protected $chain = null;
  
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
    $result = $callback($value);
    return false === is_null($this->chain) ?
      $this->chain->apply($result) :
      $result;
  }
  
  
  public function chain(TransformerInterface $transformer) {
    $this->chain = $transformer;
    return $this;
  }
}
