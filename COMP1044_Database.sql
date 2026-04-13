-- COMP1044 Internship Result Management System
-- Database: comp1044_cw_g26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `assessment_criteria` (
  `criteria_id` int(11) NOT NULL,
  `criteria_name` varchar(100) NOT NULL,
  `weightage` decimal(5,2) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `assessment_criteria` (`criteria_id`, `criteria_name`, `weightage`, `description`) VALUES
(1, 'Undertaking Tasks/Projects', '10.00', 'Ability to complete assigned tasks and projects'),
(2, 'Health and Safety Requirements at the Workplace', '10.00', 'Compliance with workplace safety standards'),
(3, 'Connectivity and Use of Theoretical Knowledge', '10.00', 'Application of theoretical concepts to practice'),
(4, 'Presentation of the Report as a Written Document', '15.00', 'Quality of written report'),
(5, 'Clarity of Language and Illustration', '10.00', 'Communication clarity and use of visuals'),
(6, 'Lifelong Learning Activities', '15.00', 'Demonstration of continuous learning'),
(7, 'Project Management', '15.00', 'Project planning and execution skills'),
(8, 'Time Management', '15.00', 'Time management and deadline adherence');

CREATE TABLE `assessment_results` (
  `result_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `score` decimal(5,2) NOT NULL,
  `comments` text,
  `assessed_by` int(11) NOT NULL,
  `assessment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `student_code` varchar(20) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `programme` varchar(100) NOT NULL,
  `company_name` varchar(200) DEFAULT NULL,
  `assessor_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `students` (`student_id`, `student_code`, `student_name`, `programme`, `company_name`, `assessor_id`, `created_at`) VALUES
(1, 'S001', 'Alice Tan', 'Computer Science', 'Tech Solutions Sdn Bhd', 2, '2026-03-29 06:17:51'),
(2, 'S002', 'Bob Lim', 'Software Engineering', 'Digital Innovations', 2, '2026-03-29 06:17:51'),
(3, 'S003', 'Carol Wong', 'Information Systems', 'Global IT Services', 3, '2026-03-29 06:17:51'),
(4, 'S004', 'David Lee', 'Computer Science', 'Data Systems Corp', 3, '2026-03-29 06:17:51');

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','assessor') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`user_id`, `username`, `password`, `full_name`, `email`, `role`, `created_at`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'System Administrator', 'admin@university.edu', 'admin', '2026-03-29 06:17:51'),
(2, 'prof_smith', '1b0a3ec526f0fb6755be187bb93d8b57', 'Prof. John Smith', 'john.smith@university.edu', 'assessor', '2026-03-29 06:17:51'),
(3, 'dr_jones', '0c19246628ada3bbec5729fb1ff89078', 'Dr. Sarah Jones', 'sarah.jones@university.edu', 'assessor', '2026-03-29 06:17:51');

ALTER TABLE `assessment_criteria`
  ADD PRIMARY KEY (`criteria_id`);

ALTER TABLE `assessment_results`
  ADD PRIMARY KEY (`result_id`),
  ADD UNIQUE KEY `unique_assessment` (`student_id`,`criteria_id`),
  ADD KEY `criteria_id` (`criteria_id`),
  ADD KEY `assessed_by` (`assessed_by`);

ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `student_code` (`student_code`),
  ADD KEY `assessor_id` (`assessor_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

ALTER TABLE `assessment_criteria`
  MODIFY `criteria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `assessment_results`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `assessment_results`
  ADD CONSTRAINT `assessment_results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assessment_results_ibfk_2` FOREIGN KEY (`criteria_id`) REFERENCES `assessment_criteria` (`criteria_id`),
  ADD CONSTRAINT `assessment_results_ibfk_3` FOREIGN KEY (`assessed_by`) REFERENCES `users` (`user_id`);

ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`assessor_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

COMMIT;
