<?php

namespace FileEnumerators\Reader\Transformer;

class CSV implements TransformerInterface {
  
  protected $only_columns = [];
  protected $columns_to_names = [];
  protected $column_maps = [];
  
  
  /**
   * Comma separated numbers for columns to return, e.g.:
   *   (new CSV)->onlyColumns(0,2,4)
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
   *     0 => "Title",
   *     2 => "Score",
   *     4 => "Average"
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
   *   (new CSV)->mapColumn(0, 'trim')
   *
   *   OR custom callback
   *   (new CSV)->mapColumn(2, function($value){
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
   * @param array $value
   * @return array
   */
  public function apply($value) {
    $resultant = [];
    
    $has_columns_filter = !empty($this->only_columns);
    $has_column_mappings = !empty($this->column_maps);
    $has_column_names = !empty($this->columns_to_names);
    
    foreach($value as $id => $value) {
      if($has_columns_filter && !in_array($id, $this->only_columns)) {
        continue;
      }
      
      $key = $has_column_names && isset($this->columns_to_names[$id]) ?
        $this->columns_to_names[$id] :
        $id;
        
      $resultant[$key] = $has_column_mappings && isset($this->column_maps[$id]) ?
        $this->column_maps[$id]($value) :
        $value;
    }
    
    return $resultant;
  }
}