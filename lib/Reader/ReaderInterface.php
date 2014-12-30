<?php

namespace FileEnumerators\Reader;

interface ReaderInterface {
  
  
  /**
   * @param string $filepath
   * @return mixed
   */
  public function open($filepath);
  
  
  /**
   * @param mixed $handle
   * @return
   */
  public function close($handle);
  
  public function consume($handle);
  
}