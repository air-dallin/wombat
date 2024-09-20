<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function add($image,$type,$id,$crop_size=300){
        $path = $image->store('public/'.$type.'/'.$id);
        $result = Image::create(['object_id'=>$id,'type'=>$type,'image'=>$path]);

        $filename = 'crop.' . $image->getClientOriginalExtension();

       \Intervention\Image\Facades\Image::make($image->getRealPath())->resize($crop_size, $crop_size)
            ->save(Storage::path('public/'.$type.'/'.$id).'/'.$filename);
        return $result;
    }

    public function small($refresh = false){

        $image = explode('/',$this->image);
        if(count($image)){
            $ext = explode('.',$image[count($image)-1]);
            $image[count($image)-1] = 'crop.' . $ext[1];
            $prefix = $refresh ? '?r='.time() : '';
            return implode('/',$image) . $prefix;
        }
        return '';

    }

    public function deleteSmall(){
        if($image = $this->small()){
            @unlink($image);
            return true;
        }
        return false;
    }



}
