<?php
/**
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license
 *
 * @category  Custom
 * @package  Custom_Db
 * @subpackage Nestedset
 * @copyright Copyright (c) 2008 Ivan Iordanov <ivan@iordanov.net>
 * @version  $Id: Nestedset.php 2008-06-06
 * @see    http://dev.mysql.com/tech-resources/articles/hierarchical-data.html
 */
/**
 * @see Zend_Db_Table
 */
require_once 'Zend/Db/Table.php';
/**
 * Class for SQL Nested set interface.
 *
 * @category  Custom
 * @package  Custom_Db
 * @subpackage Nestedset
 * @copyright Copyright (c) 2008 Ivan Iordanov (http://dev.iordanov.net)
 */
class V_Db_Nestedsets extends Zend_Db_Table
{
    /**
     * left column in nested table
     *
     * @var String
     */
    protected $_left;
    /**
     * right column in nested table
     *
     * @var String
     */
    protected $_right;
    /**
     * Column to be retrieved with getTree method
     * If not set primary key will be used.
     *
     * @var String
     */
    protected $_toString;
    /**
     * Additional data to be inserted.
     *
     * @var Array
     */
    private $_insertData = array();

    /**
     * constructor
     */
    public function __construct ()
    {
        parent::__construct();
        if (! $this->_toString) {
            $this->_toString = $this->_primary[1];
        }
    }

    /**
     * Additional data to be inserted
     *
     * @param array
     * @access public
     */
    public function setInsertData (array $data)
    {
        $this->_insertData = $data;
    }

    /**
     * Retrieve whole tree
     *
     * @access public
     * @return array
     */
    public function getTree ()
    {
        //todo: add custom node
        $ret = $this->_db->query("
            SELECT COUNT(parent.{$this->_primary[1]}) - 1 as depth, node.{$this->_toString}
            FROM {$this->_name} AS node, {$this->_name} AS parent
            WHERE node.{$this->_left}
            BETWEEN parent.{$this->_left}
            AND parent.{$this->_right}
            GROUP BY node.{$this->_primary[1]}
            ORDER BY node.{$this->_left}
        ");
        return $ret->fetchAll();
    }

    /**
     * Insert node as first child
     *
     * @param int
     * @access public
     * @return int
     */
    public function insertAsFirstChildOf ($id)
    {
        $row = $this->retrieveData($id);
        $right = (int) $row->{$this->_right};
        $left = (int) $row->{$this->_left};
        $this->_db->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} + 2 WHERE {$this->_right} > {$left}");
        $this->_db->query("UPDATE {$this->_name} SET {$this->_left} = {$this->_left} + 2 WHERE {$this->_left} > {$left}");
        $data = array($this->_left => $left + 1 , $this->_right => $left + 2);
        $this->_insertData = array_merge($this->_insertData, $data);
        return $this->insert($this->_insertData);
    }

    /**
     * Insert node as last child
     *
     * @param int
     * @access public
     * @return int
     */
    public function insertAsLastChildOf ($id)
    {
        $row = $this->retrieveData($id);
        $right = (int) $row->{$this->_right};
        $left = (int) $row->{$this->_left};
        $this->_db->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} + 2 WHERE {$this->_right} >= {$right}");
        $this->_db->query("UPDATE {$this->_name} SET {$this->_left} = {$this->_left} + 2 WHERE {$this->_left} > {$right}");
        $data = array($this->_left => $right , $this->_right => $right + 1);
        $this->_insertData = array_merge($this->_insertData, $data);
        return $this->insert($this->_insertData);
    }

    /**
     * Insert node as next sibling of given node
     *
     * @param int
     * @access public
     * @return int
     * @throws Exception
     */
    public function insertAsNextSiblingOf ($id)
    {
        $row = $this->retrieveData($id);
        $right = (int) $row->{$this->_right};
        $left = (int) $row->{$this->_left};
        if ($left === 1) {
            throw new Exception("Root node can't have siblings");
        }
        $this->_db->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} + 2 WHERE {$this->_right} > {$right}");
        $this->_db->query("UPDATE {$this->_name} SET {$this->_left} = {$this->_left} + 2 WHERE {$this->_left} > {$right}");
        $data = array($this->_left => $right + 1 , $this->_right => $right + 2);
        $this->_insertData = array_merge($this->_insertData, $data);
        return $this->insert($this->_insertData);
    }

    /**
     * Insert node as prev sibling of given node
     *
     * @param int
     * @access public
     * @return int
     * @throws Exception
     */
    public function insertAsPrevSiblingOf ($id)
    {
        $row = $this->retrieveData($id);
        $right = (int) $row->{$this->_right};
        $left = (int) $row->{$this->_left};
        if ($left === 1) {
            throw new Exception("Root node can't have siblings");
        }
        $this->_db->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} + 2 WHERE {$this->_right} > {$left}");
        $this->_db->query("UPDATE {$this->_name} SET {$this->_left} = {$this->_left} + 2 WHERE {$this->_left} >= {$left}");
        $data = array($this->_left => $left , $this->_right => $left + 1);
        $this->_insertData = array_merge($this->_insertData, $data);
        return $this->insert($this->_insertData);
    }

    /**
     * Delete node with it's child(s) and return affected rows
     *
     * @param int
     * @access public
     * @return int
     */
    public function deleteNode ($id)
    {
        $row = $this->retrieveData($id);
        $right = (int) $row->{$this->_right};
        $left = (int) $row->{$this->_left};
        $width = $right - $left + 1;
        $res = $this->_db->query("DELETE FROM {$this->_name} WHERE {$this->_left} BETWEEN {$left} AND {$right}");
        $this->_db->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} - {$width} WHERE {$this->_right} > {$right}");
        $this->_db->query("UPDATE {$this->_name} SET {$this->_left} = {$this->_left} - {$width} WHERE {$this->_left} > {$right}");
        return $res->rowCount();
    }

    /**
     * Insert root node
     *
     * @access public
     * @return int
     */
    public function createRoot ()
    {
        $data = array($this->_left => 1 , $this->_right => 2);
        $this->_insertData = array_merge($this->_insertData, $data);
        return $this->insert($this->_insertData);
    }

    /**
     * Insert node
     *
     * @param int
     * @access private
     * @return Zend_Db_Row
     */
    private function retrieveData ($id)
    {
        $select = $this->select()->where($this->_primary[1] . ' = ?', $id);
        return $this->fetchRow($select);
    }

    /*
   *  Get subtree by id
   */
    public function getSubTree ($id)
    {
        $ret = $this->_db->query("
SELECT node.{$this->_primary}, node.{$this->_toString},
(
COUNT(parent.{$this->_toString}) - (sub_tree.depth + 1)
) AS depth
FROM
`{$this->_name}` AS node,
`{$this->_name}` AS parent,
`{$this->_name}` AS sub_parent,
(
SELECT node.{$this->_primary}, (COUNT(parent.{$this->_toString}) - 1) AS depth
FROM `{$this->_name}` AS node, `{$this->_name}` AS parent
WHERE node.{$this->_left} BETWEEN parent.{$this->_left} AND parent.{$this->_right}
AND node.{$this->_primary} = $id
GROUP BY node.{$this->_primary}
ORDER BY node.{$this->_left}
) AS sub_tree
WHERE node.{$this->_left} BETWEEN parent.{$this->_left} AND parent.{$this->_right}
AND node.{$this->_left} BETWEEN sub_parent.{$this->_left} AND sub_parent.{$this->_right}
AND sub_parent.{$this->_primary} = sub_tree.{$this->_primary}
GROUP BY node.{$this->_primary}
ORDER BY node.{$this->_left}
");
        return $ret->fetchAll();
    }
}