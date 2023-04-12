<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use PDF;

class EmployeeController extends Controller
{
    public function showEmployees(){
        $employee = Employee::all();
        return view('index', compact('employee'));
      }

        // Generate PDF
    public function createPDF() {
        // retreive all records from db
        $data = Employee::all();
        // share data to view
        view()->share('employee',$data);

        //PDF::loadView('view',$data->toArray())->output();


        $pdf = PDF::loadView('pdf_view',$data->toArray());
        // download PDF file with download method


       //Storage::disk($path)->put($filename, base64_decode($image));
        //return URL::to('/').'/storage/'.$path.'/'.$filename; */

        $filename = "attestation". time().'.pdf';

       $content = $pdf->download()->getOriginalContent();
       \Storage::put('public/bills/'.$filename,$content);

       return $pdf->download('attestation.pdf');

      }


      public function getAttestation(Request $request){
      //validate fields
        $attrs = $request->validate([
            'email' => 'required|string'
        ]);

       // Get profile with matricule
       $profile = Employee::where('email', $attrs['email'])->first();



       $pdf = PDF::loadView('pdf_view',$profile->toArray());
       // download PDF file with download method


      //Storage::disk($path)->put($filename, base64_decode($image));
       //return URL::to('/').'/storage/'.$path.'/'.$filename; */

       $filename = $attrs['email']. time().'.pdf';

      $content = $pdf->download()->getOriginalContent();


     $path = \Storage::put('public/attestations/'.$filename,$pdf->output());



     Employee::sendMail($profile, $pdf);

     return response([
        'message' => 'Added Successfully!',
        'file' => $path,
    ], 200);




}




}
