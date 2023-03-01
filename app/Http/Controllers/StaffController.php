<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\StaffFiles;
use App\Models\Video;
use App\Models\Audio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Response;


class StaffController extends Controller
{
    //

    public function storeStaff(Request $request)
    {
       


       $image =$request->file('image');
       $title =$request->designation;

       
       if($image)
       {
          $currentDate =Carbon::now()->toDateString();
          $imagename=$title.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

          if(!Storage::disk('public')->exists('staff-image')){
              Storage::disk('public')->makeDirectory('staff-image');
          }

          $imageResize =Image::make($image)->resize(1600,1066)->stream();
          Storage::disk('public')->put('staff-image/'.$imagename,$imageResize);
       }
       else{
           $imagename="default.png";
       }

       //$imagebase64 = base64_encode(file_get_contents($request->file('image')));
       //env('IMAGE_PATH')+'staff-image/'+$imagename;
       $staff = new Staff;
       $staff->fullname = $request->fullname;
       $staff->designation = $request->designation;
       $staff->photo = env('WEB_PATH').'staff-image/'.$imagename;
       $staff->save();

       return response()->json(['message' => 'operation successfully','data' => $staff]);
    }

    public function staffList(Request $request)
    {
       $staff = Staff::all(); 
       //dd($staff->photo);
       return response()->json(['message' => 'operation successfully','data'=>$staff]);  
    }

    public function storeStaffPDF(Request $request)
    {
        
 
       $currentDate =Carbon::now()->toDateString();
       $fileName = $request->file->getClientOriginalName().'-'.$currentDate.'-'.uniqid();
       $filePath = 'staff-files/' . $fileName;

       $path = Storage::disk('public')->put($filePath, file_get_contents($request->file));
       $path = Storage::disk('public')->url($path);

       $staffFiles = new StaffFiles;
       $staffFiles->path = env('WEB_PATH').'staff-files/'.$fileName;
       $staffFiles->fileID = $fileName;
       $staffFiles->filename =$request->filename;
       $staffFiles->save();

       return response()->json(['message' => 'operation successfully','data' => $staffFiles]);

    }

    public function getDocumentsFiles()
    {
        $docFiles = StaffFiles::all();
        return response()->json(['message' => 'operation successfully','data'=>$docFiles]);
    }

    public function getDownloadPdfFiles(Request $request)
    {
        $isFileFound = StaffFiles::where('fileID',$request->fileID)->first();
        
        if(empty($isFileFound))
        {
            return response()->json(['message' => 'file not found','data'=>""]);
        }
       
            $headers = ['Content-Type: application/pdf'];
            $newName = 'itsolutionstuff-pdf-file-'.time().'.pdf';
          
            $file_path = public_path('storage/staff-files/'.$isFileFound->fileID);
    
          return response()->download($file_path, $newName, $headers);
    }


    public function uploadVideo(Request $request){

       $video =$request->file('video');
       $title =$request->title;

       
       if($video)
       {
          $currentDate =Carbon::now()->toDateString();
          $videoname=$currentDate.'-'.uniqid().'.'.$request->video->getClientOriginalExtension();
          
          $filePath = 'staff-video/' . $videoname;

          $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->video));
          $path = Storage::disk('public')->url($isFileUploaded);  //file  url to access video to frontend 
           
          if($isFileUploaded)
          {
            $video = new Video;
            $video->title =$videoname;
            $video->path =env('WEB_PATH').'staff-video/'.$videoname;
            $video->save();
            return response()->json(['message'=>'operation successful','data'=>$video]);
          }
          else{
            return response()->json(['message'=>'operation failed','data']);
          }

       }
       else{
           return;
       }

   
    }

    public function getvideo()
    {
        $videos = Video::all();
        return response()->json(['message'=>'operation successful','data'=>$videos]);
    }


    public function uploadAudio(Request $request)
    {
        $audio =$request->file('audio');
        $title =$request->title;

        if($audio)
        {
           $currentDate =Carbon::now()->toDateString();
           $audioname= $currentDate.'-'.uniqid().'.'.$request->audio->getClientOriginalExtension();
           
           $filePath = 'staff-audio/' . $audioname;
 
           $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->audio));
           $path = Storage::disk('public')->url($isFileUploaded);  //file  url to access video to frontend 
            
           if($isFileUploaded)
           {
             $audio = new Audio;
             $audio->title =$request->title;
             $audio->path =env('WEB_PATH').'staff-audio/'.$audioname;
             $audio->save();
             return response()->json(['message'=>'operation successful','data'=>$audio]);
           }
           else{
             return response()->json(['message'=>'operation failed','data']);
           }

 
        }
        else{
            return;
        }

      }

    public function getaudio()
    {
        $videos = Audio::all();
        return response()->json(['message'=>'operation successful','data'=>$videos]);
    }
}
