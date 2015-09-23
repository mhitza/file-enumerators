<?php

namespace FileEnumerators\Reader;

class Directory implements ReaderInterface {
  
  public function open($filepath) {
    if(false === is_dir($filepath)) {
      throw new \InvalidArgumentException("$filepath should point to a directory");
    }
    
    return new \DirectoryIterator($filepath);
  }
  
  public function close($handle) {
  }
  
  
  /**
   * @param \DirectoryIterator $handle
   */
  public function consume($handle) {
    foreach($handle as $inode) {
      if($inode->isDot()) continue;
      if($inode->isDir()) continue;
      yield $inode;
    }
  }
}

