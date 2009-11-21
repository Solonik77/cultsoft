<?php
/**
 * Examples controller.
 *
 * @author Denysenko Dmytro
 */

class Main_ExampleController extends App_Controller_Action {

    public function indexAction()
    {
         /*
         *  Create collections and records
         */
         $table = new Main_Model_DbTable_Site_Modules();
         // Table class
         $collection = $table->createCollection(); // creates a rowset collection with zero rows
         $collectionItem = $table->createCollectionItem(); // creates one row with unset values
         $collectionItem->setIsActive(1);
         $collectionItem->setName("First item");
         $collection->addItem($collectionItem); // adds one row to the rowset
         $collection->save(); // iterates over the set of rows, calling save() on each row

         // Factory method
         $rowset = App_Db_Table::collectionFactory('Main_Model_DbTable_Site_Modules');
         // rowset can create new empty row
         $row = $rowset->createItem();
         $row->setIsActive(1);
         $row->setName("Second item");
         $rowset->addRow($row);
         $rowset->save();
         
         $rowset = $table->findAllByCondition(array('is_active = ?' => 1));
         Zend_Debug::dump($rowset->getData());
         exit;
    }
}