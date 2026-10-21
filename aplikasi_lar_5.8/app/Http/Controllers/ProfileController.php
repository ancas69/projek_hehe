<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProfileSasaran;
use Session;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
	public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function getOneProfileByIdUser($id_user)
    {
    	return ProfileSasaran::where('id_user',$id_user)->first();
    }

    public function getOneProfileByIdProfile($id_profile)
    {
    	return ProfileSasaran::where('id_profile',$id_profile)->first();
    }

    public function getAllDataProfileSasaranDosen()
    {
    	$prof = DB::table('users')
        ->join('profilesasaran','users.id', '=' ,'profilesasaran.id_user')
        ->join('dosen','dosen.id_user','=','users.id')
        ->select('profilesasaran.*','dosen.*','profilesasaran.slot AS slot_prof')->get(); 
        return $prof;
    }

    public function getDataDosenProfileSasaranByIdProfmhs_tpnotnull($id_profile_mhs)
    {

        

        


        $profile_dosen = DB::table('users')
        ->join('profilesasaran','users.id', '=' ,'profilesasaran.id_user')
        ->join('dosen','dosen.id_user','=','users.id')
        ->join('pemetaangap','pemetaangap.id_profile','=','profilesasaran.id_profile')
        ->select('dosen.*','profilesasaran.*','profilesasaran.slot AS slot_prof')
        ->where([
            ['pemetaangap.id_profile_mhs','=',$id_profile_mhs],
            ['pemetaangap.topik_penelitian','<>',0],
            ['dosen.periode_pengajuan','<>','tutup'],
        ])
        ->get();

        

    	return $profile_dosen;
    }

    public function updateAntriandBebanBimbinganDosen($id_user,$id_dosen)
    {
    	
    	$CalonController = app('App\Http\Controllers\CalonController');

        $ps = ProfileSasaran::where('id_user',$id_user)->first();
        
        if(isset($ps)){
            $slot_taken_this_semester = array();  
            $slot_taken_this_semester = $CalonController->setSlotDosenActivePeriode();   
            $data_dosen = $CalonController->getDataSlotDosen($id_dosen);

            if(!isset($data_dosen->idCal)){
                $ps->antri = 1;
                $ps->beban_bimbingan = 1;
                $ps->slot = 5;
            }else{
                $ps->antri = $this->konversi_slot($data_dosen->antri);
                $ps->beban_bimbingan = $this->konversi_slot($data_dosen->beban_bimbingan);
                $ps->slot = $this->konversi_slot(8 - $slot_taken_this_semester[$id_dosen]);
            }
                   
            $ps->save();
        }
    }



    public function konversi_slot($n){
        $kembalian = 6;
        $nilai_konversi = array(8=>5,   7=>5,   6=>4,   5=>4,   4=>3,   3=>3,   2=>2,   1=>2,   0=>1);
        
        foreach ($nilai_konversi as $slot => $nilai) {
            if($slot == $n)
            {
                $kembalian = $nilai;
                break;
            }
        }
        return $kembalian;
    }

    public function simpanBobotsubKriteria(Request $req){
    
        $ps = new ProfileSasaran;
        
        $ps->pendidikan = $req->pendE;
        $ps->bidang_minat = $req->bminE;
        $ps->topik_penelitian = implode('#', ($req->topik));

        $ps->progres_bimbingan = $req->prgbimE ;
        $ps->progres_penelitian = $req->prgpenE ;

        if(Session::get('cekLevel') == 'mahasiswa'){
            $ps->antri = 1;
            $ps->beban_bimbingan = 1;        
            $ps->slot = 5;
        }
        

        $ps->id_user = Session::get('id');
        $ps->save();
        
        //return redirect()->route('processAll');
    }

    public function updateBobotsubKriteria(Request $req){
 

        $ps=$this->getOneProfileByIdUser(Session::get('id'));
    
		$ps->pendidikan = $req->pendE;
        $ps->bidang_minat = $req->bminE;
        $ps->topik_penelitian = implode('#', ($req->topik));

        $ps->progres_bimbingan = $req->prgbimE;
        $ps->progres_penelitian = $req->prgpenE;


        
        if(Session::get('cekLevel') == 'mahasiswa'){
            $ps->antri = 1;
            $ps->beban_bimbingan = 1;        
            $ps->slot = 5;
            // echo "masuk update mahasiswa";
        }
            
        
		$ps->save();

    	//return redirect()->route('processAll');
    }	
}
