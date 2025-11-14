<?php

namespace App\Http\Controllers;

use App\Models\App\Admin;
use App\Mail\OTP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailerController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $admin = Admin::where('email', $request->email)->first();
        
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan',
            ], 404);
        }

        $otp = $this->generateSecureOTP();

        Mail::to($request->email)->send(new OTP($otp));

        $data = [
            'otp' => $otp
        ];

        $admin->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP sudah dikirim',
        ]);
    }
       
    

    public function verifikasi(Request $request)
    {
       
         
    }

    private function generateSecureOTP(int $length = 6) : string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        return implode('', array_map(
            fn() => $characters[random_int(0, $charactersLength - 1)],
            range(1, '$length')
        ));
         
    }
}
