<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\UploadedFile;
use frontend\models\PickupForm;

/**
 * Ctic Controller
 */
class CticController extends Controller {
	
	//
	CONST MAX_HEIGHT = 800;
	//
	CONST MAX_WIDTH = 800;
	
	/**
	 * 显示页面
	 */
	public function actionIndex() {
		$model = new PickupForm ();
		
		$page_info = $search_result = null;
		
		if (Yii::$app->request->isPost) {
			$model->file = UploadedFile::getInstance ( $model, 'file' );
			
			if ($model->file && $model->validate ()) {
				$file_path = $model->file->tempName;
				
				try {
					$imagine = new \Imagine\Gd\Imagine ();
					$image = $imagine->open ( $file_path );
					
					$size = $image->getSize ();
					
					// 超出范围进行裁剪
					if ($size->getWidth () > self::MAX_WIDTH || $size->getHeight () > self::MAX_HEIGHT) {
						$save_file = 'runtime/upload/' . $model->file->name;
						
						$image->crop ( new \Imagine\Image\Point ( 0, 0 ), new \Imagine\Image\Box ( self::MAX_WIDTH, self::MAX_HEIGHT ) );
						$image->save ( Yii::getAlias ( '@' . $save_file ) );
					}
					
					$ai_config = Yii::$app->params ['product_ai'];
					
					// 调用接口，进行搜索
					$product_ai = new \ProductAI\API ( $ai_config ['access_key_id'], $ai_config ['secret_key'] );
					
					// 将得到的结果转为数组，赋值给页面
					$search_result = $product_ai->searchImage ( $ai_config ['service_type'], $ai_config ['service_id'], '@' . $model->file->tempName );
					
					// 测试一直没有成功，返回  API thrown an error. Service usyq3ic1 not found
					// 需要完善
				} catch ( \Exception $e ) {
					$page_info = '调用接口错误：' . $e->getMessage ();
				}
			} else {
				$page_info = '请上传图片文件';
			}
		}
		
		return $this->render ( 'index', [ 
				'model' => $model,
				'page_info' => $page_info,
				'search_result' => $search_result 
		] );
	}
}
