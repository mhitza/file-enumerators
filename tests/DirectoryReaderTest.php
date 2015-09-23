<?php

use FileEnumerators\Enumerator as Enumerator;
use FileEnumerators\Reader\Directory as DirectoryReader;

class DirectoryReaderTest extends PHPUnit_Framework_TestCase {
  
  public function testSimpleListing() {
    $enumerator = new Enumerator(__DIR__.DIRECTORY_SEPARATOR.'data', new DirectoryReader);
    
    $rows = [];
    foreach($enumerator->enumerate() as $inode) {
      $rows[] = $inode->getFilename();
    }
    
    $rows_expected = [
      'csv_sample_simple.txt',
      'line_sample.txt'
    ];
    
    $this->assertEquals(
      $rows_expected,
      $rows,
      'Should list first level of contents of tests/data/'
    );
  }
  
  /**
   * @expectedException InvalidArgumentException
   */
  public function testDirectoryValidation() {
    $target_file = __DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'line_sample.txt';
    $enumerator = new Enumerator($target_file, new DirectoryReader);
    
    foreach($enumerator->enumerate() as $inode) {}
  }
}