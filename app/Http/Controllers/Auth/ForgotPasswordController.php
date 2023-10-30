<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use App\Mail\SecondEmailVerifyMailManager;
use App\Utility\SmsUtility;
use Mail;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return $request->wantsJson()
            ? new JsonResponse(['message' => "We have send a password reset link to your mail."], 200)
            : back()->with('status', "We have send a password reset link to your mail.");
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                'email' => [trans($response)],
            ]);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => "Somthing went wrong, please try again after sometime"]);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    // public function sendResetLinkEmail(Request $request)
    // {
    //     if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
    //         $user = User::where('email', $request->email)->first();
    //         if ($user != null) {
    //             $user->verification_code = rand(100000, 999999);
    //             $user->save();

    //             $array['view'] = 'emails.verification';
    //             $array['from'] = env('MAIL_FROM_ADDRESS');
    //             $array['subject'] = translate('Password Reset');
    //             $array['content'] = translate('Verification Code is ') . $user->verification_code;

    //             Mail::to($user->email)->queue(new SecondEmailVerifyMailManager($array));

    //             return view('auth.passwords.reset');
    //         } else {
    //             flash(translate('No account exists with this email'))->error();
    //             return back();
    //         }
    //     } else {
    //         $user = User::where('phone', $request->email)->first();
    //         if ($user != null) {
    //             $user->verification_code = rand(100000, 999999);
    //             $user->save();
    //             SmsUtility::password_reset($user);
    //             return view('otp_systems.frontend.auth.passwords.reset_with_phone');
    //         } else {
    //             flash(translate('No account exists with this phone number'))->error();
    //             return back();
    //         }
    //     }
    // }
}
