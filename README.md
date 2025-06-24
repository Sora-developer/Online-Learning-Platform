# Online Learning Platform

This is a web-based Online Learning Platform built with PHP and MySQL. It supports two user roles: **Students** and **Instructors**. Students can enroll in courses, view lectures, take exams, and participate in discussion forums. Instructors can create courses, manage lectures, and set up exams.

## Features

### For Students
- Register and log in as a student
- Enroll in available courses
- View enrolled courses and access course content (lectures, videos)
- Take exams for enrolled courses
- View best exam scores (results)
- Participate in discussion forums
- (Coming soon) Download course certificates

### For Instructors
- Register and log in as an instructor
- Create new courses and set course prices
- Add, update, and delete lectures for their courses
- Create exams with multiple questions and options
- Participate in discussion forums

## Project Structure

```
php_files/
    auth.php
    certificate.php
    course_content.php
    courses.php
    db.php
    enroll.php
    exams.php
    forum.php
    instructor_dashboard.php
    lectures.php
    login.php
    logout.php
    manage_lectures.php
    my_courses.php
    recommend.php
    register.php
    results.php
    student_dashboard.php
    take_exam.php
    track_exam.php
    update_course.php
    view_exams.php
```

## Setup Instructions

1. **Clone or Download the Repository**

2. **Database Setup**
   - Create a MySQL database named `online learning`.
   - Import the required tables:
     - `users`, `courses`, `enrollments`, `lectures`, `exams`, `exam_questions`, `exam_attempts`, `exam_results`, `forum`, `comments`
   - Adjust database credentials in [`php_files/db.php`](php_files/db.php) if needed.

3. **Run the Application**
   - Place the project folder in your web server's root directory (e.g., `htdocs` for XAMPP).
   - Access the application via `http://localhost/<project-folder>/php_files/login.php`.

4. **Register Users**
   - Register as a student or instructor to access respective dashboards.

## Usage

- **Students:** After logging in, use the dashboard to enroll in courses, view content, take exams, and join forums.
- **Instructors:** After logging in, use the dashboard to create courses, manage lectures, and set up exams.

## Notes

- The certificate feature is under construction.
- Some files in the root directory may be legacy or alternate versions.
- For any issues, check your database connection and table structure.
