<?php
/**
 * Download helper class.
 *
 * $Id: download.php 3769 2008-12-15 00:48:56Z zombor $
 *
 * @author Kohana Team
 * @copyright (c) 2007-2008 Kohana Team
 * @license http://kohanaphp.com/license.html*
 * @author Denysenko Dmytro
 */
class Vendor_Helper_Download {
    /**
     * Force a download of a file to the user's browser. This function is
     * binary-safe and will work with any MIME type that Kohana is aware of.
     *
     * @param string $ a file path or file name
     * @param mixed $ data to be sent if the filename does not exist
     * @param string $ suggested filename to display in the download
     * @return void
     */
    public static function force($filename = null, $data = null, $nicename = null)
    {
        if (empty($filename))
        return false;
        if (is_file($filename)) {
            // Get the real path
            $filepath = str_replace('\\', '/', realpath($filename));
            // Set filesize
            $filesize = filesize($filepath);
            // Get filename
            $filename = substr(strrchr('/' . $filepath, '/'), 1);
            // Get extension
            $extension = strtolower(substr(strrchr($filepath, '.'), 1));
        } else {
            // Get filesize
            $filesize = strlen($data);
            // Make sure the filename does not have directory info
            $filename = substr(strrchr('/' . $filename, '/'), 1);
            // Get extension
            $extension = strtolower(substr(strrchr($filename, '.'), 1));
        }
        // Get the mime type of the file
        $mime = App::config('mimes.' . $extension);
        if (empty($mime)) {
            // Set a default mime if none was found
            $mime = array('application/octet-stream');
        }
        // Generate the server headers
        header('Content-Type: ' . $mime[0]);
        header('Content-Disposition: attachment; filename="' . (empty($nicename) ? $filename : $nicename) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . sprintf('%d', $filesize));
        // More caching prevention
        header('Expires: 0');
        if (App_Exception::user_agent('browser') === 'Internet Explorer') {
            // Send IE headers
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        } else {
            // Send normal headers
            header('Pragma: no-cache');
        }
        ob_end_clean();
        if (isset($filepath)) {
            // Open the file
            $handle = fopen($filepath, 'rb');
            // Send the file data
            fpassthru($handle);
            // Close the file
            fclose($handle);
        } else {
            // Send the file data
            echo $data;
        }
    }
} // End download
