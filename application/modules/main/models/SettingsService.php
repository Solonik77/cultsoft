<?php
class Main_Model_SettingsService {
    private $db;

    public function __construct()
    {

    }

    public function getRoutes()
    {
    }

    public function getSettings($where = array(1 => 1))
    {
        $settings = new Main_Model_DbTable_Settings;
        $select = $settings->select();
        foreach($where as $key => $value) {
            $select->where($key, $value);
        }
        return $settings->fetchAll($select);
    }
}
