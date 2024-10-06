<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Crypt;

class CheckSchoolData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role == 'teacher') {
            $school = Auth::user()->teacher->school;
            if (is_null($school->district_code) || is_null($school->province_code) || is_null($school->regency_code) || is_null($school->subdistrict_code)) {
                $id = Crypt::encrypt($school->id);
                return redirect()->route('sekolah.edit', ['id' => $id]);
            }
        } else if (Auth::user()->role == 'student') {
            $school = Auth::user()->student->school;
            if (is_null($school->district_code) || is_null($school->province_code) || is_null($school->regency_code) || is_null($school->subdistrict_code)) {

                $id = Crypt::encrypt($school->id);
                return redirect()->route('sekolah.edit', ['id' => $id]);
            }
        }
        return $next($request);
    }
}
