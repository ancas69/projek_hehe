<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // public function login(Request $req){
    //     $req->session()->put('key',$req['email']);
    //     return redirect()->route('home');
    // }
    public function login(Request $req){
        $input = $req->all();
        $this->validate($req,[
            'email' => 'required|ends_with:uad.ac.id', //|ends_with:uad.ac.id
            'password' => 'required'
        ]);


        if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password']))){
            if(auth()->user()->level == "admin"){
                $req->session()->forget('cek_registrasi');
                $req->session()->put('cekLevel','admin');
                return redirect()->route('goToAdn');
            }else{
                $user_helper = User::select('id')->where('email', $input['email'])->get();
                // $req->session()->put('email',$input['email']);
                $req->session()->put('id',$user_helper[0]->id);
                
                if(auth()->user()->level == "mahasiswa"){
                    $req->session()->forget('cek_registrasi');
                    $req->session()->put('cekLevel','mahasiswa');
                    return redirect()->route('goToMhs');
                }
                else if(auth()->user()->level == "dosen"){
                    $req->session()->forget('cek_registrasi');
                    $req->session()->put('cekLevel','dosen');
                    return redirect()->route('goToDsn');
                }
                else return redirect()->route('/');
            }
        }else{
            return redirect()->route('login')->withErrors(['error' => 'data yang anda masukkan tidak sesuai dengan data sistem']);
        }
    }

    public function logout(Request $req){
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        // $req->session()->forget('key');
        $req->session()->forget('cekLevel');
        $req->session()->forget('id');
        return redirect()->route('/');
    }
}
