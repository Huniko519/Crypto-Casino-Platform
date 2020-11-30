<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\Frontend\Disable2Fa;
use App\Http\Requests\Frontend\Enable2Fa;
use App\Http\Requests\Frontend\VerifyTotp;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PragmaRX\Google2FA\Google2FA;

class SecurityController extends Controller
{
    public function index()
    {
        return view('frontend.pages.security.index');
    }

    public function enable2Fa(Request $request)
    {
        $user = $request->user();

        if ($user->totp_secret)
            return redirect()->route('frontend.security.index');

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey(32);
        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );
        $qrCodeWriter = new Writer($renderer);
        $qrCodeSvg = $qrCodeWriter->writeString($google2fa->getQRCodeUrl(
            __('Crypto Casino'),
            $user->email,
            $secret
        ));

        return view('frontend.pages.security.2fa-enable', [
            'qr'        => $qrCodeSvg,
            'secret'    => $secret
        ]);
    }

    public function enable2FaComplete(Enable2Fa $request)
    {
        // all validations are already passed
        $request->session()->put('2fa_passed', 1);
        // save TOTP secret
        $user = $request->user();
        $user->totp_secret = $request->secret;
        $user->save();

        return redirect()->route('frontend.security.index')->with('success', __('Two-factor authentication is successfully enabled'));
    }

    public function disable2Fa(Request $request)
    {
        $user = $request->user();

        if (!$user->totp_secret)
            return redirect()->route('frontend.security.index');

        return view('frontend.pages.security.2fa-disable');
    }

    public function disable2FaComplete(Disable2Fa $request)
    {
        // all validations are already passed
        $user = $request->user();
        $user->totp_secret = NULL;
        $user->save();

        return redirect()->route('frontend.security.index')->with('success', __('Two-factor authentication is successfully disabled'));
    }

    public function verifyTotp(Request $request)
    {
        return view('auth.2fa');
    }

    public function verifyTotpComplete(VerifyTotp $request)
    {
        $request->session()->put('2fa_passed', 1);
        return redirect()->intended('/leaderboard');
    }
}
