# 🐝 ExamHive — Online Examination System

> A modern, AI-powered web-based examination platform built with PHP, MySQL, and Google Gemini AI.

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Gemini](https://img.shields.io/badge/Google_Gemini-AI-4285F4?style=for-the-badge&logo=google&logoColor=white)
![XAMPP](https://img.shields.io/badge/XAMPP-Apache-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)

---

## 📌 About

**ExamHive** is a full-stack web application that allows educational institutions to conduct online multiple-choice exams across multiple subjects. It features AI-powered question generation, real-time scoring, anti-cheating mechanisms, and a subject-wise leaderboard.

Built as a **Project Based Learning (PBL)** submission for the Web Technologies course at **Woxsen University**.

---

## ✨ Features

### 👩‍🎓 Student Features
- Register and login securely
- Choose from 4 subjects: **Math, Science, History, General Knowledge**
- Take a **10-question timed exam** (5 minutes)
- Get **instant results** with a performance chart
- View the **Global Leaderboard** with subject-wise rankings

### 🛠️ Admin Features
- Add questions manually to the question bank
- **AI Question Generator** — paste any text passage and generate 5 MCQs automatically using Google Gemini AI
- View and delete questions from the Question Bank
- View all student results with subject, score, and timestamp

### 🔒 Security
- Bcrypt password hashing
- PHP session-based authentication
- Role-based access control (Admin / Student)
- **Anti-cheating**: Tab-switch detection (2 warnings → auto-submit)
- **Auto-submit** when 5-minute timer expires

### 📊 Analytics
- Doughnut chart showing correct vs incorrect answers
- Subject-wise leaderboard with progress bars
- Medal rankings (🥇🥈🥉) for top 3 students

---

## 🖥️ Screenshots

| Login Page | Student Dashboard |
|---|---|
| ![Login](https://via.placeholder.com/400x250/3E96F4/ffffff?text=Login+Page) | ![Dashboard](https://via.placeholder.com/400x250/31393C/ffffff?text=Student+Dashboard) |

| Exam Page | Results |
|---|---|
| ![Exam](https://via.placeholder.com/400x250/EDEEEB/31393C?text=Exam+Page) | ![Results](https://via.placeholder.com/400x250/3E96F4/ffffff?text=Results+%26+Chart) |

---

## 🗂️ Project Structure

```
ExamHive/
│
├── index.php              # Landing / home page
├── login.php              # User login
├── register.php           # New user registration
├── logout.php             # Session destroy
├── db_connect.php         # MySQL connection
│
├── admin_dashboard.php    # Admin home
├── add_question.php       # Add questions manually
├── manage_questions.php   # View & delete question bank
├── ai_generator.php       # AI question generation (Gemini)
├── view_results.php       # All student results
│
├── student_dashboard.php  # Student home
├── take_exam.php          # Timed exam with anti-cheat
├── submit_exam.php        # Score calculation + chart
├── leaderboard.php        # Overall + per-subject rankings
│
└── style.css              # Global stylesheet
```

---

## ⚙️ Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP 8.2 |
| Database | MySQL |
| Web Server | Apache (XAMPP) |
| AI API | Google Gemini 2.5 Flash |
| Charts | Chart.js |
| Fonts | Google Fonts (Syne + DM Sans) |

---

## 🗄️ Database Schema

```sql
-- Users table
CREATE TABLE users (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role     ENUM('admin','student') DEFAULT 'student'
);

-- Questions table
CREATE TABLE questions (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  subject        VARCHAR(50) NOT NULL,
  question_text  TEXT NOT NULL,
  option_a       VARCHAR(255),
  option_b       VARCHAR(255),
  option_c       VARCHAR(255),
  option_d       VARCHAR(255),
  correct_answer CHAR(1) NOT NULL
);

-- Results table
CREATE TABLE results (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  user_id         INT NOT NULL,
  score           INT NOT NULL,
  total_questions INT NOT NULL,
  subject         VARCHAR(50) DEFAULT 'General',
  exam_date       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

## 🚀 Installation & Setup

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 8.2)
- Google Gemini API Key — free at [aistudio.google.com](https://aistudio.google.com)

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/manogna-reddy-abba/ExamHive.git
```

**2. Move to XAMPP's htdocs folder**
```bash
mv ExamHive /Applications/XAMPP/xamppfiles/htdocs/
```

**3. Fix permissions (macOS)**
```bash
chmod -R 755 /Applications/XAMPP/xamppfiles/htdocs/ExamHive
```

**4. Start XAMPP**
- Open XAMPP Control Panel
- Start **Apache** and **MySQL**

**5. Set up the database**
- Go to `http://localhost/phpmyadmin`
- Create a database called `online_exam_db`
- Run the SQL from the Database Schema section above

**6. Add your Gemini API key**
- Open `ai_generator.php`
- Replace the `$apiKey` value with your own key

**7. Open the app**
```
http://localhost/ExamHive/index.php
```

---

## 🤖 AI Question Generation

ExamHive uses **Google Gemini 2.5 Flash** to automatically generate 5 MCQs from any text:

```php
$prompt = "Context: $text_content. Create 5 MCQs for $subject.
Return ONLY a valid JSON array. Format:
[{
  \"question_text\": \"...\",
  \"option_a\": \"...\",
  \"option_b\": \"...\",
  \"option_c\": \"...\",
  \"option_d\": \"...\",
  \"correct_answer\": \"A\"
}]";
```

---

## 🔮 Future Enhancements

- [ ] PDF upload to auto-generate exam questions
- [ ] Answer review after submission
- [ ] Edit existing questions
- [ ] Export results to CSV
- [ ] Dark mode toggle
- [ ] Email notifications with scores
- [ ] Student performance history page
- [ ] Adaptive difficulty based on past scores

---

## 📄 License

This project is built for academic purposes as part of the Web Technologies PBL course at Woxsen University.

