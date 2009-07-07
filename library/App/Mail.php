<?php
/**
* Application mailer
*
* $Id$
*
* @package Core
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
class App_Mail {
 // Zend_Mail instance
 protected static $mail;
 // Default Zend_Mail transport
 protected static $transport;

 /**
 * Send an email message.
 */
 public static function send($to, $subject, $message, $html = false, $from = null)
 {
  // Connect to Zend_Mail
  (self::$mail === null) and App_Mail::connect();
  try {
if (is_string($to)) {
 App_Mail::$mail->addTo($to);
} else
if (is_array($to) and count($to) == 2) {
 App_Mail::$mail->addTo($to[0], $to[1]);
 $to = $to[0];
}
if ($from === null) {
 $requestLang = App::Front()->getParam('requestLang');
 $from = array(App::config()->project->email , App::config()->project->title->$requestLang);
}
if (is_string($from)) {
 App_Mail::$mail->setFrom($from);
} else
if (is_array($from) and count($from) == 2) {
 App_Mail::$mail->setFrom($from[0], $from[1]);
 $from = $from[0];
}
App_Mail::$mail->setSubject($subject);
($html) ? App_Mail::$mail->setBodyHtml($message) : App_Mail::$mail->setBodyText($message);
App_Mail::$mail->send();
return true;
  }
  catch(Exception $e) {
App::log(__CLASS__ . ' error: Sending email from ' . $from . ' to ' . $to . ' failure. Mail body is: ' . $message . ' Reason: ' . $e->getMessage(),Zend_Log::ERR);
return true;
  }
 }

 /**
 * Connect to mail server with default transport
 */
 protected static function connect()
 {
  try {
App_Mail::$mail = new Zend_Mail('UTF-8');
  }
  catch(Exception $e) {
throw new App_Exception($e->getMessage());
  }
 }

 /**
 * Disconnect
 */
 protected static function disconnect()
 {
  App_Mail::$transport->getConnection()->disconnect();
  unset(App_Mail::$mail);
  App_Mail::$mail = null;
 }

 /**
 * Set default Zend_Mail transport
 */
 public static function setDefaultTransport( array $config = array())
 {
  if (! isset($config['transport'])) {
throw new App_Exception('Default email transport is not set.');
  } else {
$config['transport'] = strtolower($config['transport']);
  }
  switch ($config['transport']) {
case 'smtp':
 $config['port'] = intval($config['port']);
 $config['auth'] = strtolower($config['auth']);
 if (! empty($config['auth']) and ! in_array($config['auth'], array('crammd5' , 'login' , 'plain'))) {
  throw new App_Exception('Wrong auth parameter for smtp connection.');
 }
 App_Mail::$transport = new Zend_Mail_Transport_Smtp($config['host'], $config);
 break;
default:
 App_Mail::$transport = new Zend_Mail_Transport_Sendmail($config);
 break;
  }
  Zend_Mail::setDefaultTransport(  App_Mail::$transport);
 }
}
