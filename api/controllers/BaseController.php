<?php
namespace Controllers;
use Common;

class BaseController{
  protected $container;
	protected $oracle;

	public function __construct(\Slim\Container $container){
    	//依赖注入  若重写构造函数，同样需要注入
      $this->container = $container;
	    // $this->oracle = Common\DBCommon::getDB();
      date_default_timezone_set('Asia/Shanghai');
    }

	public function index($request,$respond,$args) {
      echo 'hello world';
	}


}
