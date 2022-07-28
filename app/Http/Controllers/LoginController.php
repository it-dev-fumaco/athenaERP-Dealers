<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Validator;
use DB;

use App\LdapClasses\adLDAP;

class LoginController extends Controller
{
    public function view_login(){
        return view('login_v2');
    }

    public function login(Request $request){
        try {
            // validate the info, create rules for the inputs
            $rules = array(
                'email' => 'required'
            );

            $validator = Validator::make($request->all(), $rules);

            // if the validator fails, redirect back to the form
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)
                    ->withInput($request->except('password'));
            }else{
                // $adldap = new adLDAP();
                // $authUser = $adldap->user()->authenticate($request->email, $request->password);

                // if($authUser == true){
                    $user = DB::table('tabWarehouse Users')->where('wh_user', $request->email . '@fumaco.local')->first();
                    
                    if ($user) {
                        // attempt to do the login
                        if(Auth::loginUsingId($user->frappe_userid)){
                            DB::table('tabWarehouse Users')->where('name', $user->name)->update(['last_login' => Carbon::now()->toDateTimeString()]);

                            return redirect('/');
                        } 
                    } else {        
                        // validation not successful, send back to form 
                        return redirect()->back()->withErrors('<span class="blink_text">Incorrect Username or Password</span>');
                    }
                // }
                
                return redirect()->back()->withInput($request->except('password'))
                    ->withErrors('<span class="blink_text">Incorrect Username or Password</span>');
            }
        } catch (adLDAPException $e) {
            return $e;
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}


