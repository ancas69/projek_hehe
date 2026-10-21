<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BobotAspek;
use App\ProfileSasaran;
use App\User;
use App\Dosen;
use App\HasilAkhir;
use App\PemetaanGap;
use App\Mahasiswa;
use App\Calon;
use Session;
use Illuminate\Support\Facades\DB;

class MhsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }


    // public function test(){

    //     return view('layouts.mhs.test',['data' => Mahasiswa::where('id_user',Session::get('id'))->first()]);
    // }



	public $ambilData;
	public function index(Request $req){
		$this->ambilData  = $this->find_with_id_user(Session::get('id'));
		if($req->session()->get('cekLevel') != "mahasiswa")
        {
        	return redirect()->back();
        }
        else{

            $calon = DB::table('calon')->join('dosen','calon.id_dosen','=','dosen.id_dosen')->where('calon.id_mahasiswa',$this->ambilData['id_mahasiswa'])->get();          
            return view('layouts.mhs.index',['data' => $this->ambilData, 'calon' => $calon]);
        }
	}

	public function create(Request $cek){
    	if($cek->session()->get('cekLevel') != "mahasiswa")
        {
        	return redirect()->back();
        }
        else return view('layouts.mhs.create');
    }

    public function store(Request $req){
    	$this->validate($req,[
            'nama' => 'required',
            'nim' => 'required|min:10|unique:mahasiswa,id_mahasiswa',
        ]);

        $mhs = new Mahasiswa;
        $mhs->id_mahasiswa = $req->nim;
        $mhs->nama = $req->nama;
        $mhs->id_user = $req->session()->get('id');
        $mhs->save();
        return redirect()->route('goToMhs');
    }

    public function find_with_id_user($id){
    	return Mahasiswa::where('id_user',$id)->first();
    }

    public function update_form(Request $req){
    	if($req->session()->get('cekLevel') != "mahasiswa")
        {
        	return redirect()->back();
        }else{
    	$this->ambilData  = $this->find_with_id_user(Session::get('id'));
        $cek_data = Calon::where('id_mahasiswa',$this->ambilData['id_mahasiswa'])->get();
        $i=0;
        foreach ($cek_data as $key) {
            $i=$i+1;
        }
    	return view('layouts.mhs.update',['cek_data' => $i,'data' => $this->ambilData]);}
    	// return $this->find_with_id_user(Session::get('id'));
    }

    public function update_save(Request $req){
    	if($req->session()->get('cekLevel') != "mahasiswa")
        {
        	return redirect()->back();
        }else{
    	$this->validate($req,[
            'nama' => 'required',
            'nim' => 'required|min:10|unique:mahasiswa,id_mahasiswa,'.Session::get('id').',id_user',
        ]);
    	$this->ambilData  = $this->find_with_id_user(Session::get('id'));
    	$this->ambilData->id_mahasiswa = $req->nim;
    	$this->ambilData->nama = $req->nama;
    	$this->ambilData->save();
    	return redirect()->route('goToMhs');}
    	// return view('layouts.mhs.update',['data' => $this->ambilData]);
    	// return $this->find_with_id_user(Session::get('id'));
    }

    public function processAll(Request $req){
        $ProfileController = app('App\Http\Controllers\ProfileController');

        $cek = $ProfileController->getOneProfileByIdUser(Session::get('id'));

        if(!isset($cek->id_profile)){
            // jika tidak ada profile, buat baru
            $ProfileController->simpanBobotsubKriteria($req);
        }
        else{
            // jika sudah ada , update
            $ProfileController->updateBobotsubKriteria($req);
        }

        $PetaController = app('App\Http\Controllers\PetaController');
        $HasilController = app('App\Http\Controllers\HasilController');

        $PetaController->indexPeta();
        $HasilController->perhitunganTotal();
        $HasilController->hasilAkhir();
    }
}
