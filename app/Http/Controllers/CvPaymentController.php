<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CvPaymentController extends Controller
{
    public function show($token)
    {
        $cv = DB::table('cvs')
            ->join('cv_templates', 'cvs.template_id', '=', 'cv_templates.id')
            ->where('cvs.access_token', $token)
            ->orWhere('cvs.id', $token)
            ->select('cvs.*', 'cv_templates.name as template_name', 'cv_templates.price', 'cv_templates.slug as template_slug')
            ->first();

        if (!$cv) {
            abort(404, 'CV not found');
        }

        // Owner check: if cv has user_id and user is logged in, ensure ownership unless admin
        if ($cv->user_id && auth()->check() && auth()->id() !== $cv->user_id && !auth()->user()->is_admin) {
            abort(403, 'Akses tidak diizinkan untuk CV ini.');
        }

        return view('checkout.cv', compact('cv'));
    }

    public function statusApi($token)
    {
        $cv = DB::table('cvs')
            ->where('access_token', $token)
            ->orWhere('id', $token)
            ->first();

        if (!$cv) {
            return response()->json(['status' => 'NOT_FOUND'], 404);
        }

        return response()->json([
            'status' => $cv->status,
            'is_paid' => ($cv->status === 'PAID'),
            'download_url' => route('cv.download', $cv->access_token ?? $cv->id),
        ]);
    }
}
