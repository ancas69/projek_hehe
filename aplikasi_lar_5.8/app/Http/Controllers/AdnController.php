<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Dosen;
use App\Calon;
use Illuminate\Support\Facades\DB;
use App\ProfileSasaran;
use Session;

class AdnController extends Controller
{
	public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    public function index(){
    	return view('layouts.admin.index');
    }

    public function buka_pengajuan(){
        $DsnController = app('App\Http\Controllers\DsnController');
        $data_periode = $DsnController->data_periode();

        
        return view('layouts.admin.pengajuan',['data'=>$data_periode,'array_genjil_genap' => $arrayName = array('ganjil' => '','genap' => '')]);
    }

    public function pengajuanPenelitian(Request $req)
    {
    	$DsnController = app('App\Http\Controllers\DsnController');

        $dosen = DB::table('dosen')->get();

        $cek = $req;
        $this->validate($req,[
            'tahun_ajaran' => 'required|digits:4|integer|min:'.(date('Y')-1).'|max:'.(date('Y')+1),
            'tahun_ajaran2' => 'required|digits:4|integer|min:'.(($req->tahun_ajaran)+1).'|max:'.(($req->tahun_ajaran)+1),
        ]);

        foreach ($dosen as $dosen) {
        	$update_dosen = $DsnController->getOneDataDosenByIdUser($dosen->id_user);
	        if($req->stat != "tutup")
	        {	        	
        		$update_dosen->periode_pengajuan = $req->stat.$req->tahun_ajaran."-".$req->tahun_ajaran2;
	        }
	        else{
				$update_dosen->periode_pengajuan = $req->stat;
	        }
	        $update_dosen->save();
	    }
        
        return back();
    }

    public function halaman_data_dosen(){

        $beban = DB::table('dosen')
        ->leftJoin('calon','dosen.id_dosen' ,'=', 'calon.id_dosen')
        ->select('dosen.id_dosen',DB::raw('count(case calon.status when "terima" then 1 else null end) AS beban_bimbingan'))
        ->groupBy('calon.id_dosen')
        ->get();

        $data_dosen = DB::table('dosen')->get();

        $data_beban_bimbingan = array();


        foreach($data_dosen as $key){
           $data_beban_bimbingan[$key->id_dosen] = 0; 
        }
        foreach($beban as $key){
            $data_beban_bimbingan[$key->id_dosen] = $key->beban_bimbingan;
        }


        

        
        return view('layouts.admin.data_dosen',['data_dosen' => $data_dosen, 'data_beban_bimbingan' => $data_beban_bimbingan]);
    }

    public function edit_periode_satu_dosen(Request $req){

        $dsn = Dosen::where('id_dosen',$req->id_dosen)->first();

        $dsn->periode_pengajuan = $req->status_.$req->tahun_;

        $dsn->save();    
    }
}
