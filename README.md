# 💼 JobPakdo - Backend REST APIs

A RESTful API backend for a Job Portal system built using **Laravel**, **Sanctum**, and **MySQL**. It features role-based access control (Employer & Job Seeker), job posting, application status management, and search/filtering features.

---

## 🚀 Features

- **Authentication & Authorization**: User registration and login with Laravel Sanctum token-based authentication.
- **Roles**: Distinct permissions for **Employer** and **Job Seeker**.
- **Job Management**:
  - Employers can create and delete job listings.
  - Public/Seekers can view single or all job listings with flexible **Search & Filtering** (by title, company, location).
- **Application Flow**:
  - Seekers can apply for jobs by providing a resume link.
  - Seekers can track their applied job applications and live status.
  - Employers can view job applications for their postings and update status (`pending`, `accepted`, `rejected`).

---

## 🛠️ Tech Stack

- **Framework**: Laravel
- **Database**: MySQL (XAMPP)
- **Authentication**: Laravel Sanctum
- **Tools**: Postman, Git

---

## 📡 API Endpoints

### 🟢 Public Endpoints
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `POST` | `/api/register` | Register new user (Employer/Seeker) |
| `POST` | `/api/login` | Authenticate user & receive Sanctum token |
| `GET` | `/api/jobs` | Fetch all jobs (Supports `?search=` and `?location=`) |
| `GET` | `/api/jobs/{id}` | Fetch single job details |

### 🔒 Protected Endpoints (Bearer Token Required)
| Role | Method | Endpoint | Description |
| :--- | :--- | :--- | :--- |
| **All** | `POST` | `/api/logout` | Revoke current token |
| **Employer** | `POST` | `/api/jobs` | Post a new job |
| **Employer** | `DELETE` | `/api/jobs/{id}` | Delete a posted job |
| **Employer** | `GET` | `/api/employer/applications` | View applications for posted jobs |
| **Employer** | `PATCH` | `/api/employer/applications/{id}/status` | Update application status |
| **Seeker** | `POST` | `/api/applications` | Apply for a job |
| **Seeker** | `GET` | `/api/seeker/applications` | Track applied jobs & statuses |

---

## ⚙️ Setup Instructions

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/shuklasurabhi061-lgtm/jobpakdo-backend.git](https://github.com/shuklasurabhi061-lgtm/jobpakdo-backend.git)
   cd jobpakdo-backend
