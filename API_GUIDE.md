# CodeMentor AI - API Guide

Complete API reference for CodeMentor AI platform.

## Base URL

```
http://localhost:8000/api
```

## Authentication

All protected endpoints require JWT token in Authorization header:

```
Authorization: Bearer <your_jwt_token>
```

## Error Responses

All error responses follow this format:

```json
{
  "message": "Error description",
  "errors": {}
}
```

## Endpoints

### Authentication

#### Register New User
```
POST /auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}

Response: 201
{
  "message": "User registered successfully",
  "user": { ... },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

#### Login
```
POST /auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}

Response: 200
{
  "message": "Login successful",
  "user": { ... },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

#### Logout
```
POST /auth/logout
Authorization: Bearer <token>

Response: 200
{
  "message": "Logout successful"
}
```

#### Get Current User
```
GET /auth/me
Authorization: Bearer <token>

Response: 200
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "level": 5,
  "total_points": 250,
  ...
}
```

### Code Submissions

#### Submit Code
```
POST /submissions
Authorization: Bearer <token>
Content-Type: application/json

{
  "code": "def hello():\n    print('Hello')",
  "language": "python",
  "title": "Hello World",
  "description": "Simple hello world program"
}

Response: 201
{
  "message": "Code submitted successfully",
  "submission": { ... }
}
```

#### Get User Submissions
```
GET /submissions?page=1
Authorization: Bearer <token>

Response: 200
{
  "data": [ ... ],
  "current_page": 1,
  "per_page": 10,
  "total": 25
}
```

#### Get Single Submission
```
GET /submissions/:id
Authorization: Bearer <token>

Response: 200
{
  "id": 1,
  "user_id": 1,
  "code": "...",
  "language": "python",
  "title": "Hello World",
  "status": "completed",
  ...
}
```

#### Delete Submission
```
DELETE /submissions/:id
Authorization: Bearer <token>

Response: 200
{
  "message": "Submission deleted successfully"
}
```

### Reviews

#### Get Review
```
GET /reviews/:id
Authorization: Bearer <token>

Response: 200
{
  "id": 1,
  "submission_id": 1,
  "findings": {
    "bugs": [...],
    "style_issues": [...],
    "performance": [...],
    "security": [...]
  },
  "overall_score": 85,
  "bugs_count": 2,
  "style_issues_count": 1,
  "performance_issues_count": 0,
  "security_issues_count": 0,
  ...
}
```

#### Get Submission Review
```
GET /submissions/:submission/review
Authorization: Bearer <token>

Response: 200
{
  "id": 1,
  "submission_id": 1,
  "findings": { ... },
  ...
}
```

#### Get Recent Reviews
```
GET /reviews/recent?limit=5
Authorization: Bearer <token>

Response: 200
[
  { ... },
  { ... }
]
```

### Progress & Gamification

#### Get Dashboard Stats
```
GET /progress/dashboard
Authorization: Bearer <token>

Response: 200
{
  "user": { ... },
  "stats": {
    "total_submissions": 10,
    "reviewed_submissions": 8,
    "total_points": 350,
    "level": 5,
    "badges_earned": 3,
    "current_streak": 2,
    "best_streak": 7
  },
  "recent_submissions": [ ... ]
}
```

#### Get User Stats
```
GET /progress/stats
Authorization: Bearer <token>

Response: 200
{
  "stats": {
    "bugs": 15,
    "style": 8,
    "performance": 3,
    "security": 2
  },
  "improvement_rate": 15.5,
  "languages_used": [ ... ]
}
```

### Leaderboards

#### Global Leaderboard
```
GET /leaderboard/global?timeframe=all&limit=50&page=1
Authorization: Bearer <token>

Response: 200
{
  "leaderboard": { ... },
  "user_position": 42,
  "user_stats": {
    "points": 500,
    "level": 10
  }
}
```

#### Leaderboard by Level
```
GET /leaderboard/level?level=10&limit=50
Authorization: Bearer <token>

Response: 200
[
  { ... },
  { ... }
]
```

#### Leaderboard by Streak
```
GET /leaderboard/streak?limit=50
Authorization: Bearer <token>

Response: 200
[
  { ... },
  { ... }
]
```

### Badges

#### Get All Badges
```
GET /badges

Response: 200
[
  {
    "id": 1,
    "name": "Getting Started",
    "description": "Complete your first code submission",
    "icon": "star",
    "points_required": 0
  },
  ...
]
```

#### Get Badge Details
```
GET /badges/:id

Response: 200
{
  "badge": { ... },
  "users": [ ... ]
}
```

#### Get User Badges
```
GET /badges/user
Authorization: Bearer <token>

Response: 200
[
  {
    "id": 1,
    "name": "Getting Started",
    "pivot": {
      "earned_at": "2024-01-15T10:30:00Z"
    }
  },
  ...
]
```

#### Get Available Badges
```
GET /badges/available
Authorization: Bearer <token>

Response: 200
[
  {
    "id": 2,
    "name": "Bug Hunter",
    "description": "Find and fix 10 bugs",
    "points_required": 100
  },
  ...
]
```

### Learning Resources

#### Get Resources
```
GET /resources?category=bugs&type=tutorial&page=1
Authorization: Bearer <token>

Response: 200
{
  "data": [ ... ],
  "current_page": 1,
  "total": 50
}
```

#### Get Resource Details
```
GET /resources/:id
Authorization: Bearer <token>

Response: 200
{
  "id": 1,
  "title": "Understanding Null Safety",
  "content": "...",
  "type": "tutorial",
  "category": "bugs",
  "url": "https://example.com/...",
  "difficulty_level": "beginner"
}
```

#### Get Resources by Category
```
GET /resources/category/bugs?limit=10
Authorization: Bearer <token>

Response: 200
[
  { ... },
  { ... }
]
```

#### Get Submission Resources
```
GET /resources/submission/:submission
Authorization: Bearer <token>

Response: 200
[
  { ... },
  { ... }
]
```

## Status Codes

- 200: Success
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 422: Validation Error
- 500: Server Error

## Rate Limiting

Rate limits are applied per user:
- 100 requests per minute for authenticated users
- 30 requests per minute for unauthenticated users

## Pagination

List endpoints support pagination:

```
GET /endpoint?page=1&limit=20

Response:
{
  "data": [ ... ],
  "current_page": 1,
  "per_page": 20,
  "total": 150,
  "last_page": 8,
  "next_page_url": "...",
  "prev_page_url": null
}
```

## Filtering

Some endpoints support filtering:

```
GET /submissions?language=python&status=completed
GET /leaderboard/global?timeframe=month
GET /resources?category=bugs&type=tutorial
```

## Testing with cURL

### Register
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Submit Code
```bash
curl -X POST http://localhost:8000/api/submissions \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "code": "print(\"hello\")",
    "language": "python",
    "title": "Hello"
  }'
```

## Webhooks

Webhooks are not yet implemented but planned for future versions.

## SDK

Official SDKs are planned for:
- JavaScript/TypeScript
- Python
- PHP

## Support

For API issues or questions:
1. Check this guide
2. Review error messages
3. Create GitHub issue with request/response details
