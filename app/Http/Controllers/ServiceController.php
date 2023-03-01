<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Services;

class ServiceController extends Controller
{
    public function addService(Request $request)
    {
       $services = new Services;
       $services->title = $request->title;
       $services->description = $request->description;
       $services->save();
       
       return response()->json(['message' => 'Service added successfully','data' => $services]);
    }

    //Readmore information 

    public function getServices()
    {
        $services = Services::all();
        return response()->json(['message' => 'Operation successfully','data'=>$services]);
    }

    public function getServicesById(Request $request, $id)
    {
        $service = Services::where('id', $id)->first();
        return response()->json(['message'=>'operation successfully','data'=>$service]);
    }
}
