<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Manufacturer;
use App\Http\Resources\ManufacturerResource;
use App\Http\Controllers\Controller;

class ManufacturerController extends Controller
{
 
    public function index()
    {
        return ManufacturerResource::collection(Manufacturer::all());
    }

   
    public function store(Request $request)
    {
        $newManufacturer = Manufacturer::create($request->validated());
        return ManufacturerResource::make($newManufacturer);
    }


    public function show(string $id)
    
    {

        $manufacturer = Manufacturer::find($id);

        if (!$manufacturer) {
            return response()->json(['error' => 'Manufacturer not found'], 404);
        }

        return ManufacturerResource::make($manufacturer);
    }


    public function update(Request $request, string $id)
    
    {
        
        $manufacturer = Manufacturer::find($id);

        if (!$manufacturer) {
            return response()->json(['error' => 'Manufacturer not found'], 404);
        }

        $manufacturer->update($request->all());

        return ManufacturerResource::make($manufacturer);
    }

    public function destroy(string $id)
    {
        $manufacturer = Manufacturer::find($id);

        if (!$manufacturer) {
            return response()->json(['error' => 'Manufacturer not found'], 404);
        }

        $manufacturer->delete();

        return response()->json(['message' => 'Manufacturer deleted successfully']);
        
    }

}



