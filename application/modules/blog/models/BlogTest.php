<?php
/**
 * Blog Collection
 */
class Blog_Model_BlogTest extends App_Collection_Db
{

    public function __construct()
    {
        parent::__construct(new  Blog_DbTable_Blog);
    }
}