from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from routers import health, ai
from dotenv import load_dotenv

load_dotenv()

app = FastAPI(title="Watering CRM AI Service")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

app.include_router(health.router, prefix="/health", tags=["Health"])
app.include_router(ai.router, prefix="/ai", tags=["AI"])

@app.get("/")
async def root():
    return {"message": "Watering CRM AI Service is running"}
