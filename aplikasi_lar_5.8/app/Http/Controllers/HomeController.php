<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mahasiswa;
use App\Dosen;
use App\User;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // public function welcome(){
    //     return view('welcome');
    // }
    public function index(Request $req)
    {


         // dd(auth()->user()->email);

        $id_user = User::select('id')->where('email', auth()->user()->email)->get();
        // $req->session()->put('email',$input['email']);
        $req->session()->put('id',$id_user[0]->id);
        $req->session()->put('cekLevel',auth()->user()->level);






        $cek_level = $req->session()->get('cekLevel');
        
        if($cek_level == "mahasiswa" ){
            return redirect()->route('goToMhs');
        }else if($cek_level == "dosen" ){
            return redirect()->route('goToDsn');
        }else if($cek_level == "admin" ){
            return redirect()->route('goToAdn');
        }else return "masuk kesini";
    }

    public function gotoCekMhs(Request $req){
        $cekData = Mahasiswa::where('id_user',$req->session()->get('id'))->first();
        
        if(!isset($cekData->id_mahasiswa)){
            return redirect()->route('mhsCreate');
        }else{
            return redirect()->route('mhsHome');
        }
    }

    public function gotoCekDsn(Request $req){
        // return view('home');
        $cekData = Dosen::where('id_user',$req->session()->get('id'))->first();
        
        if(!isset($cekData->id_dosen)){
            return redirect()->route('dsnCreate');
        }else{
            return redirect()->route('dsnHome');
        }
    }

    public function gotoCekAdn(Request $req){
        
       // ke halaman admin

        return redirect()->route('adnHome');
    }
}
