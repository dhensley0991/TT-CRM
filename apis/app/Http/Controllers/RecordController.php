<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Record;
use App\User;
use DataTables;

class RecordController extends Controller
{
    
    public function create(Request $request)
    {
        $user_id = $request->user_id;

        if(empty($user_id)) {
            return response()->json(['message' => 'User id is required', 'status' => 400]);
        }

        $record                                   = new Record();
        $record->user_id                          = $request->user_id;
        $record->acc                              = $request->acc;
        $record->platform                         = $request->platform;
        $record->trader_id                        = $request->trader_id;
        $record->comm_deal                        = $request->comm_deal;
        $record->locate_deal                      = $request->locate_deal;
        $record->routing_deal                     = $request->routing_deal;
        $record->program_member                   = $request->program_member;
        $record->program_detail_cost_per_month    = $request->program_detail_cost_per_month;
        $record->program_detail_shares_per_month  = $request->program_detail_shares_per_month;
        $record->program_detail_time_limit        = $request->program_detail_time_limit;
        $record->save();
        return response()->json(['message' => 'Record added successfully', 'status' => 200]);
    }

    public function updateRecord(Request $request)
    {
        $user_id = $request->user_id;

        if(empty($user_id)) {
            return response()->json(['message' => 'User id is required', 'status' => 400]);
        }

        $record_id = $request->record_id;

        if(empty($record_id)) {
            return response()->json(['message' => 'Record id is required', 'status' => 400]);
        }

        $record = Record::find($record_id);

        if(!empty($record)) {

            $record->user_id                          = $request->user_id;
            $record->acc                              = $request->acc;
            $record->platform                         = $request->platform;
            $record->trader_id                        = $request->trader_id;
            $record->comm_deal                        = $request->comm_deal;
            $record->locate_deal                      = $request->locate_deal;
            $record->routing_deal                     = $request->routing_deal;
            $record->program_member                   = $request->program_member;
            $record->program_detail_cost_per_month    = $request->program_detail_cost_per_month;
            $record->program_detail_shares_per_month  = $request->program_detail_shares_per_month;
            $record->program_detail_time_limit        = $request->program_detail_time_limit;
            $record->save();

            return response()->json(['message' => 'Record updated successfully', 'status' => 200]);
        } else {
            return response()->json(['message' => 'Record not found', 'status' => 400]);
        }
    }

    public function getRecordById(Request $request)
    {
        $record_id = $request->record_id;

        if(empty($record_id)) {
            return response()->json(['message' => 'Record id is required', 'status' => 400]);
        }

        $recordDetails = Record::find($record_id);

        if(!empty($recordDetails)) {
            return response()->json(['message' => 'record details', 'status' => 200, 'data'=> $recordDetails]);
        } else {
            return response()->json(['message' => 'Record not found', 'status' => 400]);
        }
    }

    public function list(Request $request)
    {
        $results        = Record::all();
        $recordDetails = Datatables::of($results)
            ->editColumn(
                'user_id',
                function ($results) {

                    $userDetail = User::select('first_name', 'last_name')->where('id', '=', $results->user_id)->get()->toArray();

                    return $userDetail;

                }
            )->escapeColumns([])->make(true);

        return response()->json(['message' => 'records list', 'status' => 200, 'data' => $recordDetails]);
    }

    public function deleteRecord(Request $request)
    {
        $record_id = $request->record_id;

        if(empty($record_id)) {
            return response()->json(['message' => 'Record id is required', 'status' => 400]);
        }

        $recordDetails = Record::find($record_id);

        if(!empty($recordDetails)) {
            $recordDetails->delete();
            return response()->json(['message' => 'Record deleted successfully', 'status' => 200]);
        } else {
            return response()->json(['message' => 'Record not found', 'status' => 400]);
        }
    }
}
