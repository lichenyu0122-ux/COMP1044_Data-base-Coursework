SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE Roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO Roles (role_name) VALUES
('Admin'),
('Assessor');


CREATE TABLE Users(
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL ,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY username (username),
    FOREIGN KEY (role_id) REFERENCES Roles(role_id)
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO Users (role_id, username, password, full_name, email) VALUES
(1, 'therealadmin', 'databasemaster0!','Dr Tan Chye Cheah', 'ChyeCheah.Tan@nottingham.edu.my' ),
(2, 'sup1', 'neversurprise1!','Muhammad Ali', 'MuhammadAli@nottingham.edu.my' ),
(2, 'sup2', 'miloaddiction2!','Anwar Ibrahim', 'AnwarIbrahim@nottingham.edu.my' ),
(2, 'sup3', 'supercardriver3!!','Yuki Tsunoda', 'YukiTsunoda@nottingham.edu.my' );

CREATE TABLE Students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    student_code VARCHAR(20) NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    programme VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY student_code (student_code)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO Students (student_code, student_name, programme) VALUES
('A2001','George Russell','Mechanical Engineering'),
('A2002','Ronnie Coleman','Nutrition'),
('A2003','David Goggins','Sports Science'),
('A2004','Junta Suzuki','Computer Science'),
('A2005','Li Chenyu','Computer Science with AI');


CREATE TABLE Assessment_Criteria(
    criteria_id INT AUTO_INCREMENT PRIMARY KEY,
    criteria_name VARCHAR(255) NOT NULL,
    weightage DECIMAL(5,2) NOT NULL,
    description TEXT

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO Assessment_Criteria (criteria_name, weightage, description) VALUES
('Undertaking Tasks/Projects', 10.00, 'Ability to complete assigned tasks'),
('Health and Safety Requirements at the Workplace', 10.00, 'Safety compliance'),
('Connectivity and Use of Theoretical Knowledge', 10.00, 'Application of theory'),
('Presentation of the Report as a Written Document', 15.00, 'Quality of written report presentation'),
('Clarity of Language and Illustration', 10.00, 'Clarity in communication and visual explanation'),
('Lifelong Learning Activities', 15.00, 'Continuous self-learning and improvement'),
('Project Management', 15.00, 'Planning, organizing, and executing project tasks'),
('Time Management', 15.00, 'Ability to meet deadlines effectively');

CREATE TABLE Internships (
    internship_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    assessor_id INT DEFAULT NULL,
    company_name VARCHAR(200) NOT NULL,
    internship_start_date DATE,
    internship_end_date DATE,

    FOREIGN KEY (student_id) REFERENCES Students(student_id)
    ON DELETE CASCADE,

    FOREIGN KEY (assessor_id) REFERENCES Users(user_id)
    ON DELETE SET NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO Internships (student_id, assessor_id, company_name, internship_start_date, internship_end_date) VALUES
(1, 2, 'Benz Engineering', '2026-02-02', '2026-02-14'),
(2, 2, 'Protein Factory', '2026-01-01', '2026-12-31'),
(3, 3, 'Top Athletes Institution', '2026-07-06', '2026-08-25'),
(4, 3, 'Emazon', '2026-09-13', '2026-12-25'),
(5, 4, 'Meeta AI', '2026-04-01', '2026-06-07');


CREATE TABLE Assessment_Results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    internship_id INT NOT NULL,
    criteria_id INT NOT NULL,
    score DECIMAL(5,2) DEFAULT NULL CHECK (score BETWEEN 0 AND 100),
    comments TEXT,
    assessment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY unique_result (internship_id, criteria_id),

    FOREIGN KEY (internship_id) REFERENCES Internships(internship_id)
    ON DELETE CASCADE,

    FOREIGN KEY (criteria_id) REFERENCES Assessment_Criteria(criteria_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO Assessment_Results (internship_id, criteria_id, score, comments) VALUES
(4, 1, 88, 'Completed software tasks efficiently'),
(4, 2, 82, 'Follows workplace safety practices consistently'),
(4, 3, 90, 'Excellent application of theoretical knowledge'),
(4, 4, 85, 'Well organized and professional report'),
(4, 5, 87, 'Clear and effective communication'),
(4, 6, 89, 'Shows strong initiative in self-learning'),
(4, 7, 92, 'Excellent project planning and execution'),
(4, 8, 90, 'Manages time and deadlines very well'),

(2, 1, 75, 'Completed assigned tasks adequately'),
(2, 2, 80, 'Good awareness of safety requirements'),
(2, 3, 72, 'Basic understanding of theoretical concepts'),
(2, 4, 70, 'Report needs better structure'),
(2, 5, 78, 'Communication is acceptable'),
(2, 6, 76, 'Shows moderate learning effort'),
(2, 7, 74, 'Basic project management skills'),
(2, 8, 77, 'Generally meets deadlines'),

(3, 1, 65, 'Struggles with completing assigned tasks'),
(3, 2, 60, 'Needs improvement in safety awareness'),
(3, 3, 70, 'Limited application of theoretical knowledge'),
(3, 4, 68, 'Report lacks proper organization'),
(3, 5, 66, 'Communication needs improvement'),
(3, 6, 72, 'Some effort in learning new skills'),
(3, 7, 67, 'Weak project management ability'),
(3, 8, 66, 'Often struggles with time management');

CREATE VIEW Final_Results AS
SELECT
    i.internship_id,
    s.student_name,
    ROUND(SUM(ar.score*ac.weightage/100), 2 )AS final_score
FROM Assessment_Results ar
JOIN Assessment_Criteria ac ON ar.criteria_id = ac.criteria_id
JOIN Internships i ON ar.internship_id = i.internship_id
JOIN Students s ON i.student_id = s.student_id
GROUP BY i.internship_id, s.student_name;






