ITWS2110 Quiz 2
Student: Lucas Steadman
RCS ID: steadl2
Date: December 2, 2025

Project Overview
    This project is a simple web-based project management site where users can create accounts, log in, make new projects, and see details about who’s on each team. The main goal was to build something that handles users, projects, and team assignments in a clean and organized way.
Features
    User login and registration with salted password hashing
    Create new projects and assign team members
    View a list of all projects and their members
    Prevent duplicate project names
    Require at least 3 members per project

Design Decisions (3.1)
    1. Password Security
        I used salted password hashing with SHA-256. Each user gets a random salt, which is stored alongside their hash. Even if two people use the same password, their stored hashes end up different. When logging in, the system grabs the user’s salt, hashes the entered password with it, and checks if it matches the one in the database.
    2. Database Structure
        The database has three tables: Users, Projects, and ProjectMembership. The membership table connects users and projects so each user can be in multiple projects, and each project can have multiple members. I added foreign keys with cascade delete so the database cleans up membership rows automatically if a project is deleted. I also used unique constraints on project names and nicknames to avoid duplicates.
    3. Session Management
        Sessions start in db_config.php so every page can use them. After login, user info like ID, first/last name, and nickname gets stored in session variables. Any page that needs a logged-in user checks those variables and redirects to the login page if they’re missing. Logging out fully destroys the session and clears the cookie.
    4. User Experience  
        If someone tries to log in with a username that doesn’t exist, the site sends them straight to the registration page with the username already filled in. Forms remember your input if there’s an error so you don’t lose work. New projects get highlighted in the list to make them easier to find.

Handling First-Time Installation (3.2)
    Current Behavior
        Right now, if the database doesn’t exist yet, the site just shows a connection error.
    Improved Installation Approach
        I’d add an install.php page that works like a setup wizard. It would first check if it can connect to MySQL at all. Then it would see if the project’s database already exists. If it doesn’t, the installer would offer to create it and set up all the tables, constraints, and maybe some test data. It would then create an admin account. After installation is done, the installer would disable itself with a flag file or by renaming/deleting the script.

Preventing Duplicate Project Entries (3.3)
    Current Implementation
        Before creating a project, the software checks the database to see if the name already exists. If it does, it shows an error and stops. The database also has a UNIQUE constraint on the name column just in case.
    Why This Works
        The application-level check gives users immediate feedback, and the unique constraint acts as a second layer of protection. Error messages explain exactly what went wrong.

Voting System for Final Presentations (3.4)
    Additional Tables (3.4.1)
        To add voting, I would create a votes table that stores each vote, including which user voted, which project they voted on, their rating, and optional comments. I might also add a presentations table for scheduling presentation info like time, room, and status.
    Data Structure (3.4.2)
        The votes table would include:
             voteId (primary key)
             voterId (FK → users)
             projectId (FK → projects)
             rating (1–5)
             comment (optional)
             votedAt (timestamp)
        A unique key on (voterId, projectId) prevents multiple votes by the same person. Cascade deletes ensure cleanup.
        The presentations table would include:
             presentationId (primary key)
             projectId (unique FK)
             presentationDate
             location
             status (scheduled/completed/cancelled)
    Preventing Self-Voting (3.4.3)
        There are three main ways to stop users from voting on their own projects:
        1. Database Trigger: A trigger checks if the voter is in the project’s membership list and blocks the insert if so.
        2. Application Logic: Before saving a vote, the server looks up membership and rejects votes for your own project.
        3. User Interface Filtering: Don’t even show users the projects they’re part of.
        For this course, the application logic option is probably the most approachable. It could still be paired with UI filtering for smoother user experience. Other considerations include letting users edit votes before a deadline and hiding detailed results until all presentations are done.