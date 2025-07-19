# Clip Management

Clips are shortened URLs that redirect to the original URL. Each clip has:

- **URL**: The original URL to redirect to
- **Slug**: A unique identifier for the shortened URL
- **Password**: Optional password protection
- **Expiration**: Optional expiration date
- **User**: The owner of the clip

## Shortened URL Format

All shortened URLs follow this format:
```
https://clippy.msiamn.dev/{slug}
```

## Password Protection

Clips can be password-protected. When accessing a protected clip through the web interface, users will be prompted to enter the password. For API access, include the password in the request body.

## Expiration

Clips can have an expiration date. Expired clips will return a 404 error when accessed.
