<?php
/**
* Pagination sort links view helper
*/
class App_View_Helper_PaginationSort {
    private $_label;
    private $_sortBy;
    private $_view;
    private $_request;
    public function paginationSort($label = null, $sortBy = null)
    {
        if ($label != null) {
            $this->_request = App::front()->getRequest();
            $this->_view = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;

            if ($sortBy === null) {
                $sortBy = str_replace(' ', '_', strtolower($label));
            }

            $this->_label = __($label);
            $this->_sortOrder = strtolower((string) $this->_request->getParam('sort-order', 'asc'));
            $this->_sortOrder = ($this->_sortOrder == 'asc') ? $this->_sortOrder : 'desc';
            $this->_sortBy = (string) $sortBy;
        }
        return $this;
    }

    public function __toString()
    {
        $link = '<a href="';
        $array = array('sort-by' => $this->_sortBy);
        if (($this->_sortBy === $this->_request->getParam('sort-by'))) {
            $array['sort-order'] = (($this->_request->getParam('sort-order') == 'desc') ? 'asc' : 'desc');
        } else {
            $array['sort-order'] = 'asc';
        }

        $link .= $this->_view->url($array) . '" ';
        $link .= ' class="';
        if (($this->_sortBy === $this->_request->getParam('sort-by'))) {
            $link .= 'sorted-';
            $link .= ($this->_sortOrder == 'asc') ? 'asc' : 'desc';
        }
        $link .= '">';
        $link .= $this->_label . '</a>';

        return $link;
    }
}
