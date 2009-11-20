<?php
/**
 * Examples controller.
 *
 * @author Denysenko Dmytro
 */

class Main_ExampleController extends App_Controller_Action {

    public function indexAction()
    {
         $table = new Main_Model_DbTable_Site_Modules();
         var_dump($table->findAllByCondition(array('is_active = ?' => 1)));
         $collection = $table->createCollection(); // creates a rowset collection with zero rows
         $row = $table->createCollectionItem(); // creates one row with unset values
         $row->setName(Vendor_Helper_Date::now());
         $collection->addItem($row); // adds one row to the rowset
         $collection->save(); // iterates over the set of rows, calling save() on each row

         $i18nCollection = App_Db_Table::collectionFactory('Main_Model_DbTable_Site_Modules');
         $row = $i18nCollection->createItem();
         $i18nCollection->addItem($row);
         $i18nCollection->save();
    }
}