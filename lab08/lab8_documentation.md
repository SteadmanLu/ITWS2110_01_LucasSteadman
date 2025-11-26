# Lab 8 - SQL & PHP Documentation
## SQL Commands and Output

**Name:** [Lucas Steadman]  
**RCS ID:** [Steadl2]  
**Date:** November 21, 2025

---

## PART 1 (20 Points)

### Question 1: Create database named websyslab8

**Command:**
```sql
CREATE DATABASE websyslab8;
```

**Output:**
```
Query OK, 1 row affected (0.01 sec)
```

**Command:**
```sql
USE websyslab8;
```

**Output:**
```
Database changed
```

---

### Question 2: Create courses table with specified columns

**Description:** Create a table named courses containing: crn (int 11, primary key), prefix (varchar 4, not null), number (smallint 4, not null), title (varchar 255, not null), with collate utf8_unicode_ci

**Command:**
```sql
CREATE TABLE courses (
    crn INT(11) PRIMARY KEY,
    prefix VARCHAR(4) NOT NULL,
    number SMALLINT(4) NOT NULL,
    title VARCHAR(255) NOT NULL
) COLLATE utf8_unicode_ci;
```

**Output:**
```
Query OK, 0 rows affected, 3 warnings (0.04 sec)
```

---

### Question 3: Create students table with specified columns

**Description:** Create a table named students containing: rin (int 9, primary key), rcsID (char 7), first name (varchar 100, not null), last name (varchar 100, not null), alias (varchar 100, not null), phone (int 10), with collate utf8_unicode_ci

**Command:**
```sql
CREATE TABLE students (
    rin INT(9) PRIMARY KEY,
    rcsID CHAR(7),
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    alias VARCHAR(100) NOT NULL,
    phone INT(10)
) COLLATE utf8_unicode_ci;
```

**Output:**
```
Query OK, 0 rows affected, 3 warnings (0.04 sec)
```

---

## PART 2 (30 Points)

### Question 1: Add address fields (street, city, state, zip) to the students table

**Command:**
```sql
ALTER TABLE students
ADD COLUMN street VARCHAR(255),
ADD COLUMN city VARCHAR(100),
ADD COLUMN state CHAR(2),
ADD COLUMN zip INT(5);
```

**Output:**
```
Query OK, 0 rows affected, 1 warning (0.08 sec)
Records: 0  Duplicates: 0  Warnings: 1
```

---

### Question 2: Add section and year fields to the courses table

**Command:**
```sql
ALTER TABLE courses
ADD COLUMN section VARCHAR(10),
ADD COLUMN year INT(4);
```

**Output:**
```
Query OK, 0 rows affected, 1 warning (0.08 sec)
Records: 0  Duplicates: 0  Warnings: 1
```

---

### Question 3: CREATE a grades table containing id (int primary key, auto increment), crn (foreign key), rin (foreign key), grade (int 3 not null)

**Command:**
```sql
CREATE TABLE grades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    crn INT(11),
    rin INT(9),
    grade INT(3) NOT NULL,
    FOREIGN KEY (crn) REFERENCES courses(crn),
    FOREIGN KEY (rin) REFERENCES students(rin)
) COLLATE utf8_unicode_ci;
```

**Output:**
```
Query OK, 0 rows affected, 4 warnings (0.06 sec)
```

---

### Additional Step: Fix phone data type issue

**Note:** Had to modify phone column from INT to BIGINT because 10-digit phone numbers exceeded INT maximum value (2,147,483,647)

**Command:**
```sql
ALTER TABLE students MODIFY phone BIGINT;
```

**Output:**
```
Query OK, 0 rows affected (0.11 sec)
Records: 0  Duplicates: 0  Warnings: 0
```

---

### Question 4: INSERT at least 4 courses into the courses table

**Command:**
```sql
INSERT INTO courses (crn, prefix, number, title, section, year) VALUES
(12345, 'CSCI', 2300, 'Introduction to Algorithms', '01', 2025),
(12346, 'CSCI', 4210, 'Web Systems Development', '01', 2025),
(12347, 'MATH', 2400, 'Introduction to Differential Equations', '02', 2025),
(12348, 'CSCI', 4430, 'Programming Languages', '01', 2025);
```

**Output:**
```
Query OK, 4 rows affected (0.01 sec)
Records: 4  Duplicates: 0  Warnings: 0
```

**Result:** Successfully inserted 4 courses (CSCI 2300, CSCI 4210, MATH 2400, CSCI 4430)

---

### Question 5: INSERT at least 4 students into the students table

**Command:**
```sql
INSERT INTO students (rin, rcsID, first_name, last_name, alias, phone, street, city, state, zip) VALUES 
(661234567, 'smithj', 'John', 'Smith', 'Johnny', 5185551234, '123 Main St', 'Troy', 'NY', 12180), 
(661234568, 'doej', 'Jane', 'Doe', 'JD', 5185555678, '456 Oak Ave', 'Troy', 'NY', 12180), 
(661234569, 'johnsa', 'Alice', 'Johnson', 'Ali', 5185559876, '789 Elm St', 'Troy', 'NY', 12181), 
(661234570, 'brownb', 'Bob', 'Brown', 'Bobby', 5185554321, '321 Pine Rd', 'Troy', 'NY', 12180);
```

**Output:**
```
Query OK, 4 rows affected (0.01 sec)
Records: 4  Duplicates: 0  Warnings: 0
```

**Result:** Successfully inserted 4 students (John Smith, Jane Doe, Alice Johnson, Bob Brown)

---

### Question 6: ADD 10 grades into the grades table

**Command:**
```sql
INSERT INTO grades (crn, rin, grade) VALUES
(12345, 661234567, 95),
(12345, 661234568, 88),
(12346, 661234567, 92),
(12346, 661234569, 85),
(12347, 661234568, 78),
(12347, 661234570, 91),
(12348, 661234569, 94),
(12348, 661234570, 87),
(12345, 661234569, 82),
(12346, 661234570, 96);
```

**Output:**
```
Query OK, 10 rows affected (0.01 sec)
Records: 10  Duplicates: 0  Warnings: 0
```

**Result:** Successfully inserted 10 grade records linking students to courses with their grades

---

### Question 7: List all students in the following sequences; in alphabetical order by rin, last name, RCSid, and firstname

#### 7a. List all students ordered by RIN

**Command:**
```sql
SELECT * FROM students ORDER BY rin;
```

**Output:**
```
+-----------+--------+------------+-----------+--------+------------+-------------+------+-------+-------+
| rin       | rcsID  | first_name | last_name | alias  | phone      | street      | city | state | zip   |
+-----------+--------+------------+-----------+--------+------------+-------------+------+-------+-------+
| 661234567 | smithj | John       | Smith     | Johnny | 5185551234 | 123 Main St | Troy | NY    | 12180 |
| 661234568 | doej   | Jane       | Doe       | JD     | 5185555678 | 456 Oak Ave | Troy | NY    | 12180 |
| 661234569 | johnsa | Alice      | Johnson   | Ali    | 5185559876 | 789 Elm St  | Troy | NY    | 12181 |
| 661234570 | brownb | Bob        | Brown     | Bobby  | 5185554321 | 321 Pine Rd | Troy | NY    | 12180 |
+-----------+--------+------------+-----------+--------+------------+-------------+------+-------+-------+
4 rows in set (0.00 sec)
```

#### 7b. List all students ordered by Last Name

**Command:**
```sql
SELECT * FROM students ORDER BY last_name;
```

**Output:**
```
+-----------+--------+------------+-----------+--------+------------+-------------+------+-------+-------+
| rin       | rcsID  | first_name | last_name | alias  | phone      | street      | city | state | zip   |
+-----------+--------+------------+-----------+--------+------------+-------------+------+-------+-------+
| 661234570 | brownb | Bob        | Brown     | Bobby  | 5185554321 | 321 Pine Rd | Troy | NY    | 12180 |
| 661234568 | doej   | Jane       | Doe       | JD     | 5185555678 | 456 Oak Ave | Troy | NY    | 12180 |
| 661234569 | johnsa | Alice      | Johnson   | Ali    | 5185559876 | 789 Elm St  | Troy | NY    | 12181 |
| 661234567 | smithj | John       | Smith     | Johnny | 5185551234 | 123 Main St | Troy | NY    | 12180 |
+-----------+--------+------------+-----------+--------+------------+-------------+------+-------+-------+
4 rows in set (0.00 sec)
```

#### 7c. List all students ordered by RCS ID

**Command:**
```sql
SELECT * FROM students ORDER BY rcsID;
```

**Output:**
```
+-----------+--------+------------+-----------+--------+------------+-------------+------+-------+-------+
| rin       | rcsID  | first_name | last_name | alias  | phone      | street      | city | state | zip   |
+-----------+--------+------------+-----------+--------+------------+-------------+------+-------+-------+
| 661234570 | brownb | Bob        | Brown     | Bobby  | 5185554321 | 321 Pine Rd | Troy | NY    | 12180 |
| 661234568 | doej   | Jane       | Doe       | JD     | 5185555678 | 456 Oak Ave | Troy | NY    | 12180 |
| 661234569 | johnsa | Alice      | Johnson   | Ali    | 5185559876 | 789 Elm St  | Troy | NY    | 12181 |
| 661234567 | smithj | John       | Smith     | Johnny | 5185551234 | 123 Main St | Troy | NY    | 12180 |
+-----------+--------+------------+-----------+--------+------------+-------------+------+-------+-------+
4 rows in set (0.00 sec)
```

#### 7d. List all students ordered by First Name

**Command:**
```sql
SELECT * FROM students ORDER BY first_name;
```

**Output:**
```
+-----------+--------+------------+-----------+--------+------------+-------------+------+-------+-------+
| rin       | rcsID  | first_name | last_name | alias  | phone      | street      | city | state | zip   |
+-----------+--------+------------+-----------+--------+------------+-------------+------+-------+-------+
| 661234569 | johnsa | Alice      | Johnson   | Ali    | 5185559876 | 789 Elm St  | Troy | NY    | 12181 |
| 661234570 | brownb | Bob        | Brown     | Bobby  | 5185554321 | 321 Pine Rd | Troy | NY    | 12180 |
| 661234568 | doej   | Jane       | Doe       | JD     | 5185555678 | 456 Oak Ave | Troy | NY    | 12180 |
| 661234567 | smithj | John       | Smith     | Johnny | 5185551234 | 123 Main St | Troy | NY    | 12180 |
+-----------+--------+------------+-----------+--------+------------+-------------+------+-------+-------+
4 rows in set (0.00 sec)
```

---

### Question 8: List all students rin, name and address if their grade in any course was higher than a 90

**Command:**
```sql
SELECT DISTINCT s.rin, s.first_name, s.last_name, s.street, s.city, s.state, s.zip
FROM students s
JOIN grades g ON s.rin = g.rin
WHERE g.grade > 90;
```

**Output:**
```
+-----------+------------+-----------+-------------+------+-------+-------+
| rin       | first_name | last_name | street      | city | state | zip   |
+-----------+------------+-----------+-------------+------+-------+-------+
| 661234567 | John       | Smith     | 123 Main St | Troy | NY    | 12180 |
| 661234569 | Alice      | Johnson   | 789 Elm St  | Troy | NY    | 12181 |
| 661234570 | Bob        | Brown     | 321 Pine Rd | Troy | NY    | 12180 |
+-----------+------------+-----------+-------------+------+-------+-------+
3 rows in set (0.00 sec)
```

**Result:** 3 students (John Smith with grade 95 and 92, Alice Johnson with grade 94, Bob Brown with grades 91 and 96) scored above 90 in at least one course.

---

### Question 9: List out the average grade in each course

**Command:**
```sql
SELECT c.crn, c.prefix, c.number, c.title, AVG(g.grade) AS average_grade
FROM courses c
JOIN grades g ON c.crn = g.crn
GROUP BY c.crn, c.prefix, c.number, c.title;
```

**Output:**
```
+-------+--------+--------+----------------------------------------+---------------+
| crn   | prefix | number | title                                  | average_grade |
+-------+--------+--------+----------------------------------------+---------------+
| 12345 | CSCI   |   2300 | Introduction to Algorithms             |       88.3333 |
| 12346 | CSCI   |   4210 | Web Systems Development                |       91.0000 |
| 12347 | MATH   |   2400 | Introduction to Differential Equations |       84.5000 |
| 12348 | CSCI   |   4430 | Programming Languages                  |       90.5000 |
+-------+--------+--------+----------------------------------------+---------------+
4 rows in set (0.00 sec)
```

**Result:** 
- Introduction to Algorithms (CSCI 2300): Average grade 88.33
- Web Systems Development (CSCI 4210): Average grade 91.00 (highest)
- Introduction to Differential Equations (MATH 2400): Average grade 84.50 (lowest)
- Programming Languages (CSCI 4430): Average grade 90.50

---

### Question 10: List out the number of students in each course

**Command:**
```sql
SELECT c.crn, c.prefix, c.number, c.title, COUNT(DISTINCT g.rin) AS student_count
FROM courses c
JOIN grades g ON c.crn = g.crn
GROUP BY c.crn, c.prefix, c.number, c.title;
```

**Output:**
```
+-------+--------+--------+----------------------------------------+---------------+
| crn   | prefix | number | title                                  | student_count |
+-------+--------+--------+----------------------------------------+---------------+
| 12345 | CSCI   |   2300 | Introduction to Algorithms             |             3 |
| 12346 | CSCI   |   4210 | Web Systems Development                |             3 |
| 12347 | MATH   |   2400 | Introduction to Differential Equations |             2 |
| 12348 | CSCI   |   4430 | Programming Languages                  |             2 |
+-------+--------+--------+----------------------------------------+---------------+
4 rows in set (0.00 sec)
```

**Result:**
- Introduction to Algorithms (CSCI 2300): 3 students (John Smith, Jane Doe, Alice Johnson)
- Web Systems Development (CSCI 4210): 3 students (John Smith, Alice Johnson, Bob Brown)
- Introduction to Differential Equations (MATH 2400): 2 students (Jane Doe, Bob Brown)
- Programming Languages (CSCI 4430): 2 students (Alice Johnson, Bob Brown)
