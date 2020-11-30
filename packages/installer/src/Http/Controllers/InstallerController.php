<?php

namespace Packages\Installer\Http\Controllers;

use App\Models\User;
use App\Services\AccountService;
use App\Services\DotEnvService;
use App\Services\LicenseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Packages\Installer\Services\InstallerService;

class InstallerController extends Controller
{
    private $env;
    private $dotEnvService;

    public function __construct(Request $request)
    {
        $this->dotEnvService = new DotEnvService();

        if (!$this->env = $this->dotEnvService->load()) {
            if (!$this->dotEnvService->createFromDefault())
                die('Can not create env file');
        }

        // update the default APP_KEY in .env
        if (!env('APP_KEY')) {
            // it's important to use --force flag in all artisan commands when APP_ENV=production
            Artisan::call('key:generate', ['--force' => TRUE]);
            // create public/storage --> storage/app/public symbolic link
            Artisan::call('storage:link');
        }
    }

    /**
     * Display installation step form
     *
     * @param Request $request
     * @param $step
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function view(Request $request, $step)
    {
        // don't allow direct access to routes via GET, only through redirect
        if ($step > 1 && !$request->session()->has('app_redirect')) {
            return redirect()->route('install.view', ['step' => 1]);
        }

        return view('installer::pages.step' . $step, ['step' => $step]);
    }

    /**
     * Process form on each step
     *
     * @param Request $request
     * @param $step
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request, $step)
    {
        return call_user_func_array([$this, 'step' . $step], [$request, $step]);
    }

    public function step1(Request $request, $step)
    {
        try {
            $licenseService = new LicenseService();
            $result = $licenseService->register($request->code, $request->email);
        } catch(\Exception $e) {
            return back()->withInput()->withErrors($e->getMessage())->with('app_redirect', TRUE);
        }

        if (!$result->success) {
            return back()->withInput()->withErrors($result->message)->with('app_redirect', TRUE);
        }

        $licenseService->save($request->code, $request->email, $result->message);

        return redirect()->route('install.view', ['step' => $step + 1])->with('app_redirect', TRUE);
    }

    public function step2(Request $request, $step)
    {
        try {
            // check if DB connection can be created
            new \pdo(
                'mysql:host='.$request->host.';port='.$request->port.';dbname='.$request->name,
                $request->username,
                $request->password,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );

            // save DB settings
            $this->env['DB_HOST'] = $request->host;
            $this->env['DB_PORT'] = $request->port;
            $this->env['DB_DATABASE'] = $request->name;
            $this->env['DB_USERNAME'] = $request->username;
            $this->env['DB_PASSWORD'] = $request->password;
            $this->dotEnvService->save($this->env);

            // set current DB connection settings on the fly, otherwise Laravel doesn't recognize them
            config(['database.connections.mysql.host' => $request->host]);
            config(['database.connections.mysql.port' => $request->port]);
            config(['database.connections.mysql.database' => $request->name]);
            config(['database.connections.mysql.username' => $request->username]);
            config(['database.connections.mysql.password' => $request->password]);

            // it's important to purge current DB connection
            DB::purge('mysql');

            set_time_limit(1800);
            // run migrations and seeds
            Artisan::call('migrate:fresh', [
                '--force' => TRUE,
                '--seed' => TRUE,
            ]);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors($e->getMessage())->with('app_redirect', TRUE);
        }

        return redirect()->route('install.view', ['step' => $step + 1])->with('app_redirect', TRUE);
    }

    public function step3(Request $request, $step)
    {
        try {
            // create user
            $user = User::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'role'              => User::ROLE_ADMIN,
                'status'            => User::STATUS_ACTIVE,
                'last_login_at'     => Carbon::now(),
                'last_login_from'   => request()->ip(),
                'password'          => Hash::make($request->password),
                'email_verified_at' => Carbon::now(),
            ]);
            // create account
            AccountService::create($user);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors($e->getMessage())->with('app_redirect', TRUE);
        }

        // unset debug mode
        unset($this->env['APP_DEBUG']);
        unset($this->env['APP_LOG_LEVEL']);
        $this->dotEnvService->save($this->env);

        // mark isntallation as completed
        touch(storage_path('installed'));

        try {
            $installerService = new InstallerService();
            $installerService->register();
        } catch (\Exception $e) {
            //
        }

        return redirect()->route('install.view', ['step' => $step + 1])->with('app_redirect', TRUE);
    }
}
