<?php 

namespace App\Reponsitories;

use App\Models\Category;
use App\Models\ThumbnailCategories;

class CategoriesReponsitory{

    
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Retrieve all categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */

    public function get(){
        return $this->category->all();
    }

    /**
     * Create a new category.
     *
     * @param string $image
     * @param string $name
     * @return \App\Models\Category
     */

    public function create($image, $file_id,$name)
    {
        $category = new Category();
        $category->thumbnail = $image;
        $category->file_id = $file_id;
        $category->name = $name;
        $category->save();

        return $category;
    }

    /**
     * Edit an existing category.
     *
     * @param int $id
     * @param string|null $image
     * @param string $name
     * @return \App\Models\Category
     */
    public function edit($id, $image = null, $name)
    {
        $category = $this->category->find($id);
        if ($category) {
            if ($image) {
                
                $category->image = $image;
            }
            $category->name = $name;
            $category->save();
        }

        return $category;
    }


    /**
     * Delete a category.
     *
     * @param int $id
     * @return bool|null
     */
    public function delete($id){

    }


    /**
     * Add thumbail category
     * 
     * @param int $user_id
     * @param string $thumbnail
     * @param string $file_id
     * @param string $title
     * @param string $description
     * @param int $type
     * @return bool|null
     * 
     */
    public function ThumbnailCategories($user_id, $thumbnail, $file_id, $title, $description, $type){
        $thumbnailCategory = new ThumbnailCategories();
        $thumbnailCategory->category_id = $user_id;
        $thumbnailCategory->thumbnail = $thumbnail;
        $thumbnailCategory->file_id = $file_id;
        $thumbnailCategory->title = $title;
        $thumbnailCategory->description = $description;
        $thumbnailCategory->type = $type;
        $thumbnailCategory->save();
    }
}