<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mail;

class Employee extends Model
{
    use HasFactory;
    public $fillable = ['name', 'email', 'phone_number', 'dob'];


    public  static function sendMail($employee, $pdf){


      $filename = $employee->name. time().'.pdf';

    $path =  \Storage::put('public/attestations/'.$filename,$pdf->output());
     \Storage::put($path,$pdf->output());



     $data['name'] = $employee->name;
     $data['email'] = $employee->email;


     Mail::send('pdf_view', $data, function ($message) use($employee, $pdf, $path){
         $message->from('georgefack237@gmail.com', env('APP_NAME'));
         $message->to([$employee->email, 'georgefack237@gmail.com'])->subject('Subject')
         ->attachData($pdf->output(), $path, [
            'mime' => 'application/pdf',
            'as' => $employee->name. '.'.'pdf'
         ]);
     });

    }
}
