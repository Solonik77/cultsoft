<?php
/**
 * Manipulate images using standard methods such as resize, crop, rotate, etc.
 * This class must be re-initialized for every image you wish to manipulate.
 */
class App_Image {
    // Master Dimension
    const NONE = 1;
    const AUTO = 2;
    const HEIGHT = 3;
    const WIDTH = 4;
    // Flip Directions
    const HORIZONTAL = 5;
    const VERTICAL = 6;
    // Allowed image types
    public static $allowed_types = array(IMAGETYPE_GIF => 'gif' , IMAGETYPE_JPEG => 'jpg' , IMAGETYPE_PNG => 'png' , IMAGETYPE_TIFF_II => 'tiff' , IMAGETYPE_TIFF_MM => 'tiff');
    // Adapter instance
    protected $adapter;
    // Adapter actions
    protected $actions = array();
    // Reference to the current image filename
    protected $image = '';

    /**
     * Creates a new Image instance and returns it.
     *
     * @param string $ filename of image
     * @param array $ non-default configurations
     * @return object
     */
    public static function factory($image, $config = null)
    {
        return new App_Image($image, $config);
    }

    /**
     * Creates a new image editor instance.
     *
     * @throws App_Exception
     * @param string $ filename of image
     * @param array $ non-default configurations
     * @return void
     */
    public function __construct($image, $config = null)
    {
        static $check;
        // Make the check exactly once
        ($check === null) and $check = function_exists('getimagesize');
        if ($check === false)
        throw new App_Exception('The Image library requires the getimagesize() PHP function, which is not available in your installation.');
        // Check to make sure the image exists
        if (! is_file($image))
        throw new App_Exception('The specified image, ' . $image . ', was not found. Please verify that images exist by using file_exists() before manipulating them.');
        // Disable error reporting, to prevent PHP warnings
        $ER = error_reporting(0);
        // Fetch the image size and mime type
        $image_info = getimagesize($image);
        // Turn on error reporting again
        error_reporting($ER);
        // Make sure that the image is readable and valid
        if (! is_array($image_info) or count($image_info) < 3)
        throw new App_Exception('The specified image, ' . $image . ', is unreadable.');
        // Check to make sure the image type is allowed
        if (! isset(Image::$allowed_types[$image_info[2]]))
        throw new App_Exception('The specified image, ' . $image . ', is not an allowed image type.');
        // Image has been validated, load it
        $this->image = array('file' => str_replace('\\', '/', realpath($image)) , 'width' => $image_info[0] , 'height' => $image_info[1] , 'type' => $image_info[2] , 'ext' => App_Image::$allowed_types[$image_info[2]] , 'mime' => $image_info['mime']);
        // Load configuration
        $this->config = (array) $config + App::config()->image->toArray();
        // Set adapter class name
        $adapter = 'App_Image_Adapter_' . ucfirst($this->config['adapter']);
        // Load the adapter
        if (! Zend_Loader::loadClass($adapter))
        throw new App_Exception('The ' . $this->config['adapter'] . ' adapter for the Image  library could not be found');
        // Initialize the adapter
        $this->adapter = new $adapter($this->config['params']);
        // Validate the adapter
        if (! ($this->adapter instanceof App_Image_Adapter))
        throw new App_Exception('The ' . $this->config['adapter'] . ' adapter for the %s library must implement the ' . get_class($this) . ' interface');
    }

    /**
     * Handles retrieval of pre-save image properties
     *
     * @param string $ property name
     * @return mixed
     */
    public function __get($property)
    {
        if (isset($this->image[$property])) {
            return $this->image[$property];
        } else {
            throw new App_Exception('The ' . $property . ' property does not exist in the ' . get_class($this) . ' class.');
        }
    }

    /**
     * Resize an image to a specific width and height. By default, Kohana will
     * maintain the aspect ratio using the width as the master dimension. If you
     * wish to use height as master dim, set $image->master_dim = App_Image::HEIGHT
     * This method is chainable.
     *
     * @throws App_Exception
     * @param integer $ width
     * @param integer $ height
     * @param integer $ one of: App_Image::NONE, App_Image::AUTO, App_Image::WIDTH, App_Image::HEIGHT
     * @return object
     */
    public function resize($width, $height, $master = null)
    {
        if (! $this->valid_size('width', $width))
        throw new App_Exception('The width you specified, ' . $width . ', is not valid.');
        if (! $this->valid_size('height', $height))
        throw new App_Exception('The height you specified, ' . $height . ', is not valid.');
        if (empty($width) and empty($height))
        throw new App_Exception('The dimensions specified for ' . __FUNCTION__ . ' are not valid.');
        if ($master === null) {
            // Maintain the aspect ratio by default
            $master = App_Image::AUTO;
        } elseif (! $this->valid_size('master', $master))
        throw new App_Exception('The master dimension specified is not valid.');
        $this->actions['resize'] = array('width' => $width , 'height' => $height , 'master' => $master);
        return $this;
    }

    /**
     * Crop an image to a specific width and height. You may also set the top
     * and left offset.
     * This method is chainable.
     *
     * @throws App_Exception
     * @param integer $ width
     * @param integer $ height
     * @param integer $ top offset, pixel value or one of: top, center, bottom
     * @param integer $ left offset, pixel value or one of: left, center, right
     * @return object
     */
    public function crop($width, $height, $top = 'center', $left = 'center')
    {
        if (! $this->valid_size('width', $width))
        throw new App_Exception('The width you specified, ' . $width . ', is not valid.');
        if (! $this->valid_size('height', $height))
        throw new App_Exception('The height you specified, ' . $height . ', is not valid.');
        if (! $this->valid_size('top', $top))
        throw new App_Exception('Invalid ' . $top . ' top');
        if (! $this->valid_size('left', $left))
        throw new App_Exception('Invalid ' . $left . ' left');
        if (empty($width) and empty($height))
        throw new App_Exception('The dimensions specified for ' . __FUNCTION__ . ' are not valid.');
        $this->actions['crop'] = array('width' => $width , 'height' => $height , 'top' => $top , 'left' => $left);
        return $this;
    }

    /**
     * Allows rotation of an image by 180 degrees clockwise or counter clockwise.
     *
     * @param integer $ degrees
     * @return object
     */
    public function rotate($degrees)
    {
        $degrees = (int) $degrees;
        if ($degrees > 180) {
            do {
                // Keep subtracting full circles until the degrees have normalized
                $degrees -= 360;
            } while ($degrees > 180);
        }
        if ($degrees < - 180) {
            do {
                // Keep adding full circles until the degrees have normalized
                $degrees += 360;
            } while ($degrees < - 180);
        }
        $this->actions['rotate'] = $degrees;
        return $this;
    }

    /**
     * Flip an image horizontally or vertically.
     *
     * @throws App_Exception
     * @param integer $ direction
     * @return object
     */
    public function flip($direction)
    {
        if ($direction !== App_Image::HORIZONTAL and $direction !== App_Image::VERTICAL)
        throw new App_Exception('The flip direction specified is not valid.');
        $this->actions['flip'] = $direction;
        return $this;
    }

    /**
     * Change the quality of an image.
     *
     * @param integer $ quality as a percentage
     * @return object
     */
    public function quality($amount)
    {
        $this->actions['quality'] = max(1, min($amount, 100));
        return $this;
    }

    /**
     * Sharpen an image.
     *
     * @param integer $ amount to sharpen, usually ~20 is ideal
     * @return object
     */
    public function sharpen($amount)
    {
        $this->actions['sharpen'] = max(1, min($amount, 100));
        return $this;
    }

    /**
     * Save the image to a new image or overwrite this image.
     *
     * @throws App_Exception
     * @param string $ new image filename
     * @param integer $ permissions for new image
     * @param boolean $ keep or discard image process actions
     * @return object
     */
    public function save($new_image = false, $chmod = 0644, $keep_actions = false)
    {
        // If no new image is defined, use the current image
        empty($new_image) and $new_image = $this->image['file'];
        // Separate the directory and filename
        $dir = pathinfo($new_image, PATHINFO_DIRNAME);
        $file = pathinfo($new_image, PATHINFO_BASENAME);
        // Normalize the path
        $dir = str_replace('\\', '/', realpath($dir)) . '/';
        if (! is_writable($dir))
        throw new App_Exception('The specified directory, ' . $dir . ', is not writable.');
        if ($status = $this->adapter->process($this->image, $this->actions, $dir, $file)) {
            if ($chmod !== false) {
                // Set permissions
                chmod($new_image, $chmod);
            }
        }
        // Reset actions. Subsequent save() or render() will not apply previous actions.
        if ($keep_actions === false)
        $this->actions = array();
        return $status;
    }

    /**
     * Output the image to the browser.
     *
     * @param boolean $ keep or discard image process actions
     * @return object
     */
    public function render($keep_actions = false)
    {
        $new_image = $this->image['file'];
        // Separate the directory and filename
        $dir = pathinfo($new_image, PATHINFO_DIRNAME);
        $file = pathinfo($new_image, PATHINFO_BASENAME);
        // Normalize the path
        $dir = str_replace('\\', '/', realpath($dir)) . '/';
        // Process the image with the adapter
        $status = $this->adapter->process($this->image, $this->actions, $dir, $file, $render = true);
        // Reset actions. Subsequent save() or render() will not apply previous actions.
        if ($keep_actions === false)
        $this->actions = array();
        return $status;
    }

    /**
     * Sanitize a given value type.
     *
     * @param string $ type of property
     * @param mixed $ property value
     * @return boolean
     */
    protected function valid_size($type, &$value)
    {
        if (is_null($value))
        return true;
        if (! is_scalar($value))
        return false;
        switch ($type) {
            case 'width':
            case 'height':
                if (is_string($value) and ! ctype_digit($value)) {
                    // Only numbers and percent signs
                    if (! preg_match('/^[0-9]++%$/D', $value))
                    return false;
                } else {
                    $value = (int) $value;
                }
                break;
            case 'top':
                if (is_string($value) and ! ctype_digit($value)) {
                    if (! in_array($value, array('top' , 'bottom' , 'center')))
                    return false;
                } else {
                    $value = (int) $value;
                }
                break;
            case 'left':
                if (is_string($value) and ! ctype_digit($value)) {
                    if (! in_array($value, array('left' , 'right' , 'center')))
                    return false;
                } else {
                    $value = (int) $value;
                }
                break;
            case 'master':
                if ($value !== App_Image::NONE and $value !== App_Image::AUTO and $value !== App_Image::WIDTH and $value !== App_Image::HEIGHT)
                return false;
                break;
        }
        return true;
    }
} // End Image