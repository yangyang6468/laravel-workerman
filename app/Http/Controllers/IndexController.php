<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\User;
use DB;

class IndexController extends Controller
{

    public function __construct(){
        $this->middleware("auth");
    }


    public function index(){

        $user  = User::where(['status'=>1 , 'isdelete'=> 0])->get()->toArray();

        return view("welcome" , compact('user'));
    }


    /**
     * 聊天信息列表
     * @author  yy
     * @date
     *
     */
    public function infolist(Request $request){

        $from_userid = $request->input("to_uid");
        $uid = Auth::user()->id;


        DB::enableQueryLog();  //开启sql调试
        $infolist = DB::select("
            SELECT
                    * 
                from 
                (
                        SELECT c.* , u.nickname,u.headimage,u.flag as userflag FROM cmf_chat c INNER JOIN cmf_userinfos u on u.id = c.from_userid  WHERE from_userid = $uid and to_userid= $from_userid
                        UNION
                        SELECT c.* , u.nickname,u.headimage,u.flag as userflag FROM cmf_chat c INNER JOIN cmf_userinfos u on u.id = c.from_userid  WHERE from_userid = $from_userid and to_userid = $uid  
                ) t
                ORDER BY
                    t.create_time
            ");
//        dd($infolist);
//        dd(DB::getQueryLog());
        return view("infolist" , compact('infolist'));
    }


}
