<?php

namespace plugins\Snow\Controller;
use Api\Controller\PluginController;

class IndexController extends PluginController{
	
	function index(){
		$this->display(":index");
	}

}
