-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 04, 2023 at 03:35 AM
-- Server version: 5.7.44
-- PHP Version: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rxd7078_wdm_backend`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `Admin_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Communication`
--

CREATE TABLE `Communication` (
  `Message_id` int(255) NOT NULL,
  `Sender_id` int(255) NOT NULL,
  `Receiver_id` int(255) NOT NULL,
  `Content` varchar(255) NOT NULL,
  `Time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Course`
--

CREATE TABLE `Course` (
  `Course_id` int(255) NOT NULL,
  `Course_desc` varchar(255) NOT NULL,
  `Course_name` varchar(255) NOT NULL,
  `instructor_teaching` varchar(255) NOT NULL,
  `course_objectives` varchar(255) NOT NULL,
  `program` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Course`
--

INSERT INTO `Course` (`Course_id`, `Course_desc`, `Course_name`, `instructor_teaching`, `course_objectives`, `program`) VALUES
(1, 'Course will cover in depth concepts of AI & ML.', 'Machine Learning', 'Alex Dilhoff', 'CSE-6363', 'Computer Science'),
(2, 'You learn multiple languages ', 'Web Data Management', 'Elizabeth Diaz', 'CSE-5365', 'Computer Science'),
(3, 'Algorithms and design analysis', 'DAA', 'Song Jiang', 'CSE-5312', 'Computer Science'),
(5, 'abc', 'PLC', 'Kelly french', 'CSE-5477', 'Computer Science');

-- --------------------------------------------------------

--
-- Table structure for table `CourseContent`
--

CREATE TABLE `CourseContent` (
  `Content_id` int(255) NOT NULL,
  `course_material` varchar(255) NOT NULL,
  `courseFile` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Exam`
--

CREATE TABLE `Exam` (
  `Exam_id` int(255) NOT NULL,
  `Course_id` int(255) NOT NULL,
  `instructor_mail` varchar(255) NOT NULL,
  `Exam_date` varchar(255) NOT NULL,
  `Max_score` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Exam`
--

INSERT INTO `Exam` (`Exam_id`, `Course_id`, `instructor_mail`, `Exam_date`, `Max_score`) VALUES
(1, 1, 'alex.dilhoff@gmail.com', '20 October 2023', 100),
(2, 2, 'negin.damt@gmail.com', '25 October 2023', 60),
(3, 3, 'murad.ishraf@gmail.com', '18th October 2023', 100);

-- --------------------------------------------------------

--
-- Table structure for table `Feedback`
--

CREATE TABLE `Feedback` (
  `Feedback_id` int(255) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `Message` mediumtext NOT NULL,
  `reply_msg` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Feedback`
--

INSERT INTO `Feedback` (`Feedback_id`, `sender`, `Message`, `reply_msg`) VALUES
(1, 'vidhi12desai@gmail.com', 'My problem is with Abhu.', 'np');

-- --------------------------------------------------------

--
-- Table structure for table `ForgotPassword`
--

CREATE TABLE `ForgotPassword` (
  `Forgot_id` int(255) NOT NULL,
  `Email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `guidelines`
--

CREATE TABLE `guidelines` (
  `guideline_id` int(255) NOT NULL,
  `progcord_email` varchar(255) NOT NULL,
  `guideline` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Instructor`
--

CREATE TABLE `Instructor` (
  `Instructor_id` int(255) NOT NULL,
  `Course_id` int(255) NOT NULL,
  `Exam_id` int(255) NOT NULL,
  `Performance_id` int(255) NOT NULL,
  `Course_taught` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Login`
--

CREATE TABLE `Login` (
  `Login_id` int(255) NOT NULL,
  `Register_id` int(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `manage_policy`
--

CREATE TABLE `manage_policy` (
  `policy_id` int(255) NOT NULL,
  `policy_name` varchar(255) NOT NULL,
  `policy_desc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `manage_policy`
--

INSERT INTO `manage_policy` (`policy_id`, `policy_name`, `policy_desc`) VALUES
(1, 'Policy-1', 'We gotta do..{{');

-- --------------------------------------------------------

--
-- Table structure for table `manage_students`
--

CREATE TABLE `manage_students` (
  `manage_student_id` int(255) NOT NULL,
  `course_id` int(255) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_email` varchar(255) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `program_name` varchar(255) NOT NULL,
  `isapproved` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `manage_students`
--

INSERT INTO `manage_students` (`manage_student_id`, `course_id`, `student_name`, `student_email`, `course_name`, `program_name`, `isapproved`) VALUES
(6, 1, 'Vidhi', 'desaividhi123@gmail.com', 'Machine Learning', 'Computer Science', NULL),
(7, 2, 'Deep ', 'deep.patel@gmail.com', 'Web Data Management', 'Computer Science', NULL),
(9, 1, 'Vidhi', 'vidhi.desai788@gmail.com', 'Machine Learning', 'Computer Science', NULL),
(13, 5, 'Deep', 'Deep@gmail.com', 'PLC', 'Computer Science', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Performance`
--

CREATE TABLE `Performance` (
  `Performance_id` int(255) NOT NULL,
  `User_id` int(255) NOT NULL,
  `Exam_id` int(255) NOT NULL,
  `Score` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ProgCoordinator`
--

CREATE TABLE `ProgCoordinator` (
  `ProgCoordinator_id` int(255) NOT NULL,
  `PE_id` int(255) NOT NULL,
  `coordinator_name` varchar(255) NOT NULL,
  `coordinator_mail` varchar(255) NOT NULL,
  `co_program` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ProgCoordinator`
--

INSERT INTO `ProgCoordinator` (`ProgCoordinator_id`, `PE_id`, `coordinator_name`, `coordinator_mail`, `co_program`) VALUES
(1, 1, 'Donna French', 'french.donna@uta.edu', 'Computer Science'),
(2, 2, 'John Doe', 'john.doe@gmail.com', 'INSY'),
(4, 3, 'VDRS,JECVKDR', 'abc@gmail.com', 'FSZAVRD');

-- --------------------------------------------------------

--
-- Table structure for table `Program`
--

CREATE TABLE `Program` (
  `Program_id` int(255) NOT NULL,
  `ProgramCoordinator_id` int(255) NOT NULL,
  `Program_name` varchar(255) NOT NULL,
  `Course_id` int(255) NOT NULL,
  `Objective_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ProgramObjectives`
--

CREATE TABLE `ProgramObjectives` (
  `Objective_id` int(255) NOT NULL,
  `program` varchar(255) NOT NULL,
  `program_objective` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ProgramObjectives`
--

INSERT INTO `ProgramObjectives` (`Objective_id`, `program`, `program_objective`) VALUES
(1, 'Computer Science', ' Students should gain proficiency in programming languages, software development methodologies, and tools commonly used in the field.'),
(2, 'Aerospace', ' Understanding aerodynamics and flight mechanics is fundamental to aerospace engineering. This includes topics like lift, drag, stability, and control.');

-- --------------------------------------------------------

--
-- Table structure for table `ProgramsManaged`
--

CREATE TABLE `ProgramsManaged` (
  `Programs_managed_id` int(255) NOT NULL,
  `ProgramCoordinator_id` int(255) NOT NULL,
  `Program_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `QA_officer`
--

CREATE TABLE `QA_officer` (
  `QA_officer_id` int(11) NOT NULL,
  `Content_id` int(11) NOT NULL,
  `P_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Register`
--

CREATE TABLE `Register` (
  `Register_id` int(255) NOT NULL,
  `F_name` varchar(255) NOT NULL,
  `L_name` varchar(255) NOT NULL,
  `Role` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Remember_flag` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` int(255) NOT NULL,
  `f_name` varchar(255) NOT NULL,
  `l_name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_approved` tinyint(1) DEFAULT NULL,
  `validation_token` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `f_name`, `l_name`, `role`, `email`, `password`, `is_approved`, `validation_token`, `is_verified`) VALUES
(6, 'Abhishek', 'Doshi', 'admin', 'abhidoshi2000@gmail.com', 'rutvij', 1, NULL, NULL),
(9, 'Jainam', 'Gandhi', 'Student', 'jainamgandhis@gmail.com', 'Gandhijainam', 1, NULL, NULL),
(10, 'Vidhi', 'Desai', 'admin', 'vidhi.desai788@gmail.com', 'vidhi', 1, NULL, NULL),
(11, 'Vidhi', 'Desai', 'Student', 'desaividhi123@gmail.com', 'vidhi', 0, NULL, NULL),
(15, 'student', 'one', 'Student', 'studentone@gmail.com', 'rutvij', 0, NULL, NULL),
(16, 'yugam', 'chavda', 'QA Officer', 'yuchavda@gmail.com', '1234', 1, NULL, NULL),
(18, 'vd', 'de', 'Program Coordinator', 'vidhi12desai@gmail.com', '1234', 1, NULL, NULL),
(19, 'Vishwa', 'Desai', 'Instructor', 'vidhiplaystore12@gmail.com', '1234', 1, NULL, NULL),
(36, 'Vidhi', 'Desai', 'Student', 'vidhidesai.ict17@gmail.com', '1234', 1, 'c8b8c17df90f2c0850acf165307baa0f', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Report`
--

CREATE TABLE `Report` (
  `Report_id` int(255) NOT NULL,
  `Admin_id` int(255) NOT NULL,
  `Report_name` varchar(255) NOT NULL,
  `Report_data` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ResetPassword`
--

CREATE TABLE `ResetPassword` (
  `Reset_id` int(255) NOT NULL,
  `New_pass` varchar(255) NOT NULL,
  `Confirm_new_pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Student`
--

CREATE TABLE `Student` (
  `Student_id` int(255) NOT NULL,
  `Enrollment_id` int(255) NOT NULL,
  `Course_id` int(255) NOT NULL,
  `User_id` int(255) NOT NULL,
  `Enroll_status` varchar(255) NOT NULL,
  `Course_enrolled` varchar(255) NOT NULL,
  `Program_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student_score`
--

CREATE TABLE `student_score` (
  `score_id` int(255) NOT NULL,
  `student_email` varchar(255) NOT NULL,
  `instructor_mail` varchar(255) NOT NULL,
  `Date` varchar(255) NOT NULL,
  `score` int(255) NOT NULL,
  `max_score` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student_score`
--

INSERT INTO `student_score` (`score_id`, `student_email`, `instructor_mail`, `Date`, `score`, `max_score`) VALUES
(1, 'desaividhi123@gmail.com', 'vidhi.desai@gmail.com', '25 October 2024', 90, 100),
(2, 'vidhidesai.ict17@gmail.com', 'kelly@gmail.com', '25 October 2023', 65, 80);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `User_id` int(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `F_name` varchar(255) NOT NULL,
  `L_name` varchar(255) NOT NULL,
  `Role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`Admin_id`);

--
-- Indexes for table `Communication`
--
ALTER TABLE `Communication`
  ADD PRIMARY KEY (`Message_id`);

--
-- Indexes for table `Course`
--
ALTER TABLE `Course`
  ADD PRIMARY KEY (`Course_id`);

--
-- Indexes for table `CourseContent`
--
ALTER TABLE `CourseContent`
  ADD PRIMARY KEY (`Content_id`);

--
-- Indexes for table `Exam`
--
ALTER TABLE `Exam`
  ADD PRIMARY KEY (`Exam_id`);

--
-- Indexes for table `Feedback`
--
ALTER TABLE `Feedback`
  ADD PRIMARY KEY (`Feedback_id`);

--
-- Indexes for table `ForgotPassword`
--
ALTER TABLE `ForgotPassword`
  ADD PRIMARY KEY (`Forgot_id`);

--
-- Indexes for table `guidelines`
--
ALTER TABLE `guidelines`
  ADD PRIMARY KEY (`guideline_id`);

--
-- Indexes for table `Instructor`
--
ALTER TABLE `Instructor`
  ADD PRIMARY KEY (`Instructor_id`);

--
-- Indexes for table `Login`
--
ALTER TABLE `Login`
  ADD PRIMARY KEY (`Login_id`);

--
-- Indexes for table `manage_policy`
--
ALTER TABLE `manage_policy`
  ADD PRIMARY KEY (`policy_id`);

--
-- Indexes for table `manage_students`
--
ALTER TABLE `manage_students`
  ADD PRIMARY KEY (`manage_student_id`),
  ADD KEY `fk1` (`course_id`),
  ADD KEY `student_email` (`student_email`) USING BTREE,
  ADD KEY `student_email_2` (`student_email`) USING BTREE;

--
-- Indexes for table `Performance`
--
ALTER TABLE `Performance`
  ADD PRIMARY KEY (`Performance_id`);

--
-- Indexes for table `ProgCoordinator`
--
ALTER TABLE `ProgCoordinator`
  ADD PRIMARY KEY (`ProgCoordinator_id`);

--
-- Indexes for table `Program`
--
ALTER TABLE `Program`
  ADD PRIMARY KEY (`Program_id`);

--
-- Indexes for table `ProgramObjectives`
--
ALTER TABLE `ProgramObjectives`
  ADD PRIMARY KEY (`Objective_id`);

--
-- Indexes for table `ProgramsManaged`
--
ALTER TABLE `ProgramsManaged`
  ADD PRIMARY KEY (`Programs_managed_id`);

--
-- Indexes for table `QA_officer`
--
ALTER TABLE `QA_officer`
  ADD PRIMARY KEY (`QA_officer_id`);

--
-- Indexes for table `Register`
--
ALTER TABLE `Register`
  ADD PRIMARY KEY (`Register_id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `Report`
--
ALTER TABLE `Report`
  ADD PRIMARY KEY (`Report_id`);

--
-- Indexes for table `ResetPassword`
--
ALTER TABLE `ResetPassword`
  ADD PRIMARY KEY (`Reset_id`);

--
-- Indexes for table `Student`
--
ALTER TABLE `Student`
  ADD PRIMARY KEY (`Student_id`);

--
-- Indexes for table `student_score`
--
ALTER TABLE `student_score`
  ADD PRIMARY KEY (`score_id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`User_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admin`
--
ALTER TABLE `Admin`
  MODIFY `Admin_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Communication`
--
ALTER TABLE `Communication`
  MODIFY `Message_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Course`
--
ALTER TABLE `Course`
  MODIFY `Course_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `CourseContent`
--
ALTER TABLE `CourseContent`
  MODIFY `Content_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Exam`
--
ALTER TABLE `Exam`
  MODIFY `Exam_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Feedback`
--
ALTER TABLE `Feedback`
  MODIFY `Feedback_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ForgotPassword`
--
ALTER TABLE `ForgotPassword`
  MODIFY `Forgot_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guidelines`
--
ALTER TABLE `guidelines`
  MODIFY `guideline_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Instructor`
--
ALTER TABLE `Instructor`
  MODIFY `Instructor_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Login`
--
ALTER TABLE `Login`
  MODIFY `Login_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manage_policy`
--
ALTER TABLE `manage_policy`
  MODIFY `policy_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `manage_students`
--
ALTER TABLE `manage_students`
  MODIFY `manage_student_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `Performance`
--
ALTER TABLE `Performance`
  MODIFY `Performance_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ProgCoordinator`
--
ALTER TABLE `ProgCoordinator`
  MODIFY `ProgCoordinator_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Program`
--
ALTER TABLE `Program`
  MODIFY `Program_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ProgramObjectives`
--
ALTER TABLE `ProgramObjectives`
  MODIFY `Objective_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ProgramsManaged`
--
ALTER TABLE `ProgramsManaged`
  MODIFY `Programs_managed_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `QA_officer`
--
ALTER TABLE `QA_officer`
  MODIFY `QA_officer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Register`
--
ALTER TABLE `Register`
  MODIFY `Register_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `Report`
--
ALTER TABLE `Report`
  MODIFY `Report_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ResetPassword`
--
ALTER TABLE `ResetPassword`
  MODIFY `Reset_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Student`
--
ALTER TABLE `Student`
  MODIFY `Student_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_score`
--
ALTER TABLE `student_score`
  MODIFY `score_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `User_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `manage_students`
--
ALTER TABLE `manage_students`
  ADD CONSTRAINT `fk1` FOREIGN KEY (`course_id`) REFERENCES `Course` (`Course_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
