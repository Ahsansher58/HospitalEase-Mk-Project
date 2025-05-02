<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Doctor;

class LinkRegisteredDoctorConfirmationMail extends Mailable
{
  use Queueable, SerializesModels;

  public $doctor;
  public $hospital;


  public function __construct(Doctor $doctor, $hospital)
  {
    $this->doctor   = $doctor;
    $this->hospital = $hospital;
  }

  public function build()
  {
    return $this->subject('Hospital Confirmation Mail')
      ->view('emails.send_link_registered_doctor_confirmation')
      ->with([
        'doctor_id'     => $this->doctor->id,
        'name'          => $this->doctor->name,
        'hospital_id'   => $this->hospital->id,
        'hospital_name' => $this->hospital->name,
      ]);
  }
}
