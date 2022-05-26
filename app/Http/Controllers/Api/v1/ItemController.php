<?php
namespace App\Http\Controllers\Api\v1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function itemEventFromOversea(Request $request)
    {
        $datas = array();

        $randomItem = DB::table('randomitem')->where('status', 'Y')->orderBy('order_id', 'ASC')->limit(8)->get();

        return response()->json($randomItem);
    }

    public function itemList(){
        $datas = [];
        $itemList = DB::table('itemlist')->whereRaw('supplyPrice >= 10000')->orderBy('no', 'DESC')->limit(10)->get();
        return response()->json($itemList);
    }
}
