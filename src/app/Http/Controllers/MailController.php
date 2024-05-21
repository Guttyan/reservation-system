<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationMail;

class MailController extends Controller
{
    public function createMail($shop_id = null){
        $users = User::all();
        return view('create_notification_mail', compact('users', 'shop_id'));
    }

    public function sendMail(Request $request){
        $data = $request->except('_token');

        if (isset($data['shop_id'])) {
            $shop = Shop::find($data['shop_id']);
            $data['shop'] = $shop;
        }

        if ($request->address === 'all') {
            $users = User::all();
            $to = [];
            foreach ($users as $user) {
                $to[] = $user->email;
            }
        } else {
            $to = $request->address;
        }

        Mail::to($to)->send(new NotificationMail($data));
        return redirect()->back();
    }
}
