<?php

namespace FileEnumerators\Reader\Transformer;

/**
 * @todo in 2.0 make this an abstract class with the chain implementation done here
 */
interface TransformerInterface {
  
  /**
   * Convention for internal calls by the Reader's
   *
   * @param mixed $value
   * @return mixed
   */
  public function apply($value);
  
  
  /**
   * Followup transformer to call after the current one has
   * been applied
   *
   * @param mixed $transformer
   * @return self
   */
  public function chain(TransformerInterface $transformer);
}

