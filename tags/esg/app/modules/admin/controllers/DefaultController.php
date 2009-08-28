<?php

class DefaultController extends ESGController {
    public $siteTree;
    public function actionIndex()
    {
        $this->setPageTitle('Панель управления');
        $root = Sitetree::model()->findByPK(1);
        if ($root) {
            $nt = $root->getNestedTree();
            foreach($nt as $key => $val) {
                $this->siteTree = $this->_generateViewTree($val);
            }
        }
        $this->render('index');
    }

    private function _generateViewTree($tree)
    {
        $result = array();
        if (isset($tree['children']) and is_array($tree['children'])) {
            foreach($tree['children'] as $key => $child) {
                if ($child['node']->element_type == 'static_page') {
                    $static_page = Static_pages::model()->with('static_pages_content')->findByPK($child['node']->page_id);
                    if ($static_page) {
                        $static_page = $static_page->static_pages_content[0]->title;
                        $result[] = array('text' => '<img width="16" height="16" src="/images/admin/module-icon-static-page.png" /> <a href="/admin/managestaticpages/update/id/' . $child['node']->page_id . '" title="Перейти к редактированию элемента">'
                             . $static_page . '</a>',
                            'expanded' => true, 'hasChildren' => ((count($child) > 1) ? true : false) , 'children' =>
                            ((count($child) > 1) ? $this->_generateViewTree($child) : false)
                            );
                    }
                } else {
                    $site_modules = Yii::app()->params['site_modules'];
                    if (isset($site_modules[$child['node']->element_type])) {
                        $result[] = array('text' => '<img width="16" height="16" src="/images/admin/module-icon-' . $child['node']->element_type . '.png" /> <a href="/admin/Manage' . ucfirst(strtolower($child['node']->element_type)) . '" title="Перейти к управлением модулем ' . $site_modules[$child['node']->element_type] . '">'
                             . $site_modules[$child['node']->element_type] . '</a>', 'hasChildren' => false ,
                            );
                    }
                }
            }
        }
        return $result;
    }

    public function rules()
    {
    }

    public function filters()
    {
        return array('accessControl');
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'roles' => array('admin'),
                ),
            array('deny',
                'users' => array('*'),
                ),
            );
    }
}
