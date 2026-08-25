<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CvPaymentController extends Controller
{
    public function show($token)
    {
        // Read directly from the cvs table in the same database using access_token or fallback id
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

        if ($cv->status === 'PAID') {
            return redirect(route('cv.download', $cv->access_token ?? $cv->id));
        }

        return view('checkout.cv', compact('cv'));
    }

    public function simulate(Request $request, $token)
    {
        $cv = DB::table('cvs')
            ->where('access_token', $token)
            ->orWhere('id', $token)
            ->first();

        if (!$cv) {
            abort(404, 'CV not found');
        }

        // Owner check
        if ($cv->user_id && auth()->check() && auth()->id() !== $cv->user_id && !auth()->user()->is_admin) {
            abort(403, 'Akses tidak diizinkan untuk CV ini.');
        }

        // Simulate payment success by directly updating the CV status
        DB::table('cvs')->where('id', $cv->id)->update([
            'status' => 'PAID',
            'updated_at' => now(),
        ]);

        // Redirect back to CV download using secret token
        return redirect(route('cv.download', $cv->access_token ?? $cv->id));
    }
}
