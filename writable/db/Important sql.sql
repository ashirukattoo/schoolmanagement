use lyamahoro;
drop procedure if exists insertStudent;
DELIMITER //
CREATE PROCEDURE insertStudent(
	IN p_fname VARCHAR(45),
	IN p_mname VARCHAR(45),
	IN p_surname VARCHAR(45),
	IN p_sex ENUM('Male','Female'),
	IN p_dob DATE,
	IN p_stream INT,
	IN p_guardian INT ) BEGIN 
DECLARE v_count INT DEFAULT 0;
START TRANSACTION;
SELECT COUNT(*) INTO v_count FROM students WHERE LOWER(stuFname) = LOWER(p_fname)
              AND LOWER(stuSurname) = LOWER(p_surname)
              AND stream_id = p_stream
              AND stuDob = p_dob;
IF v_count > 0 THEN ROLLBACK;
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Student with same info already exists";
ELSE
	INSERT INTO students (stuFname, stuMname, stuSurname, stuSex, stuDob, stream_id, guardian_id, stuStatus)
                VALUES (p_fname, p_mname, p_surname, p_sex, p_dob, p_stream, p_guardian, 'Active');
	COMMIT;
END IF;
END //
DELIMITER ;

drop procedure if exists insertGuardian;
DELIMITER //
CREATE PROCEDURE insertGuardian(
	IN p_fname VARCHAR(75),
	IN p_sex ENUM('Male','Female'),
	IN p_dob DATE,
	IN p_email VARCHAR(75),
	IN p_password VARCHAR(255),
	IN p_occupation varchar(75)
    ) BEGIN 
DECLARE v_count INT DEFAULT 0;
START TRANSACTION;
SELECT COUNT(*) INTO v_count FROM guardians WHERE LOWER(empname) = LOWER(p_fname)
              AND LOWER(empEmail) = LOWER(p_email);
IF v_count > 0 THEN ROLLBACK;
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Guardian with same info already exists";
ELSE
	INSERT INTO guardians (guName, guSex, guDob, guOccupasion, guEmail, guPassword, guRegisterd, guStatus) 
			VALUES (lower(p_fname), p_sex, p_dob,  p_occupation, p_email, p_password, now(), 'Active');
	COMMIT;
END IF;
END //
DELIMITER ;

drop procedure if exists insertStream;
DELIMITER //
CREATE PROCEDURE insertStream(
	IN p_name VARCHAR(75),
	IN p_class INT,
    IN p_status VARCHAR(25)
    ) BEGIN 
DECLARE v_count INT DEFAULT 0;
START TRANSACTION;
SELECT COUNT(*) INTO v_count FROM streams WHERE LOWER(sName) = LOWER(p_name)
              AND class_id = p_class;
IF v_count > 0 THEN ROLLBACK;
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Stream with same info already exists";
ELSE
	INSERT INTO `streams` (`class_id`, `sName`, `sCreated`, `sStatus`) VALUES (p_class, p_name, now(), p_status);
	COMMIT;
END IF;
END //
DELIMITER ;

drop procedure if exists insertRoute;
DELIMITER //
CREATE PROCEDURE insertRoute(
	IN p_rouName VARCHAR(45),
	IN p_rouStart VARCHAR(45),
	IN p_rouEnd VARCHAR(45)
    ) BEGIN 
DECLARE v_count INT DEFAULT 0;
	START TRANSACTION;
	SELECT COUNT(*) INTO v_count FROM routes WHERE LOWER(rouName) = LOWER(p_rouName)  
		AND (LOWER(rouStart) = LOWER(p_roustart)  
		AND LOWER(rouEnd) = LOWER(p_rouEnd));
IF v_count > 0 THEN 
ROLLBACK;
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Route with same info already exists!";
ELSE
	INSERT INTO `routes` (`rouName`, `rouStart`, `rouEnd`, `rouCreated`, `rouStatus`) VALUES (p_rouName, p_rouStart, p_rouEnd, CURRENT_TIMESTAMP, 'active');
	COMMIT;
END IF;
END //
DELIMITER ;

-- Get Subjects on  Classes
SELECT scID, concat(named, ' -', sName) as class, subName,  isMandatory, isSubsidiary, hasPractical  FROM subject_streams ss
	JOIN streams st ON st.sid=ss.stream_id
    JOIN subjects sb ON sb.subID = ss.subject_id
    JOIN classes cl ON cl.cid = st.class_id;

-- Get Subject taught by
SELECT code as id, concat(named, '-', sName) as class, subName as subject, concat(empFname, ' ', empMname, ' ', empSurname) as teacher, assigneDate, todate as until, et.status FROM employee_teaches et
	join employees e on e.empID = et.employee_id
    join subject_streams ss on ss.scID = et.stream_id
    join subjects sb on sb.subID = ss.subject_id
    join streams st on st.sid = ss.stream_id
    join classes cl on cl.cid= st.class_id; 

CREATE TABLE `lyamahoro`.`timetable` (
  `tmID` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lesson` VARCHAR(15) NOT NULL,
  `startat` TIME NOT NULL,
  `endat` VARCHAR(45) NOT NULL,
  `periodNo` INT NOT NULL DEFAULT 0,
  `weekDay` ENUM("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Satday", "Sunday") NOT NULL,
  `term` VARCHAR(10) NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  `status` ENUM("Old", "Previous", "Active", "Next", "Preparation", "Deleted", "Draft") NOT NULL DEFAULT 'Preparation',
  PRIMARY KEY (`tmID`))
ENGINE = InnoDB
COMMENT = 'It handle lessons timetable by showin when start and when end up';

create table student_promotions(
	spid  int auto_increment primary key,
    student_id int not null,
    stream_id int not null,
    class_id int not null,
    level enum("O-level", "A-level"),
    action enum("Joined", "Promoted", "Repeated", "Completed", "Admitted"),
    action_date_ date, 
    created_at datetime default current_timestamp,
    updated datetime default null on update current_timestamp,
    foreign key (student_id) references students(stuID) on update cascade on delete cascade,
	foreign key (class_id) references classes(cid) on update cascade on delete cascade,
    foreign key (stream_id) references streams(sid) on update cascade on delete cascade
);

ALTER TABLE student_promotions
ADD academic_year_id VARCHAR(10) NOT NULL
AFTER student_id;
ALTER TABLE student_promotions
ADD CONSTRAINT fk_sp_ay
FOREIGN KEY (academic_year_id)
REFERENCES academicYears(ayID)
ON UPDATE CASCADE
ON DELETE RESTRICT;

CREATE TABLE salary_grades (
    grade_id INT AUTO_INCREMENT PRIMARY KEY,
    grade_name VARCHAR(100) NOT NULL,
    basic_salary DECIMAL(12,2) NOT NULL DEFAULT 0,
    housing_allowance DECIMAL(12,2) DEFAULT 0,
    transport_allowance DECIMAL(12,2) DEFAULT 0,
    medical_allowance DECIMAL(12,2) DEFAULT 0,
    other_allowance DECIMAL(12,2) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE employees 
ADD COLUMN empSalaryGradeID INT NULL AFTER empPassword;

ALTER TABLE employees
ADD CONSTRAINT fk_employee_grade
FOREIGN KEY (empSalaryGradeID)
REFERENCES salary_grades(grade_id)
ON DELETE SET NULL;

CREATE TABLE payroll_runs (
    payroll_id INT AUTO_INCREMENT PRIMARY KEY,
    payroll_month INT NOT NULL,
    payroll_year INT NOT NULL,
    status ENUM('Draft','Approved','Paid') DEFAULT 'Draft',
    processed_by INT NULL,
    processed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_payroll (payroll_month, payroll_year)
);

CREATE TABLE payroll_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    payroll_id INT NOT NULL,
    employee_id INT NOT NULL,

    basic_salary DECIMAL(12,2) DEFAULT 0,
    housing_allowance DECIMAL(12,2) DEFAULT 0,
    transport_allowance DECIMAL(12,2) DEFAULT 0,
    medical_allowance DECIMAL(12,2) DEFAULT 0,
    other_allowance DECIMAL(12,2) DEFAULT 0,

    gross_salary DECIMAL(12,2) DEFAULT 0,
    total_deductions DECIMAL(12,2) DEFAULT 0,
    net_salary DECIMAL(12,2) DEFAULT 0,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (payroll_id) REFERENCES payroll_runs(payroll_id) ON DELETE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES employees(empID) ON DELETE CASCADE
);

CREATE TABLE deductions (
    deductionID INT AUTO_INCREMENT PRIMARY KEY,
    deduction_name VARCHAR(100) NOT NULL,
    deduction_code VARCHAR(20) UNIQUE,
    deduction_type ENUM('percentage','fixed') NOT NULL,
    deduction_value DECIMAL(10,2) NOT NULL,
    applies_to ENUM('basic','gross') NOT NULL,
    is_statutory TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE employee_deductions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empID INT NOT NULL,
    deductionID INT NOT NULL,
    custom_value DECIMAL(10,2) NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (empID) REFERENCES employees(empID),
    FOREIGN KEY (deductionID) REFERENCES deductions(deductionID)
);

CREATE TABLE payroll_item_deductions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payroll_item_id INT NOT NULL,
    deduction_name VARCHAR(100),
    amount DECIMAL(12,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (payroll_item_id) REFERENCES payroll_items(item_id)
);

INSERT INTO deductions 
(deduction_name, deduction_type, deduction_value, applies_to, is_active)
VALUES
('PAYE', 'percentage', 10, 'gross', 1),
('Pension', 'percentage', 5, 'basic', 1),
('NHIF', 'fixed', 1500, 'gross', 1);

CREATE TABLE exam_analytics(
    eaid INT AUTO_INCREMENT PRIMARY KEY,
    exam_id varchar(11) NOT NULL,
    registered int(4) not null default 0,
    sat int(4) not null default 0,
    absent int(4) not null default 0,
    passed int(4) not null default 0,
    failed int(4) not null default 0,
    gpa decimal (7, 5) not null default 0,
    gpa_grade char(2) not null default 0,
    pass_percent DECIMAL(5,2) not null default 0,
    grade_summary text,
    subject_summary text,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT null,
    eastatus enum('raw', 'compiled', 'forwarded', 'locked', 'rejected', 'deleted') default 'raw',
    FOREIGN KEY (exam_id) REFERENCES exams(exID) on update cascade on delete cascade
);

-- Analyse the Results
SELECT * FROM lyamahoro.compiledresults
	where exam_id = 'EXOL800001'
		ORDER BY
			crAverage desc,
			CASE crDivision
				WHEN 'I' THEN 1
				WHEN 'II' THEN 2
				WHEN 'III' THEN 3
				WHEN 'IV' THEN 4
				WHEN '0' THEN 5
				WHEN 'INCOMP' THEN 6
				ELSE 7
			END ASC,
            crPoints ASC;

select subName as subject, raGrade as grade, count(student_id) as num, 
		(select count(distinct student_id) from rawresults where exam_id='EXOL800001') as sat 
	from rawresults ra
	join subjects sb on ra.subject_id = sb.subID
    where exam_id = 'EXOL800001'
    group by subName, raGrade
    order by subName ASC, raGrade ASC;

ALTER TABLE `lyamahoro`.`compiledresults` 
	ADD COLUMN `crTotal` FLOAT(6,2) NOT NULL AFTER `crResults`,
	ADD COLUMN `crAverage` DECIMAL(5,2) NOT NULL AFTER `crTotal`,
	ADD COLUMN `crPoints` INT NOT NULL AFTER `crAverage`,
	ADD COLUMN `crDivision` CHAR(3) NOT NULL AFTER `crPoints`,
	ADD COLUMN `crGPA` DECIMAL(5,3) NULL AFTER `crDivision`,
	ADD COLUMN `crgpa_class` VARCHAR(20) NULL AFTER `crGPA`,
	ADD COLUMN `crRemarks` VARCHAR(45) NOT NULL AFTER `crgpa_class`,
	ADD COLUMN `crMaoni` VARCHAR(45) NULL AFTER `crRemarks`;

CREATE TABLE exam_subject_summary (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_id VARCHAR(11),
    subject_id INT,
    reg INT,
    seat INT,
    abs INT,
    grades_json JSON,   -- 🔥 dynamic grades
    pass INT,
    pass_percent DECIMAL(5,2),
    gpa DECIMAL(5,4),
    grade VARCHAR(2),
    level VARCHAR(10), -- OLEVEL / ALEVEL
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    stream_id INT,
    session enum('Morning', 'Noon', 'Evening') not null default 'Morning',
    date DATE,
    term_id varchar(10) not null,
    status ENUM('I','A','P','S'),
	created_at timestamp default current_timestamp,
    updated_at datetime default null on update current_timestamp,
    recorded_by int not null,
    UNIQUE KEY unique_attendance (student_id, date, session)
);

CREATE INDEX idx_student ON attendance(student_id);
CREATE INDEX idx_date ON attendance(date);
CREATE INDEX idx_stream ON attendance(stream_id);
CREATE INDEX idx_term ON attendance(term_id);

ALTER TABLE attendance
ADD FOREIGN KEY (student_id) REFERENCES students(stuID),
ADD FOREIGN KEY (stream_id) REFERENCES streams(sid);

CREATE TABLE sms_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NULL,
    phone VARCHAR(20),
    message TEXT,
    status VARCHAR(20),
    response TEXT,
    sent_by INT,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE students
ADD guardian_phone VARCHAR(20) null unique default null after guardian_id;
CREATE TABLE sms_queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(20),
    message TEXT,
    status ENUM('Pending','Sent','Failed') DEFAULT 'Pending',
    retries INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

create table if not exists roles(
	id int not null auto_increment primary key,
    roleName varchar(75) not null default 'unnamed',
    ranked int default 10,
    status enum('Active', 'Deleted') default 'Active'
    );
insert into roles (id, roleName, ranked) values 
(null, 'Admin', 1),
(null, 'Head', 2),
(null, 'Deputy', 3),
(null, 'Academic', 4),
(null, 'Bursor', 5),
(null, 'Discipline', 6),
(null, 'CM', 7),
(null, 'SM', 8),
(null, 'Secretary', 3);


CREATE TABLE student_promotions (
    pid INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    from_class_id INT NOT NULL,
    from_stream_id INT NOT NULL,
    to_class_id INT NOT NULL,
    to_stream_id INT NOT NULL,
    academic_year VARCHAR(20) NOT NULL,
    promotion_type ENUM('Individual', 'Stream', 'Class',  'Level') NOT NULL,
    remarks TEXT NULL,
    promoted_by INT NOT NULL,
    promoted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending',  'Completed', 'RolledBack',  'Failed') DEFAULT 'Completed'
);

CREATE TABLE student_promotion_sessions (
    psid INT AUTO_INCREMENT PRIMARY KEY,
    academic_year VARCHAR(20) NOT NULL,
    promotion_scope ENUM('Individual', 'Stream', 'Class',  'Level') NOT NULL,
    created_by INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM( 'Draft',  'Confirmed',    'Cancelled'  ) DEFAULT 'Draft'
);