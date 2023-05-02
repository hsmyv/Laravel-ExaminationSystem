<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\studentRegisterRequest;
use App\Models\Exam;
use App\Models\PasswordReset;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function loadRegister()
    {
        if (auth()->user() && Auth()->user()->is_admin == 1) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user() && Auth()->user()->is_admin == 0) {
            return redirect()->route('student.dashboard');
        }

        return view('layout.register');
    }

    public function studentRegister(studentRegisterRequest $request)
    {
        $user = User::create($request->validated());
        return redirect('/')->with('success', 'Your Registration has been successfully');
    }

    public function loadLogin()
    {
        if (auth()->user() && Auth()->user()->is_admin == 1) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user() && Auth()->user()->is_admin == 0) {
            return redirect()->route('student.dashboard');
        }
        return view('layout.login');
    }

    public function userLogin(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            Session()->regenerate();
            if (Auth::user()->is_admin == 1) {
                return redirect()->route('admin.dashboard')->with('success', 'You are now logged in!');
            } else {
                return redirect()->route('student.dashboard')->with('success', 'You are now logged in!');
            }
        } else {
            throw ValidationException::withMessages([
                'email' => 'Invalid Credentials'
            ]);
        }
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/')->with('success', 'You have been logout');
    }

    public function loadDashboard()
    {
        $exams = Exam::with('subjects')->orderBy('date')->get();

        return view('student.dashboard', ['exams' => $exams]);
    }

    public function adminDashboard()
    {
        $subjects = Subject::all();

        return view('admin.dashboard', compact('subjects'));
    }

    public function forgotPassword(Request $request)
    {
        return view('layout.forgot-password');
    }

    public function forgetPassword(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->get();

            if (count($user) > 0) {
                $token = Str::random(40);
                $domain = URL::to('/');
                $url = $domain . '/reset-password?token=' . $token;

                $data['url'] = $url;
                $data['email'] = $request->email;
                $data['title'] = 'Password Reset';
                $data['body'] = 'Please click on below link to reset your password.';

                Mail::send('mail.forgetPasswordMail', ['data' => $data], function($message) use ($data){
                    $message->to($data['email'])->subject($data['title']);
                });

                $dateTime = Carbon::now()->format('Y-m-d H:i:s');
                PasswordReset::updateOrCreate(
                    ['email' => $request->email],

                    ['email' => $request->email,
                    'token'  => $token,
                    'created_at' => $dateTime
                ]
                );
                return back()->with('success', 'Please check your mail to reset your password');

            } else {
                return back()->with('error', 'Email is not exists!');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function resetPasswordLoad(Request $request)
    {
        $resetData = PasswordReset::where('token', $request->token)->get();

        if(isset($request->token) && count($resetData) > 0)
        {
            $user = User::where('email', $resetData[0]['email'])->get();

            return view('layout.resetPassword', compact('user'));
        }else{
            return view('404');
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::find($request->id);
        $user->password = $request->password;
        $user->save();

        PasswordReset::where('email', $user->email)->delete();

        return "<h2>Your password has been reset successfully</h2>";
    }
}
