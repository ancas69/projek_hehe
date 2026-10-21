<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BobotAspek;
use App\ProfileSasaran;
use App\User;
use App\Dosen;
use App\Mahasiswa;
use Session;
use Illuminate\Support\Facades\DB;

class BaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function getOneDataBobotAspek($id_user)
    {
        return BobotAspek::where('id_user',$id_user)->first();
    }

    


    public function pembobotan(Request $req){
        $MhsController = app('App\Http\Controllers\MhsController');
    	$mhs = $MhsController->find_with_id_user(Session::get('id'));
    	$duserid = Session::get('id');
    	$ba= $this->getOneDataBobotAspek($duserid); //BobotAspek::where('id_user',$duserid)->first();
    	
	    return view('layouts.mhs.bobot',['data'=>$mhs, 'dbobot' => $ba]);  
	    
    }

    public function bobotsubKriteria()
    {
        $bobot_kriteria = $this->getOneDataBobotAspek(Session::get('id'));//BobotAspek::where('id_user',Session::get('id'))->first(); 

        $MhsController = app('App\Http\Controllers\MhsController');
        $mhs = $MhsController->find_with_id_user(Session::get('id'));

        $ProfileController = app('App\Http\Controllers\ProfileController');
        $id_profile = $ProfileController->getOneProfileByIdUser(Session::get('id'));//ProfileSasaran::where('id_user',Session::get('id'))->first();

        if(!isset($id_profile)){
            $id_profile = (object) array('bidang_minat' => 0, 'topik_penelitian' => 0, 'pendidikan' => 0, 'progres_bimbingan' => 0, 'progres_penelitian' => 0, 'kosong' => 'kosong');
        }

        $daftar_topik = DB::table('topik_penelitian')->get();

        
        $cek_Pengajuan_ = DB::table('dosen')->select('periode_pengajuan')->first();

   
        return view('layouts.mhs.sub_kriteria',['cek_pengajuan_' => $cek_Pengajuan_,'daftar_topik'=>$daftar_topik,'data'=>$mhs,'id_profile'=>$id_profile,'bobot_kriteria' => $bobot_kriteria]);
    }

    public function simpanPersentaseKriteria(Request $req){
    	$ba = new BobotAspek;
    	$ba->NCF = $req->NCF;
    	$ba->NSF = $req->NSF;
    	$ba->id_user = $req->id_user;
    	$ba->save();

        return redirect()->route('bobotsubKriteria');
    }

    public function updatePersentaseKriteria(Request $req){
    	$ba = $this->getOneDataBobotAspek(Session::get('id') );
    	$ba->NCF = $req->NCF;
        $ba->NSF = $req->NSF;
        $ba->id_user = $req->id_user;
    	$ba->save();

        return redirect()->route('bobotsubKriteria');
    }


}
