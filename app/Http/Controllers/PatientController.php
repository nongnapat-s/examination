<?php

namespace App\Http\Controllers;

use App\Patient;
use App\PatientLab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class PatientController extends Controller
{
    public function patient (){
        $patient = null;
        $women = 0;
        $men = 0;
        $start_date = Carbon::createFromFormat('Y-m-d H:i:s','2018-07-01 00:00:00');
        $end_date = Carbon::createFromFormat('Y-m-d H:i:s','2018-09-30 23:59:59');
        $patients = DB::table('patients')
                    ->join('patient_labs','patients.hn','=','patient_labs.hn')
                    ->where('patient_labs.date_lab','<=',$end_date)
                    ->where('patient_labs.date_lab','>=',$start_date)
                    ->get();
        foreach ($patients as $patient){
            $age = Carbon::parse($patient->dob)->age;
            if ($patient->gender == 0){
                if ($patient->Cr <= 0.7){
                    $eGFR = (144) * pow(($patient->Cr / 0.7),-0.329) * pow(0.993,$age);
                }else{
                    $eGFR = (144) * pow(($patient->Cr / 0.7),-1.209) * pow(0.993,$age);
                }
                if ($eGFR >= 60 && $eGFR <= 80){
                    $women = $women + 1 ;
                }
            }else if ($patient->gender == 1){
                if ( $patient->Cr <= 0.9 ){
                    $eGFR = (141) * pow(($patient->Cr/ 0.7),-0.411) * pow(0.993,$age);
                }else{
                    $eGFR = (141) * pow(($patient->Cr / 0.7),-1.209) * pow(0.993,$age);
                }
                if ($eGFR >= 60 && $eGFR <= 80){
                    $men = $men + 1 ;
                }
            }
        }
        return 'women ' . $women . ' men ' . $men;
    }
}