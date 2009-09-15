<?php
/**
 * GraphicsMagick Image Adapter.
 */
class App_Image_Adapter_GraphicsMagick extends App_Image_Adapter {
    // Directory that GM is installed in
    protected $dir = '';
    // Command extension(exe for windows)
    protected $ext = '';
    // Temporary image filename
    protected $tmp_image;

    /**
     * Attempts to detect the GraphicsMagick installation directory.
     *
     * @throws App_Exception
     * @param array $ configuration
     * @return void
     */
    public function __construct($config)
    {
        if (empty($config['directory'])) {
            // Attempt to locate GM by using "which"(only works for *nix!)
            if (! is_file($path = exec('which gm')))
            throw new App_Exception('The GraphicsMagick directory specified does not contain a required program.');
            $config['directory'] = dirname($path);
        }
        // Set the command extension
        $this->ext = (PHP_SHLIB_SUFFIX === 'dll') ? '.exe' : '';
        // Check to make sure the provided path is correct
        if (! is_file(realpath($config['directory']) . '/gm' . $this->ext))
        throw new App_Exception('The GraphicsMagick directory specified does not contain a required program, gm.' . $this->ext);
        // Set the installation directory
        $this->dir = str_replace('\\', '/', realpath($config['directory'])) . '/';
    }

    /**
     * Creates a temporary image and executes the given actions. By creating a
     * temporary copy of the image before manipulating it, this process is atomic.
     */
    public function process($image, $actions, $dir, $file, $render = false)
    {
        // We only need the filename
        $image = $image['file'];
        // Unique temporary filename
        $this->tmp_image = $dir . 'k2img--' . sha1(time() . $dir . $file) . substr($file, strrpos($file, '.'));
        // Copy the image to the temporary file
        copy($image, $this->tmp_image);
        // Quality change is done last
        $quality = (int) Vendor\Helper\Arr::remove('quality', $actions);
        // Use 95 for the default quality
        empty($quality) and $quality = 95;
        // All calls to these will need to be escaped, so do it now
        $this->cmd_image = escapeshellarg($this->tmp_image);
        $this->new_image = ($render) ? $this->cmd_image : escapeshellarg($dir . $file);
        if ($status = $this->execute($actions)) {
            // Use convert to change the image into its final version. This is
            // done to allow the file type to change correctly, and to handle
            // the quality conversion in the most effective way possible.
            if ($error = exec(
            escapeshellcmd($this->dir . 'gm' . $this->ext . ' convert') . ' -quality ' . $quality . '% ' . $this->cmd_image . ' ' . $this->new_image)) {
                $this->errors[] = $error;
            } else {
                // Output the image directly to the browser
                if ($render !== false) {
                    $contents = file_get_contents($this->tmp_image);
                    switch (substr($file, strrpos($file, '.') + 1)) {
                        case 'jpg':
                        case 'jpeg':
                            header('Content-Type: image/jpeg');
                            break;
                        case 'gif':
                            header('Content-Type: image/gif');
                            break;
                        case 'png':
                            header('Content-Type: image/png');
                            break;
                    }
                    echo $contents;
                }
            }
        }
        // Remove the temporary image
        unlink($this->tmp_image);
        $this->tmp_image = '';
        return $status;
    }

    public function crop($prop)
    {
        // Sanitize and normalize the properties into geometry
        $this->sanitize_geometry($prop);
        // Set the IM geometry based on the properties
        $geometry = escapeshellarg($prop['width'] . 'x' . $prop['height'] . '+' . $prop['left'] . '+' . $prop['top']);
        if ($error = exec(escapeshellcmd($this->dir . 'gm' . $this->ext . ' convert') . ' -crop ' . $geometry . ' ' . $this->cmd_image . ' ' . $this->cmd_image)) {
            $this->errors[] = $error;
            return false;
        }
        return true;
    }

    public function flip($dir)
    {
        // Convert the direction into a GM command
        $dir = ($dir === App_Image::HORIZONTAL) ? '-flop' : '-flip';
        if ($error = exec(escapeshellcmd($this->dir . 'gm' . $this->ext . ' convert') . ' ' . $dir . ' ' . $this->cmd_image . ' ' . $this->cmd_image)) {
            $this->errors[] = $error;
            return false;
        }
        return true;
    }

    public function resize($prop)
    {
        switch ($prop['master']) {
            case App_Image::WIDTH: // Wx
                $dim = escapeshellarg($prop['width'] . 'x');
                break;
            case App_Image::HEIGHT: // xH
                $dim = escapeshellarg('x' . $prop['height']);
                break;
            case App_Image::AUTO: // WxH
                $dim = escapeshellarg($prop['width'] . 'x' . $prop['height']);
                break;
            case App_Image::NONE: // WxH!
                $dim = escapeshellarg($prop['width'] . 'x' . $prop['height'] . '!');
                break;
        }
        // Use "convert" to change the width and height
        if ($error = exec(escapeshellcmd($this->dir . 'gm' . $this->ext . ' convert') . ' -resize ' . $dim . ' ' . $this->cmd_image . ' ' . $this->cmd_image)) {
            $this->errors[] = $error;
            return false;
        }
        return true;
    }

    public function rotate($amt)
    {
        if ($error = exec(escapeshellcmd($this->dir . 'gm' . $this->ext . ' convert') . ' -rotate ' . escapeshellarg($amt) . ' -background transparent ' . $this->cmd_image . ' ' . $this->cmd_image)) {
            $this->errors[] = $error;
            return false;
        }
        return true;
    }

    public function sharpen($amount)
    {
        // Set the sigma, radius, and amount. The amount formula allows a nice
        // spread between 1 and 100 without pixelizing the image badly.
        $sigma = 0.5;
        $radius = $sigma * 2;
        $amount = round(($amount / 80) * 3.14, 2);
        // Convert the amount to an GM command
        $sharpen = escapeshellarg($radius . 'x' . $sigma . '+' . $amount . '+0');
        if ($error = exec(escapeshellcmd($this->dir . 'gm' . $this->ext . ' convert') . ' -unsharp ' . $sharpen . ' ' . $this->cmd_image . ' ' . $this->cmd_image)) {
            $this->errors[] = $error;
            return false;
        }
        return true;
    }

    protected function properties()
    {
        return array_slice(getimagesize($this->tmp_image), 0, 2, false);
    }
} // End Image GraphicsMagick Adapter