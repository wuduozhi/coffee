<?php
namespace Controllers;
use \PDO;
class PurchaseController extends BaseController{
	protected $container;

	public function __construct(\Slim\Container $container){
    	//依赖注入  若重写构造函数，同样需要注入
      $this->container = $container;
	    // $this->oracle = Common\DBCommon::getDB();
      date_default_timezone_set('Asia/Shanghai');

      if(!isset($_SESSION['user_id'])){
      	 $res['status'] = 'error';
      	 $res['errCode'] = "001";
		 $res['errMsg'] = 'please login';
		 echo json_encode($res);
		 exit();
      }
    }

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
		$purchase["USER_ID"] = $_SESSION['user_id'];
		unset($purchase['IMAGE_IDS']);
		$purchase_id = $this->addPurchase($purchase);
		
		if($purchase_id == false){
			$res['status'] = 'error';
			$res['errMsg'] = 'add purchase error';
			return $respond->withJson($res,200);
		}

		if( isset($data['IMAGE_IDS']) && is_array($data['IMAGE_IDS'])){
			$ids = $data['IMAGE_IDS'];
			$num = count($ids);
			for($i=0;$i<$num;$i++){
				$image_id = $ids[$i];
		        $this->purchase_image($purchase_id,$image_id);
	        }
		}

		$res['status'] = 'success';
		$res['purchase_id'] = $purchase_id;
		return $respond->withJson($res,200);

	}

	public function get($request,$respond,$args){
		$id = intval($_GET['id']);
		$purchase = $this->getPurchase($id);
		$res = array();
		if(!$purchase){
			$res['status'] = 'error';
			$res['errMsg'] = 'id error';
			return $respond->withJson($res,200);
		}

		$res['status'] = 'success';
		$res['purchase'] = $purchase;
		return $respond->withJson($res,200);
	}

	private function getPurchase($id){
		$sql = 'SELECT
			T_PURCHASE_BAK.*, 
			GROUP_CONCAT(IMAGE.NAME) AS IMAGES,
			COUNT(DISTINCT(IMAGE.NAME)) AS IMAGE_NUM
		FROM
			T_PURCHASE_BAK
		LEFT JOIN IMAGE ON IMAGE.ID IN (
			SELECT
				IMAGE_ID
			FROM
				PURCHASE_IMAGE
			WHERE
				PURCHASE_ID = :id
		)
		GROUP BY
			T_PURCHASE_BAK.PURCHASE_ID,
			T_PURCHASE_BAK.`NAME`,
			T_PURCHASE_BAK.NATURE,
			T_PURCHASE_BAK.`DESCRIBE`,
			T_PURCHASE_BAK.PURCHASE_TIME,
			T_PURCHASE_BAK.TOTAL_NUM,
			T_PURCHASE_BAK.TOTAL_PRICE
		HAVING T_PURCHASE_BAK.FLOWID = :id';

		$sth = $this->container['coffee']->pdo->prepare($sql);
		$sth->bindParam(":id",$id,PDO::PARAM_INT);
		$sth->execute();	
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			return false;
		}
		$result = $result[0];
		if($result['IMAGE_NUM'] != 0){
			$result['IMAGES'] = explode(',',$result['IMAGES']);
		}

		return $result;
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
          "IMAGE_ID" => $image_id,
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