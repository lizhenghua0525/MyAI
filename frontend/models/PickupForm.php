<?php

namespace frontend\models;

use yii\base\Model;

/**
 * PickupForm
 */
class PickupForm extends Model {
	
	// 选择的文件
	public $file;
	
	// 验证规则
	public function rules() {
		return [ 
				[ 
						[ 
								'file' 
						],
						'file',
						'extensions' => 'jpg, png',
						'mimeTypes' => 'image/jpeg, image/png' 
				] 
		];
	}
}
