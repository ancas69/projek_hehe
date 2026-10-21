<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PemetaanGap;
use Session;
use Illuminate\Support\Facades\DB;

//use App\Http\Controllers\MhsController;

class PetaController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function pemetaan($n){
        $kembalian=0;

        $selisihGap = array(0=>5,   1=>4.5,   -1=>4,   2=>3.5,   -2=>3,    3=>2.5,    -3=>2,    4=>1.5,    -4=>1);
        
        foreach ($selisihGap as $selisih => $nilai) {
            if($n == $selisih)
            {
                $kembalian = $nilai;
                break;
            }
        }
        return number_format($kembalian,1);
    }

    public function getDataPemetaanByIdprofmhsAndIdProfile($id_profile_mhs, $id_profile_dosen){
    	return DB::table('pemetaangap')
            ->where([
                ['id_profile_mhs','=',$id_profile_mhs], //,
                ['id_profile','=',$id_profile_dosen],// ], 
            ])->first();
    }

    public function getDataPemetaanByIdPemetaan($id_gap){
    	return PemetaanGap::where('id_gap', $id_gap)->first();
    }

    public function getDataPemetaanByIdProfMhs($id_profile_mhs){
    	return PemetaanGap::where('id_profile_mhs',$id_profile_mhs)->get();
    }

    public function getDataDosenPemetaanByIdProfmhs_tpnotnull($id_profile_mhs)
    {
        $MhsController = app('App\Http\Controllers\MhsController');
        $mhs = $MhsController->find_with_id_user(Session::get('id'));

        $CalonController = app('App\Http\Controllers\CalonController');
        $data_dosen_mhs_ini = $CalonController->getDataCalonByIdMhs($mhs->id_mahasiswa);

    	return DB::table('users')
        ->join('profilesasaran','users.id', '=' ,'profilesasaran.id_user')
        ->join('dosen','dosen.id_user','=','users.id')
        ->join('pemetaangap','pemetaangap.id_profile','=','profilesasaran.id_profile')
        ->select('dosen.*', 'dosen.slot AS slot_dosen','pemetaangap.*')
        ->where([
            ['pemetaangap.id_profile_mhs','=',$id_profile_mhs], 
            ['pemetaangap.topik_penelitian','<>',0],
            ['dosen.periode_pengajuan','<>','tutup'],            
        ])
        ->get();
    }




    public function indexPeta(){
    	
    	$MhsController = app('App\Http\Controllers\MhsController');
    	$ProfileController = app('App\Http\Controllers\ProfileController');

        $mhs = $MhsController->find_with_id_user(Session::get('id'));
        $profmhs = $ProfileController->getOneProfileByIdUser(Session::get('id'));


        if(!isset($profmhs->id_user)){
            return redirect()->route('bobotsubKriteria');
        }        

        // get all data profilesasaran dosen
        $prof = $ProfileController->getAllDataProfileSasaranDosen();
      


        //update all data slot, beban bimbingan dan antrian all dosen
        foreach ($prof as $all_dosen) {
        	$ProfileController->updateAntriandBebanBimbinganDosen($all_dosen->id_user,$all_dosen->id_dosen) ;
        }


                $matching_to_dosen = array();
                foreach ($prof as $profDos) 
                { // $profDos untuk profile semua dosen

                    $id_gap = $this->getDataPemetaanByIdprofmhsAndIdProfile($profmhs['id_profile'],$profDos->id_profile);

                    if(isset($id_gap)){ 
                        $update_gap = $this->getDataPemetaanByIdPemetaan($id_gap->id_gap);
                        //$profile_dosen = $ProfileController->getOneProfileByIdProfile($id_gap->id_profile);
                        $update_gap->pendidikan = $this->pemetaan($profDos->pendidikan-$profmhs['pendidikan']);

                        // menggabungkan tp dosen dan tp mhs
                        $all_topik = array();
                        $i=0;
                        foreach (explode('#',$profDos->topik_penelitian) as $topik_dosen2) {
                            $all_topik[$i] = strtolower($topik_dosen2);
                            $i++;
                        }
                        $all_topik = array_unique($all_topik);
                        foreach (explode('#',$profmhs['topik_penelitian']) as $topik_mhs2) {
                            $all_topik[$i] = strtolower($topik_mhs2);
                            $i++;
                        }
                        
                        // total yang sama
                        $matching = count(array_diff_assoc($all_topik, array_unique($all_topik)));
                        $matching_to_dosen[$profDos->id_dosen] = $matching;

                        // total tp mhs
                        $pengurang = count(explode('#',$profmhs['topik_penelitian']));
                        
                        //strtolower
                        if($matching == 0){
                            $update_gap->topik_penelitian = 0;
                        }
                        else
                        {
                        	$update_gap->topik_penelitian = $this->pemetaan($matching-$pengurang);
                        }
                        $update_gap->bidang_minat = $this->pemetaan($profDos->bidang_minat-$profmhs['bidang_minat']);



                        $update_gap->antri = $this->pemetaan($profDos->antri-$profmhs['antri']);
                        $update_gap->beban_bimbingan = $this->pemetaan($profDos->beban_bimbingan-$profmhs['beban_bimbingan']);
                        $update_gap->progres_bimbingan = $this->pemetaan($profDos->progres_bimbingan-$profmhs['progres_bimbingan']);
                        $update_gap->progres_penelitian = $this->pemetaan($profDos->progres_penelitian-$profmhs['progres_penelitian']);
                        $update_gap->slot = $this->pemetaan($profDos->slot_prof-$profmhs['slot']);
                        $update_gap->save(); 

                    }else{
                        $update_gap = new PemetaanGap;

                        $update_gap->pendidikan = $this->pemetaan($profDos->pendidikan-$profmhs['pendidikan']);
                        $all_topik = array();

                        $i=0;

                        foreach (explode('#',$profDos->topik_penelitian) as $topik_dosen2) {
                            $all_topik[$i] = strtolower($topik_dosen2);
                            $i++;
                        }
                        $all_topik = array_unique($all_topik);
                        foreach (explode('#',$profmhs['topik_penelitian']) as $topik_mhs2) {
                            $all_topik[$i] = strtolower($topik_mhs2);
                            $i++;
                        }
                      	// get jumlah topik yang sama antara dosen dan mhs
                        $matching = count(array_diff_assoc($all_topik, array_unique($all_topik)));

                        $matching_to_dosen[$profDos->id_dosen] = $matching;
                        $pengurang = count(explode('#',$profmhs['topik_penelitian']));
                        //strtolower
                        if($matching == 0){
                            $update_gap->topik_penelitian = 0;
                        }
                        else
                        {
                        	$update_gap->topik_penelitian = $this->pemetaan($matching-$pengurang);
                        }

                        $update_gap->antri = $this->pemetaan($profDos->antri-$profmhs['antri']);
                        $update_gap->beban_bimbingan = $this->pemetaan($profDos->beban_bimbingan-$profmhs['beban_bimbingan']);
                        $update_gap->bidang_minat = $this->pemetaan($profDos->bidang_minat-$profmhs['bidang_minat']);
                        $update_gap->progres_bimbingan = $this->pemetaan($profDos->progres_bimbingan-$profmhs['progres_bimbingan']);
                        $update_gap->progres_penelitian = $this->pemetaan($profDos->progres_penelitian-$profmhs['progres_penelitian']);
                        $update_gap->slot = $this->pemetaan($profDos->slot_prof-$profmhs['slot']);

                        $update_gap->id_profile_mhs = $profmhs['id_profile'];
                        $update_gap->id_profile = $profDos->id_profile;
                        $update_gap->save(); 
                    }
                }
        

        $CalonController = app('App\Http\Controllers\CalonController');
        $data_dosen_mhs_ini = $CalonController->getDataCalonByIdMhs($mhs->id_mahasiswa);

       	$hasilGap = $this->getDataDosenPemetaanByIdProfmhs_tpnotnull($profmhs['id_profile']);



        $profile_dosen = $ProfileController->getDataDosenProfileSasaranByIdProfmhs_tpnotnull($profmhs['id_profile']);


       

        $jk = array(); // jk = jangan_keluar

        foreach ($profile_dosen as $profile) {
            $jk[$profile->id_dosen] = "boleh";
        }

        // seleksi dosen yang boleh keluar dalam perhitungan
        foreach ($data_dosen_mhs_ini as $calon) {
            foreach ($profile_dosen as $profile) {
                if($calon->id_dosen == $profile->id_dosen){
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
      

  
        

        if($hasilGap->count()==0){
            return view('layouts.mhs.pemetaan',['data' => $mhs,'warning' => "ada"]);
        }else{
	        return view('layouts.mhs.pemetaan',['nomer' => $nomer ,'jangan_keluar' => $jk,'matching_to_dosen' => $matching_to_dosen,'data' => $mhs ,'profile' => $profile_dosen,'hasil' => $hasilGap, 'profmhs' => $profmhs]);
    	}
       
    }

}
