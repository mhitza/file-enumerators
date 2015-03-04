<?php

namespace FileEnumerators\Reader\Transformer;

interface TransformerInterface {
  
  /**
   * Convention for internal calls by the Reader's
   *
   * @param mixed $value
   * @return mixed
   */
  public function apply($value);
}

