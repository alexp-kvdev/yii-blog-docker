<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImageUpload extends Model{

    public $image;

    public function rules(){

        return [
            [['image'], 'required'],
            [['image'], 'file', 'extensions' => 'jpg,png']
        ];
    }

    public function uploadFile(UploadedFile $file, $currentImage){

        $this->image = $file;

        if($this->validate()){

            //delete old file if isset
            $this->deleteCurrentImage($currentImage);

            $filename = $this->generateFileName();

            $file->saveAs($this->getFolder() . $filename);

            return $filename;
        }

    }

    private function getFolder(){
        return Yii::getAlias('@web') . 'uploads/';
    }

    private function generateFileName(){
        return strtolower(md5(uniqid($this->image->baseName)) . '.' .$this->image->extension);
    }

    public function deleteCurrentImage($currentImage){

        $currentFileName = $this->getFolder().$currentImage;

        if(!empty($currentImage) && file_exists($currentFileName)){

            unlink($currentFileName);
        }
    }
}

//TODO 8 22:53