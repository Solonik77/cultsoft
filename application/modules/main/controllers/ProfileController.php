<?php
/**
 * Profile controller. This controller used for authorization and user profile information pages.
 *
 * @author Denysenko Dmytro
 */
class Main_ProfileController extends App_Controller_Action
{

    /**
     * Profile action: index
     * View member information
     */
    public function indexAction()
    {
    }

    /**
     * View member profile
     */
    public function viewAction()
    {
    }

    /**
     * Profile action: signin
     */
    public function signinAction()
    {
        if(App_Member::isAuth()){
            $this->view->form = __('You are already logged.');
        }
        else{
            $form = new Main_Form_Signin();
            if($this->getRequest()->isPost()){
                $formData = $this->_request->getPost();
                $form->populate($formData);
                if(! $form->isValid($_POST)){
                    $this->view->form = $form;
                    return $this->render();
                }
                else{
                    $memberPassword = md5(md5($formData['member_password']));
                    $authAdapter = $this->_getAuthAdapter($formData['member_email'], $memberPassword);
                    $result = App_Member::getAuth()->authenticate($authAdapter);
                    if(! $result->isValid()){
                        $form->addDecorator('Description', array('escape' => true , 'placement' => 'prepend'));
                        $form->setDescription('Wrong email or password');
                        $this->view->form = $form;
                    }
                    else{
                        App_Member::getAuth()->getStorage()->write($authAdapter->getResultRowObject(array('id' , 'role_id' , 'email')));
                        if(isset($formData['remember_me'])){
                            Zend_Session::rememberMe(3600 * 24 * 14);
                        }
                        $this->_redirect('admin');
                    }
                }
            }
            else{
                $this->view->form = $form;
            }
        }
    }

    /**
     * Logout action
     */
    public function logoutAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        Zend_Layout::getMvcInstance()->disableLayout();
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        Zend_Session::forgetMe();
        $this->_redirect('/');
    }

    /**
     * Getting Auth Adapter
     *
     * @return Zend_Auth_Adapter_DbTable object
     */
    protected function _getAuthAdapter($email, $password)
    {
        $authAdapter = new Zend_Auth_Adapter_DbTable(App::db(), DB_TABLE_PREFIX . 'members', 'email', 'password', '?');
        $authAdapter->setIdentity($email)->setCredential($password);
        return $authAdapter;
    }
}