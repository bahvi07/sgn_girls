-- Database schema for SGN Law College Admission System

-- Create database
CREATE DATABASE IF NOT EXISTS sgn_law_college;
USE sgn_law_college;

-- Table for storing student personal information
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    form_no VARCHAR(50) UNIQUE NOT NULL,
    admission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    class_sought VARCHAR(100),
    class_roll_no VARCHAR(20) UNIQUE,
    id_card_no VARCHAR(50),
    medium_of_instruction ENUM('English', 'Hindi') NOT NULL,
    applicant_name_english VARCHAR(100) NOT NULL,
    applicant_name_hindi VARCHAR(100),
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    date_of_birth DATE NOT NULL,
    category ENUM('General', 'SC', 'ST', 'OBC', 'Other') NOT NULL,
    applicant_photo_path VARCHAR(255),
    blood_group VARCHAR(10),
    hobbies_interests TEXT,
    document_list TEXT,
    institution_last_attended VARCHAR(255),
    in_service ENUM('Yes', 'No'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for family details
CREATE TABLE family_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    father_name_english VARCHAR(100) NOT NULL,
    father_name_hindi VARCHAR(100),
    father_occupation VARCHAR(100),
    mother_name_english VARCHAR(100) NOT NULL,
    mother_name_hindi VARCHAR(100),
    mother_occupation VARCHAR(100),
    guardian_name_english VARCHAR(100),
    guardian_name_hindi VARCHAR(100),
    guardian_occupation VARCHAR(100),
    guardian_relation VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- Table for contact information
CREATE TABLE contact_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    permanent_address TEXT NOT NULL,
    local_address TEXT,
    pincode VARCHAR(10) NOT NULL,
    mobile_number VARCHAR(15) NOT NULL,
    whatsapp_number VARCHAR(15),
    email VARCHAR(100),
    aadhar_number VARCHAR(20) UNIQUE,
    is_same_address BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- Table for educational qualifications
CREATE TABLE educational_qualifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    exam_type VARCHAR(100) NOT NULL,
    roll_no VARCHAR(50),
    year INT NOT NULL,
    university VARCHAR(255) NOT NULL,
    max_marks DECIMAL(10,2),
    marks_obtained DECIMAL(10,2),
    percentage DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- Table for documents
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    document_name VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- Table for office use
CREATE TABLE office_use (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    eligible_for_admission TEXT,
    scrutinizer_name VARCHAR(100),
    admission_status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    admission_date DATE,
    admission_incharge VARCHAR(100),
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- Table for tracking form status
CREATE TABLE application_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    current_status ENUM('Draft', 'Submitted', 'Under Review', 'Approved', 'Rejected') DEFAULT 'Draft',
    status_changed_by VARCHAR(100),
    comments TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- Create indexes for better performance
CREATE INDEX idx_student_form_no ON students(form_no);
CREATE INDEX idx_student_name ON students(applicant_name_english);
CREATE INDEX idx_contact_mobile ON contact_details(mobile_number);
CREATE INDEX idx_contact_aadhar ON contact_details(aadhar_number);
CREATE INDEX idx_application_status ON application_status(current_status);

-- Create a view for quick student overview
CREATE VIEW student_overview AS
SELECT 
    s.student_id,
    s.form_no,
    s.applicant_name_english as student_name,
    s.class_sought,
    s.class_roll_no,
    c.mobile_number,
    c.email,
    os.admission_status,
    app.current_status as application_status
FROM 
    students s
LEFT JOIN 
    contact_details c ON s.student_id = c.student_id
LEFT JOIN 
    office_use os ON s.student_id = os.student_id
LEFT JOIN 
    application_status app ON s.student_id = app.student_id;