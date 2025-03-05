<?php 
namespace App\Reponsitories;

use App\Models\Thumbnail;

class ThumbnailReponsitory{
    

    protected $thumbnail;
    public function __construct(Thumbnail $thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }


    public function getALL(){
        return $this->thumbnail::orderByDesc('created_at')->get()->toArray();
    }


    public function removeThumbnail($id){
        
    }

}