<?php
/**
 * Application Exception Class
 * $Id$
 *
 * @author Denysenko Dmytro


 */
require_once 'Zend/Exception.php';
require_once 'Vendor/Helper/Html.php';
class App_Exception extends Zend_Exception {
    /**
     *
     * @var array PHP error code => human readable name
     */
    public static $php_errors = array(
    E_ERROR => 'Fatal Error',
    E_USER_ERROR => 'User Error',
    E_PARSE => 'Parse Error',
    E_WARNING => 'Warning',
    E_USER_WARNING => 'User Warning',
    E_STRICT => 'Strict',
    E_NOTICE => 'Notice',
    E_RECOVERABLE_ERROR => 'Recoverable Error',
    );
    /**
     * Creates a new exception.
     *
     * @param string $ error message
     * @param array $ translation variables
     * @return void
     */
    public function __construct($message, array $variables = null, $code = 0)
    {
        // Pass the message to the parent
        parent::__construct($message, $code);
    }

    /**
     * Enable Application exception handling.
     *
     * @return void
     */
    public static function enable()
    {
        set_exception_handler(array(__CLASS__ , 'exceptionHandler'));
        set_error_handler(array(__CLASS__ , 'errorHandler'));
    }

    /**
     * Disable Application exception handling.
     *
     * @return void
     */
    public static function disable()
    {
        restore_exception_handler();
        restore_error_handler();
    }

    /**
     * PHP error handler, converts all errors into ErrorExceptions. This handler
     * respects error_reporting settings.
     *
     * @throws ErrorException
     * @return TRUE
     */
    public static function errorHandler($code, $error, $file = null, $line = null)
    {
        if ((error_reporting() &$code) !== 0) {
            // This error is not suppressed by current error reporting settings
            // Convert the error into an ErrorException
            throw new ErrorException($error, $code, 0, $file, $line);
        }
        // Do not execute the PHP error handler
        return true;
    }

    /**
     * Exception handler.
     *
     * @param object $ Exception instance
     * @return void
     */
    public static function exceptionHandler(Exception $e)
    {
        try {
            // Get the exception information
            $type = get_class($e);
            $code = $e->getCode();
            $message = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            // Create a text version of the exception
            $error = App_Exception::exceptionText($e);

            App::log($error, Zend_Log::ERR);

            if (App::isCli()) {
                // Just display the text of the exception
                echo "\n{$error}\n";

                return true;
            }
            // Get the exception backtrace
            $trace = $e->getTrace();

            if ($e instanceof ErrorException) {
                if (isset(App_Exception::$php_errors[$code])) {
                    // Use the human-readable error name
                    $code = App_Exception::$php_errors[$code];
                }

                if (version_compare(PHP_VERSION, '5.3', '<')) {
                    // Workaround for a bug in ErrorException::getTrace() that exists in
                    // all PHP 5.2 versions. @see http://bugs.php.net/bug.php?id=45895
                    for ($i = count($trace) - 1; $i > 0; --$i) {
                        if (isset($trace[$i - 1]['args'])) {
                            // Re-position the args
                            $trace[$i]['args'] = $trace[$i - 1]['args'];
                            // Remove the args
                            unset($trace[$i - 1]['args']);
                        }
                    }
                }
            }

            if (! headers_sent()) {
                // Make sure the proper content type is sent with a 500 status
                header('Content-Type: text/html; charset=utf-8', true, 500);
            }
            // Start an output buffer
            ob_start();
            // Include the exception HTML
            include APPLICATION_PATH . 'modules/main/views/scripts/error.phtml';
            // Display the contents of the output buffer
            echo ob_get_clean();

            return true;
        }
        catch (Exception $e) {
            // Clean the output buffer if one exists
            ob_get_level() and ob_clean();
            // Display the exception text
            echo App_Exception::exceptionText($e), "\n";
            // Exit with an error status
            exit(1);
        }
    }

    /**
     * Magic object-to-string method.
     *
     * @uses exceptionText method
     * @return string
     */
    public function __toString()
    {
        return App_Exception::exceptionText($this);
    }

    public static function exceptionText(Exception $e)
    {
        return sprintf('%s [ %s ]: %s ~ %s [ %d ]', get_class($e), $e->getCode(), strip_tags($e->getMessage()), App_Exception::debugPath($e->getFile()), $e->getLine());
    }

    /**
     * Removes application, system, modpath, or docroot from a filename,
     * replacing them with the plain text equivalents. Useful for debugging
     * when you want to display a shorter path.
     *
     * @param string $ path to debug
     * @return string
     */
    public static function debugPath($file)
    {
        if (strpos($file, LIBRARY_PATH) === 0) {
            $file = 'LIBRARY_PATH/' . substr($file, strlen(LIBRARY_PATH));
        } elseif (strpos($file, STATIC_PATH) === 0) {
            $file = 'STATIC_PATH/' . substr($file, strlen(STATIC_PATH));
        } elseif (strpos($file, VAR_PATH) === 0) {
            $file = 'VAR_PATH/' . substr($file, strlen(VAR_PATH));
        } elseif (strpos($file, APPLICATION_PATH) === 0) {
            $file = 'APPLICATION_PATH/' . substr($file, strlen(APPLICATION_PATH));
        } elseif (strpos($file, DOC_ROOT) === 0) {
            $file = 'DOC_ROOT/' . substr($file, strlen(DOC_ROOT));
        }

        return $file;
    }

    /**
     * Returns an HTML string, highlighting a specific line of a file, with some
     * number of lines padded above and below.
     *
     *        // Highlights the current line of the current file
     *        echo App_exception::debugSource(__FILE__, __LINE__);
     *
     * @param string $ file to open
     * @param integer $ line number to highlight
     * @param integer $ number of padding lines
     * @return string
     */
    public static function debugSource($file, $line_number, $padding = 5)
    {
        // Open the file and set the line position
        $file = fopen($file, 'r');
        $line = 0;
        // Set the reading range
        $range = array('start' => $line_number - $padding, 'end' => $line_number + $padding);
        // Set the zero-padding amount for line numbers
        $format = '% ' . strlen($range['end']) . 'd';

        $source = '';
        while (($row = fgets($file)) !== false) {
            // Increment the line number
            if (++$line > $range['end'])
            break;

            if ($line >= $range['start']) {
                // Make the row safe for output
                $row = htmlspecialchars($row, ENT_NOQUOTES, 'UTF-8');
                // Trim whitespace and sanitize the row
                $row = '<span class="number">' . sprintf($format, $line) . '</span> ' . $row;

                if ($line === $line_number) {
                    // Apply highlighting to this row
                    $row = '<span class="line highlight">' . $row . '</span>';
                } else {
                    $row = '<span class="line">' . $row . '</span>';
                }
                // Add to the captured source
                $source .= $row;
            }
        }
        // Close the file
        fclose($file);

        return '<pre class="source"><code>' . $source . '</code></pre>';
    }

    /**
     * Returns an array of HTML strings that represent each step in the backtrace.
     *
     *        // Displays the entire current backtrace
     *        echo implode('<br/>', App_Exception::trace());
     *
     * @param string $ path to debug
     * @return string
     */
    public static function trace(array $trace = null)
    {
        if ($trace === null) {
            // Start a new trace
            $trace = debug_backtrace();
        }
        // Non-standard function calls
        $statements = array('include', 'include_once', 'require', 'require_once');

        $output = array();
        foreach ($trace as $step) {
            if (! isset($step['function'])) {
                // Invalid trace step
                continue;
            }

            if (isset($step['file']) AND isset($step['line'])) {
                // Include the source of this step
                $source = self::debugSource($step['file'], $step['line']);
            }

            if (isset($step['file'])) {
                $file = $step['file'];

                if (isset($step['line'])) {
                    $line = $step['line'];
                }
            }
            // function()
            $function = $step['function'];

            if (in_array($step['function'], $statements)) {
                if (empty($step['args'])) {
                    // No arguments
                    $args = array();
                } else {
                    // Sanitize the file path
                    $args = array($step['args'][0]);
                }
            } elseif (isset($step['args'])) {
                if (isset($step['class'])) {
                    if (method_exists($step['class'], $step['function'])) {
                        $reflection = new ReflectionMethod($step['class'], $step['function']);
                    } else {
                        $reflection = new ReflectionMethod($step['class'], '__call');
                    }
                } else {
                    $reflection = new ReflectionFunction($step['function']);
                }
                // Get the function parameters
                $params = $reflection->getParameters();

                $args = array();

                foreach ($step['args'] as $i => $arg) {
                    if (isset($params[$i])) {
                        // Assign the argument by the parameter name
                        $args[$params[$i]->name] = $arg;
                    } else {
                        // Assign the argument by number
                        $args[$i] = $arg;
                    }
                }
            }

            if (isset($step['class'])) {
                // Class->method() or Class::method()
                $function = $step['class'] . $step['type'] . $step['function'];
            }

            $output[] = array(
                'function' => $function,
                'args' => isset($args) ? $args : null,
                'file' => isset($file) ? $file : null,
                'line' => isset($line) ? $line : null,
                'source' => isset($source) ? $source : null,
            );

            unset($function, $args, $file, $line, $source);
        }

        return $output;
    }

    /**
     * Returns an HTML string of information about a single variable.
     *
     * Borrows heavily on concepts from the Debug class of {@link http://nettephp.com/ Nette}.
     *
     * @param mixed $ variable to dump
     * @param integer $ maximum length of strings
     * @return string
     */
    public static function dump($value, $length = 128)
    {
        return self::_dump($value, $length);
    }

    /**
     * Helper for App_Exception::dump(), handles recursion in arrays and objects.
     *
     * @param mixed $ variable to dump
     * @param integer $ maximum length of strings
     * @param integer $ recursion level (internal)
     * @return string
     */
    private static function _dump(&$var, $length = 128, $level = 0)
    {
        if ($var === null) {
            return '<small>NULL</small>';
        } elseif (is_bool($var)) {
            return '<small>bool</small> ' . ($var ? 'TRUE' : 'FALSE');
        } elseif (is_float($var)) {
            return '<small>float</small> ' . $var;
        } elseif (is_resource($var)) {
            if (($type = get_resource_type($var)) === 'stream' AND $meta = stream_get_meta_data($var)) {
                $meta = stream_get_meta_data($var);

                if (isset($meta['uri'])) {
                    $file = $meta['uri'];

                    if (function_exists('stream_is_local')) {
                        // Only exists on PHP >= 5.2.4
                        if (stream_is_local($file)) {
                            $file = self::debug_path($file);
                        }
                    }

                    return '<small>resource</small><span>(' . $type . ')</span> ' . htmlspecialchars($file, ENT_NOQUOTES, self::$charset);
                }
            } else {
                return '<small>resource</small><span>(' . $type . ')</span>';
            }
        } elseif (is_string($var)) {
            if (strlen($var) > $length) {
                // Encode the truncated string
                $str = htmlspecialchars(substr($var, 0, $length), ENT_NOQUOTES, 'UTF-8') . '&nbsp;&hellip;';
            } else {
                // Encode the string
                $str = htmlspecialchars($var, ENT_NOQUOTES, 'UTF-8');
            }

            return '<small>string</small><span>(' . strlen($var) . ')</span> "' . $str . '"';
        } elseif (is_array($var)) {
            $output = array();
            // Indentation for this variable
            $space = str_repeat($s = '    ', $level);

            static $marker;

            if ($marker === null) {
                // Make a unique marker
                $marker = uniqid("\x00");
            }

            if (empty($var)) {
                // Do nothing
            } elseif (isset($var[$marker])) {
                $output[] = "(\n$space$s*RECURSION*\n$space)";
            } elseif ($level < 5) {
                $output[] = "<span>(";

                $var[$marker] = true;
                foreach ($var as $key => &$val) {
                    if ($key === $marker) continue;
                    if (! is_int($key)) {
                        $key = '"' . $key . '"';
                    }

                    $output[] = "$space$s$key => " . self::_dump($val, $length, $level + 1);
                }
                unset($var[$marker]);

                $output[] = "$space)</span>";
            } else {
                // Depth too great
                $output[] = "(\n$space$s...\n$space)";
            }

            return '<small>array</small><span>(' . count($var) . ')</span> ' . implode("\n", $output);
        } elseif (is_object($var)) {
            // Copy the object as an array
            $array = (array) $var;

            $output = array();
            // Indentation for this variable
            $space = str_repeat($s = '    ', $level);

            $hash = spl_object_hash($var);
            // Objects that are being dumped
            static $objects = array();

            if (empty($var)) {
                // Do nothing
            } elseif (isset($objects[$hash])) {
                $output[] = "{\n$space$s*RECURSION*\n$space}";
            } elseif ($level < 5) {
                $output[] = "<code>{";

                $objects[$hash] = true;
                foreach ($array as $key => &$val) {
                    if ($key[0] === "\x00") {
                        // Determine if the access is private or protected
                        $access = '<small>' . ($key[1] === '*' ? 'protected' : 'private') . '</small>';
                        // Remove the access level from the variable name
                        $key = substr($key, strrpos($key, "\x00") + 1);
                    } else {
                        $access = '<small>public</small>';
                    }

                    $output[] = "$space$s$access $key => " . self::_dump($val, $length, $level + 1);
                }
                unset($objects[$hash]);

                $output[] = "$space}</code>";
            } else {
                // Depth too great
                $output[] = "{\n$space$s...\n$space}";
            }

            return '<small>object</small> <span>' . get_class($var) . '(' . count($array) . ')</span> ' . implode("\n", $output);
        } else {
            return '<small>' . gettype($var) . '</small> ' . htmlspecialchars(print_r($var, true), ENT_NOQUOTES, 'UTF-8');
        }
    }
}