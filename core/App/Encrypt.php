<?php
/**
* The Encrypt library provides two-way encryption of text and binary strings
* using the MCrypt extension.
*
* @see http://php.net/mcrypt
*/
class App_Encrypt {
    // OS-dependant RAND type to use
    protected static $rand;
    // Configuration
    protected $config;

    /**
    * Returns a singleton instance of Encrypt.
    *
    * @param array $ configuration options
    * @return App_Encrypt
    */
    public static function instance($config = null)
    {
        static $instance;
        // Create the singleton
        empty($instance) and $instance = new App_Encrypt((array) $config);

        return $instance;
    }

    /**
    * Loads encryption configuration and validates the data.
    *
    * @param array $ |string      custom configuration or config group name
    * @throws App_Exception
    */
    public function __construct($config = false)
    {
        if (! defined('MCRYPT_ENCRYPT'))
            throw new App_Exception('To use the Encrypt library, mcrypt must be enabled in your PHP installation');

        if (is_string($config)) {
            $name = $config;
            // Test the config group name
            if (($config = App::config()->encryption->$config) === null)
                throw new App_Exception('The ' . $name . ' group is not defined in your configuration.');
        }

        if (is_array($config)) {
            // Append the default configuration options
            $config += App::config()->encryption->default->toArray();
            } else {
                // Load the default group
                $config = App::config()->encryption->default->toArray();
                }

                if (empty($config['key']))
                    throw new App_Exception('To use the Encrypt library, you must set an encryption key in your config file');
                // Find the max length of the key, based on cipher and mode
                $size = mcrypt_get_key_size($config['cipher'], $config['mode']);

                if (strlen($config['key']) > $size) {
                    // Shorten the key to the maximum size
                    $config['key'] = substr($config['key'], 0, $size);
                }
                // Find the initialization vector size
                $config['iv_size'] = mcrypt_get_iv_size($config['cipher'], $config['mode']);
                // Cache the config in the object
                $this->config = $config;

                App::log('Encrypt Library initialized', Zend_Log::DEBUG);
            }

            /**
            * Encrypts a string and returns an encrypted string that can be decoded.
            *
            * @param string $ data to be encrypted
            * @return string encrypted data
            */
            public function encode($data)
            {
                // Set the rand type if it has not already been set
                if (App_Encrypt::$rand === null) {
                    if (App::isWin()) {
                        // Windows only supports the system random number generator
                        App_Encrypt::$rand = MCRYPT_RAND;
                    } else {
                        if (defined('MCRYPT_DEV_URANDOM')) {
                            // Use /dev/urandom
                            App_Encrypt::$rand = MCRYPT_DEV_URANDOM;
                        } elseif (defined('MCRYPT_DEV_RANDOM')) {
                            // Use /dev/random
                            App_Encrypt::$rand = MCRYPT_DEV_RANDOM;
                        } else {
                            // Use the system random number generator
                            App_Encrypt::$rand = MCRYPT_RAND;
                        }
                    }
                }

                if (App_Encrypt::$rand === MCRYPT_RAND) {
                    // The system random number generator must always be seeded each
                    // time it is used, or it will not produce true random results
                    mt_srand();
                }
                // Create a random initialization vector of the proper size for the current cipher
                $iv = mcrypt_create_iv($this->config['iv_size'], App_Encrypt::$rand);
                // Encrypt the data using the configured options and generated iv
                $data = mcrypt_encrypt($this->config['cipher'], $this->config['key'], $data, $this->config['mode'], $iv);
                // Use base64 encoding to convert to a string
                return base64_encode($iv . $data);
            }

            /**
            * Decrypts an encoded string back to its original value.
            *
            * @param string $ encoded string to be decrypted
            * @return string decrypted data
            */
            public function decode($data)
            {
                // Convert the data back to binary
                $data = base64_decode($data);
                // Extract the initialization vector from the data
                $iv = substr($data, 0, $this->config['iv_size']);
                // Remove the iv from the data
                $data = substr($data, $this->config['iv_size']);
                // Return the decrypted data, trimming the \0 padding bytes from the end of the data
                return rtrim(mcrypt_decrypt($this->config['cipher'], $this->config['key'], $data, $this->config['mode'], $iv), "\0");
            }
        } // End Encrypt
