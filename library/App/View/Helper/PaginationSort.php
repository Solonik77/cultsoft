<?php
/**
* Pagination sort links view helper
*
*/
class App_View_Helper_PaginationSort
{
    private $_label;
    private $_sortBy;
    private $_view;
    private $_request;
    public function paginationSort($label = NULL, $sortBy = NULL)
    {
        if($label != NULL) {
        $this->_request = App::front()->getRequest();
        $this->_view = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;

        

        if($sortBy === NULL)
        {
           $sortBy = str_replace(' ', '_', strtolower($label));
        }
        
        $this->_label = __($label);
        $this->_sortOrder =  strtolower((string) $this->_request->getParam('sort-order', 'asc'));
        $this->_sortOrder = ($this->_sortOrder == 'asc') ? $this->_sortOrder : 'desc';
        $this->_sortBy = (string) $sortBy;        
        }
       return $this;
     
    }
    
    public function __toString()
    {    
        $link = '<a href="';
        $link .= $this->_view->url(array(
        'sort-order' => ( ($this->_sortBy == $this->_request->getParam('sort-by')) ?  $this->_sortOrder : 'asc'),
        'sort-by' => $this->_sortBy )) . '" ';
        $link .= ' class="';
        if(($this->_sortBy === $this->_request->getParam('sort-by')) ? 'sorted-' : ''){
        $link .= 'sorted-';
         $link .= ($this->_sortOrder == 'asc') ?  'desc' : 'asc';        
        }
        $link .= '">';
        $link .= $this->_label . '</a>';
        
        return $link;
    }
}
