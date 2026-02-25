CREATE TABLE IF NOT EXISTS student_academic_histories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    academic_year_id BIGINT UNSIGNED NOT NULL,
    class_id BIGINT UNSIGNED NOT NULL,
    roll INT NULL,
    section VARCHAR(20) NULL,
    registration_status ENUM('registered', 'unregistered') DEFAULT 'unregistered',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY student_academic_histories_student_id_academic_year_id_unique (student_id, academic_year_id)
);
