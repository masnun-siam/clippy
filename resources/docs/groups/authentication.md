# Authentication

The Clippy API uses token-based authentication via Laravel Sanctum. 

## Getting Started

1. **Login**: Use the `/api/login` endpoint to authenticate with your email and password
2. **Get Token**: The login response includes an access token
3. **Use Token**: Include the token in the `Authorization` header for all authenticated requests

## Authentication Header

```
Authorization: Bearer {your-token-here}
```

All authenticated endpoints require this header to be present.
