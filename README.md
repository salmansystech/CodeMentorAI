# 🎓 CodeMentor AI - Intelligent Code Review & Learning Platform

An **AI-powered code review platform** combining Laravel backend, Vue.js frontend, and Claude AI to provide intelligent code analysis, personalized learning paths, and gamified progress tracking.

## 🎯 Features

✅ **AI Code Review** - Claude analyzes code for bugs, style, performance, and security
✅ **Learning Generator** - AI creates tutorials and explanations
✅ **Progress Tracking** - Monitor improvement over time
✅ **Gamification** - Points, badges, leaderboards, streaks
✅ **Multi-Language** - Python, JavaScript, Java, C++, PHP, Go, Rust, SQL
✅ **Real-time Dashboard** - Interactive analytics and progress visualization
✅ **Community Features** - Share reviews, compete on leaderboards

## 📁 Project Structure

```
CodeMentorAI/
├── backend/                    # Laravel API
│   ├── app/
│   │   ├── Models/
│   │   ├── Http/Controllers/
│   │   ├── Agents/            # AI agents
│   │   └── Services/
│   ├── routes/
│   ├── database/migrations/
│   ├── config/
│   └── .env.example
├── frontend/                   # Vue.js UI
│   ├── src/
│   │   ├── components/
│   │   ├── pages/
│   │   ├── stores/
│   │   └── App.vue
│   ├── package.json
│   └── vite.config.js
├── docker-compose.yml
└── README.md
```

## 🚀 Quick Start

### Requirements
- PHP 8.2+
- Node.js 18+
- Docker (optional)

### Setup Without Docker

```bash
# Backend setup
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

# Frontend setup (new terminal)
cd frontend
npm install
npm run dev
```

### Setup With Docker

```bash
docker-compose up -d
# Frontend: http://localhost:3000
# Backend: http://localhost:8000
# PhpMyAdmin: http://localhost:8080
```

## 📚 API Documentation

### Authentication
```
POST   /api/auth/register          # Register new user
POST   /api/auth/login             # User login
POST   /api/auth/logout            # Logout
```

### Code Submissions
```
POST   /api/submissions            # Submit code
GET    /api/submissions/:id        # Get submission
GET    /api/submissions            # List user submissions
DELETE /api/submissions/:id        # Delete submission
```

### Reviews
```
GET    /api/reviews/:id            # Get review results
GET    /api/reviews/:submission    # Get submission review
```

### Progress & Gamification
```
GET    /api/progress               # User progress data
GET    /api/leaderboard            # Global leaderboard
GET    /api/badges                 # User badges
GET    /api/stats                  # User statistics
```

### Learning Resources
```
GET    /api/resources              # List resources
GET    /api/resources/:id          # Get resource
GET    /api/resources/category/:cat # By category
```

## 🛠 Tech Stack

**Backend:**
- Laravel 11 (PHP framework)
- MySQL 8.0 (database)
- Redis (caching, queues)
- Claude API (AI analysis)
- JWT authentication

**Frontend:**
- Vue.js 3 (TypeScript)
- Tailwind CSS (styling)
- Monaco Editor (code editor)
- Chart.js (analytics)
- Axios (HTTP client)

**DevOps:**
- Docker & Docker Compose
- GitHub Actions (CI/CD)
- MySQL container
- Redis container

## 🎮 Features Breakdown

### Code Review Engine
- Detects bugs and vulnerabilities
- Analyzes code style and quality
- Identifies performance issues
- Provides severity levels and explanations

### Learning System
- AI-generated tutorials
- Concept explanations
- Code examples
- Practice exercises

### Gamification
- Points system
- Achievement badges
- Level progression (1-50)
- Daily/weekly streaks
- Leaderboards (global, friends)

### Analytics Dashboard
- Submission history
- Improvement trends
- Skill development
- Issue patterns

## 💾 Database Schema

**Users**
- id, name, email, password_hash
- level, total_points, total_submissions
- created_at, last_login

**Submissions**
- id, user_id, code, language
- submission_time, status

**Reviews**
- id, submission_id, findings (JSON)
- severity_levels, explanations

**Resources**
- id, review_id, type, content, links

**Gamification**
- id, user_id, points, badges
- streak_count, level

## 🔐 Authentication

Uses JWT tokens for stateless API authentication:
```
Authorization: Bearer <token>
```

## 📊 Example Workflow

1. User signs up and chooses language
2. Uploads or pastes code
3. Backend sends to Claude AI for analysis
4. AI returns detailed review with issues
5. Frontend displays review with scores
6. User earns points and badges
7. Dashboard shows progress
8. Leaderboard updates

## 🚢 Deployment

### Deploy Backend
```bash
# Heroku
heroku create codementor-ai-api
git push heroku main

# DigitalOcean
doctl apps create --spec app.yaml
```

### Deploy Frontend
```bash
# Vercel
vercel deploy

# Netlify
netlify deploy --prod
```

## 🔮 Future Enhancements

- [ ] Video tutorials
- [ ] Mobile app
- [ ] Team/classroom features
- [ ] Advanced analytics
- [ ] API for third-party integrations
- [ ] Certification system

## 📄 License

MIT License

## 👨‍💻 Author

**Salman Khan** - [GitHub](https://github.com/salmansystech)

---

**Learn to code better. One review at a time.** 🚀
