<?php

class App_Form_Element extends Zend_Form_Element
/**
 * Constructor
 *
 * $spec may be:
 * - string: name of element
 * - array: options with which to configure element
 * - Zend_Config: Zend_Config with options for configuring element
 *
 * @param string $ |array|Zend_Config $spec
 * @return void
 * @throws Zend_Form_Exception if no element name after initialization
 */
public function __construct($spec, $options = null)
{
    parent::__construct($spec, $options);
}

/**
 * Filter a name to only allow valid variable characters
 *
 * @param string $value
 * @param bool $allowBrackets
 * @return string
 */
public function filterName($value, $allowBrackets = true)
{
    return parent::filterName($value, $allowBrackets);
}
}