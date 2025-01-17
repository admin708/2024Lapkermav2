<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use Session;
use App\Models\User;
use nusoap_client;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) { // true sekalian session field di users nanti bisa dipanggil via Auth
            //Login Success
            return redirect()->route('index');
        }
        return view('MasterApp.login');
    }

    public function pimpinan()
    {
        return view('MasterApp.Dashboard');
    }

    public function test()
    {
        $username = '198801072018015001';
        $password = 'unhas2015';
        $client = new nusoap_client("http://apps.unhas.ac.id/nusoap/serviceApps.php");
        $client->setCredentials("informatikaUNHAS", "createdbyMe", "basic");
        $result = $client->call("login2", array("username" => $username, "password" => md5($password)));
        $result = json_decode($result);
        dd($result);
    }

    public function verifyOtp(Request $request)
    {
        // Validate the OTP input
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        // Retrieve the OTP and its expiry from session
        $storedOtp = session('otp');
        $otpExpiry = session('otp_expiry');

        if ($storedOtp == $request->otp && now()->lessThanOrEqualTo($otpExpiry)) {
            // OTP is valid, register the user
            $newUser = new User();
            $newUser->name = session('name');
            $newUser->email = session('email');
            $newUser->password = session('password');
            $newUser->fakultas_id = session('fakultas_id');
            $newUser->prodi_id = session('prodi_id');
            $newUser->request = session('request');
            $newUser->role_id = session('role_id');
            $newUser->request = session("request");
            $newUser->prodi_id = session('prodi');
            $newUser->fakultas_id = session('fakultas');
            $newUser->save();

            // Clear session after successful registration
            session()->forget(['otp', 'otp_expiry', 'name', 'email', 'password', 'fakultas_id', 'prodi_id', 'request', 'role_id']);

            return redirect()->back()->with('success', 'Account has been registered, please contact our team for activation');
        } else {
            // OTP is invalid or expired
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }
    }

    public function login(Request $request)
    {
        $username = $request->input('email');
        $password = $request->input('password');
        $client = new nusoap_client("http://apps.unhas.ac.id/nusoap/serviceApps.php");
        $client->setCredentials("informatikaUNHAS", "createdbyMe", "basic");
        $result = $client->call("login2", array("username" => $username, "password" => md5($password)));
        $result = json_decode($result);

        # Checking as apps user
        if ($result != NULL) {
            $simpan = User::firstOrCreate([
                'email' => $result->userAccount,
            ], [
                'name' => $result->userNama,
                'password' => bcrypt($password),
            ]);

            // if ($simpan->request == 0) {
            //     return redirect()->back()->withInput()->withErrors(['pesan' => 'Your account has not been activated.']);
            // }

            Auth::login($simpan);
            if (auth()->user()->role_id == null) {
                return redirect()->route('request_role');
            } else {
                // if (auth()->user()->id == 3 || auth()->user()->id == 379 || auth()->user()->id == 382) {
                return redirect()->route('index');
                // } else {
                //     Auth::logout();
                //     return redirect()->route('index');
                // }               

            }
        } else {
            #checking local db
            Auth::attempt(['email' => $username, 'password' => $password]);
            if (Auth::check()) {
                // if (auth()->user()->request == 0) {
                //     Auth::logout();
                //     return redirect()->back()->withErrors(['pesan' => 'Your account has not been activated.']);
                // }
                if (auth()->user()->role_id == null) {
                    return redirect()->route('request_role');
                } else {
                    // if (auth()->user()->id == 3 || auth()->user()->id == 379 || auth()->user()->id == 382) {
                    return redirect()->route('index');
                    // } else {
                    //     Auth::logout();
                    //     return redirect()->route('index');
                    // }
                }
            } else {
                return redirect()->back()->withInput()->withErrors(['pesan' => 'wrong password or username']);
            }
        }
    }
    public function master()
    {
        return view('MasterApp.ChangeApp');
    }
    public function map()
    {
        return view('MasterApp.map');
    }
    public function search()
    {
        $results = session()->get('search_results');

        if ($results) {
            return view('search', ['results' => $results]);
        } else {
            return redirect()->route('home');
        }
    }
    public function logout()
    {
        Auth::logout(); // menghapus session yang aktif
        return redirect()->route('home');
    }
}
