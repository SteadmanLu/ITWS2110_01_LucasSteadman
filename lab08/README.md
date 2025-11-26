# Lab 8 - SQL & PHP
## README
- **Name:** [Lucas Steadman]
- **RCS ID:** [Steadl2]
- **Date:** November 21, 2025

---

### Overview
This lab focused on creating and manipulating a MySQL database using SQL commands. The lab involved creating three tables (courses, students, and grades), establishing relationships between them using foreign keys, and performing various queries to retrieve and analyze data.

---

### Challenges Encountered

#### 1. Phone Number Data Type Issue
**Problem:** When inserting student data, received error: `ERROR 1264 (22003): Out of range value for column 'phone' at row 1`

**Cause:** The phone column was defined as `INT(10)`, but MySQL's INT type can only store values up to 2,147,483,647. US phone numbers (like 5185551234) exceed this limit.

**Solution:** Modified the phone column to BIGINT using:
```sql
ALTER TABLE students MODIFY phone BIGINT;
```

---

#### 2. Collation Typo
**Problem:** First attempt at creating the courses table failed with syntax error.

**Cause:** Typed `uft8_unicode_ci` instead of `utf8_unicode_ci` (missing the '8').

**Solution:** Corrected the typo in the second attempt.

---

#### 3. Foreign Key Reference Error
**Problem:** When creating the grades table, got error: `ERROR 1824 (HY000): Failed to open the referenced table 'cources'`

**Cause:** Misspelled "courses" as "cources" in the FOREIGN KEY reference.

**Solution:** Corrected the spelling to reference `courses(crn)` properly.

---

### Key Observations

#### Database Design
- Successfully created a relational database with three interconnected tables
- Foreign keys in the grades table (crn and rin) maintain referential integrity
- The design allows tracking which students are enrolled in which courses and their grades

#### Data Relationships
- One-to-many relationship: One course can have many grades
- One-to-many relationship: One student can have many grades
- Many-to-many relationship: Multiple students can be in multiple courses (implemented through the grades junction table)

#### Query Insights
1. **ORDER BY queries:** Demonstrated that data can be sorted by any column, showing the same students in different orders
2. **JOIN with WHERE:** Successfully filtered students who scored above 90 in any course (3 out of 4 students)
3. **Aggregate functions:** 
   - AVG() calculated average grades per course (ranging from 84.5 to 91.0)
   - COUNT(DISTINCT) counted unique students per course (2-3 students per course)
4. **GROUP BY:** Essential for aggregate functions to work per course rather than across all data

---

### Assumptions Made

1. RINs follow the format of 9-digit numbers starting with 661
2. RCS IDs are unique identifiers up to 7 characters
3. Phone numbers are stored as 10-digit US phone numbers without formatting
4. ZIP codes are 5-digit integers (not accounting for ZIP+4 format)
5. Grades are stored as integers (0-100 scale assumed)
6. Each grade entry represents a student's performance in a specific course