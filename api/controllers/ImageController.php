<?php
namespace Controllers;

class ImageController extends BaseController{
	protected $container;

	//上传图片需要的选项
    private $options = array(
        'extension' => array('gif', 'jpeg', 'jpg', 'png', 'svg', 'blob'),
        'types' => array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png', 'image/svg+xml')
    );

    private $path = './upload/';

    public function file($request,$respond,$args){
        $files = $request->getUploadedFiles();
        $images = $files['image'];
        $count = count($images);
        $ids  = array();
        for($i = 0;$i<$count;$i++){
           $name = $this->uploadFile($images[$i]);
           // $name = $this->uploadFileWithOperation($images[$i]);
           if($name){
              $ids[] = $this->addImage($name);
           }
        }
        $res = array();
        $res['status'] = 'success';
        if(count($ids) == 0){
        	$res['status'] = 'error';
        	$res['errMsg'] = 'file type error or other error';
        }
        $res['id'] = $ids;
        return $respond->withJson($res,200);
    }



    /**
    * Upload a file to the specified location.
    *
    * @param file object(Slim\Http\UploadedFile)
    *
    * @return array filenames
    */
    private function uploadFile($file){
		if(!is_dir($this->path)){
			mkdir($this->path,0777,true);
		}

        $type = $file->getClientMediaType();
        if(!in_array($type, $this->options['types'])){
            return null;
        }

        $name = $file->getClientFilename();
        $exten = explode('.', $name);
        $extension = end($exten);
        if(!in_array($extension, $this->options['extension'])){
            return null;
        }

        $random = (string)(rand(1,100)).(string)(rand(1,100));
        $fullname = time().$random.'.'.$extension;
        $targetPath = $this->path.$fullname;
        try{
           $file->moveTo($targetPath);
        }catch(Exception $e){
           return null;
        }

        return $fullname;
    }

    private function addImage($image_name){
      $result = $this->container['coffee']->insert('IMAGE', [
          "NAME" => $image_name
         ]);
      if ($result) {
          $id = $this->container['coffee']->id();   //获取id
          return $id;
      } else {
          return false;
      }
    }

}