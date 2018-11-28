<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\Member;
use App\PasswordReset;
class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */

    public function create(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
        ]);
        $member = Member::where('email', $request->email)->first();
        if (!$member)
            return response()->json([
                'message' => 'we cant find a member with that email'
            ], 404);
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $member->email],
            [
                'email' => $member->email,
                'token' => str_random(60),
                'created_at' => now(),
             ]
        );
        if ($member && $passwordReset)
            $member->notify(
                new PasswordResetRequest($passwordReset->token)
            );
        return response()->json([
            'message' => 'We have e-mailed your password reset link!'
        ]);
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }
        return response()->json($passwordReset);
    }
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] member object
     */
    public function resetpassword($token)
    {

        $data = [
            'token' => $token,
        ];
        return view('memberPasswordReset',$data);
    }
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required|string|same:password',
            'token' => 'required|string'
        ]);
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
//            return error;
        $member = Member::where('email', $passwordReset->email)->first();
        if (!$member)
            return response()->json([
                'message' => 'we cant find a user with that email address'
            ], 404);
        $member->password = bcrypt($request->password);
        $member->save();
        $passwordReset->delete();
        $member->notify(new PasswordResetSuccess($passwordReset));
        return response()->json([
            'success' => true,
        ]);
    }
}
