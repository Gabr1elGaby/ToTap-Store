<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CvPaymentController extends Controller
{
    public function show($cv_id)
    {
        // Read directly from the cvs table in the same database
        $cv = DB::table('cvs')
            ->join('cv_templates', 'cvs.template_id', '=', 'cv_templates.id')
            ->where('cvs.id', $cv_id)
            ->select('cvs.*', 'cv_templates.name as template_name', 'cv_templates.price', 'cv_templates.slug as template_slug')
            ->first();

        if (!$cv) {
            abort(404, 'CV not found');
        }

        if ($cv->status === 'PAID') {
            return redirect(route('cv.download', $cv->id));
        }

        return view('checkout.cv', compact('cv'));
    }

    public function simulate(Request $request, $cv_id)
    {
        $cv = DB::table('cvs')->where('id', $cv_id)->first();

        if (!$cv) {
            abort(404, 'CV not found');
        }

        // Simulate payment success by directly updating the CV status
        DB::table('cvs')->where('id', $cv_id)->update([
            'status' => 'PAID',
            'updated_at' => now(),
        ]);

        // Redirect back to CV app to download
        return redirect(route('cv.download', $cv->id));
    }
}
