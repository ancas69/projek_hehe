<?php

namespace App\Http\Controllers;
use App\Calon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Session;

class CalonController extends Controller
{
	public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function getOneDataSlotThisSmester($id_dosen, $periode_sekarang){
    	return DB::table('dosen')
        ->join('calon','dosen.id_dosen','=','calon.id_dosen')
        ->where([
            ['calon.id_dosen', '=', $id_dosen],
            ['calon.periode', '=', $periode_sekarang],
        ])
        ->select(DB::raw('count(case calon.status when "terima" then 1 else null end) AS slot_taken_this_smester'))
        ->groupBy('dosen.id_dosen')
        ->first();
    }
    public function getDataCalonByIdDosenAndStatus($id_dosen, $periode_pengajuan, $status){
    	$kembalian = DB::table('calon')
            ->where([
                ['calon.id_dosen','=',$id_dosen],
                ['calon.periode','=',$periode_pengajuan],
                ['calon.status','=',$status],
            ])
            ->select('calon.*')->get();

        return $kembalian;
    }

    public function getDataSlotDosen($id_dosen)
    {
    	return DB::table('dosen')->join('calon','dosen.id_dosen','=','calon.id_dosen')
    	->where([
            ['calon.id_dosen', '=', $id_dosen],
        ])
        ->select('dosen.*','calon.id_calon AS idCal','calon.periode',DB::raw('count(case calon.status when "menunggu" then 1 else null end)  antri'),DB::raw('count(case calon.status when "terima" then 1 else null end) AS beban_bimbingan'))
        ->groupBy('dosen.id_dosen')
        ->first();
    }

    public function getOneDataCalonDosen($id_dsn)
    {
    	return DB::table('dosen')
        ->leftjoin('calon','dosen.id_dosen','=','calon.id_dosen')
        ->select('dosen.*','calon.id_dosen as cid',DB::raw('count(case calon.status when "terima" then 1 else null end) AS slot_taken'))
        ->where('dosen.id_dosen',$id_dsn)
        ->groupBy('dosen.id_dosen')
        ->first();
    }

    public function getAllDataDosenCalon()
    {
    	return DB::table('dosen')
        ->Join('users','users.id','=','dosen.id_user')
        ->join('profilesasaran','profilesasaran.id_user','=','users.id')
        ->leftJoin('calon','calon.id_dosen','=','dosen.id_dosen')
        ->select('profilesasaran.bidang_minat','dosen.*',DB::raw('count(case calon.status when "menunggu" then 1 else null end) AS peminat'),DB::raw('count(case calon.status when "terima" then 1 else null end) AS slot_taken'))
        ->groupBy('dosen.id_dosen')
        ->get(); 
    }

    public function cekMhsinListCalonDosen($id_dsn_in_tabel_calon,$id_mhs){
    	return DB::table('calon')
    	->where('id_dosen',$id_dsn_in_tabel_calon)
    	->select(DB::raw('count(case calon.id_mahasiswa when '.$id_mhs.' then 1 else null end) as cek'))
    	->groupBy('calon.id_dosen')
    	->first();
    }

    public function setSlotDosenActivePeriode(){
    	$DsnController = app('App\Http\Controllers\DsnController');
    	$DS = array();
    	$data_dosen = $DsnController->getAllDosen();
           
		foreach ( $data_dosen as $dosen2) {
      
           // echo $dosen2->id_dosen." ==> ".$dosen2->periode_pengajuan."<br>";

            $data_calon = $this->getDataCalonByIdDosenAndStatus($dosen2->id_dosen,$dosen2->periode_pengajuan,"terima");
            
            // echo "<br><br>";
            $DS[$dosen2->id_dosen] = $data_calon->count();
            
        }
      
        return $DS;
    }

    public function getDataCalonByIdMhs($id_mahasiswa){
    	return Calon::where('id_mahasiswa',$id_mahasiswa)->get();
    }

    public function getDataCalonByIdDosen($id_dosen){
    	return Calon::where('id_dosen',$id_dosen)->get();
    }

    public function getDataCalonByIdcalon($id_calon){
    	return Calon::where('id_calon',$id_calon)->first();
    }

    public function getDataCalonMahasiswaByIdCalon($id_calon){
    	return DB::table('calon')
        ->join('mahasiswa','calon.id_mahasiswa','=','mahasiswa.id_mahasiswa')
        ->where('calon.id_calon',$id_calon)
        ->first();
    }

    public function saveCalon(Request $req){
    	$this->validate($req,[
            'file_resume' => 'max:1000|mimes:pdf',
            'judul_penelitian' => 'required',
        ]);

        $cal = new Calon;

        $cal->id_dosen = $req->id_dosen;
        $cal->topik = $req->topik;
        $cal->id_mahasiswa = $req->id_mahasiswa;
        $cal->status = "menunggu";
      
        	$nama_file = $req->file_resume->getClientOriginalName();
        	$ext_file = $req->file_resume->getClientOriginalExtension();
        
        $cal->file_syarat = file_get_contents($req->file_resume);
        $cal->nama_file = $nama_file;
        $cal->ext_file = $ext_file;
        
        $cal->periode = $req->periode;
        $cal->judul_penelitian = $req->judul_penelitian;

        $cal->save();
        return redirect()->route('home');
    }



    public function deleteCalon(Request $req){
        Calon::where('id_calon',$req->id_calon)->delete();
         return redirect()->route('home');
    }

    public function upload_data($id){
        $MhsController = app('App\Http\Controllers\MhsController');
        $data_mhs = $MhsController->find_with_id_user(Session::get('id'));
        $data_calon = DB::table('calon')->join('dosen','calon.id_dosen','=','dosen.id_dosen')->where('calon.id_calon',$id)->select('dosen.*','calon.*')->first();

        $nama_topik = array();
        $i=0;
        foreach(explode('#',$data_calon->topik_bimbingan) as $topik){
            $nama_topik[$i] = DB::table('topik_penelitian')->select('topik')->where('id',$topik)->first()->topik;
            $i = $i +1; 
        }
        return view('layouts.mhs.upload_data',['data' => $data_mhs , 'data_calon' => $data_calon, 'nama_tpk' => $nama_topik ]);
    }

    public function saveFileCalon(Request $req){

        $this->validate($req,[
            'file_data' => 'max:1000|mimes:pdf',
            'judul_penelitian' => 'required',
        ]);
         //dd($req->id_calon);
        // dd($req->file_data);
        $cal = $this->getDataCalonByIdcalon($req->id_calon);
        if($req->file_data != null){
            $nama_file = $req->file_data->getClientOriginalName();
            $ext_file = $req->file_data->getClientOriginalExtension();
            
            $cal->file_syarat = file_get_contents($req->file_data);
            $cal->nama_file = $nama_file;
            $cal->ext_file = $ext_file;
        }
        $cal->topik = $req->topik;
        $cal->judul_penelitian = $req->judul_penelitian;
        $cal->save();
        return back();
    }

    public function downloadFile(Request $req){
        $decoded = base64_decode($req->unduh);
        $file = $req->id_m;
        file_put_contents($file, $decoded);


        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }   
        //return $req->unduh;
    }

    public function hapusFile(Request $req){
        $hapus = $this->getDataCalonByIdcalon($req->id_calon);//Calon::where('id_calon',$req->id_calon)->first();
        $hapus->file_syarat = null;
        $hapus->nama_file = null;
        $hapus->ext_file = null;
        $hapus->save();
        return back();
    }

    public function perbaharuiPengajuan(Request $req)
    {
        $cal = $this->getDataCalonByIdcalon($req->id_calon);
        $cal->periode = $req->periode;
        $cal->save();
        return back();
    }
    


    public function updateStatus(Request $req){
    	$DsnController = app('App\Http\Controllers\DsnController');
    	$dosen = $DsnController->getOneDataDosenByIdUser(Session::get('id'));
        $cal = $this->getDataCalonByIdcalon($req->id_calon);
        // $cal->periode = $dosen->periode_pengajuan;
        $cal->status = $req->stat;       
        $cal->save();

        return back();
    }

    // public function deleteCekCalonMhs(Request $r)
    // {
        
    //     if(!isset($r->cek_id_calon))
    //     {
    //         return redirect()->route('home');
    //     }

    //     $a = array_count_values($r->cek_id_calon);
    //     $deleted_id = array();
    //     $i = 0;
    //     foreach ($a as $key => $value) {
    //         if($value % 2 == 1){
    //             $deleted_id[$i] = $key;
    //             $i++;
    //         }
    //     }
    //     foreach ($deleted_id as $id_calon) {
    //         $calon = Calon::where('id_calon',$id_calon)->delete();
    //     }

    //     return back();
    // }

    public function updateCekCalonMhs(Request $r)
    {
        
        //dd($r->cek_id_calon);
        // dd($update_id);

        $DsnController = app('App\Http\Controllers\DsnController');
    	$dosen = $DsnController->getOneDataDosenByIdUser(Session::get('id'));

        //dd($r->stat);
        
        foreach ($r->cek_id_calon as $id_calon) {
        	if($r->stat == "hapus"){
                //dd("terhapus ssss");
        		Calon::where('id_calon',$id_calon)->delete();
        	}
        	else{
	            $calon = $this->getDataCalonByIdcalon($id_calon);
	            // $calon->periode = $dosen->periode_pengajuan;
	            $calon->status = $r->stat;
	            $calon->save();
        	}
        }        
         return back();
    }

    public function getDataCalonMhsByIdDosenandStatus($id_dosen, $stat){
    	return DB::table('calon')
        ->join('mahasiswa','calon.id_mahasiswa','=','mahasiswa.id_mahasiswa')
        ->where([
            ['calon.id_dosen','=',$id_dosen],
            ['calon.status','=',$stat],
        ])
        ->get();
    }

    public function getDataCalonDsnByIdDosenandPeriode($id_dosen, $period){
    	return DB::table('calon')
        ->where([
            ['calon.id_dosen','=',$id_dosen],
            ['calon.periode','=',$period],
        ])
        ->groupBy('periode')
        ->select('calon.periode')
        ->first();
    }

    public function getDataCalonAllPeriodeDosenByIdDosen($id_dosen){
    	return DB::table('calon')
        ->where([
            ['calon.id_dosen','=',$id_dosen],
        ])
        ->groupBy('periode')
        ->select('calon.periode',DB::raw('count(calon.id_mahasiswa) AS jumlah_tiap_semester'))
        ->get();
    }

    public function getDataCountCalonDsnByIdDosenandPeriode($id_dosen, $period){
        return DB::table('calon')
        ->where([
            ['calon.id_dosen','=',$id_dosen],
            ['calon.periode','=',$period],
        ])
        ->groupBy('periode')
        ->select('calon.periode')
        ->count();
    }


}
