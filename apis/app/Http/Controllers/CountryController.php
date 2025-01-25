<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Country;
use App\State;

class CountryController extends Controller
{
    
    public function list(Request $request)
    {
        $countries = Datatables::of(Country::all());
        return response()->json(['message' => 'country details', 'status' => 200, 'data'=> $countries]);
    }

    public function getStateList(Request $request)
    {
        $country_id = $request->country_id;
        if(empty($country_id)) {
            return response()->json(['message' => 'Country id is required', 'status' => 400]);
        }

        $states = State::where('country_id', '=', $country_id)->get();
        return response()->json(['message' => 'states details', 'status' => 200, 'data'=> $states]);
    }
}
