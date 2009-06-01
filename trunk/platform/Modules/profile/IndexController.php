<?php
/**
* Profile controller. This controller used for authorization and user profile information pages.
*
* @package Core
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/
class Profile_IndexController extends App_Controller_Action {
    /**
    * Profile action: index
    * View member information
    */
    public function indexAction()
    {
    }

    /**
    * Profile action: signin
    */
    public function signinAction()
    {
        if (App::isAuth ()) {
            $this->view->form = 'You are already logged.';
        } else {
            $form = new Site_Form_Login ();
            if ($this->getRequest ()->isPost ()) {
                $formData = $this->_request->getPost ();
                $form->populate ($formData);
                if (! $form->isValid ($_POST)) {
                    $this->view->form = $form;
                    return $this->render ();
                } else {
                    $authAdapter = $this->_getAuthAdapter ($formData ['member_login'], $formData ['member_password']);
                    $result = App::Auth ()->authenticate ($authAdapter);
                    if (! $result->isValid ()) {
                        $form->addDecorator ('Description', array ('escape' => true, 'placement' => 'prepend'));
                        $form->setDescription ('Wrong login or password');
                        $this->view->form = $form;
                    } else {
                        App::Auth ()->getStorage ()->write ($authAdapter->getResultRowObject (array ('id', 'login', 'email')));
                        if (isset ($formData ['remember_me'])) {
                            Zend_Session::rememberMe (3600 * 24 * 14);
                        }
                        $this->_redirect ('admin');
                    }
                }
            } else {
                $this->view->form = $form;
            }
        }
    }
    /**
    * Logout action
    */
    public function logoutAction()
    {
        $this->_helper->viewRenderer->setNoRender (true);
        Zend_Layout::getMvcInstance ()->disableLayout ();
        $auth = Zend_Auth::getInstance ();
        $auth->clearIdentity ();
        Zend_Session::forgetMe ();
        $this->_redirect ('/');
    }
    /**
    * Getting Auth Adapter
    *
    * @return Zend_Auth_Adapter_DbTable object
    */
    protected function _getAuthAdapter($login, $password)
    {
        $authAdapter = new Zend_Auth_Adapter_DbTable (App::DB ());
        $authAdapter->setTableName ('members')->setIdentityColumn ('login')->setCredentialColumn ('password')->setIdentity ($login)->setCredential ($password);

        return $authAdapter;
    }
}
