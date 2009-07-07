<?php
/**
* Remote url/file helper.
*
* 
$Id: remote.php 3769 2008-12-15 00:48:56Z zombor 
$
*
* @package Core
* @author Kohana Team
* @copyright (c) 2007-2008 Kohana Team
* @license http://kohanaphp.com/license.html
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
class V_Helper_Remote {
    public static function status(
$url)
    {
        if (! V_Helper_Valid::url(
$url, 'http'))
            return false;
        // Get the hostname and path
        
$url = parse_url(
$url);
        if (empty(
$url['path'])) {
            // Request the root document
            
$url['path'] = '/';
        }
        // Open a remote connection
        
$remote = fsockopen(
$url['host'], 80, 
$errno, 
$errstr, 5);
        if (! is_resource(
$remote))
            return false;
        // Set CRLF
        
$CRLF = "\r\n";
        // Send request
        fwrite(
$remote,           'HEAD ' . 
$url['path'] . ' HTTP/1.0' . 
$CRLF);
        fwrite(
$remote,           'Host: ' . 
$url['host'] . 
$CRLF);
        fwrite(
$remote,           'Connection: close' . 
$CRLF);
        fwrite(
$remote,           'User-Agent: Zend Framework' . 
$CRLF);
        // Send one more CRLF to terminate the headers
        fwrite(
$remote, 
$CRLF);
        while (! feof(
$remote)) {
            // Get the line
            
$line = trim(               fgets(
$remote, 512));
            if (
$line !== '' and preg_match('#^HTTP/1\.[01] (\d{3})#',                   
$line, 
$matches)) {
                // Response code found
                
$response = (int) 
$matches[1];
                break;
            }
        }
        // Close the connection
        fclose(
$remote);
        return isset(
$response) ? 
$response : false;
    }
} // End remote
