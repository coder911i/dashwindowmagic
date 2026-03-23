from fastapi import APIRouter, HTTPException
from models.chatbot_models import ChatbotMessageRequest, ChatbotResponse
from services.chatbot_service import chatbot_service
from typing import List, Dict

router = APIRouter()

@router.post("/message", response_model=ChatbotResponse)
async def get_chatbot_message(request: ChatbotMessageRequest):
    # In a real app, we'd fetch actual properties from Firestore via the Python service
    # For now, we expect properties to be passed from Laravel or fetched here
    mock_properties = [
        {"id": "1", "title": "Ocean View Residency", "price": 8500000, "bhk": "2BHK", "locality": "Worli"},
        {"id": "2", "title": "Green Meadows", "price": 4500000, "bhk": "1BHK", "locality": "Thane"},
    ]
    
    try:
        response = await chatbot_service.generate_response(request, mock_properties)
        return response
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
