<?php

namespace Pterodactyl\Models\Billing\Gateways;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Pterodactyl\Models\Billing\BillingLogs;
use Bill;

class FreekassaListener
{
    private $merchantId;
    private $secretKey;
    private $twoSecretKey;

    public function __construct()
    {
        $this->merchantId = Bill::settings()->getParam('freekassa_shop_id');
        $this->secretKey = Bill::settings()->getParam('freekassa_secret_key');
        $this->twoSecretKey = Bill::settings()->getParam('freekassa_secret_key_two');
    }

    public function proccessPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric'
        ]);

        $amount = $request->input('amount');
        $currency = Bill::settings()->getParam('paypal_currency');
        $userId = Auth::user()->id;
        $transactionId = md5(Auth::user()->email . '|' . time());
        $email = Auth::user()->email;
        $signature = md5("{$this->merchantId}:$amount:{$this->secretKey}:{$currency}:$userId");

        $paymentUrl = "https://pay.freekassa.ru?" .
            "m={$this->merchantId}" .
            "&oa=$amount" .
            "&o=$userId" .
            "&s=$signature" .
            "&currency=$currency" .
            "&em=$email" .
            "&us_id=$transactionId";

        return redirect()->away($paymentUrl);
    }

    public function handleRequest()
    {
        $amount = $_REQUEST['AMOUNT'];

        if (!isset($amount) or $amount <= 0) {
            return redirect()->back()->withErrors(['You must specify an amount you want to add']);
        }

        $transactionId = $_REQUEST['us_id'];
        $userId = $_REQUEST['MERCHANT_ORDER_ID'];
        $merchantId = $_REQUEST['MERCHANT_ID'];
        $signature = $_REQUEST['SIGN'];

        $exSignature = md5("{$merchantId}:{$amount}:{$this->twoSecretKey}:{$userId}");

        if ($exSignature != $signature) {
            die('Error payment validation');
        }

        $this->saveLog($userId, $transactionId, $_REQUEST);
        DB::table('billing_users')->where('user_id', $userId)->increment('balance', $amount);
        Bill::events()->create('client', false, 'balance:freekassa:added', Auth::user()->email . ' added amount: ' . $amount);

        die('YES');
    }

    private function isTXN($txn)
    {
        $log = DB::table('billing_logs')->where('txn_id', $txn)->first();
        if (empty($log)) {
            return false;
        }
        return true;
    }

    private function saveLog($user_id, $transaction_id, $req_data)
    {
        if ($this->isTXN($transaction_id)) {
            DB::table('billing_logs')->where('txn_id', $transaction_id)->update(
                array(
                    'status' => 'VERIFIED',
                    'data' => json_encode($req_data),
                )
            );
            exit;
        } else {
            $log = new BillingLogs;
            $log->user_id = $user_id;
            $log->type = 'freekassa';
            $log->txn_id = $transaction_id;
            $log->status = 'VERIFIED';
            $log->data = json_encode($req_data);
            $log->save();
        }
    }
}