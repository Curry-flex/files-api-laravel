<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\Carousel;

use Illuminate\Http\Request;

class CarouselController extends Controller
{
    public function uploadCarousel(Request $request)
    {
        $carousel =$request->file('photo');
        
 
        
        if($carousel)
        {
           $currentDate =Carbon::now()->toDateString();
           $carouselname=$currentDate.'-'.uniqid().'.'.$request->photo->getClientOriginalExtension();
           
           $filePath = 'Carousel/' . $carouselname;
 
           $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->photo));
           $path = Storage::disk('public')->url($isFileUploaded);  //file  url to access video to frontend 
            
           if($isFileUploaded)
           {
             $carousel = new Carousel;
             $carousel->description=$request->description;
             $carousel->photo =env('WEB_PATH').'Carousel/'.$carouselname;
             $carousel->save();
             return response()->json(['message'=>'operation successful','data'=>$carousel]);
           }
           else{
             return response()->json(['message'=>'operation failed','data']);
           }
 
        }
        else{
            return;
        }

    }

    public function getCarousel()
    {
        $carousel = Carousel::all();
        return response()->json(["message"=>"operation completed","data"=>$carousel]);
    }
}
