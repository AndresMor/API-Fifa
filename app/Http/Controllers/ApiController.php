<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Player;


use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function team(Request $request){
        try {
            $team = DB::table('players')->where('club','ilike',$request->Name)->paginate(10,['*'],'page', $request->Page);
    
            $response = ['Page' => $team->currentPage(), 'totalPages' => $team->lastPage(), 
                         'Items' => $team->count(), 'totalItems' => $team->total(), 'Players' => $team->items()];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            $response = ['data' => [], 'status' => 500,'message'=> 'server error', 'error' => $e->getMessage()];
            return response()->json($response,$response['status']);
        }
    }

    public function players(Request $request){
        try {
            if($request->has('order')){
                if($request->order == 'asc' || $request->order == 'desc'){
                    $order = $request->order;
                }else{
                    $response = ['data' => [], 'status' => 400,'message'=> 'bad request', 'error' => 'param error'];
                    return response()->json($response,$response['status']);
                }
            }else{
                $order = 'asc';
            }
            $players = DB::table('players')->where('name','ilike',$request->name.'%')->orderBy('name', $order)->paginate(10,['*'],'page', $request->page);
            $response = ['Page' => $players->currentPage(), 'totalPages' => $players->lastPage(), 
                         'Items' => $players->count(), 'totalItems' => $players->total(), 'Players' => $players->items()];
            return response()->json($response,200);
        } catch (\Throwable $th) {
            $response = ['data' => [], 'status' => 500,'message'=> 'server error', 'error' => $e->getMessage()];
            return response()->json($response,$response['status']);
        }
    }
}
