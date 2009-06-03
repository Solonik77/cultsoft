<?php

class App_View_Helper_HeadLink extends Zend_View_Helper_HeadLink {

	public function toString() {

		$stylesheets = array();
		$stylesheets = array();
		foreach ($this as $stylesheet) {
			if ($stylesheet->type == 'text/css' && $stylesheet->conditionalStylesheet === false) {
				$stylesheets[$stylesheet->media][] = $stylesheet->href;
			} else {
				$stylesheets[] = $this->stylesheetToString($stylesheet);
			}
		} 

		foreach ($stylesheets as $media => $styles) {
			$stylesheet = new stdClass();
			$stylesheet->rel = 'stylesheet';
			$stylesheet->type = 'text/css';
			$stylesheet->href = $this->getMinUrl() . '?f=' . implode(',', str_replace(App::baseUri(), '/' , $styles));
			$stylesheet->media = $media;
			$stylesheet->conditionalStylesheet = false;             
			$items[] = $this->itemToString($stylesheet);
		}
		return implode("\n", $items);
	}
   

    
    /*
     Getting Minify URL
    */
	public function getMinUrl() {
		return App::baseUri () . 'static/system/min/';
	}
}