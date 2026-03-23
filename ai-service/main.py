import os
from fastapi import FastAPI
from dotenv import load_dotenv
from routers import health

load_dotenv()

app = FastAPI(
    title="Watering CRM AI Service",
    description="Python FastAPI service handling all AI tasks for Watering CRM",
    version="1.0.0"
)

# Register routers
app.include_router(health.router)

@app.get("/")
async def root():
    return {
        "success": true,
        "message": "Watering CRM AI Service is running",
        "version": "1.0.0"
    }

if __name__ == "__main__":
    import uvicorn
    uvicorn.run("main:app", host="0.0.0.0", port=8001, reload=True)
