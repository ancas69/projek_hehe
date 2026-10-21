<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Dosen;
use App\Calon;
use Illuminate\Support\Facades\DB;
use App\ProfileSasaran;
use Session;
use App\Topik;

class DsnController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function showDosen(){
        $MhsController = app('App\Http\Controllers\MhsController');
        $CalonController = app('App\Http\Controllers\CalonController');
        $mhs = $MhsController->find_with_id_user(Session::get('id'));
        
        $dosen = $CalonController->getAllDataDosenCalon(); // get all data dosen, antrian, beban bimbingan.

        $slot_taken_this_semester = array();  
        $slot_taken_this_semester = $CalonController->setSlotDosenActivePeriode();  
        
        return view('layouts.mhs.dosen',['data' => $mhs, 'dosen' => $dosen , 'slot_taken_this_semester' =>$slot_taken_this_semester]);
    }

    public function showOneDosen($id){
        $MhsController = app('App\Http\Controllers\MhsController');
        $ProfileController = app('App\Http\Controllers\ProfileController');
        $CalonController = app('App\Http\Controllers\CalonController');

        $data = $MhsController->find_with_id_user(Session::get('id'));

        $ddosen = $this->getOneDataDosen($id);

        $dps = $ProfileController->getOneProfileByIdUser($ddosen['id_user']);

        $slot_taken = $CalonController->getOneDataCalonDosen($ddosen['id_dosen']); // get data slot, bebanbimbingan.

  
        if($slot_taken!=null){
            // nilai cek == 1 jika dalam daftar mahasiswa dosen tersebut ada nim si pengecek , jika tidak == null
            $cek_calon = $CalonController->cekMhsinListCalonDosen($slot_taken->cid,$data['id_mahasiswa']);
        }
        else $cek_calon = null; 

        $slot_taken_this_semester = array();  
                   
        $slot_taken_this_semester = $CalonController->setSlotDosenActivePeriode(); // get data bimbingan saat ini saat ini.
       
        $nama_topik = array();
        $i=0;
        foreach(explode('#',$ddosen->topik_bimbingan) as $topik){
            $nama_topik[$i] = DB::table('topik_penelitian')->select('topik')->where('id',$topik)->first()->topik;
            $i = $i +1; 
        }



        


        return view('layouts.mhs.halaman-dosen',['slot_taken_this_semester' => $slot_taken_this_semester,'slot_taken' => $slot_taken,'dps' => $dps, 'ddosen' => $ddosen, 'data' => $data,'cek'=>$cek_calon, 'nama_topik' => $nama_topik]);
    }

    public function getAllDosen(){
        return DB::table('dosen')->select('dosen.*')->get();
    }

    public function getOneDataDosen($id_dosen)
    {
        return Dosen::where('id_dosen',$id_dosen)->first();
    }

    public function getOneDataDosenByIdUser($id_user)
    {
        return Dosen::where('id_user',$id_user)->first();
    }



    
    public function create(Request $req){

        $daftar_topik = DB::table('topik_penelitian')->get();
    	return view('layouts.dosen.create',['daftar_topik' => $daftar_topik]);
    }

    public function store(Request $req){
    	$this->validate($req,[
            'nama' => 'required',
            'niy' => 'required|unique:dosen,id_dosen|min:5|max:25',
            'slot' => 'required',
            'foto' => 'mimes:jpg,png,jpeg|max:1024',
        ]);

    	$dosen = new Dosen;
        if(isset($req->foto)){
            $dosen->foto = file_get_contents($req->foto);
        }
        $dosen->tentang_dosen = $req->content;
    	$dosen->id_dosen = $req->niy;
    	$dosen->nama = $req->nama;
    	$dosen->jabatan = $req->job;
    	$dosen->nomor_hp = $req->hp;
    	$dosen->slot = $req->slot;
        $dosen->periode_pengajuan = 'tutup';


        // $clean_topik = array();
        // $i=0;
        // foreach ($req->topik as $topik) {
        //     if($topik!=""){
        //         $clean_topik[$i] = strtolower($topik);
        //         $i++;
        //     }
        // }

        $dosen->topik_bimbingan =implode("#", $req->topik);
    	$dosen->id_user = Session::get('id');
    	$dosen->save();

        $ProfileController = app('App\Http\Controllers\ProfileController');

        $ProfileController->simpanBobotsubKriteria($req);

    	return redirect()->route('dsnHome');
    }

    public function index(Request $req){

        $dsn = $this->getOneDataDosenByIdUser(Session::get('id'));
        
        $stats = array("terima","tolak","selesai","menunggu");
        $jumlah_tiap_status = array();
        foreach ($stats as $stats) {
            $jumlah_tiap_status[$stats] = count(DB::table('calon')->join('mahasiswa','calon.id_mahasiswa','=','mahasiswa.id_mahasiswa')->where('calon.id_dosen',$dsn['id_dosen'])->where('calon.status',$stats)->get());
        }        

    	return view('layouts.dosen.index',['data'=>$dsn,'count_stat' => $jumlah_tiap_status]);
    }

    public function update_form(Request $req){
    	$data = $this->getOneDataDosenByIdUser(Session::get('id'));
        $topik = array();

        $topik = explode("#",$data->topik_bimbingan);

        $CalonController = app('App\Http\Controllers\CalonController');
        $cek_data = $CalonController->getDataCalonByIdDosen($data['id_dosen']);

        // jika ada data maka tidak bisa mengubah id dosen.
        $i=$cek_data->count();

        $daftar_topik = DB::table('topik_penelitian')->get();

        

        $profileDosen = ProfileSasaran::where('id_user',Session::get('id'))->first();
    	return view('layouts.dosen.update',['daftar_topik' => $daftar_topik,'profileDosen' => $profileDosen, 'data' => $data,'topik' => $topik, 'cek_data' => $i]);
    }

    public function update_save(Request $req){
        $id_user = Session::get('id');
    	$this->validate($req,[
            'nama' => 'required',
            'niy' => 'required|unique:dosen,id_dosen,'.Session::get('id').',id_user|min:5|max:20',
            'slot' => 'required',
            'foto' => 'mimes:jpg,png,jpeg|max:1024',
        ]);

    	$dosen = $this->getOneDataDosenByIdUser(Session::get('id'));
        if(isset($req->foto)){
            $dosen->foto = file_get_contents($req->foto);
        }
        $dosen->id_dosen = $req->niy;
    	$dosen->nama = $req->nama;
    	$dosen->jabatan = $req->job;
    	$dosen->nomor_hp = $req->hp;
    	$dosen->slot = $req->slot;

        //$clean_topik = array();
     
        // $i=0;
        // foreach ($req->topik as $topik) {
        //     if($topik!=""){
        //         $clean_topik[$i] = $topik;
        //         $i++;
        //     }
        // }
        $dosen->topik_bimbingan = implode("#", $req->topik);
        $dosen->tentang_dosen = $req->content;

    	$dosen->save();

        $ProfileController = app('App\Http\Controllers\ProfileController');
        $profile = $ProfileController->getOneProfileByIdUser(Session::get('id'));
        if($profile->count()==0)
        {
            $ProfileController->simpanBobotsubKriteria($req);
        }else{
            $ProfileController->updateBobotsubKriteria($req);
        }

    	return back();
    }



    






    public function detailMhs($id_calon){
        $data = $this->getOneDataDosenByIdUser(Session::get('id'));

        $CalonController = app('App\Http\Controllers\CalonController');
        $calon= $CalonController->getDataCalonMahasiswaByIdCalon($id_calon);

        return view('layouts.dosen.detail-mhs',['data' => $data , 'calon' => $calon]);
    }

    public function data_periode(){
        return DB::table('dosen')->select("periode_pengajuan")->first();
    }

    public function daftarMahasiswa($stat)
    {
        if($stat == 'Terima')
        {
            $judul = "SEDANG MELAKUKAN PENELITIAN";
        }
        else if($stat == 'Menunggu')
        {
            $judul = "PENGAJUAN PENELITIAN";
        }
        else if($stat == 'Tolak')
        {
            $judul = "DAFTAR TOLAK";
        }
        else{
            $judul = "PENELITIAN SELESAI";
        }


        $dsn = $this->getOneDataDosenByIdUser(Session::get('id'));

        $CalonController = app('App\Http\Controllers\CalonController');
        
        $calon = $CalonController->getDataCalonMhsByIdDosenandStatus($dsn['id_dosen'],$stat);
        $cek_periode_sekarang = $CalonController->getDataCalonDsnByIdDosenandPeriode($dsn->id_dosen,$dsn->periode_pengajuan);
        $all_periode = $CalonController->getDataCalonAllPeriodeDosenByIdDosen($dsn->id_dosen);
        
        $slot_sekarang = array();
        $slot_sekarang = $CalonController->setSlotDosenActivePeriode();

        $array_all_periode = array();
        if(isset($cek_periode_sekarang->periode) || $dsn->periode_pengajuan == "tutup")
        {
            foreach ($all_periode as $data) {
                $array_all_periode[$data->periode] = $data->periode;            
            }        
        }
        else{
            foreach ($all_periode as $data) {
                $array_all_periode[$data->periode] = $data->periode;            
            }
            $array_all_periode[$dsn->periode_pengajuan] = $dsn->periode_pengajuan; 
        } 

        $jumlah_bimbingan_tiap_periode = array();
        foreach ($all_periode as $data) {
            $jumlah_bimbingan_tiap_periode[$data->periode] = $CalonController->getDataCountCalonDsnByIdDosenandPeriode($dsn->id_dosen,$data->periode);            
        }   

        
        


        return view('layouts.dosen.daftar-mahasiswa',['slot_periode_ini'=>$slot_sekarang,'array_all_periode' => $array_all_periode,'data' => $dsn, 'judul' => $judul, 'calon' => $calon, 'color_stat' =>$stat ]);
    }

 



    public function getOneDataDosenJson(Request $req){
       $data = Dosen::where('id_dosen',$req->id_dosen)
       ->join('users','users.id','=','dosen.id_user')
       ->join('profilesasaran','users.id', '=' ,'profilesasaran.id_user')
       ->join('pemetaangap','pemetaangap.id_profile','=','profilesasaran.id_profile')
        ->select('dosen.nama','dosen.topik_bimbingan','dosen.periode_pengajuan','pemetaangap.antri')
        ->first();


        

        // $data = DB::table('users')
        // ->join('profilesasaran','users.id', '=' ,'profilesasaran.id_user')
        // ->join('dosen','dosen.id_user','=','users.id')
        // ->join('pemetaangap','pemetaangap.id_profile','=','profilesasaran.id_profile')
        // ->where('id_dosen',$req->id_dosen)
        // ->select('dosen.nama','dosen.topik_bimbingan','dosen.periode_pengajuan')
        // ->first();

      
       return $data;
    }

    public function hapus_topik(Request $req){
        DB::table('topik_penelitian')->where('id','=',$req->id_topik)->delete();
        return back();
    }

    public function tambah_topik(Request $req){
        $topik = new Topik;
        $topik->topik = strtoupper($req->topik);
        $topik->save();
        return back();
    }
}
