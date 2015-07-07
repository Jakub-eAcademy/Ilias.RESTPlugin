<?php
/**
 * ILIAS REST Plugin for the ILIAS LMS
 *
 * Authors: D.Schaefer, T.Hufschmidt <(schaefer|hufschmidt)@hrz.uni-marburg.de>
 * Since 2014
 */
namespace RESTController\libs\Exceptions;

/**
 * This class provides generic exception handling for Create / POST events.
 */
class CreateFailed extends \Exception {
    /**
     * List of default REST error-codes
     *  Extensions are allowed to create their own error-codes.
     *  Using a unique string seems to be an easier solution than assigning unique numbers.
     */
    const ID = 'RESTController\\libs\\Exceptions\\CreateFailed';


    /**
     * Stores api-id and fieldname of client causing POST issue
     */
    protected $id;
    protected $fieldName;


    /**
     * Constructor
     *  Don't set member variables, since this is a Exception that handles multiple issues
     */
    public function __construct ($message, $id, $fieldName, $code = 0, $previous = NULL) {
        parent::__construct ($message, $code, $previous);
        $this->id = $id;
        $this->fieldName = $fieldName;
    }

    /**
     * Get api-id or fieldName of client that caused the DELETE issue
     */
    public function id() {
        return $this->id;
    }
    public function fieldName() {
        return $this->fieldName;
    }


    /**
     *
     */
    public function getMessage() {
        $message = parent::getMessage();
        $message = str_replace('%id%', $this->id, $message);
        $message = str_replace('%fieldName%', $this->fieldName, $message);
        return $message;
    }
}
