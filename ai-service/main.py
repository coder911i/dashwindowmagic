import firebase_admin
from firebase_admin import credentials, firestore
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from routers import health, ai, messages, chatbot
from dotenv import load_dotenv
import os

load_dotenv()

# Initialize Firebase
cred_path = os.getenv("GOOGLE_APPLICATION_CREDENTIALS")
if cred_path and os.path.exists(cred_path):
    cred = credentials.Certificate(cred_path)
    firebase_admin.initialize_app(cred)
else:
    # Use default credentials for Cloud Run environments
    firebase_admin.initialize_app()

db = firestore.client()

app = FastAPI(title="Watering CRM AI Service")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

app.include_router(health.router, prefix="/health", tags=["Health"])
app.include_router(ai.router, prefix="/ai", tags=["AI"])
app.include_router(messages.router, prefix="/ai", tags=["Outreach"])
app.include_router(chatbot.router, prefix="/chatbot", tags=["Chatbot"])


@app.get("/")
async def root():
    return {
        "status": "online",
        "service": "Watering AI Service",
        "provider": "Groq",
        "model": "Llama 3.1 70b"
    }
