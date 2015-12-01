<?php
/**
 * ILIAS REST Plugin for the ILIAS LMS
 *
 * Authors: D.Schaefer and T.Hufschmidt <(schaefer|hufschmidt)@hrz.uni-marburg.de>
 * Since 2014
 */
namespace RESTController\database;

// This allows us to use shortcuts instead of full quantifier
use \RESTController\libs as Libs;


/**
 * Class: RESTPermissions (Database-Table)
 *  Abstraction for 'ui_uihk_rest_perm' database table.
 *  See RESTDatabase class for additional information.
 */
class RESTPermissions extends Libs\RESTDatabase {
  // This three variables contain information about the table layout
  protected static $primaryKey  = 'id';
  protected static $tableName   = 'ui_uihk_rest_perm';
  protected static $tableKeys   = array(
    'id'      => 'integer',
    'api_id'  => 'integer',
    'pattern' => 'text',
    'verb'    => 'text'
  );


  /**
   * Function: setKey($key, $value, $write)
   *  @See RESTDatabase->setKey(...)
   */
  public function setKey($key, $value, $write = false) {
    // Parse input based on key
    switch ($key) {
      // Convert int values
      case 'api_id':
        $value = intval($value);
        break;

      // Convert string to lower case
      case 'pattern':
        $value = strtolower($value);
        break;

      // Convert string to upper case
      case 'verb':
        $value = strtoupper($value);
        break;

      // No default behaviour
      default:
    }

    // Store key's value after convertion
    return parent::setKey($key, $value, $write);
  }


  /**
   * Function: setKey($joinTable)
   *  @See RESTDatabase::getJoinKey(...)
   */
  public static function getJoinKey($joinTable) {
    // JOIN ui_uihk_rest_keys ON api_id
    if ($joinTable == 'ui_uihk_rest_keys')
      return 'api_id';

    // Otherwise join on primary
    return parent::getJoinKey($joinTable);
  }
}
