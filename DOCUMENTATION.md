# InviGo - Event Registration System Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [Architecture](#architecture)
3. [File Structure](#file-structure)
4. [Database Schema](#database-schema)
5. [Configuration](#configuration)
6. [Routes & Endpoints](#routes--endpoints)
7. [Templates](#templates)
8. [Helper Functions](#helper-functions)
9. [Features](#features)
10. [Setup Instructions](#setup-instructions)

---

## Project Overview

**InviGo** เป็นระบบจัดการลงทะเบียนกิจกรรม (Event Registration System) ที่พัฒนาด้วย PHP + MySQL + Tailwind CSS พร้อมดีไซน์แบบ Neo-brutalism

### Key Features
- สร้างและจัดการกิจกรรม
- ระบบลงทะเบียนเข้าร่วมกิจกรรม
- ระบบอนุมัติ/ปฏิเสธผู้ลงทะเบียน
- OTP เช็คอิน
- สถิติและ Dashboard
- อัปโหลดรูปภาพ

---

## Architecture

### Tech Stack
- **Backend:** PHP 8.2 (Apache)
- **Database:** MySQL 8.0
- **Frontend:** Tailwind CSS + Google Fonts (Kanit)
- **Icons:** Material Symbols Outlined
- **Avatars:** DiceBear API
- **Container:** Docker + Docker Compose

### Design Pattern
- **MVC-like Structure:** Routes → Controllers → Views
- **Template Rendering:** `renderView()` function
- **Database Layer:** Separate database functions in `/databases/`
- **Authentication:** Session-based

---

## File Structure

```
InviGo/
├── databases/              # Database functions
│   ├── user.php           # User CRUD
│   ├── event.php          # Event CRUD
│   ├── registration.php   # Registration CRUD
│   ├── otp.php            # OTP generation
│   ├── stats.php          # Statistics queries
│   └── event_image.php    # Image handling
│
├── includes/              # Core functionality
│   ├── config.php         # Constants & config
│   ├── database.php       # DB connection
│   ├── router.php         # URL routing
│   ├── view.php           # Template rendering
│   ├── auth.php           # Authentication helpers
│   └── helpers/           # Utility functions
│       ├── auth.php
│       ├── date.php
│       ├── flash.php
│       ├── format.php
│       ├── password.php
│       └── sanitize.php
│   └── utils/
│       └── upload.php     # File upload
│
├── public/                # Web root
│   ├── index.php          # Front controller
│   ├── .htaccess          # URL rewriting
│   └── uploads/           # Uploaded files
│       ├── events/
│       └── profiles/
│
├── routes/                # Route handlers
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   ├── explore.php
│   ├── profile/
│   │   ├── index.php
│   │   └── edit.php
│   ├── my-events.php
│   ├── my-registrations.php
│   └── events/
│       ├── create.php
│       ├── edit.php
│       ├── delete.php
│       ├── join.php
│       ├── withdraw.php
│       ├── manage.php
│       └── otp.php
│
├── templates/             # View templates
│   ├── layout.php         # Main layout
│   ├── header.php         # Page header
│   ├── footer.php         # Page footer
│   ├── login.php
│   ├── register.php
│   ├── explore_content.php
│   ├── event_detail_content.php
│   ├── event_form_content.php
│   ├── my_events_content.php
│   ├── my_registrations_content.php
│   ├── manage_event_content.php
│   ├── profile_content.php
│   ├── profile_edit_content.php
│   ├── otp_display_content.php
│   ├── 401.php            # Error pages
│   ├── 403.php
│   ├── 404.php
│   └── 500.php
│   └── partials/            # Reusable components
│       ├── event-card.php
│       ├── status-badge.php
│       ├── flash.php
│       ├── mobile-nav.php
│       ├── sidebar.php
│       └── loading.php
│
├── database.sql           # Database schema
├── docker-compose.yml     # Docker config
├── Dockerfile             # PHP container
└── README.md
```

---

## Database Schema

### Tables

#### Users
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | User ID |
| email | VARCHAR(255) | Unique email |
| password | VARCHAR(255) | Hashed password |
| name | VARCHAR(255) | Display name |
| birth_date | DATE | Date of birth |
| gender | ENUM | male, female, other |
| profile_image | VARCHAR(255) | Image path |
| created_at | TIMESTAMP | Registration date |

#### Events
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Event ID |
| user_id | INT FK | Event creator |
| title | VARCHAR(255) | Event name |
| description | TEXT | Event details |
| event_date | DATETIME | Start time |
| end_date | DATETIME | End time |
| location | VARCHAR(255) | Venue |
| max_participants | INT | Capacity limit |
| status | ENUM | active, cancelled, completed |
| created_at | TIMESTAMP | Creation date |

#### Registrations
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Registration ID |
| event_id | INT FK | Event |
| user_id | INT FK | Participant |
| status | ENUM | pending, approved, rejected |
| checked_in | BOOLEAN | Check-in status |
| checked_in_at | TIMESTAMP | Check-in time |
| created_at | TIMESTAMP | Registration time |

#### Event_Images
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Image ID |
| event_id | INT FK | Event |
| image_path | VARCHAR(255) | File path |
| created_at | TIMESTAMP | Upload time |

#### Otps
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | OTP ID |
| registration_id | INT FK | Registration |
| otp_code | VARCHAR(6) | 6-digit code |
| expires_at | TIMESTAMP | Expiry time |
| used_at | TIMESTAMP | Usage time |

---

## Configuration

### config.php Constants

```php
// Directory paths
INCLUDES_DIR = __DIR__
ROUTE_DIR = __DIR__ . '/../routes'
TEMPLATES_DIR = __DIR__ . '/../templates'
DATABASES_DIR = __DIR__ . '/../databases'

// Security
PUBLIC_ROUTES = ['/', '/login', '/register', '/explore']
ALLOW_METHODS = ['GET', 'POST']

// Validation
MIN_AGE = 10
MAX_AGE = 120
OTP_LENGTH = 6
OTP_EXPIRY_MINUTES = 30
MAX_UPLOAD_SIZE_MB = 2

// Gender values
GENDER_MALE = 'male'
GENDER_FEMALE = 'female'
GENDER_OTHER = 'other'

// Status values
STATUS_PENDING = 'pending'
STATUS_APPROVED = 'approved'
STATUS_REJECTED = 'rejected'

// Brand colors
COLOR_PRIMARY = '#FFE600'    // Yellow
COLOR_SECONDARY = '#D4FF33'  // Lime
COLOR_ACCENT = '#40E0D0'     // Teal
COLOR_SUCCESS = '#22c55e'
COLOR_WARNING = '#eab308'
COLOR_DANGER = '#ef4444'
COLOR_BG = '#FFFBF0'         // Cream
```

---

## Routes & Endpoints

### Public Routes (No Auth Required)
| Method | Route | Description |
|--------|-------|-------------|
| GET | / | Redirect to /explore |
| GET | /explore | Browse events |
| GET/POST | /login | Login page |
| GET/POST | /register | Registration page |
| GET | /events/{id} | Event details |

### Authenticated Routes
| Method | Route | Description |
|--------|-------|-------------|
| GET | /logout | Logout |
| GET | /profile | User profile |
| GET/POST | /profile/edit | Edit profile |
| GET | /my-events | My events |
| GET | /my-registrations | My registrations |
| GET/POST | /events/create | Create event |
| GET/POST | /events/{id}/edit | Edit event |
| POST | /events/{id}/delete | Delete event |
| GET | /events/{id}/join | Join event |
| GET | /events/{id}/withdraw | Cancel registration |
| GET/POST | /events/{id}/manage | Manage event |
| GET | /events/{id}/otp | Generate OTP |

---

## Templates

### Main Templates

#### layout.php
- Main application layout
- Sidebar navigation (desktop)
- Mobile bottom navigation
- Toast notifications
- User profile card

#### header.php
- HTML head section
- CSS styles (Neo-brutalism)
- JavaScript utilities

#### footer.php
- Delete confirmation modal
- Mobile navigation
- Loading spinner

### Content Templates

| Template | Purpose |
|----------|---------|
| explore_content.php | Event browsing |
| event_detail_content.php | Event details page |
| event_form_content.php | Create/edit event form |
| my_events_content.php | List user's events |
| my_registrations_content.php | List registrations |
| manage_event_content.php | Event dashboard |
| profile_content.php | Profile view |
| profile_edit_content.php | Profile edit form |

### Error Pages

| Code | Template | Purpose |
|------|----------|---------|
| 401 | 401.php | Unauthorized + login form |
| 403 | 403.php | Forbidden |
| 404 | 404.php | Not found |
| 500 | 500.php | Server error |

---

## Helper Functions

### Authentication (auth.php)
```php
getCurrentUser(): ?array          // Get logged in user
getCurrentUserId(): int          // Get user ID
requireAuth(): void              // Redirect if not logged in
requireGuest(): void             // Redirect if logged in
```

### Date Helpers (date.php)
```php
formatThaiDate(string $date): string
formatThaiDateTime(string $datetime): string
calculateAge(string $birthDate): int
formatShortDate(string $date): string
```

### Flash Messages (flash.php)
```php
setFlashMessage(string $type, string $message): void
getFlashMessage(): ?array
```

### Sanitization (sanitize.php)
```php
sanitize(string $text): string
```

### Password (password.php)
```php
hashPassword(string $password): string
verifyPassword(string $password, string $hash): bool
```

---

## Features

### 1. Event Management
- Create events with images
- Set date/time, location, capacity
- Edit/Delete events
- View event statistics

### 2. Registration System
- Register for events
- Approval workflow (pending → approved/rejected)
- Cancel registration
- Registration limits

### 3. OTP Check-in
- Generate 6-digit OTP
- 30-minute expiry
- Organizer scans OTP
- Real-time check-in status

### 4. Statistics Dashboard
- Total registrations
- Gender distribution
- Age group analysis
- Check-in rates

### 5. Responsive Design
- Desktop: Sidebar navigation
- Mobile: Bottom tab navigation
- Neo-brutalism UI style

### 6. Guest Access
- Browse events without login
- Login prompt for protected features
- Grayed-out disabled menu items

---

## Setup Instructions

### Prerequisites
- Docker & Docker Compose
- Git

### Installation

1. **Clone repository**
```bash
git clone https://github.com/K1Dev-Core/InviGo.git
cd InviGo
```

2. **Start containers**
```bash
docker-compose up -d
```

3. **Import database**
```bash
docker-compose exec mysql mysql -u root -p invigo < database.sql
```

4. **Access application**
- Web: http://localhost:8888
- phpMyAdmin: http://localhost:8080

### Default Credentials
- MySQL root: `root` / `rootpassword`
- MySQL user: `invigo` / `invigopass`

### Environment Variables
Create `.env` file (optional):
```env
DB_HOST=mysql
DB_NAME=invigo
DB_USER=invigo
DB_PASS=invigopass
```

---

## Design System

### Colors
| Name | Hex | Usage |
|------|-----|-------|
| Primary | #FFE600 | Buttons, highlights |
| Secondary | #D4FF33 | Success, OTP display |
| Accent | #40E0D0 | Actions, links |
| Background | #FFFBF0 | Page background |
| Black | #000000 | Borders, text, shadows |

### CSS Classes
```css
.neo-box      /* Cards with shadow */
.neo-card     /* Hoverable cards */
.neo-btn      /* Buttons with shadow */
.neo-btn-small /* Small buttons */
.neo-input    /* Form inputs */
```

### Icons
All icons from **Material Symbols Outlined**
```html
<span class="material-symbols-outlined">icon_name</span>
```

---

## Security

### Implemented
- Password hashing (bcrypt)
- SQL injection prevention (prepared statements)
- XSS prevention (output sanitization)
- CSRF protection (session-based)
- File upload validation

### Recommendations
- Use HTTPS in production
- Regular database backups
- Input validation on all forms
- Rate limiting for login attempts

---

## Troubleshooting

### Common Issues

1. **Database connection failed**
   - Check Docker containers running
   - Verify database credentials

2. **File upload errors**
   - Check `/public/uploads/` permissions
   - Verify file size limits

3. **404 errors**
   - Check `.htaccess` enabled
   - Verify mod_rewrite active

4. **Session issues**
   - Check PHP session configuration
   - Verify cookie settings

---

## API Endpoints (Future)

Planned REST API endpoints:
```
GET    /api/events
GET    /api/events/{id}
POST   /api/events
PUT    /api/events/{id}
DELETE /api/events/{id}
GET    /api/events/{id}/registrations
POST   /api/events/{id}/register
```

---

## Changelog

### v1.0.0 (2024-02-18)
- Initial release
- Event management
- Registration system
- OTP check-in
- Statistics dashboard
- Guest access
- Error pages (401, 403, 404, 500)
- Mobile responsive design

---

## Contributors
- K1Dev (Developer)

## License
MIT License

---

**For support:** Contact via GitHub Issues
