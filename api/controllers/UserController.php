<?php
namespace Controllers;

class UserController extends BaseController{
	protected $container;

	public function login($request,$respond,$args){
		$data = $request->getParsedBody();
		$res = $this->find($data['number'],md5($data['password']));
		if($res){
			$_SESSION['user_id'] = $res;
			$result = array();
			$result['status'] = "success";
			$result['message'] = "success";
			return $respond->withJson($result,200);
		}else{
			$result = array();
			$result['status'] = "error";
			$result['errMsg'] = "密码错误或账号还未通过审核";
			return $respond->withJson($result,200);
		}
	}

	public function register($request,$respond,$args){
		$data = $request->getParsedBody();
		$data["password"] = md5($data["password"]);
		$flag = 0;
		$status = $this->add($data,$flag);
		if($flag == 1){
			$result = array();
			$result['status'] = "error";
			$result['errMsg'] = "该工号已经被注册，请联系管理员";
			return $respond->withJson($result,200);
		}
		if($status){
			// $_SESSION['user_id'] = $status;
			$result = array();
			$result['status'] = "success";
			return $respond->withJson($result,200);
		}else{
			$result = array();
			$result['status'] = "error";
			$result['errMsg'] = "注册失败，请重试";
			return $respond->withJson($result,200);
		}
	}

	private function find($number,$password){
		$data = $this->container['coffee']->select("users",[
			"id"
		],[
			"number[=]"=>$number,
			"password[=]"=>$password,
			"status[>=]"=>1
		]);
		if($data){
			return $data[0]['id'];
		}else{
			return false;
		}
	}

	private function find_by_number($number){
		$data = $this->container['coffee']->select("users",[
			"id"
		],[
			"number[=]"=>$number
		]);
		if($data){
			return $data[0]['id'];
		}else{
			return false;
		}
	}

	private function add($data,&$flag){
		if($this->find_by_number($data['number'])){
			$flag = 1;
		}
		$result = $this->container['coffee']->insert('users',$data);
		if($result){
			$id = $this->container['coffee']->id();   //获取id
            return $id;
		}else{
			return false;
		}
	}
}