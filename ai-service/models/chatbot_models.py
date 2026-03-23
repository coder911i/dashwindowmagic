from pydantic import BaseModel
from typing import List, Optional, Dict

class ChatbotMessageRequest(BaseModel):
    tenantId: str
    sessionId: str
    message: str
    conversationHistory: List[Dict[str, str]] = []
    visitorName: Optional[str] = None
    visitorPhone: Optional[str] = None

class PropertyMatch(BaseModel):
    id: str
    title: str
    price: int
    bhk: str
    locality: str
    matchScore: float

class ChatbotResponse(BaseModel):
    reply: str
    capturedLead: bool = False
    matches: List[PropertyMatch] = []
    step: str
