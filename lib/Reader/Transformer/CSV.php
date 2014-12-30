<?php

namespace FileEnumerators\Reader\Transformer;

class CSV {
  
  protected $only_columns = [];
  protected $columns_to_names = [];
  protected $column_maps = [];
  
  
  /**
   * Comma separated numbers for columns to return, e.g.:
   *   (new CSV)->onlyColumns(1,3,5)
   *
   * @return FileEnumerators\Reader\Transformer\CSV
   */
  public function onlyColumns() {
    $this->only_columns = func_get_args();
    return $this;
  }
  
  
  /**
   * e.g.:
   *   (new CSV)->columnsToNames([
   *     1 => "Title",
   *     3 => "Score",
   *     5 => "Average"
   *   ])
   *
   * @param array $mapping Key => Value maps a given column ID to a column NAME
   * @return FileEnumerators\Reader\Transformer\CSV
   */
  public function columnsToNames(array $mapping) {
    $this->columns_to_names = $mapping;
    return $this;
  }
  
  
  /**
   * Apply a value level transformation for the given column.
   * NOTE that the transformations do not stack, for each column a single
   *      transformation can be defined
   *
   * e.g.:
   *   (new CSV)->mapColumn(1, 'trim')
   *
   *   OR custom callback
   *   (new CSV)->mapColumn(3, function($value){
   *     return preg_split(",", $value);
   *   })
   *
   * @param int $column_id ID of the source column
   * @param callable $callback Any PHP 'callable type' function
   * @return FileEnumerators\Reader\Transformer\CSV
   */
  public function mapColumn($column_id, callable $callback) {
    $this->column_maps[$column_id] = $callback;
    return $this;
  }
  
  
  /**
   * Applies the defined transformations to a CSV row.
   * Called internally by the Reader\CSV for each row, but can also be invoked
   * manually outside the processing pipeline.
   *
   * @param array $row
   * @return array
   */
  public function apply(array $row) {
    $resultant = [];
    
    $has_columns_filter = !empty($this->only_columns);
    $has_column_mappings = !empty($this->column_maps);
    $has_column_names = !empty($this->columns_to_names);
    
    foreach($row as $id => $value) {
      // one of those guys, for whom indexes should start at 1
      $offset_id = $id + 1;
      
      if($has_columns_filter && !isset($this->only_columns[$offset_id])) {
        continue;
      }
      
      $key = $has_column_names && isset($this->columns_to_names[$offset_id]) ?
        $this->columns_to_names[$offset_id] :
        $offset_id;
        
      $resultant[$key] = $has_column_mappings && isset($this->column_maps[$offset_id]) ?
        $this->column_maps[$offset_id]($value) :
        $value;
    }
    
    return $resultant;
  }
}