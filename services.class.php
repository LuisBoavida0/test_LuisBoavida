<?php
require_once 'Console/Table.php';

/**This Enumerator is to know which Service Field are we talking about*/
abstract class ServicesValuesEnum
{
  const ref = 0;
  const centre = 1;
  const service = 2;
  const country = 3;
}

/**This Class is a single service*/
class SingleService
{
  private $reference;
  private $centre;
  private $service;
  private $country;

  /**The constructor takes one array as input and we and we match his values ​​to the fields*/
  function __construct($singleServiceArr)
  {
    $this->reference = $singleServiceArr[ServicesValuesEnum::ref];
    $this->centre = $singleServiceArr[ServicesValuesEnum::centre];
    $this->service = $singleServiceArr[ServicesValuesEnum::service];
    $this->country = $singleServiceArr[ServicesValuesEnum::country];
  }

  /**Returns all the fields as an array */
  function get_as_array()
  {
    return array(
      $this->reference,
      $this->centre,
      $this->service,
      $this->country,
    );
  }
}

/**This Class is a set of single services.
 * This class has the Queries to be able to get results*/
class Services
{
  private $services;
  private $headers;

  /**This Constructor sets the variables.
   * has $servicesArr to enable to create the services class empty 
   * (to later implement a service push [allowing to add new single services])
   */
  function __construct($servicesArr = null)
  {
    /*The first field of the array is the header 
      so, set the variable and then delete that same row*/
    $this->headers = $servicesArr[0];
    array_shift($servicesArr);

    //push all services to the field as single services.
    $this->services = array();
    if ($servicesArr) {
      foreach ($servicesArr as $line) {
        $singleService = new SingleService($line);
        array_push($this->services, $singleService);
      }
    }
  }

  /**Returns the headers */
  function set_headers($headers)
  {
    $this->headers = $headers;
  }

  /** Queries the data to return all the values on a field where field (column) = text.
   * $field = ServicesValuesEnum;   
   * $text = value to filter
   */
  function get_by_field($field, $text)
  {
    //Creates the table and sets its headers
    $tbl = new Console_Table();
    $tbl->setHeaders($this->headers);

    //foreach service. $line = single service class
    $foundAnyRow = false;
    foreach ($this->services as $line) {
      /*Converts the single service class into an array 
      (to be able to find by index wich column are we comparing)*/
      $singleServiceArr = $line->get_as_array();
      //if value of column = text
      if (strtolower($singleServiceArr[$field]) == strtolower($text)) {
        $foundAnyRow = true;
        $tbl->addRow($singleServiceArr);
      }
    }
    if ($foundAnyRow)
      return $tbl->getTable();
    else 
      return "No matches Found";
  }

  /** Queries the data to return the number of times each different value appears in a column.
   * $field = ServicesValuesEnum;
   */
  function get_count_by_field($field)
  {
    /*This is an associative array that will have as a key the different value and 
      has a value the number of times it appears*/
    $num_countries = array();

    //foreach service. $line = single service class
    foreach ($this->services as $line) {
      /*Gets the value of the column in lowercase.
        $line will have the values of the current row.
        the index will say from each column we are getting the value*/
      $getFieldValue = strtolower($line->get_as_array()[$field]);
      //If value already exists the increase 1, if not, create a new one
      if (array_key_exists($getFieldValue, $num_countries))
        $num_countries[$getFieldValue]++;
      else
        $num_countries += [$getFieldValue => 1];
    }

    //Creates the table and sets its headers
    $tbl = new Console_Table();
    $tbl->setHeaders(
      array($this->headers[$field], "number of items")
    );

    //For each different value add a new row with that same value and the number of repetitions
    foreach ($num_countries as $key => $val) {
      $tbl->addRow(array($key, $val));
    }

    return $tbl->getTable();
  }
}
