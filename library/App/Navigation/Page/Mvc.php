<?
class App_Navigation_Page_Mvc extends Zend_Navigation_Page_Mvc
{
    public function isActive($recursive = false)
    {
        if (!$this->_active) {
            $front = Zend_Controller_Front::getInstance();
			$router = $front->getRouter();
			
			$request = $router->route($front->getRequest());
            $reqParams = $request->getParams();

            if (!array_key_exists('module', $reqParams)) {
                $reqParams['module'] = $front->getDefaultModule();
            }

            $myParams = $this->_params;
			
			$route = $router->getRoute($this->_route);
            $routeDefaults = $route->getDefaults();

            if (null !== $this->_module) {
                $myParams['module'] = $this->_module;
            } else {
                $myParams['module'] = $routeDefaults['module'];
            }

            if (null !== $this->_controller) {
                $myParams['controller'] = $this->_controller;
            } else {
                $myParams['controller'] = $routeDefaults['controller'];
            }

            if (null !== $this->_action) {
                $myParams['action'] = $this->_action;
            } else {
                $myParams['action'] = $routeDefaults['action'];
            }

            if (count(array_intersect_assoc($reqParams, $myParams)) ==
                count($myParams)) {
                $this->_active = true;
                return true;
            }
        }

        return parent::isActive($recursive);
    }
}