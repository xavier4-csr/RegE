<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AiAgentController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// ── Public routes ──────────────────────────────────────────────────────────

Route::get('/', fn() => view('home'))->name('home');
Route::get('/contact', fn() => view('contact'))->name('contact');

// Business directory (public)
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{company:slug}', [CompanyController::class, 'show'])->name('companies.show');

// ── Auth routes ────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Email verification
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', fn() => view('auth.verify-email'))
        ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('dashboard');
    })->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');
});

// Password reset
Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');

// ── Authenticated routes ───────────────────────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile',            [ProfileController::class, 'show'])->name('profile');
    Route::patch('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo',     [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Company CRUD
    Route::resource('my-companies', CompanyController::class)->except(['index', 'show']);

    // Compliance
    Route::get('/compliance/{company}',                      [ComplianceController::class, 'index'])->name('compliance.index');
    Route::patch('/compliance/progress/{progress}/complete', [ComplianceController::class, 'markComplete'])->name('compliance.complete');
    Route::patch('/compliance/progress/{progress}/skip',     [ComplianceController::class, 'markSkipped'])->name('compliance.skip');

    // Payments
    Route::post('/payments/mpesa', [PaymentController::class, 'initiateMpesa'])->name('payments.mpesa');

    // AI Agents
    Route::post('/ai/names',       [AiAgentController::class, 'suggestNames'])->name('ai.names');
    Route::post('/ai/description', [AiAgentController::class, 'writeDescription'])->name('ai.description');
    Route::post('/ai/compliance',  [AiAgentController::class, 'complianceGuide'])->name('ai.compliance');

    // Reviews
    Route::post('/companies/{company}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// ── Webhook (no CSRF) ──────────────────────────────────────────────────────

Route::post('/webhooks/mpesa', [PaymentController::class, 'mpesaCallback'])
    ->name('payments.mpesa.callback')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/test-mpesa', function () {
    $response = \Illuminate\Support\Facades\Http::withoutVerifying()
        ->withBasicAuth(config('services.mpesa.consumer_key'), config('services.mpesa.consumer_secret'))
        ->get('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');

    $token = $response->json('access_token');

    if (!$token) {
        return 'TOKEN FAILED: ' . json_encode($response->json());
    }

    $shortcode = config('services.mpesa.shortcode');
    $passkey   = config('services.mpesa.passkey');
    $timestamp = now()->format('YmdHis');
    $password  = base64_encode($shortcode . $passkey . $timestamp);

    $stk = \Illuminate\Support\Facades\Http::withoutVerifying()
        ->withToken($token)
        ->post('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', [
            'BusinessShortCode' => $shortcode,
            'Password'          => $password,
            'Timestamp'         => $timestamp,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => 1,
            'PartyA'            => '254708374149',
            'PartyB'            => $shortcode,
            'PhoneNumber'       => '254708374149',
            'CallBackURL'       => 'https://webhook.site/test',
            'AccountReference'  => 'RegE',
            'TransactionDesc'   => 'Test',
        ]);

    return response()->json([
        'token'      => substr($token, 0, 20) . '...',
        'stk_status' => $stk->status(),
        'stk_body'   => $stk->json(),
    ]);
});