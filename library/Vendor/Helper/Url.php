<?php
/**
 * URL Generator
 * @author Denysenko Dmytro
 */
 
namespace Vendor\Helper
{
class Url {
 public static function base($index = false)
 {
    return \App::baseUri();
 }
}
}