<?php
/**
 * URL Generator
 * @author Denysenko Dmytro
 */


class Vendor_Helper_Url {
    public static function base($index = false)
    {
        return App::baseUri();
    }
}