
# Event Management System

## Overview
This is a **web-based event management system** that allows users to **create, manage, and view events**, as well as **register attendees** and **generate event reports**. It is developed using **pure PHP**, **MySQL**, and **Bootstrap 5** for a clean and responsive UI.

## Features
- **User Authentication**: Secure login and registration with password hashing.
- **Event Management**: Create, update, view, and delete events with details like name, description, date, location, and max capacity.
- **Attendee Registration**: Register attendees for events, with checks to prevent over-registration.
- **Event Dashboard**: View and filter events in a paginated and sortable table.
- **Event Reports**: Admins can download a list of attendees for each event in CSV format.
- **JSON API**: Fetch event details programmatically via a RESTful API.
- **AJAX for Event Registration**: Smooth registration experience without page reloads.

## Installation Instructions

### 1. Clone or Download the Repository
```bash
git clone https://github.com/emon1432/event-management-system.git
```

### 2. Database Setup
- Import the following SQL into your **MySQL database**:
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    date DATETIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    max_capacity INT NOT NULL,
    created_by INT NOT NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE attendees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, event_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (event_id) REFERENCES events(id)
);
```

### 3. Configure Database Connection
- In the **config/db.php** file, update the database connection details:
```php
$host = 'localhost';
$db = 'event_management';
$user = 'root';
$pass = 'yourpassword';
```

### 4. Deploy the Project
- Upload the files to your shared hosting provider, or set up the project on a local server using tools like **XAMPP** or **MAMP**.

## Usage

1. **Create an Admin Account**: Use the provided registration page to create an **admin** account.
2. **Create Events**: Admin users can create events with name, description, date, location, and max capacity.
3. **Event Registration**: Users can register for events. **Max capacity** is checked to prevent over-registration.
4. **Download Reports**: Admin users can download CSV files with attendee lists for each event.

## API Endpoints
### GET `/api/events.php`
- Fetch all events.
```json
{
    "success": true,
    "events": [
        {
            "id": 1,
            "name": "Tech Conference 2025",
            "description": "A conference on emerging tech trends",
            "date": "2025-02-15 10:00:00",
            "location": "New York",
            "max_capacity": 200
        }
    ]
}
```

### GET `/api/events.php?id={event_id}`
- Fetch a specific event by its ID.
```json
{
    "success": true,
    "event": {
        "id": 1,
        "name": "Tech Conference 2025",
        "description": "A conference on emerging tech trends",
        "date": "2025-02-15 10:00:00",
        "location": "New York",
        "max_capacity": 200
    }
}
```

## Technologies Used
- **PHP** (Vanilla, no frameworks)
- **MySQL** (Database)
- **Bootstrap 5** (Frontend framework)
- **AJAX** (Event registration)
- **CSV Export** (Event reports)

