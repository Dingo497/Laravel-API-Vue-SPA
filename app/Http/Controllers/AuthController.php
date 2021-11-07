<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\CustomMethods;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
  use CustomMethods;

  /**
   * Register new user with token.
   *
   * @return \Illuminate\Http\Response
   */
  public function register(Request $request)
  {
    $validatedData = Validator::make($request->all(),[
      'name'     => 'required|string|max:255|min:3',
      'email'    => 'required|string|email|max:255|min:6|unique:users',
      'password' => 'required|string|min:8',
    ]);

    if($validatedData->fails()) {
      return $this->sendErrorMessage('Your registration failed');
    }

    $user = User::create([
      'name'     => $request['name'],
      'email'    => $request['email'],
      'password' => Hash::make($request['password']),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;
    $data = [
      'access_token' => $token,
      'token_type' => 'Bearer',
    ];

    return $this->sendSuccessMessage($data, 'Thank you for your registration', count($data));
  }



  /**
   * Login user with token.
   *
   * @return \Illuminate\Http\Response
   */
  public function login(Request $request)
  {
    if (!Auth::attempt($request->only('email', 'password'))) {
      return $this->sendErrorMessage('Your login failed');
    }

    $user = User::where('email', $request['email'])->firstOrFail();

    $user->tokens()->where('tokenable_id', $user->id)->delete();
    $token = $user->createToken('auth_token')->plainTextToken;
    $data = [
      'access_token' => $token,
      'token_type' => 'Bearer',
    ];

    return $this->sendSuccessMessage($data, 'Welcome back', count($data));
  }



  /**
   * Logout user with token.
   *
   * @return \Illuminate\Http\Response
   */
  public function logout(Request $request)
  {
    Auth::logout();
    // $request->session()->invalidate();
    // $request->session()->regenerateToken();

    return $this->sendSuccessMessage(null, 'Bye, bye maaaaaaaan', null);
  }
  


  /**
   * Return user if he is logged in.
   *
   * @return \Illuminate\Http\Response
   */
  public function profile(Request $request)
  {
    $user = $request->user()->toArray();
    return $this->sendSuccessMessage($user, 'Success', count($user));
  }
  
}
