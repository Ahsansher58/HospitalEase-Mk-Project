<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HospitalAppointment;

class HospitalAppointmentSeeder extends Seeder
{
  public function run()
  {
    $appointments = [
      [
        'patient_name' => 'Krishnan',
        'phone_number' => '+91 9876543210',
        'email' => 'example@gmail.com',
        'appointment_date' => '2024-09-04',
        'hospital_id' => 13, // Assuming hospital_id 1 exists in the hospitals table
      ],
      [
        'patient_name' => 'Jeyanthi Nadeshan',
        'phone_number' => '+91 9876543210',
        'email' => 'example@gmail.com',
        'appointment_date' => '2024-09-04',
        'hospital_id' => 13,
      ],
    ];

    foreach ($appointments as $appointment) {
      HospitalAppointment::create($appointment);
    }
  }
}
