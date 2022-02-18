<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use DB;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $allow = false;
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'queue' => 'required:number'
        ]);

        $user = User::where("email", $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        foreach ($user->queues as $queue) {
            if ($queue->id == $request->queue) {
                $allow = true;
                break;
            }
        }

        if(!$allow){
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.2'],
            ]);
        }

        return response()->json(['user' => $user, 'token' => $user->createToken($user->name)->plainTextToken, ]);
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['user' => $user, 'token' => $user->createToken($user->name)->plainTextToken]);
    }
    public function profile(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }
    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json(['token' => $user->createToken($user->name)->plainTextToken]);
    }

    public function logout(Request $request){
        // $request->user()->currentAccessToken()->delete();
        auth()->user()->tokens()->delete();

        return ['logout' => 'success'];
    }

}
