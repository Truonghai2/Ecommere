<?php
namespace App\Services;

use App\Reponsitories\ThumbnailReponsitory;

class HomeService{
    protected $thumbnail;
    
    public function __construct(ThumbnailReponsitory $thumbnail)
    {
        $this->thumbnail = $thumbnail;        
    }

    public function getALL(){
        return $this->thumbnail->getALL();
    }

    
}