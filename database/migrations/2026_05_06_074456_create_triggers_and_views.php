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
        // 1. CLEANUP: Drop existing triggers/views first to avoid "already exists" errors
        DB::unprepared("DROP TRIGGER IF EXISTS after_appointment_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS before_appointment_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS after_appointment_complete");
        DB::unprepared("DROP TRIGGER IF EXISTS after_appointment_cancel");
        DB::unprepared("DROP TRIGGER IF EXISTS before_appointment_duplicate_check");
        DB::unprepared("DROP TRIGGER IF EXISTS before_appointment_update_lock");
        
        DB::unprepared("DROP VIEW IF EXISTS View_Appointment_Master_List");
        DB::unprepared("DROP VIEW IF EXISTS View_Daily_Department_Load");
        DB::unprepared("DROP VIEW IF EXISTS View_Available_Doctors");

        // --- TRIGGERS ---

        // 1. Increment booked count in schedules when a new appointment is made
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

        // 2. Prevent booking if the schedule is at max capacity
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

        // 3. Auto-generate Invoice when Appointment status is updated to 'Completed'
        // Fixed to include Laravel's required timestamp columns
        DB::unprepared("
            CREATE TRIGGER after_appointment_complete
            AFTER UPDATE ON appointments
            FOR EACH ROW
            BEGIN
                IF NEW.status = 'Completed' AND OLD.status <> 'Completed' THEN
                    INSERT INTO invoices (appointment_id, total_amount, payment_status, created_at, updated_at)
                    VALUES (NEW.appointment_id, 500.00, 'Unpaid', NOW(), NOW());
                END IF;
            END;
        ");

        // 4. Decrement booked count in schedules if an appointment is cancelled
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

        // 5. Prevent duplicate active appointments for the same patient on the same schedule
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

        // 6. Data Lock: Prevent edits to crucial fields if appointment is already finished or cancelled
        DB::unprepared("
            CREATE TRIGGER before_appointment_update_lock
            BEFORE UPDATE ON appointments
            FOR EACH ROW
            BEGIN
                -- If the record is already final, block EVERYTHING
                IF OLD.status IN ('Completed', 'Cancelled') THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Error: This appointment is finalized and cannot be modified.';
                END IF;
            END;
        ");

        // --- VIEWS ---

        // 1. Appointment Master List: A flat view of everything related to an appointment
        DB::unprepared("
            CREATE VIEW View_Appointment_Master_List AS
            SELECT 
                A.appointment_id,
                A.reference_number,
                A.status AS appointment_status,
                A.booking_timestamp,
                -- Concat using your new split name fields
                CONCAT(P.first_name, ' ', IFNULL(P.middle_name, ''), ' ', P.last_name) AS patient_full_name,
                P.philhealth_id AS philhealth_number, -- Changed from contact_number
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

        // 2. Daily Department Load: See how busy departments are for each date
        DB::unprepared("
            CREATE VIEW View_Daily_Department_Load AS
            SELECT 
                D.department_name,
                Sch.schedule_date,
                Sch.max_capacity,
                Sch.current_booked,
                (Sch.max_capacity - Sch.current_booked) as slots_remaining
            FROM departments D
            JOIN schedules Sch ON D.department_id = Sch.department_id;
        ");

        // 3. Available Doctors: Shows only doctors who have slots left today or in the future
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