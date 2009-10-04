<?php
class Main_Model_Settings {
    private $db;

    public function __construct()
    {
    }

    public function getRoutes()
    {
    }

    public function getSettings($where = '1 = 1')
    {
        $settings = new Main_Model_DbTable_Settings;
        $select = $settings->select();
        if($where) {
            $select->where($where);
        }
        return $settings->fetchAll($select)->getCollection();
    }
}