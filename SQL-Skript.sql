DROP DATABASE IF EXISTS studyorganizer;
CREATE DATABASE studyorganizer;
USE studyorganizer;


CREATE TABLE Course(
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name varchar(255) NOT NULL
);

CREATE TABLE User(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username varchar(255) NOT NULL UNIQUE,
    password_hash varchar(255) NOT NULL,
    user_role varchar(255) NOT NULL
);

CREATE TABLE Assignment(
    id INT AUTO_INCREMENT PRIMARY KEY,
    title varchar(255) NOT NULL,
    description TEXT,
    due_date DATE NOT NULL,
    is_done TINYINT(1) DEFAULT 0,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES User(id),
    FOREIGN KEY (course_id) REFERENCES Course(id),
    FOREIGN KEY (teacher_id) REFERENCES Teacher(id)
);

CREATE TABLE Teacher(
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_name varchar(255) NOT NULL,
    course_id INT NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
);

CREATE TABLE Teacher_Course(
    teacher_id INT NOT NULL,    
    course_id INT NOT NULL,
    PRIMARY KEY (teacher_id, course_id),
    FOREIGN KEY (teacher_id) REFERENCES Teacher(id),
    FOREIGN KEY (course_id) REFERENCES Course(id)
);
