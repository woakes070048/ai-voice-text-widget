# Security Policy

## Supported Versions

We release patches for security vulnerabilities for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |
| < 1.0   | :x:                |

## Reporting a Vulnerability

**⚠️ Please do NOT report security vulnerabilities through public GitHub issues.**

If you discover a security vulnerability, please send an email to:

**Email:** security@workfluz.com  
**Subject:** [SECURITY] AI Widget - Brief description

### What to Include

Please include the following information:

- Type of vulnerability
- Full path of source file(s) related to the vulnerability
- Location of the affected source code (tag/branch/commit or direct URL)
- Step-by-step instructions to reproduce the issue
- Proof-of-concept or exploit code (if possible)
- Impact of the vulnerability
- Your suggested fix (if you have one)

### What to Expect

1. **Acknowledgment:** Within 48 hours, we'll acknowledge receipt of your vulnerability report.

2. **Initial Assessment:** Within 7 days, we'll provide an initial assessment of the report.

3. **Investigation:** We'll investigate and determine the impact and severity.

4. **Fix Development:** We'll develop a fix and test it thoroughly.

5. **Release:** We'll release a security update and publicly disclose the vulnerability.

6. **Credit:** We'll credit you in the security advisory (unless you prefer to remain anonymous).

### Timeline

- **Acknowledgment:** 48 hours
- **Initial assessment:** 7 days
- **Fix development:** 14-30 days (depending on severity)
- **Release:** As soon as fix is tested and ready

### Severity Levels

We use the CVSS (Common Vulnerability Scoring System) to assess severity:

- **Critical (9.0-10.0):** Immediate action required
- **High (7.0-8.9):** Urgent fix needed
- **Medium (4.0-6.9):** Important but not urgent
- **Low (0.1-3.9):** Minor issue

### Bug Bounty

Currently, we do not offer a bug bounty program. However, we deeply appreciate security researchers who help us keep our users safe.

**Recognition:**
- Public credit in security advisories
- Mention in CONTRIBUTORS.md
- Special thanks in release notes

## Security Best Practices for Users

### For Site Administrators

1. **Keep Updated:**
   - Always use the latest version of the plugin
   - Enable automatic WordPress updates

2. **Secure API Keys:**
   - Store API keys in wp-config.php using constants
   - Never commit API keys to version control
   - Use environment variables when possible

3. **Review Permissions:**
   - Only give admin access to trusted users
   - Review user capabilities regularly

4. **Monitor Activity:**
   - Check analytics for unusual patterns
   - Review conversation logs periodically

5. **Privacy Compliance:**
   - Review terms of external services (OpenAI, VAPI, ElevenLabs)
   - Update your privacy policy
   - Ensure GDPR/CCPA compliance

### For Developers

1. **Code Review:**
   - Review all code changes before deployment
   - Use WordPress coding standards
   - Follow security best practices

2. **Input Validation:**
   - Validate all user input
   - Sanitize data before storage
   - Escape output properly

3. **SQL Queries:**
   - Always use prepared statements
   - Never use direct SQL concatenation
   - Use $wpdb methods

4. **Authentication:**
   - Verify nonces for all AJAX requests
   - Check user capabilities
   - Validate permissions

5. **External Services:**
   - Verify SSL/TLS for API calls
   - Handle API errors gracefully
   - Don't expose API keys in responses

## Known Security Considerations

### External Service Dependencies

This plugin uses external services:

1. **OpenAI API**
   - User messages are sent to OpenAI
   - Follow OpenAI's security guidelines
   - Review OpenAI's privacy policy

2. **VAPI SDK**
   - Voice audio is processed by VAPI
   - Review VAPI's security measures
   - Follow VAPI's best practices

3. **ElevenLabs API** (optional)
   - Text is sent for voice synthesis
   - Review ElevenLabs security
   - Follow their guidelines

### Data Storage

- Session IDs stored in browser cookies
- Conversations stored in WordPress database
- API keys stored in WordPress options
- Usage statistics tracked locally

### Encryption

- API calls use HTTPS/SSL
- Database values sanitized and escaped
- Cookies use httponly and secure flags (when on HTTPS)

## Compliance

### GDPR Compliance

- Users informed about data processing
- Option to delete conversation history
- No personal data collected without consent
- External services disclosed

### CCPA Compliance

- Clear privacy policy required
- Users can request data deletion
- No sale of personal data
- Transparent data practices

## Security Checklist for Deployment

Before deploying to production:

- [ ] Update to latest version
- [ ] Review security settings
- [ ] Test with WordPress debug enabled
- [ ] Check for PHP errors/warnings
- [ ] Verify API keys are secure
- [ ] Review user permissions
- [ ] Update privacy policy
- [ ] Test on staging first
- [ ] Backup database before update
- [ ] Monitor for issues after deployment

## Contact

**Security Team:** security@workfluz.com  
**General Support:** support@workfluz.com  
**WhatsApp:** +57 333 430 8871

**Response Time:**
- Critical: Within 24 hours
- High: Within 48 hours
- Medium/Low: Within 7 days

## References

- [WordPress Plugin Security](https://developer.wordpress.org/plugins/security/)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [WordPress Security Guidelines](https://wordpress.org/support/article/hardening-wordpress/)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)

---

**Last Updated:** October 24, 2025  
**Version:** 1.0.0

Thank you for helping keep AI Widget by Workfluz and our users safe! 🔒
