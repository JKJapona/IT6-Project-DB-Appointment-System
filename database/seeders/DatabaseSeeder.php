<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // 0. CLEANUP: Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('invoices')->truncate();
        DB::table('appointments')->truncate();
        DB::table('schedules')->truncate();
        DB::table('users')->truncate();
        DB::table('patients')->truncate();
        DB::table('staff')->truncate();
        DB::table('departments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. SEED DEPARTMENTS (5)
        $departmentIds = [];
        $depts = ['Cardiology', 'Pediatrics', 'Neurology', 'General Medicine', 'Radiology'];

        foreach ($depts as $name) {
            $departmentIds[] = DB::table('departments')->insertGetId([
                'department_name' => $name,
                'description' => "Specialized care and treatment for $name patients.",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. SEED STAFF (5)
        $staffIds = [];
        $doctorIds = [];
        $roles = ['Doctor', 'Nurse', 'Admissions Clerk', 'Admin'];

        for ($i = 0; $i < 5; $i++) {
            $role = ($i == 0) ? 'Doctor' : $faker->randomElement($roles); // Ensure at least one doctor
            $sid = DB::table('staff')->insertGetId([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'role' => $role,
                'specialization' => ($role == 'Doctor') ? $faker->randomElement(['Cardiologist', 'Surgeon', 'Physician']) : null,
                'department_id' => $faker->randomElement($departmentIds),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $staffIds[] = $sid;
            if ($role == 'Doctor') $doctorIds[] = $sid;
        }

        // 3. SEED PATIENTS (5)
        $patientIds = [];
        for ($i = 0; $i < 5; $i++) {
            $patientIds[] = DB::table('patients')->insertGetId([
                'philhealth_id' => $faker->numerify('##-#########-#'),
                'first_name' => $faker->firstName,
                'middle_name' => $faker->lastName, // Using faker lastName for middle name variety
                'last_name' => $faker->lastName,
                'suffix' => 'None',
                'date_of_birth' => $faker->date('Y-m-d', '2010-01-01'), // Older than 2010
                'gender' => $faker->randomElement(['Male', 'Female']),
                
                // Parental info (Required by your schema logic)
                'fathers_first_name' => $faker->firstNameMale,
                'fathers_last_name' => $faker->lastName,
                'mothers_first_name' => $faker->firstNameFemale,
                'mothers_last_name' => $faker->lastName,
                
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. SEED SCHEDULES (5)
        $scheduleIds = [];
        for ($i = 0; $i < 5; $i++) {
            $scheduleIds[] = DB::table('schedules')->insertGetId([
                'department_id' => $faker->randomElement($departmentIds),
                'schedule_date' => $faker->dateTimeBetween('now', '+1 week')->format('Y-m-d'),
                'max_capacity' => 10,
                'current_booked' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 5. SEED APPOINTMENTS (5)
        $appointmentIds = [];
        for ($i = 0; $i < 5; $i++) {
            try {
                $appointmentIds[] = DB::table('appointments')->insertGetId([
                    'reference_number' => 'REF-' . strtoupper(Str::random(8)),
                    'patient_id' => $faker->randomElement($patientIds),
                    'schedule_id' => $faker->randomElement($scheduleIds),
                    'assigned_doctor_id' => $faker->randomElement($doctorIds),
                    'processed_by_id' => $faker->randomElement($staffIds),
                    'status' => $faker->randomElement(['Pending', 'Confirmed', 'Completed']),
                    'booking_timestamp' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                // If the trigger blocks a duplicate patient/schedule combo, skip it
                continue;
            }
        }

        // 6. SEED INVOICES (5)
        $availableApps = $appointmentIds;
        shuffle($availableApps);
        $invoiceCount = count($availableApps); // Might be less than 5 if some appointments failed

        for ($i = 0; $i < $invoiceCount; $i++) {
            DB::table('invoices')->insert([
                'appointment_id' => $availableApps[$i],
                'total_amount' => $faker->randomFloat(2, 500, 2000),
                'payment_status' => $faker->randomElement(['Unpaid', 'Paid']),
                'issued_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 7. SEED USERS (Total 10: 1 Admin, 4 Staff, 5 Patients)
        
        // 1 Admin Account
        DB::table('users')->insert([
            'first_name' => 'System',
            'middle_name' => null,
            'last_name' => 'Administrator',
            'email' => 'admin@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
            'created_at' => now(),
        ]);

        // 4 Staff Accounts
        for ($i = 0; $i < 4; $i++) {
            $staff = DB::table('staff')->where('staff_id', $staffIds[$i])->first();
            DB::table('users')->insert([
                'first_name' => $staff->first_name,
                'middle_name' => $staff->middle_name,
                'last_name' => $staff->last_name,
                'email' => "staff{$i}@hospital.com",
                'password' => Hash::make('password'),
                'role' => 'Staff',
                'staff_id' => $staff->staff_id,
                'created_at' => now(),
            ]);
        }

        // 5 Patient Accounts
        for ($i = 0; $i < 5; $i++) {
            $patient = DB::table('patients')->where('patient_id', $patientIds[$i])->first();
            DB::table('users')->insert([
                'first_name' => $patient->first_name,
                'middle_name' => $patient->middle_name,
                'last_name' => $patient->last_name,
                'email' => "patient{$i}@patient.com",
                'password' => Hash::make('password'),
                'role' => 'Patient',
                'patient_id' => $patient->patient_id,
                'created_at' => now(),
            ]);
        }
    }
}