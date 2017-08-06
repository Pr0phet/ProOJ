<?php

namespace App\Http\Controllers;

use App\Jobs\AddPoint;
use App\Quiz;
use App\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FlagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); //Authorized User Only
        $this->middleware('active');//Active User Only
        //TODO 根据IP限制频率 参照Auth 根据题目
    }

    public function index(Request $request)
    {
        $data = $request->input(); //Type name flag

        $res = Quiz::verifyFlag($data['type'],$data['name'],$data['flag']);

        if($res)
        {
            if(!Record::checkAnswered(Auth::user()->id, $data['name']))
                dispatch(new AddPoint(Auth::user(), $data['name'], $request->getClientIp()));

            return [
                'statusCode' => 200,
                'message' => 1
            ];
        }
        else
            return [
                'statusCode' => 200,
                'message' => 0
            ];
    }
}