<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\District;
use App\Models\Library;
use Illuminate\Support\Str;

class PublicApiController extends Controller
{
    public function getLibraryTypes()
    {
        $types = [
            'Reading Library',
            'Study Library',
            'AC Library',
            'Non AC Library',
            'Girls Library',
            'Boys Library',
            '24x7 Library',
            'Premium Library',
            'UPSC Library',
            'SSC Library',
            'IAS Library'
        ];

        return response()->json($types);
    }

    public function getStates()
    {
        $states = State::orderBy('name', 'asc')->get(['id', 'name']);
        
        $states = $states->map(function ($state) {
            return [
                'id' => $state->id,
                'name' => $state->name,
                'slug' => Str::slug($state->name)
            ];
        });

        return response()->json($states);
    }

    public function getDistricts(Request $request)
    {
        if (!$request->state_id) {
            return response()->json([]);
        }

        $districts = District::where('state_id', $request->state_id)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);
            
        $districts = $districts->map(function ($district) {
            return [
                'id' => $district->id,
                'name' => $district->name,
                'slug' => Str::slug($district->name)
            ];
        });
            
        return response()->json($districts);
    }
}
