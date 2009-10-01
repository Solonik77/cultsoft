<?php
/**
 * Data Collection
 *
 * @author Denysenko Dmytro
 */
abstract class App_Collection_Abstract implements Countable, Iterator, SeekableIterator, ArrayAccess
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