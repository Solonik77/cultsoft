<?php

class sitetreeDropdown extends CWidget {
    public $model;
    public $primaryId = 1;
    public $currentElementId;
    private $html;
    private $_treeLevel = 0;

    public function run()
    {
        $root = $this->model->findByPK($this->primaryId);
        $this->html = '';
        if ($root) {
            $nt = $root->getNestedTree();
            $this->html .= '<select name="sitetreeParent" id="sitetreeParent">';
            if ($this->currentElementId) {
                $this->html .= '<option value="no_change">- не изменять позицию -</option>';
            }
            $this->html .= '<option value="0">Главная страница</option>';

            foreach($nt as $key => $val) {
                $this->html .= $this->_generateOptions($val);
            }
            $this->html .= '</select>';
        }
        echo $this->html;
    }

    private function _generateOptions($tree)
    {
        $this->_treeLevel++;
        $result = '';
        $site_modules = Yii::app()->params['site_modules'];

        if (isset($tree['children']) and is_array($tree['children'])) {
            foreach($tree['children'] as $key => $child) {
                if (isset($site_modules[$child['node']->element_type])) {
                    $value = $site_modules[$child['node']->element_type];
                } else {
                    $value = static_pages::model()->with('static_pages_content')->findByPK($child['node']->page_id);
                    $value = $value->static_pages_content[0]->title;
                }

                if ($this->currentElementId != $child['node']->page_id and $child['node']->element_type == 'static_page') {
                    $this->html .= '<option value="' . $child['node']->id . '">' . str_repeat('&nbsp;&rarr;&nbsp;', $this->_treeLevel) . $value . '</option>';

                    if (isset($child['children']) and count($child['children']) > 0) {
                        $this->_generateOptions($child);
                        $this->_treeLevel--;
                    }
                }
            }
        }
    }
}
