<?php

namespace App\Http\Controllers\Backend;

use App\Services\LicenseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LicenseController extends Controller
{
    public function index() {
        return view('backend.pages.license');
    }

    public function register(Request $request) {
        try {
            $licenseService = new LicenseService();
            $result = $licenseService->register($request->code, $request->email);
        } catch(\Exception $e) {
            return back()->withInput()->withErrors($e->getMessage());
        }

        if (!$result->success) {
            $licenseService->save('', '', '');
            return back()->withInput()->withErrors($result->message);
        }

        $licenseService->save($request->code, $request->email, $result->message);

        return redirect()->route('backend.license.index')->with('success', __('Your license is successfully registered!'));
    }
}
