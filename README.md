# Task 2 - Web Form With Database

## Task Overview

This task is a simple web application that collects a person's name and age, stores the data in a MySQL database, displays saved records in a table, and allows the status value to be toggled between `0` and `1` without reloading the page.

website live link: https://waleedkhald26.fwh.is/

## Technologies Used

- HTML5 for the page structure
- CSS for the user interface design
- JavaScript for client-side interaction and asynchronous requests
- PHP for backend request handling
- MySQL for database storage
- JSON for communication between the frontend and backend

## Technical Details

The frontend is contained in `index.html`. It includes the HTML structure, embedded CSS, and JavaScript in one file. The page contains a form with two fields: name and age. It also contains a table that displays all saved records.

The JavaScript uses the Fetch API to communicate with `api.php`. It sends data as JSON, loads records from the database, and updates the status cell immediately after a toggle request.

The backend is handled by `api.php`. It uses PDO to connect to MySQL, creates the database and `people` table if they do not already exist, and supports three actions:

- `list`: returns all saved people.
- `add`: inserts a new name and age with default status `0`.
- `toggle`: switches the selected person's status between `0` and `1`.

The database table stores `id`, `name`, `age`, `status`, and `created_at`.

## Abstract Steps

1. Build a web form for name and age input.
2. Add a table to display stored records.
3. Use JavaScript to submit form data without page reload.
4. Create a PHP endpoint to process frontend requests.
5. Connect PHP to MySQL using PDO.
6. Insert submitted records into the database.
7. Return saved records as JSON and render them in the table.
8. Add a toggle action that updates the database and the visible table value instantly.

## Project Files

- `index.html`: Contains HTML, CSS, and JavaScript.
- `api.php`: Handles database connection, insert, list, and toggle operations.
