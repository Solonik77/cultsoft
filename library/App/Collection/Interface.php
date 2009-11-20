<?php
/**
 * Collections interface
 *
 * @author Denysenko Dmytro
 * @category Engine
 */
interface App_Collection_Interface
{

    public function __construct(array $config);

    public function getCollection();

    public function getItems();

    public function getFirstItem();

    public function getLastItem();

    public function addItem($collectionItem);

    public function removeItemByKey($position);

    public function createItem(array $data = array());

    public function walk($callback, array $args = array());

    public function each($obj_method, $args = array());

    public function clear();
}