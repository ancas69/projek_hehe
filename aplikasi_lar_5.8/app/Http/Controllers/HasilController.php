<?php

namespace App\Http\Controllers;
use App\HasilAkhir;
use Session;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class HasilController extends Controller
{
	public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    public function perhitunganTotal(){ // ncs nsf
    	$MhsController = app('App\Http\Controllers\MhsController');
    	$ProfileController = app('App\Http\Controllers\ProfileController');
    	$PetaController = app('App\Http\Controllers\PetaController');
    	$BaController = app('App\Http\Controllers\BaController');

        $mhs = $MhsController->find_with_id_user(Session::get('id'));
        $profmhs = $ProfileController->getOneProfileByIdUser(Session::get('id'));
       // $petaGap = $PetaController->getDataPemetaanByIdProfMhs($profmhs->id_profile);
     
        // if($petaGap->count() == 0){
        //     return redirect()->route('bobotsubKriteria');
        // }







        $CalonController = app('App\Http\Controllers\CalonController');
        $data_dosen_mhs_ini = $CalonController->getDataCalonByIdMhs($mhs->id_mahasiswa);

        $hasilGap = $PetaController->getDataDosenPemetaanByIdProfmhs_tpnotnull($profmhs->id_profile);


        $jk = array(); // jk = jangan_keluar

        foreach ($hasilGap as $hasil) {
            $jk[$hasil->id_dosen] = "boleh";
        }

        // seleksi dosen yang boleh keluar dalam perhitungan
        foreach ($data_dosen_mhs_ini as $calon) {
            foreach ($hasilGap as $hasil) {
                if($calon->id_dosen == $hasil->id_dosen){
                    $jk[$calon->id_dosen] = "jangan";
                }
            }
        }

        $nomer = array();
        $i = 0;
        foreach ($hasilGap as $key) {
            if( $jk[$key->id_dosen] == "boleh")
            {
                $i = $i + 1;
                $nomer[$key->id_dosen] = $i;
            }
        }







        $ba = $BaController->getOneDataBobotAspek(Session::get('id'));
        if(!isset($hasilGap)){
            return dd($hasilGap);
        }
        
        foreach ($hasilGap as $i => $key) {
           $cekHasil = HasilAkhir::where('id_gap',$key->id_gap)->first();
            if(!isset($cekHasil->id_gap)){
                $h = new HasilAkhir;
                $h->id_gap = $key->id_gap;
            }else{
                $h = HasilAkhir::where('id_gap',$cekHasil->id_gap)->first();
            }

            // NSF & NCF 
            $NSF = (((($key->pendidikan+$key->progres_bimbingan+$key->progres_penelitian+$key->beban_bimbingan)/4)*$ba['NSF'])/100);
            $NCF = (((($key->bidang_minat+$key->topik_penelitian+$key->antri+$key->slot)/4)*$ba['NCF'])/100);

            $totalll = $NCF + $NSF;

            $persentase = array('NCF' => $ba['NCF'], 'NSF' => $ba['NSF'] );

            $h->tot = number_format($totalll,2);

            $h->id_profile_mhs = $profmhs['id_profile'];
            
            $h->save();
        }



        if($hasilGap->count() == 0)
        {
            return view('layouts.mhs.perhitungan-total',['warning' => "ada",'data' => $mhs]);
        }
        else return view('layouts.mhs.perhitungan-total',['persentase' => $persentase, 'nomer' => $nomer ,'jangan_keluar' => $jk ,'data' => $mhs, 'hasil' => $hasilGap, 'bobot' => $ba]);
    }

    public function hasilAkhir(){
    	$MhsController = app('App\Http\Controllers\MhsController');
    	$ProfileController = app('App\Http\Controllers\ProfileController');
    	$CalonController = app('App\Http\Controllers\CalonController');
		$mhs = $MhsController->find_with_id_user(Session::get('id'));
        $profmhs = $ProfileController->getOneProfileByIdUser(Session::get('id'));

        // if(!isset($profmhs->id_user)){
        //     return redirect()->route('bobotsubKriteria');
        // }

        $helper_penampung_id_calon = array();
        $list_req = DB::table('dosen')
                        ->Join('users','users.id','=','dosen.id_user')
                        ->Join('profilesasaran','profilesasaran.id_user','=','users.id')
                        ->Join('pemetaangap','profilesasaran.id_profile','=','pemetaangap.id_profile')
                        ->Join('hasilakhir','hasilakhir.id_gap','=','pemetaangap.id_gap')
                        ->leftJoin('calon','calon.id_dosen','=','dosen.id_dosen')
                        ->where([
                            ['pemetaangap.id_profile_mhs','=',$profmhs['id_profile']],
                            ['pemetaangap.topik_penelitian','<>',0],
                            ['dosen.periode_pengajuan','<>','tutup'],
                        ])
                        ->select('dosen.periode_pengajuan','calon.id_dosen as cid','pemetaangap.*','pemetaangap.slot AS slot_prof','dosen.nama','dosen.slot','dosen.id_dosen','profilesasaran.bidang_minat AS bidang_minat_dosen','hasilakhir.tot',DB::raw('count(case calon.status when "menunggu" then 1 else null end)  peminat'),DB::raw('count(case calon.status when "terima" then 1 else null end) AS slot_taken'))
                        ->groupBy('dosen.id_dosen')
                        ->orderBy('hasilakhir.tot','desc')
                        //->limit()
                        ->get();
        $cekCalon = $CalonController->getDataCalonByIdMhs($mhs['id_mahasiswa']);


        // agar bisa mengedit pada halaman hasil. (kurang digunakan)
        
        
        // end nya


        // kode yang mencegah alternatif yang sudah dipilih muncul lagi
        $CalonController = app('App\Http\Controllers\CalonController');
        $data_dosen_mhs_ini = $CalonController->getDataCalonByIdMhs($mhs->id_mahasiswa);

        $jk = array(); // jk = jangan_keluar
        $nomer = array();

        foreach ($list_req as $hasil) {
            $jk[$hasil->id_dosen] = "boleh";
        }

        // seleksi ,jika dosen terdapat dalam list calon maka tidak boleh keluar
        foreach ($data_dosen_mhs_ini as $calon) {
            foreach ($list_req as $hasil) {
                if($calon->id_dosen == $hasil->id_dosen){
                    $jk[$calon->id_dosen] = "jangan";
                }
            }
        }

        $i = 0;
        foreach ($list_req as $key) {
            if( $jk[$key->id_dosen] == "boleh")
            {
                $i = $i + 1;
                $nomer[$key->id_dosen] = $i;
            }
        }

        // end kodenya


         $slot_taken_this_semester = array();  
         $slot_taken_this_semester = $CalonController->setSlotDosenActivePeriode();


         // kode untuk daftar topik 
        $daftar_topik = DB::table('topik_penelitian')->get();        
        //end nya.

        // cek status pengajuan
        $cek_status_pengajuan = DB::table('calon')
        ->where([
            ['calon.id_mahasiswa','=',$mhs->id_mahasiswa],
        ])->get();

        $memilih="boleh";
        if($cek_status_pengajuan->count() != 0)
        foreach ($cek_status_pengajuan as $cek) {
            if($cek->status == "terima" || $cek->status == "menunggu" || $cek->status == "selesai")
            {
                $memilih = "tidak";
            }
        }
                   
       // dd($slot_taken_this_semester);
        if($list_req->count() == 0)
        {
            return view('layouts.mhs.hasil_akhir',['data' => $mhs,'warning' => "ada"]);     
        }
        else return view('layouts.mhs.hasil_akhir',['memilih' => $memilih,'tp_form' => $daftar_topik,'nomer' => $nomer,'jangan_keluar' => $jk ,'data' => $mhs,'akhir' => $list_req, 'calon' => $cekCalon, 'slot_taken_this_semester' => $slot_taken_this_semester, 'profmhs'=>$profmhs]);
    }
}
