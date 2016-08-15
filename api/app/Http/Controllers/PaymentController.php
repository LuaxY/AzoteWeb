<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use Auth;

use App\Transaction;
use App\Services\Payment\DediPass;
use App\Shop\ShopStatus;

class PaymentController extends Controller
{
    private $payment;

    public function __construct()
    {
        $used = config('dofus.payment.used');

        if ($used == "dedipass") $this->payment = new DediPass;

        if (!$this->payment) die("No valid payment method found");
    }

    public function country()
    {
        return view('shop.payment.country', ['rates' => $this->payment->rates()]);
    }

    public function method($country = 'fr')
    {
        if (isset($this->payment->rates()->$country))
        {
            $methods = $this->payment->rates()->$country;
        }
        else
        {
            $methods = $this->payment->rates()->fr;
            $country = 'fr';
        }

        return view('shop.payment.method', ['methods' => $methods, 'country' => $country]);
    }

    public function code(Request $request)
    {
        $country = $request->old('country');
        $method  = $request->old('method');
        $code    = $request->old('code');
        $cgv     = $request->old('cgv');

        $data = [];
        $data['country'] = (!empty($country) ? $country : $request->input('country'));
        $data['method']  = (!empty($method)  ? $method  : $request->input('method'));
        $data['code']    = (!empty($code)    ? $code    : $request->input('code'));
        $data['cgv']     = (!empty($cgv)     ? $cgv     : $request->input('cgv'));

        $split = explode('_', $data['method']);
        $data['method_'] = @$split[0];
        $data['palier']  = @$split[1];

        $validator = Validator::make($data, [
            'country' => 'required|min:2|max:3|alpha_num',
            'method_' => 'required|in:sms,audiotel,mobilecall,paypal,paysafecard,neosurf,carte bancaire,internet plus mobile',
            'cgv'     => 'required'
        ]);

        if ($validator->fails())
        {
            return redirect()->route('shop.payment.method', $data['country'])->withErrors($validator);
        }

        if ($this->payment->palier($data['country'], $data['method_'], $data['palier']) == null)
        {
            return redirect()->route('shop.payment.method', $data['country'])->withErrors(['palier' => 'Le palier selectionné est invalide.']);
        }
        else
        {
            $payment = $this->payment->palier($data['country'], $data['method_'], $data['palier']);
            $country = $data['country'];

            return view('shop.payment.code', [
                'payment' => $payment,
                'country' => $country,
                'method' => $data['method_'],
                'palier' => $data['palier'],
                'cgv' => 1
            ]);
        }
    }

    public function process(Request $request)
    {
        $data = $request->all();

        $split = explode('_', $data['method']);
        $data['method_'] = $split[0];
        $data['palier']  = $split[1];

        $validator = Validator::make($data, [
            'country' => 'required|min:2|max:3|alpha_num',
            'method_' => 'required|in:sms,audiotel,mobilecall,paypal,paysafecard,neosurf,carte bancaire,internet plus mobile',
            'code'    => 'required|min:6|max:8|alpha_num',
            'cgv'     => 'required'
        ]);

        if ($validator->fails())
        {
            return redirect()->route('shop.payment.code')->withErrors($validator)->withInput($data);
        }

        if ($this->payment->palier($data['country'], $data['method_'], $data['palier']) == null)
        {
            return redirect()->route('shop.payment.code')->withErrors(['palier' => 'Le palier selectionné est invalide.'])->withInput($data);
        }

        $validation = $this->payment->check($data['palier'], $data['code']);

        if ($validation->error)
        {
            $transaction = [
                'user_id'     => Auth::user()->id,
                'state'       => ShopStatus::PAYMENT_ERROR,
                'code'        => $data['code'],
                'points'      => 0,
                'country'     => $data['country'],
                'palier_name' => $data['method'],
                'type'        => 'error',
            ];

            Transaction::create($transaction);

            return redirect()->route('shop.payment.code')->withErrors(['code' => $validation->error])->withInput();
        }
        else
        {
            if ($validation->success)
            {
                $transaction = [
                    'user_id'     => Auth::user()->id,
                    'state'       => ShopStatus::PAYMENT_SUCCESS,
                    'code'        => $validation->code,
                    'points'      => $validation->points,
                    'country'     => $validation->country,
                    'palier_name' => $validation->palier_name,
                    'palier_id'   => $validation->palier_id,
                    'type'        => $validation->type,
                ];

                Transaction::create($transaction);

                Auth::user()->points += $validation->points;
                Auth::user()->save();

                $request->session()->flash('msg_flash', "Vous avez reçus {$validation->points} points !");

                return redirect()->route('home');
            }
            else
            {
                $transaction = [
                    'user_id'     => Auth::user()->id,
                    'state'       => ShopStatus::PAYMENT_FAIL,
                    'code'        => $validation->code,
                    'points'      => 0,
                    'country'     => $data['country'],
                    'palier_name' => $data['method'],
                    'type'        => 'fail',
                ];

                Transaction::create($transaction);

                return redirect()->route('shop.payment.code')->withErrors(['code' => $validation->message])->withInput();
            }
        }
    }
}
