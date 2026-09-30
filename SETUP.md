# CodeMentor AI - Setup Guide

Complete setup instructions for the CodeMentor AI platform.

## Prerequisites

- **Docker & Docker Compose** (Recommended for easy setup)
- OR:
  - PHP 8.2+
  - Node.js 18+
  - MySQL 8.0+
  - Redis 7+
  - Composer

## Quick Start with Docker (Recommended)

### 1. Clone and Setup

```bash
git clone https://github.com/yourusername/CodeMentorAI.git
cd CodeMentorAI
```

### 2. Configure Environment

```bash
cp backend/.env.example backend/.env
cp frontend/.env.example frontend/.env
```

Update `backend/.env`:
```
ANTHROPIC_API_KEY=your-actual-api-key-here
```

### 3. Start Services

```bash
docker-compose up -d
```

This will start:
- **Backend API**: http://localhost:8000
- **Frontend UI**: http://localhost:3000
- **PhpMyAdmin**: http://localhost:8080
- **MySQL**: Port 3306
- **Redis**: Port 6379

### 4. Initialize Database

```bash
docker-compose exec backend php artisan migrate
docker-compose exec backend php artisan db:seed
```

### 5. Access the Application

- Frontend: http://localhost:3000
- API: http://localhost:8000/api
- Database UI: http://localhost:8080

## Manual Setup (Without Docker)

### Backend Setup

```bash
# Navigate to backend
cd backend

# Install dependencies
composer install

# Create environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Create database
mysql -u root -p -e "CREATE DATABASE codementor_ai;"

# Run migrations
php artisan migrate

# Seed data (optional)
php artisan db:seed

# Start server
php artisan serve
```

### Frontend Setup

```bash
# Navigate to frontend
cd frontend

# Install dependencies
npm install

# Create environment file
cp .env.example .env

# Start dev server
npm run dev
```

## Project Structure

```
CodeMentorAI/
├── backend/                    # Laravel API
│   ├── app/
│   │   ├── Models/            # Database models
│   │   ├── Http/Controllers/  # API endpoints
│   │   ├── Services/          # Business logic
│   │   └── Jobs/              # Queue jobs
│   ├── database/migrations/   # Database migrations
│   ├── config/                # Configuration
│   ├── routes/api.php         # API routes
│   └── .env.example           # Environment template
│
├── frontend/                  # Vue.js UI
│   ├── src/
│   │   ├── pages/            # Page components
│   │   ├── components/       # Reusable components
│   │   ├── stores/           # Pinia stores
│   │   ├── router/           # Vue Router
│   │   └── style.css         # Tailwind styles
│   ├── index.html
│   └── package.json
│
├── docker-compose.yml         # Docker services
├── README.md                  # Project docs
└── SETUP.md                   # This file
```

## API Endpoints

### Authentication
```
POST   /api/auth/register          # Register user
POST   /api/auth/login             # Login
POST   /api/auth/logout            # Logout
GET    /api/auth/me                # Get current user
```

### Code Submissions
```
POST   /api/submissions            # Submit code
GET    /api/submissions            # List user submissions
GET    /api/submissions/:id        # Get submission
DELETE /api/submissions/:id        # Delete submission
```

### Reviews
```
GET    /api/reviews/:id            # Get review
GET    /api/reviews/recent         # Recent reviews
```

### Progress & Stats
```
GET    /api/progress/dashboard     # Dashboard stats
GET    /api/progress/stats         # Detailed stats
```

### Leaderboard
```
GET    /api/leaderboard/global     # Global leaderboard
GET    /api/leaderboard/level      # By level
GET    /api/leaderboard/streak     # By streak
```

## Environment Variables

### Backend (.env)
```
APP_NAME=CodeMentorAI
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=codementor_ai
DB_USERNAME=root
DB_PASSWORD=

REDIS_HOST=127.0.0.1
REDIS_PORT=6379

ANTHROPIC_API_KEY=sk-ant-your-key-here

JWT_SECRET=your-secret-key
JWT_TTL=60
```

### Frontend (.env)
```
VITE_API_URL=http://localhost:8000/api
```

## Database Credentials

Default credentials (change in production):
- **User**: codementor
- **Password**: secret
- **Database**: codementor_ai

## Available Commands

### Backend
```bash
# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Start development server
php artisan serve

# Process queued jobs
php artisan queue:work

# Run tests
php artisan test

# Clear cache
php artisan cache:clear
```

### Frontend
```bash
# Install dependencies
npm install

# Start development
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview

# Lint code
npm run lint
```

## Docker Commands

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f

# Execute command in container
docker-compose exec backend php artisan migrate

# Rebuild images
docker-compose build --no-cache
```

## Common Issues

### Database Connection Error
```
Error: SQLSTATE[HY000] [2002] Connection refused
```

Solution:
```bash
# Make sure MySQL is running
docker-compose up -d mysql

# Check database credentials in .env
```

### API Not Responding
```
Error: Cannot POST /api/auth/login
```

Solution:
```bash
# Restart backend service
docker-compose restart backend

# Check logs
docker-compose logs backend
```

### CORS Error in Frontend
```
Access to XMLHttpRequest blocked by CORS policy
```

Update backend `config/cors.php` or use proxy in Vite config.

### Missing Anthropic API Key
```
Error: ANTHROPIC_API_KEY is not set
```

Solution:
```bash
# Add your API key to .env
ANTHROPIC_API_KEY=sk-ant-your-actual-key

# Restart service
docker-compose restart backend
```

## Development Workflow

### 1. Create Feature Branch
```bash
git checkout -b feature/submit-code-form
```

### 2. Make Changes
- Backend: Add models, controllers, migrations
- Frontend: Add components, pages, stores

### 3. Test Changes
```bash
# Backend tests
php artisan test

# Frontend tests
npm run test
```

### 4. Commit Changes
```bash
git add .
git commit -m "Add code submission form"
```

### 5. Push and Create PR
```bash
git push origin feature/submit-code-form
```

## Production Deployment

### Deploy to Heroku (Backend)
```bash
heroku create codementor-ai-api
heroku addons:create cleardb:ignite
git push heroku main
heroku run "php artisan migrate"
```

### Deploy to Vercel (Frontend)
```bash
vercel
```

Set environment variable on Vercel dashboard:
```
VITE_API_URL=https://codementor-ai-api.herokuapp.com/api
```

## Support

For issues or questions:
1. Check the README.md
2. Review API documentation
3. Create GitHub issue with details

## License

MIT License - See LICENSE file
