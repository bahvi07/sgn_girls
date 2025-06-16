-- Create database if not exists
CREATE DATABASE IF NOT EXISTS sgn_girl_admission;

-- Use the database
USE sgn_girl_admission;

-- Create admission table
CREATE TABLE IF NOT EXISTS admissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    form_no VARCHAR(50) NOT NULL UNIQUE,
    
    -- Class Details
    class VARCHAR(50) NOT NULL,
    part VARCHAR(10) NOT NULL,
    medium ENUM('English', 'Hindi') NOT NULL,
    faculty ENUM('Arts', 'Science', 'Commerce', 'Computer') NOT NULL,
    
    -- Personal Details
    applicant_name VARCHAR(100) NOT NULL,
    hindi_name VARCHAR(100),
    father_name VARCHAR(100) NOT NULL,
    f_occupation VARCHAR(100),
    mother_name VARCHAR(100) NOT NULL,
    m_occupation VARCHAR(100),
    dob DATE NOT NULL,
    category ENUM('General', 'SC', 'ST', 'OBC', 'Other') NOT NULL,
    aadhar VARCHAR(12) NOT NULL,
    photo VARCHAR(255) NOT NULL,
    
    -- Contact Details
    perm_address TEXT NOT NULL,
    same_address BOOLEAN DEFAULT FALSE,
    local_address TEXT,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    
    -- Optional Subjects
    subject1 VARCHAR(100),
    subject2 VARCHAR(100),
    subject3 VARCHAR(100),
    
    -- Compulsory Subjects
    comp_computer BOOLEAN DEFAULT FALSE,
    comp_env BOOLEAN DEFAULT FALSE,
    comp_english BOOLEAN DEFAULT FALSE,
    comp_hindi BOOLEAN DEFAULT FALSE,
    
    -- Previous Exam Details
    prev_course_title VARCHAR(100) NOT NULL,
    prev_year VARCHAR(4) NOT NULL,
    prev_board VARCHAR(100) NOT NULL,
    prev_subjects VARCHAR(255) NOT NULL,
    prev_percentage DECIMAL(5,2) NOT NULL,
    prev_division ENUM('1st', '2nd', '3rd') NOT NULL,
    
    -- Institution Last Attended
    institution_name VARCHAR(255) NOT NULL,
    institution_address TEXT NOT NULL,
    institution_contact VARCHAR(15) NOT NULL,
    
    -- University Enrollment
    university_enrollment VARCHAR(50),
    
    -- Extra-Curricular Activities
    nss_offered ENUM('Yes', 'No'),
    other_activities TEXT,
    
    -- Declaration
    declaration BOOLEAN NOT NULL DEFAULT FALSE,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 