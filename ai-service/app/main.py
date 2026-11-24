from fastapi import FastAPI

from app.api import health

app = FastAPI(title="HR_Proj AI Service")

# Public
app.include_router(health.router)

# Model endpoints are protected by the service token, e.g.:
#   from fastapi import Depends
#   from app.api.deps import verify_service_token
#   app.include_router(attrition.router, prefix="/v1", dependencies=[Depends(verify_service_token)])
