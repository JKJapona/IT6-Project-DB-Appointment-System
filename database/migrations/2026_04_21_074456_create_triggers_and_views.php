<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. CLEANUP: Drop existing triggers/views first
        DB::unprepared("DROP TRIGGER IF EXISTS after_appointment_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS before_appointment_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS after_appointment_complete");
        DB::unprepared("DROP TRIGGER IF EXISTS after_appointment_cancel");
        DB::unprepared("DROP TRIGGER IF EXISTS before_appointment_duplicate_check");
        DB::unprepared("DROP TRIGGER IF EXISTS before_appointment_update_lock");
        
        DB::unprepared("DROP VIEW IF EXISTS View_Appointment_Master_List");
        DB::unprepared("DROP VIEW IF EXISTS View_Daily_Department_Load");
        DB::unprepared("DROP VIEW IF EXISTS View_Available_Doctors");

        // --- YOUR EXISTING TRIGGERS (UNCHANGED) ---

        // 1. Trigger: Increment booked count
        DB::unprepared("
            CREATE TRIGGER after_appointment_insert
            AFTER INSERT ON appointments
            FOR EACH ROW
            BEGIN
                UPDATE schedules
                SET current_booked = current_booked + 1
                WHERE schedule_id = NEW.schedule_id;
            END;
        ");

        // 2. Trigger: Check capacity
        DB::unprepared("
            CREATE TRIGGER before_appointment_insert
            BEFORE INSERT ON appointments
            FOR EACH ROW
            BEGIN
                IF (SELECT current_booked FROM schedules WHERE schedule_id = NEW.schedule_id) >= 
                   (SELECT max_capacity FROM schedules WHERE schedule_id = NEW.schedule_id) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Error: This schedule is already at maximum capacity.';
                END IF;
            END;
        ");

        // 3. Trigger: Create invoice on completion
        DB::unprepared("
            CREATE TRIGGER after_appointment_complete
            AFTER UPDATE ON appointments
            FOR EACH ROW
            BEGIN
                IF NEW.status = 'Completed' AND OLD.status <> 'Completed' THEN
                    INSERT INTO invoices (appointment_id, total_amount, payment_status)
                    VALUES (NEW.appointment_id, 500.00, 'Unpaid', NOW(), NOW());
                END IF;
            END;
        ");

        // 4. Trigger: Decrement count on cancellation
        DB::unprepared("
            CREATE TRIGGER after_appointment_cancel
            AFTER UPDATE ON appointments
            FOR EACH ROW
            BEGIN
                IF NEW.status = 'Cancelled' AND OLD.status <> 'Cancelled' THEN
                    UPDATE schedules
                    SET current_booked = current_booked - 1
                    WHERE schedule_id = NEW.schedule_id;
                END IF;
            END;
        ");

        // --- NEW COMPREHENSIVE TRIGGERS ---

        // 5. Trigger: Prevent Duplicate Active Appointments
        // Ensures a patient can't book the same doctor on the same schedule twice.
        DB::unprepared("
            CREATE TRIGGER before_appointment_duplicate_check
            BEFORE INSERT ON appointments
            FOR EACH ROW
            BEGIN
                IF (SELECT COUNT(*) FROM appointments 
                    WHERE patient_id = NEW.patient_id 
                    AND schedule_id = NEW.schedule_id 
                    AND status NOT IN ('Cancelled')) > 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Error: Patient already has an active appointment for this schedule.';
                END IF;
            END;
        ");

        // 6. Trigger: Data Lock
        // Prevents changing crucial details once an appointment is finished.
        DB::unprepared("
            CREATE TRIGGER before_appointment_update_lock
            BEFORE UPDATE ON appointments
            FOR EACH ROW
            BEGIN
                IF OLD.status IN ('Completed', 'Cancelled') AND NEW.status = OLD.status THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Error: Completed or Cancelled appointments cannot be modified.';
                END IF;
            END;
        ");

        // --- COMPREHENSIVE VIEWS ---

        // 1. YOUR ORIGINAL VIEW: Master List
        DB::unprepared("
            CREATE VIEW View_Appointment_Master_List AS
            SELECT 
                A.appointment_id,
                A.reference_number,
                A.status AS appointment_status,
                A.booking_timestamp,
                CONCAT(P.first_name, ' ', P.last_name) AS patient_full_name,
                P.contact_number AS patient_phone,
                CONCAT(S.first_name, ' ', S.last_name) AS doctor_name,
                S.specialization AS doctor_field,
                D.department_name,
                Sch.schedule_date,
                Sch.max_capacity,
                Sch.current_booked,
                IFNULL(I.total_amount, 0.00) AS total_bill,
                IFNULL(I.payment_status, 'No Invoice') AS billing_status
            FROM appointments A
            JOIN patients P ON A.patient_id = P.patient_id
            JOIN staff S ON A.assigned_doctor_id = S.staff_id
            JOIN departments D ON S.department_id = D.department_id
            JOIN schedules Sch ON A.schedule_id = Sch.schedule_id
            LEFT JOIN invoices I ON A.appointment_id = I.appointment_id;
        ");

        // 2. NEW VIEW: Daily Department Load
        // Useful for Admins to see which departments are overbooked today.
        DB::unprepared("
            CREATE VIEW View_Daily_Department_Load AS
            SELECT 
                D.department_name,
                Sch.schedule_date,
                COUNT(A.appointment_id) as total_appointments,
                Sch.max_capacity,
                (Sch.max_capacity - Sch.current_booked) as slots_remaining
            FROM departments D
            JOIN schedules Sch ON D.department_id = Sch.department_id
            LEFT JOIN appointments A ON Sch.schedule_id = A.schedule_id
            GROUP BY 
                D.department_id, 
                D.department_name, 
                Sch.schedule_id, 
                Sch.schedule_date, 
                Sch.max_capacity, 
                Sch.current_booked;
        ");

        // 3. NEW VIEW: Doctor Availability Finder
        // Quick look-up for the front desk to see who has slots left.
        DB::unprepared("
            CREATE VIEW View_Available_Doctors AS
            SELECT 
                CONCAT(S.first_name, ' ', S.last_name) as doctor_name,
                S.specialization,
                D.department_name,
                Sch.schedule_date,
                (Sch.max_capacity - Sch.current_booked) as available_slots
            FROM staff S
            JOIN departments D ON S.department_id = D.department_id
            JOIN schedules Sch ON D.department_id = Sch.department_id
            WHERE S.role = 'Doctor' AND (Sch.max_capacity - Sch.current_booked) > 0;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS after_appointment_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS before_appointment_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS after_appointment_complete");
        DB::unprepared("DROP TRIGGER IF EXISTS after_appointment_cancel");
        DB::unprepared("DROP TRIGGER IF EXISTS before_appointment_duplicate_check");
        DB::unprepared("DROP TRIGGER IF EXISTS before_appointment_update_lock");
        
        DB::unprepared("DROP VIEW IF EXISTS View_Appointment_Master_List");
        DB::unprepared("DROP VIEW IF EXISTS View_Daily_Department_Load");
        DB::unprepared("DROP VIEW IF EXISTS View_Available_Doctors");
    }
};