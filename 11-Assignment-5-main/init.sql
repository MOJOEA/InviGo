-- Create students table
CREATE TABLE IF NOT EXISTS students (
    student_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    password VARCHAR(255) NULL,
    image VARCHAR(1000) NOT NULL,
    date_of_birth DATE,
    email VARCHAR(100) UNIQUE,
    phone_number VARCHAR(20)
);

-- Create courses table
CREATE TABLE IF NOT EXISTS courses (
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    course_name VARCHAR(100) NOT NULL,
    course_code VARCHAR(20) UNIQUE,
    instructor VARCHAR(50)
);

-- Create enrollment table
CREATE TABLE IF NOT EXISTS enrollment (
    enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    course_id INT,
    enrollment_date DATE,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
);

-- Insert sample courses
INSERT INTO courses (course_name, course_code, instructor)
VALUES
('Introduction to Programming', 'CS101', 'Prof. Smith'),
('Database Systems', 'DB201', 'Prof. Johnson'),
('Web Development', 'WEB301', 'Prof. Lee'),
('Data Structures and Algorithms', 'DSA401', 'Prof. Patel'),
('Artificial Intelligence', 'AI501', 'Prof. Kim'),
('Machine Learning', 'ML601', 'Prof. Chen'),
('Cybersecurity', 'CYB701', 'Prof. Harris'),
('Network Security', 'NS801', 'Prof. Wilson'),
('Software Engineering', 'SE901', 'Prof. Taylor'),
('Cloud Computing', 'CC1001', 'Prof. Garcia');

-- Insert sample students with hashed passwords
INSERT INTO students (first_name, last_name, image, date_of_birth, email, phone_number, password)
VALUES
('Alice', 'Johnson', 'https://api.dicebear.com/9.x/avataaars/svg?seed=alice@example.com', '2000-01-01', 'alice@example.com', '123-456-7890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password
('Bob', 'Smith', 'https://api.dicebear.com/9.x/avataaars/svg?seed=bob@example.com', '2001-02-02', 'bob@example.com', '987-654-3210', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Charlie', 'Brown', 'https://api.dicebear.com/9.x/avataaars/svg?seed=charlie@example.com', '2002-03-03', 'charlie@example.com', '555-555-5555', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('David', 'Lee', 'https://api.dicebear.com/9.x/avataaars/svg?seed=david@example.com', '2003-04-04', 'david@example.com', '111-222-3333', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Emily', 'Davis', 'https://api.dicebear.com/9.x/avataaars/svg?seed=emily@example.com', '2004-05-05', 'emily@example.com', '999-888-7777', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample enrollments for Alice
INSERT INTO enrollment (student_id, course_id, enrollment_date)
VALUES
(1, 1, '2023-09-01'),
(1, 2, '2023-09-01');

-- Update student images to use DiceBear API
UPDATE students SET image = CONCAT('https://api.dicebear.com/9.x/avataaars/svg?seed=', email);
