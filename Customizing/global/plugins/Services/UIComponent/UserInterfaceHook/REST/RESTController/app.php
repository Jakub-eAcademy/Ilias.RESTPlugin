<?php
/**
 * ILIAS REST Plugin for the ILIAS LMS
 *
 * Authors: D.Schaefer and T.Hufschmidt <(schaefer|hufschmidt)@hrz.uni-marburg.de>
 * Since 2014
 */
namespace RESTController;

// Include SLIM-Framework
require_once('Slim/Slim.php');


/**
 * Class: RESTController
 *  This is the RESTController Slim-Application
 *  Handles all REST related logic and uses ILIAS
 *  Services to fetch requested data.
 *
 * Usage:
 *  require_once("<PATH-TO-THIS-FILE>". "/app.php");
 *  \RESTController\RESTController::registerAutoloader();
 *  $app = new \RESTController\RESTController("<PATH-TO-THIS-FILE>");
 *  $app->run();
 */
class RESTController extends \Slim\Slim {
  // Allow to re-use status messages and codes
  const MSG_NO_ROUTE  = 'There is no route matching this URI!';
  const ID_NO_ROUTE   = 'RESTController\RESTController::ID_NO_ROUTE';


  /**
   * Static-Function: autoload($classname)
   *  PSR-0 autoloader for RESTController classes.
   *  Automatically adds a "models" subname into the namespace of \RESTController\core und
   *  @See \Slim\Slim::autoload(...)
   *  Register this outload via RESTController::registerAutoloader().
   *
   * Parameters:
   *  $className <String> - Fully quantified classname (includes namespace) of a class that needs to be loaded
   */
  public static function autoload($className) {
    // Fetch sub namespaces
    $subNames = explode('\\', $className);

    // Only load classes inside RESTController namespace
    if ($subNames[0] === __NAMESPACE__) {
      // (Core-) Extentions can leave-out the "models" subname in their namespace
      if ($subNames[1] == 'extensions' || $subNames[1] == 'core') {
        // Add 'Models' to class namespace
        array_splice($subNames, 3, 0, array('models'));
        array_shift($subNames);
        parent::autoload(implode($subNames, '\\'));

        // Fallback (without appending 'models')
        if (!class_exists($className, false))
          parent::autoload(substr($className, strlen(__NAMESPACE__)));
      }
      // Everything else gets forwarded directly to Slim
      else
        parent::autoload(substr($className, strlen(__NAMESPACE__)));
    }

    // Use Slim-Frameworks autoloder for non-RESTController classes
    else
      parent::autoload($className);
  }


  /**
   * Function: registerAutoloader()
   *  Register PSR-0 autoloader. Call this before doing $app = new RESTController();
   */
  public static function registerAutoloader() {
    // Attach RESTController autoloader
    spl_autoload_register(__NAMESPACE__.'\\RESTController::autoload');
  }


  /**
   * Function: setCustomContainers()
   *  Attach custom 'containers' (singleton-instances) for
   *  logging, reading requests, writing responses and
   *  fetching available routes.
   */
  protected function setCustomContainers() {
    // Attach our custom RESTRouter, RESTRequest, RESTResponse
    $this->container->singleton('router',   function ($c) { return new libs\RESTRouter(); });
    $this->container->singleton('response', function ($c) { return new libs\RESTResponse(); });
    $this->container->singleton('request',  function ($c) { return new libs\RESTRequest($this->environment()); });

    // Configure custom LogWriter
    $this->container->singleton('logWriter', function ($c) {
      // Log to this file
      $restLog = ILIAS_LOG_DIR . '/restplugin.log';

      // Does the file need to be created first?
      if (!file_exists($restLog)) {
        $fh = fopen($restLog, 'w');
        fclose($fh);
      }

      // File needs to be writeable...
      if (!is_writable($restLog)) {
        // Use ilLog as fallback
        $ilLog = GLOBALS['ilLog'];
        $ilLog->write('Plugin REST -> Warning: Log file ' . $restLog . ' is not write-able!');
        return $ilLog;
      }

      // Use SLIMs LogWriter with custom file
      return new \Slim\LogWriter(fopen($restLog, 'a'));
    });
  }


  /**
   * Function: setErrorHandlers()
   *  Registers both a custom error-handler for errors/exceptions caughts by
   *  SLIM as well as registering a shutdown function for other FATAL errors.
   *  Additionally also disable PHP's display_errors flag!
   */
  protected function setErrorHandlers() {
    // Set default error-handler for exceptions caught by SLIM
    $this->error(function (\Exception $error) {
      $this->displayError($error->getMessage(), $error->getCode(), $error->getFile(), $error->getLine(), $error->getTraceAsString());
    });

    // Set default error-handler for any error/exception not caught by SLIM
    ini_set('display_errors', false);
    register_shutdown_function(function () {
      // Fetch latch error
      $err = error_get_last();

      // Check wether the error should to be displayed
      $allowed = array(
        E_ERROR         => 'E_ERROR',
        E_PARSE         => 'E_PARSE',
        E_CORE_ERROR    => 'E_CORE_ERROR',
        E_COMPILE_ERROR => 'E_COMPILE_ERROR',
        E_USER_ERROR    => 'E_USER_ERROR'
      );
      $errName = $allowed[$err['type']];

      // Log and display error?
      if ($errName)
        $this->displayError($err['message'], $err['type'], $err['file'], $err['line']);
    });
  }


  /**
   * Constructor: RESTController($appDirectory, $userSettings)
   *  Creates a new instance of the RESTController. There should always
   *  be only one instance and a reference can be fetches via:
   *   RESTController::getInstance()
   *
   * Parameters:
   *  $appDirectory <String> - Directory in which the app.php is contained
   *  $userSettings <Array[Mixed]> - Associative array of application settings
   */
  public function __construct($appDirectory, array $userSettings = array()) {
    // Call parent (SLIM) constructor
    parent::__construct($userSettings);

    // Setup custom router, request- & response classes
    $this->setCustomContainers();

    // Add Content-Type middleware (support for JSON requests)
    $contentType = new \Slim\Middleware\ContentTypes();
    $this->add($contentType);

    // Set default template base-directory
    $this->view()->setTemplatesDirectory($appDirectory);

    // Set default 404 template
    $this->notFound(function () { $this->halt(404, self::MSG_NO_ROUTE, self::ID_NO_ROUTE); });

    // Setup error-handler
    $this->setErrorHandlers();

    // Disable fancy debug-messages but enable logging
    $this->config('debug', false);
    $this->log->setEnabled(true);
    $this->log->setLevel(\Slim\Log::DEBUG);

    // Apped useful information to (global) slim-environment
    $env = $this->environment();
    $env['client_id']     = CLIENT_ID;
    $env['app_directory'] = $appDirectory;
  }


  /**
   * Function: Run()
   *  This method starts the actual RESTController application, including the middleware stack#
   *  and the core Slim application, which includes route-handling, etc.
   */
  public function run() {
    // Log each incoming rest request
    $this->log->info('REST call from ' . $_SERVER['REMOTE_ADDR'] . ' at ' . date('d/m/Y, H:i:s', time()));

    // Make $this available in all included models/routes
    $app = self::getInstance();

    // Load core routes
    foreach (glob(realpath(__DIR__).'/core/*/routes/*.php') as $filename)
      include_once($filename);

    // Load extension routes
    foreach (glob(realpath(__DIR__).'/extensions/*/routes/*.php') as $filename)
      include_once($filename);

    // Start the SLIM application
    parent::run();
  }


  /**
   * Function: displayError($msg, $code, $file, $line, $trace)
   *  Send the error-message given by the parameters to the clients
   *  and add a (critical) log-message to the active logfile.
   *
   * Parameters:
   *  $msg <String> - [Optional] Description of error/exception
   *  $code <Integer> - [Optional] Code of error/exception
   *  $file <String> - [Optional] File where the error/exception occured
   *  $line <Integer> - [Optional] Line in file where the error/exception occured
   *  $trace <String> - [Optional] Full (back-)trace (string) of error/exception
   */
  public function displayError($msg = '', $code = 0, $file = '', $line = 0, $trace = '') {
    // Format file-name and trace-data to be easer to read inside JSON
    $file   = str_replace('/', '\\', $file);
    $trace  = str_replace('/', '\\', $trace);

    // Generate error-object that will be logged and displayed
    $error = array(
      'msg'   => 'An error occured while handling this route!',
      'data'  => array(
        'message' => $msg,
        'code'    => $code,
        'file'    => $file,
        'line'    => $line,
        'trace'   => $trace
      )
    );

    // Log error to file
    $this->log->critical($error);

    // Send error to client
    header('content-type: application/json');
    echo json_encode($error);
  }


  /**
   * Function: success(($data)
   *  This function should be used by any route that wants to return
   *  data after a successfull query. The application will be terminated
   *  afterwards, so make sure any required cleanup happens before
   *  a call to success(...).
   *
   *  @See RESTController->halt(...) for additional notes!
   *
   * Parameters:
   *  $data <String>/<Array[Mixed]> -
   */
  public function success($data) {
    // Delegate to halt(...)
    $this->halt(200, $data, null);
  }


  /**
   * Function: halt(($httpCode, $data, $restCode)
   *  This function should be used by any route that wants to return
   *  data or any kind of information after query/request has failed
   *  for some reason . The application will be terminated afterwards,
   *  so make sure any required cleanup happens before a call to halt(...).
   *
   *
   * Note 1:
   *  It is important to note, that this will imidiately send the given $data
   *  (as JSON, unless changed via response->setFormat(...)) and in addition
   *  will cause the application to be terminated by internally throwing
   * 'Slim\Exception\Stop'. This is to prevent any further data from 'leaking'
   *  to the client, which could invalidate the transmitted JSON object.
   *  In case of failure this also negates the requirement to manually invoke
   *  die() or exit() each time...
   *
   * Note 2:
   *  In the rare cases where this behaviour might not be usefull, there is also
   *  the options to directly access the response-object via $app->response() and
   *  (See libs\RESTResponse and Slim\Http\Response for additonal details)
   *  The Data will then be send either after the exiting the route-function or
   *  by manually throwing 'Slim\Exception\Stop'. (Not recommended)
   *  (Transmitting data this way should be used sparingly!)
   *
   * Note 3:
   *  Never use this method or access the $app->request() and $app->response()
   *  object from within a model, since this would make it difficult to reuse.
   *  Only use inside a route or IO-Class and pass data from/to models!
   *
   * Parameters:
   *  $httpCode <Integer> -
   *  $data <String>/<Array[Mixed]> - [Optional]
   *  $restCode <String> - [Optional]
   */
  public function halt($httpCode, $data = null, $restCode = 'halt') {
    // Do some pre-processing on the $data
    $response = libs\RESTLib::responseObject($data, $restCode);

    // Delegate transmission of response to SLIM
    parent::halt($httpCode, $response);
  }
}
