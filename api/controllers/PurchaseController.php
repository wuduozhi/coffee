<?php
namespace Controllers;

class PurchaseController extends BaseController{
	protected $container;

	public function add($request,$respond,$args){
		$data = $request->getParsedBody();
		$res = array();
		if(empty($data['NATURE']) || empty($data['NAME']) || empty($data['TOTAL_PRICE']) || empty($data['TOTAL_NUM'])){
			$res['status'] = 'error';
			$res['errMsg'] = 'some value is empty';
			return $respond->withJson($res,200);
		}
		# 生成采购id
		$purchase_id = date('Y-m-d-h-i-s').'-'.rand(0,10);
		$data['PURCHASE_ID'] = $purchase_id;
		$data['TIME'] = time();
		
		$purchase = $data;
		unset($purchase['IMAGE_IDS']);
		$purchase_id = $this->addPurchase($purchase);
		
		if($purchase_id == false){
			$res['status'] = 'error';
			$res['errMsg'] = 'add purchase error';
			return $respond->withJson($res,200);
		}

		if(is_array($data['IMAGE_IDS'])){
			$ids = $data['IMAGE_IDS'];
			$num = count($ids);
			for($i=0;$i<$count;$i++){
				$image_id = $ids[$i];
		        $this->purchase_image($purchase_id,$image_id);
	        }
		}

		$res['status'] = 'success';
		$res['purchase_id'] = $purchase_id;
		return $respond->withJson($res,200);

	}

	private function addPurchase($data){
		$result = $this->container['coffee']->insert('T_PURCHASE_BAK',$data);
		if($result){
			$id = $this->container['coffee']->id();   //获取id
            return $id;
		}else{
			return false;
		}
	}

	private function purchase_image($purchase_id,$image_id){
		$result = $this->container['coffee']->insert('PURCHASE_IMAGE', [
          "NAME_ID" => $image_id,
          "PURCHASE_ID"=> $purchase_id
         ]);

	    if ($result) {
            $id = $this->container['coffee']->id();   //获取id
            return $id;
        } else {
           return false;
        }
	}
}