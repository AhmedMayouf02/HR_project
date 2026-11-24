from fastapi import Header, HTTPException, status

from app.config import settings


def verify_service_token(x_service_token: str | None = Header(default=None)) -> None:
    """Only the Laravel backend may call this service."""
    if settings.service_token and x_service_token != settings.service_token:
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Invalid service token")
