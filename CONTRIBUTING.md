# Contributing to CodeMentor AI

Thank you for your interest in contributing to CodeMentor AI! This document provides guidelines and instructions for contributing.

## Code of Conduct

Be respectful and professional in all interactions. We are committed to providing a welcoming environment.

## Getting Started

1. Fork the repository
2. Clone your fork: `git clone https://github.com/your-username/CodeMentorAI.git`
3. Add upstream: `git remote add upstream https://github.com/salmansystech/CodeMentorAI.git`
4. Create a feature branch: `git checkout -b feature/your-feature`
5. Make your changes
6. Push to your fork
7. Create a Pull Request

## Development Setup

```bash
# Backend
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

# Frontend
cd frontend
npm install
npm run dev
```

## Commit Guidelines

Use clear, descriptive commit messages:

```
type(scope): subject

body (optional)
```

Types: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`

Examples:
- `feat(api): add badge calculation logic`
- `fix(frontend): fix leaderboard pagination bug`
- `docs: update setup guide`

## Code Style

### PHP (Laravel)
- PSR-12 coding standard
- Use meaningful variable names
- Keep methods focused and small
- Add type hints

```php
public function submitCode(SubmitCodeRequest $request): JsonResponse
{
    // Implementation
}
```

### JavaScript (Vue.js)
- Use ES6+ syntax
- Use meaningful variable names
- Keep components focused
- Add comments for complex logic

```javascript
const handleSubmit = async () => {
  try {
    await api.post('/submissions', formData)
  } catch (error) {
    console.error('Failed to submit:', error)
  }
}
```

### CSS/Tailwind
- Use Tailwind utilities
- Keep custom styles minimal
- Follow mobile-first approach

## Pull Request Process

1. Update README.md or documentation if needed
2. Add tests for new features
3. Ensure all tests pass
4. Keep PRs focused on single features
5. Write clear PR description
6. Link related issues

## Testing

### Backend Tests
```bash
cd backend
php artisan test
```

### Frontend Tests
```bash
cd frontend
npm run test
```

## Feature Areas

### High Priority
- Improve AI code analysis accuracy
- Add more languages support
- Implement real-time notifications
- Add team/classroom features

### Medium Priority
- Mobile app
- Video tutorials integration
- Advanced analytics
- API rate limiting

### Low Priority
- Custom themes
- Browser extensions
- Desktop app

## Reporting Issues

Include:
- Clear title
- Detailed description
- Steps to reproduce
- Expected vs actual behavior
- Screenshots if applicable
- Environment details

Example:
```
Title: Login fails with special characters in password

Steps:
1. Go to login page
2. Enter email
3. Enter password with special chars: p@ss!word
4. Click login

Expected: Login successful
Actual: "Invalid credentials" error

Environment: Chrome 120, Windows 11
```

## Documentation

- Keep README.md updated
- Add comments to complex code
- Update API_GUIDE.md for API changes
- Add CHANGELOG entries

## Performance Considerations

- Optimize database queries
- Use caching where appropriate
- Minimize bundle size
- Lazy load components
- Use CDN for static assets

## Security

- Never commit secrets
- Validate all user input
- Use HTTPS in production
- Follow OWASP guidelines
- Report vulnerabilities privately

## Questions?

- Check existing issues and PRs
- Read documentation
- Ask in discussions
- Email project maintainer

## License

By contributing, you agree your code will be licensed under MIT License.

## Recognition

Contributors will be recognized in:
- README.md contributors section
- Release notes
- GitHub contributors graph

Thank you for making CodeMentor AI better!
