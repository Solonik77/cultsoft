<?php
class Breadcrumbs extends CWidget {
 
    public $crumbs = array();
    public $delimiter = ' / ';
 
    public function run() {
        $this->render('breadcrumbs');
    }
 
}