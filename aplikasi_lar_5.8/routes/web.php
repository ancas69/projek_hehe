<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(Request $r){
	
	return view('welcome');
})->name('/');

Auth::routes(['verify' => true]);


Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');


Route::get('/goto/Mhs','HomeController@gotoCekMhs')->name('goToMhs');
Route::get('/mhs/create','MhsController@create')->middleware(['cek_level','auth','verified'])->name('mhsCreate');
Route::post('/mhs/save','MhsController@store')->middleware(['cek_level','auth','verified']);
Route::get('/mhs/home','MhsController@index')->middleware(['cek_level','auth','verified'])->name('mhsHome');
Route::get('/mhs/update','MhsController@update_form')->middleware(['cek_level','auth','verified'])->name('mhsUpdate');
Route::post('/mhs/updateSave','MhsController@update_save')->middleware(['cek_level','auth','verified']);

Route::get('/goto/Dsn','HomeController@gotoCekDsn')->name('goToDsn');
Route::get('/dsn/create','DsnController@create')->middleware(['cek_level','auth','verified'])->name('dsnCreate');
Route::post('/dsn/save','DsnController@store')->middleware(['cek_level','auth','verified'])->name('dsnSave');
Route::get('/dsn/home','DsnController@index')->middleware(['cek_level','auth','verified'])->name('dsnHome');
Route::get('/dsn/update','DsnController@update_form')->middleware(['cek_level','auth','verified'])->name('dsnUpdate');
Route::post('/dsn/updateSave','DsnController@update_save')->middleware(['cek_level','auth','verified'])->name('saveEdit');

Route::get('/mhs/bobotkriteria','BaController@pembobotan')->middleware(['cek_level','auth','verified'])->name('pembobotan');
Route::post('/mhs/savebobotkriteria','BaController@simpanPersentaseKriteria')->middleware(['cek_level','auth','verified'])->name('simpanPersentaseKriteria');
Route::post('/mhs/updatebobotkriteria','BaController@updatePersentaseKriteria')->middleware(['cek_level','auth','verified'])->name('updatePersentaseKriteria');


Route::post('/mhs/savebobotsubkriteria','ProfileController@simpanBobotsubKriteria')->middleware(['cek_level','auth','verified'])->name('simpanBobotsubKriteria');
Route::post('/mhs/updatebobotsubkriteria','ProfileController@updateBobotsubKriteria')->middleware(['cek_level','auth','verified'])->name('updateBobotsubKriteria');

Route::get('/mhs/bobotsubKriteria','BaController@bobotsubKriteria')->middleware(['cek_level','auth','verified'])->name('bobotsubKriteria');




Route::get('/mhs/pemetaan','PetaController@indexPeta')->middleware(['cek_level','auth','verified'])->name('peta');
Route::get('/mhs/hasilTotal','HasilController@perhitunganTotal')->middleware(['cek_level','auth','verified'])->name('perhitunganTotal');
Route::get('/mhs/hasilAkhir','HasilController@hasilAkhir')->middleware(['cek_level','auth','verified'])->name('hasilAkhir');
Route::post('/mhs/svcalon','CalonController@saveCalon')->middleware(['cek_level','auth','verified'])->name('tambahCalon');
Route::post('/mhs/dlcalon','CalonController@deleteCalon')->middleware(['cek_level','auth','verified'])->name('hapusCalon');
Route::post('/mhs/svfcalon','CalonController@saveFileCalon')->middleware(['cek_level','auth','verified'])->name('tambahFile');
Route::get('/mhs/upload/{id}','CalonController@upload_data')->middleware(['cek_level','auth','verified'])->name('uploadData');

Route::post('/mhs/donlot','CalonController@downloadFile')->middleware(['cek_level','auth','verified'])->name('donlot');
Route::post('/mhs/hapus','CalonController@hapusFile')->middleware(['cek_level','auth','verified'])->name('hapus');


Route::post('/dsn/ucalon','CalonController@updateStatus')->middleware(['cek_level','auth','verified'])->name('updateStatusCalon');

//Route::post('/dsn/sl','DsnController@saveLink')->middleware(['cek_level','auth','verified'])->name('saveLink');

Route::get('/mhs/dosen','DsnController@showDosen')->middleware(['cek_level','auth','verified'])->name('Dosen');
Route::get('/mhs/dosen{id}','DsnController@showOneDosen')->middleware(['cek_level','auth','verified']);



Route::post('/dsn/hapuscek','CalonController@deleteCekCalonMhs')->middleware(['cek_level','auth','verified'])->name('hapusCek');
Route::post('/dsn/updatecek','CalonController@updateCekCalonMhs')->middleware(['cek_level','auth','verified'])->name('updateCek');

Route::get('/dsn/detail/mhs/{id}','DsnController@detailMhs')->middleware(['cek_level','auth','verified'])->name('detailMahasiswa');
Route::get('/dsn/mahasiswa/{stat}','DsnController@daftarMahasiswa')->middleware(['cek_level','auth','verified'])->name('daftarMahasiswa');


Route::post('/mhs/perbarui','CalonController@perbaharuiPengajuan')->middleware(['cek_level','auth','verified'])->name('perbaharuiPengajuan');

Route::post('/mhs/processing','MhsController@processAll')->middleware(['cek_level','auth','verified'])->name('processAll');

Route::post('/mhs/sending','DsnController@getOneDataDosenJson')->middleware(['cek_level','auth','verified'])->name('getOneDataDosenJson');


Route::get('/goto/Adn','HomeController@gotoCekAdn')->name('goToAdn');
Route::get('/admin/home','AdnController@index')->middleware(['cek_level','auth','verified'])->name('adnHome');

Route::get('/admin/pengajuan','AdnController@buka_pengajuan')->middleware(['cek_level','auth','verified'])->name('subKriteriaDosen');
Route::post('/admin/pengajuan_save','AdnController@pengajuanPenelitian')->middleware(['cek_level','auth','verified'])->name('thePengajuan');



Route::get('/admin/data_dosen','AdnController@halaman_data_dosen')->middleware(['cek_level','auth','verified'])->name('dataDosen');

Route::post('/admin/simpansatudata','AdnController@edit_periode_satu_dosen')->middleware(['cek_level','auth','verified'])->name('simpanDataSatuPeriode');

Route::post('/dosen/hapus_topik','DsnController@hapus_topik')->middleware(['cek_level','auth','verified'])->name('hapusTopik');

Route::post('/dosen/tambah_topik','DsnController@tambah_topik')->middleware(['cek_level','auth','verified'])->name('tambahTopik');


Route::get('spk/test','MhsController@test');
