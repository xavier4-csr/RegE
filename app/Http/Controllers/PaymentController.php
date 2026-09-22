<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function initiateMpesa(Request $request)
    {
        $data = $request->validate([
            'phone'      => ['required', 'string'],
            'amount'     => ['required', 'numeric', 'min:1'],
            'type'       => ['required', 'in:registration_fee,premium_listing,document_fee,subscription'],
            'company_id' => ['nullable', 'exists:companies,id'],
        ]);

        $phone = preg_replace('/^(0|254|\+254)/', '254', $data['phone']);

        $token = $this->getDarajaToken();

        if (!$token) {
            \Illuminate\Support\Facades\Log::error('Daraja token fetch failed');
            return back()->withErrors(['payment' => 'Could not connect to M-Pesa. Try again.']);
        }

        $shortcode  = config('services.mpesa.shortcode');
        $passkey    = config('services.mpesa.passkey');
        $timestamp  = now()->format('YmdHis');
        $password   = base64_encode($shortcode . $passkey . $timestamp);

        \Illuminate\Support\Facades\Log::info('STK Push attempt', ['phone' => $phone, 'amount' => $data['amount']]);

        $response = Http::withoutVerifying()
            ->withToken($token)
            ->post('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', [
                'BusinessShortCode' => $shortcode,
                'Password'          => $password,
                'Timestamp'         => $timestamp,
                'TransactionType'   => 'CustomerPayBillOnline',
                'Amount'            => (int) $data['amount'],
                'PartyA'            => $phone,
                'PartyB'            => $shortcode,
                'PhoneNumber'       => $phone,
                'CallBackURL'       => 'https://webhook.site/test',
                'AccountReference'  => 'RegE',
                'TransactionDesc'   => 'Payment',
            ]);

            \Illuminate\Support\Facades\Log::info('STK Push response', [
            'status' => $response->status(),
            'body'   => $response->json(),
        ]);

            if (!$response->successful() || $response->json('ResponseCode') !== '0') {
                return back()->withErrors(['payment' => 'STK Push failed: ' . $response->json('ResponseDescription')]);
        }

        Payment::create([
            'user_id'           => Auth::id(),
            'company_id'        => $data['company_id'] ?? null,
            'amount'            => $data['amount'],
            'type'              => $data['type'],
            'status'            => 'pending',
            'phone_number'      => $phone,
            'mpesa_checkout_id' => $response->json('CheckoutRequestID'),
            'metadata'          => $response->json(),
        ]);

        return back()->with('success', '📱 Check your phone — enter your M-Pesa PIN to complete payment.');
    }

    public function mpesaCallback(Request $request)
    {
        $body = $request->input('Body.stkCallback');
        if (!$body) return response()->json(['ResultCode' => 0]);

        $payment = Payment::where('mpesa_checkout_id', $body['CheckoutRequestID'])->first();
        if (!$payment) return response()->json(['ResultCode' => 0]);

        if ($body['ResultCode'] === 0) {
            $items   = collect($body['CallbackMetadata']['Item']);
            $receipt = $items->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;
            $payment->update(['status' => 'completed', 'mpesa_receipt' => $receipt, 'metadata' => $body]);
            Notification::create([
                'user_id'    => $payment->user_id,
                'title'      => 'Payment Confirmed ✅',
                'body'       => "KES {$payment->amount} received. Receipt: {$receipt}",
                'type'       => 'payment_confirmed',
                'action_url' => route('dashboard'),
            ]);
        } else {
            $payment->update(['status' => 'failed', 'metadata' => $body]);
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    private function getDarajaToken(): ?string
    {
        $response = Http::withoutVerifying()
            ->withBasicAuth(config('services.mpesa.consumer_key'), config('services.mpesa.consumer_secret'))
            ->get('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
        return $response->successful() ? $response->json('access_token') : null;
    }
}