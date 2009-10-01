<?php
/**
 * Data Collection
 *
 * @author Denysenko Dmytro
 */
abstract class App_Data_Collection_Abstract implements Iterator, ArrayAccess, Countable
{
    const SORT_ORDER_ASC    = 'ASC';
    const SORT_ORDER_DESC   = 'DESC';
        /**
     * Collection items
     *
     * @var array
     */
    protected $_items = array();

}