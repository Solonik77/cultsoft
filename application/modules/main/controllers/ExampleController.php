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
         $rowset = $table->findAllByCondition(array('is_active = ?' => 1));

         $collection = $table->createCollection(); // creates a rowset collection with zero rows
         $collectionItem = $table->createCollectionItem(); // creates one row with unset values
         $collectionItem->setName("First item");
         $collection->addItem($collectionItem); // adds one row to the rowset
         $collection->save(); // iterates over the set of rows, calling save() on each row

         $rowset = App_Db_Table::collectionFactory('Main_Model_DbTable_Site_Modules');
         $row = $rowset->createItem();
         $row->setName("Second item");
         $rowset->addRow($row);
         $rowset->save();
    }
}