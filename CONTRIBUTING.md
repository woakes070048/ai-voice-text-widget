# Contributing to AI Widget by Workfluz

First off, thank you for considering contributing to AI Widget by Workfluz! It's people like you that make this plugin better for everyone.

## 🌟 Ways to Contribute

- 🐛 Report bugs
- 💡 Suggest new features
- 📝 Improve documentation
- 🔧 Submit bug fixes
- ✨ Implement new features
- 🌍 Translate the plugin
- 📣 Spread the word

## 📋 Before You Start

1. Check existing [issues](https://github.com/josueayala94/ai-voice-text-widget/issues) to avoid duplicates
2. For major changes, open an issue first to discuss what you'd like to change
3. Make sure your code follows WordPress Coding Standards
4. Test your changes thoroughly

## 🐛 Reporting Bugs

**Before submitting a bug report:**
- Check the [documentation](https://workfluz.com/docs)
- Search existing [issues](https://github.com/josueayala94/ai-voice-text-widget/issues)
- Update to the latest version to see if the issue persists

**When reporting a bug, include:**
- WordPress version
- PHP version
- Plugin version
- Description of the bug
- Steps to reproduce
- Expected behavior
- Actual behavior
- Screenshots (if applicable)
- Browser/OS information (for frontend issues)

**Use this template:**
```markdown
**WordPress Version:** 6.4
**PHP Version:** 8.1
**Plugin Version:** 1.0.0

**Bug Description:**
Clear description of the problem.

**Steps to Reproduce:**
1. Go to...
2. Click on...
3. See error

**Expected Behavior:**
What should happen.

**Actual Behavior:**
What actually happens.

**Screenshots:**
[Attach if applicable]
```

## 💡 Suggesting Features

**Before suggesting a feature:**
- Check if it already exists or is planned
- Consider if it fits the plugin's scope
- Think about how it benefits users

**When suggesting a feature, include:**
- Use case: Why is this needed?
- Description: What should it do?
- Examples: How would it work?
- Mockups: Visual examples (if applicable)

## 🔧 Pull Request Process

### 1. Fork & Clone

```bash
# Fork the repository on GitHub, then:
git clone https://github.com/YOUR-USERNAME/ai-voice-text-widget.git
cd ai-voice-text-widget
```

### 2. Create a Branch

```bash
git checkout -b feature/your-feature-name
# or
git checkout -b fix/bug-description
```

**Branch naming conventions:**
- `feature/add-custom-voices` - New features
- `fix/chat-scroll-issue` - Bug fixes
- `docs/update-readme` - Documentation
- `refactor/cleanup-api-class` - Code refactoring
- `test/add-unit-tests` - Tests

### 3. Make Your Changes

**Coding Standards:**
- Follow [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- Follow [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/)
- Use PHPDoc for all functions/classes
- Write meaningful commit messages

**Code Quality:**
```bash
# Install PHPCS (if not already installed)
composer global require "squizlabs/php_codesniffer=*"

# Install WordPress Coding Standards
composer global require wp-coding-standards/wpcs

# Check your code
phpcs --standard=WordPress path/to/file.php
```

### 4. Test Your Changes

**Manual Testing:**
- Test in clean WordPress installation
- Test with different themes
- Test on different browsers (Chrome, Firefox, Safari, Edge)
- Test on mobile devices
- Test with PHP 7.4, 8.0, 8.1, 8.2

**Checklist:**
- [ ] No PHP errors/warnings
- [ ] No JavaScript console errors
- [ ] Works with WordPress 6.0+
- [ ] Works with PHP 7.4+
- [ ] Database changes handled correctly
- [ ] Security best practices followed
- [ ] Backwards compatibility maintained

### 5. Commit Your Changes

```bash
git add .
git commit -m "feat: add custom voice provider support"
```

**Commit message format:**
- `feat:` New feature
- `fix:` Bug fix
- `docs:` Documentation changes
- `style:` Code style changes (formatting, etc.)
- `refactor:` Code refactoring
- `test:` Adding tests
- `chore:` Maintenance tasks

**Example commit messages:**
```
feat: add ElevenLabs voice cloning support
fix: resolve chat scroll issue on mobile
docs: update installation guide with screenshots
refactor: optimize database queries in analytics
test: add unit tests for freemium limits
```

### 6. Push to GitHub

```bash
git push origin feature/your-feature-name
```

### 7. Create Pull Request

- Go to your fork on GitHub
- Click "Pull Request"
- Select your branch
- Fill in the PR template
- Link related issues

**PR Title Format:**
```
[Feature] Add custom voice provider support
[Fix] Resolve chat scroll issue on mobile
[Docs] Update installation guide
```

**PR Description Template:**
```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Related Issues
Fixes #123
Closes #456

## Testing
- [ ] Tested locally
- [ ] Tested on staging
- [ ] Added unit tests
- [ ] Updated documentation

## Screenshots
[If applicable]

## Checklist
- [ ] Code follows WordPress standards
- [ ] No PHP errors/warnings
- [ ] Backwards compatible
- [ ] Documentation updated
```

## 📝 Code Style Guidelines

### PHP

```php
<?php
/**
 * Short description.
 *
 * Long description with details about what this does.
 *
 * @package AI_Voice_Text_Widget
 * @since 1.0.0
 */

class AI_Widget_Example {
    
    /**
     * Property description.
     *
     * @var string
     */
    private $property;
    
    /**
     * Method description.
     *
     * @param string $param Parameter description.
     * @return bool True on success, false on failure.
     */
    public function method_name( $param ) {
        // Code here
        return true;
    }
}
```

### JavaScript

```javascript
/**
 * Function description.
 *
 * @param {string} param Parameter description.
 * @returns {boolean} Return value description.
 */
function functionName( param ) {
    // Code here
    return true;
}
```

### SQL

```php
// Always use prepared statements
$wpdb->prepare(
    "SELECT * FROM {$table} WHERE id = %d AND status = %s",
    $id,
    $status
);

// Never use direct concatenation
// BAD: "SELECT * FROM $table WHERE id = $id"
```

## 🔒 Security

**Security best practices:**
- Validate and sanitize all input
- Escape all output
- Use nonces for forms
- Check capabilities before actions
- Use prepared statements for SQL
- Follow [WordPress Security Guidelines](https://developer.wordpress.org/plugins/security/)

**Reporting Security Issues:**
Email security@workfluz.com (not via public issues)

## 🌍 Translations

**To contribute translations:**

1. Download [Poedit](https://poedit.net/)
2. Open `languages/ai-voice-text-widget.pot`
3. Create translation for your language
4. Save as `ai-voice-text-widget-{locale}.po`
5. Submit via pull request

**Translation guidelines:**
- Keep formatting placeholders (`%s`, `%d`)
- Maintain context and tone
- Test translations in the plugin

## 📚 Documentation

**Documentation contributions:**
- Fix typos and grammar
- Add examples and use cases
- Improve clarity
- Add screenshots/diagrams
- Update outdated information

**Where to contribute:**
- `README.md` - Main documentation
- `readme.txt` - WordPress.org format
- `/docs/` - Additional guides (to be created)
- Code comments - PHPDoc/JSDoc

## 🎨 Design Guidelines

**UI/UX contributions:**
- Follow WordPress admin design patterns
- Ensure accessibility (WCAG 2.1 AA)
- Test responsive design
- Consider RTL languages
- Use WordPress color schemes

## ✅ Review Process

1. **Automated checks:**
   - Code style (PHPCS)
   - Security scanning
   - Unit tests

2. **Manual review:**
   - Code quality
   - Functionality testing
   - Security review
   - Documentation review

3. **Feedback:**
   - Address review comments
   - Make requested changes
   - Re-request review

4. **Merge:**
   - Approved PRs merged to `develop`
   - Released in next version
   - Contributors credited

## 🏆 Recognition

Contributors will be:
- Listed in CONTRIBUTORS.md
- Mentioned in release notes
- Added to plugin credits

## 📞 Questions?

- **Email:** support@workfluz.com
- **WhatsApp:** +57 333 430 8871
- **GitHub Discussions:** [Start a discussion](https://github.com/josueayala94/ai-voice-text-widget/discussions)
- **WordPress Forum:** [Support forum](https://wordpress.org/support/plugin/ai-voice-text-widget/)

## 📜 License

By contributing, you agree that your contributions will be licensed under the GPLv2 or later license.

---

**Thank you for contributing to AI Widget by Workfluz! 🚀**

Made with ❤️ in Medellín, Colombia
