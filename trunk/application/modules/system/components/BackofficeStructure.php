<?php
/**
* Backoffice navigation
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
class System_Component_BackofficeStructure  {
    public function __construct()
    {
    }
    
        public function getFooterMenu()
    {        
        return array(
            array(
                'module' => 'system',
                'controller' => 'admindashboard',
                'method' => 'index',
                'label'         => __('Главная страница'),
            ),
            array (
                'controller' => 'users',
                'action'        => 'registration',
                'label'         => __('Регистрация'),
            )
            );
    }
    
    public function getTopMenu()
    {        
        return array(
            array(
                'module' => 'system',
                'controller' => 'admindashboard',
                'method' => 'index',
                'label'         => __('Главная страница'),
            ),
            array(
                'controller' => 'users',
                'action' => 'index',
              
                'label'         => __('Пользователи'),
                'pages' => array (
                    array (
                        'controller' => 'users',
                        'action'        => 'new',
                        'label'         => __('Добавить пользователя'),
                    ),
                )
            ),

        );
    }

}