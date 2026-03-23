from pydantic import BaseModel
from typing import Optional, Dict

class MessageRequest(BaseModel):
    leadName: str
    propertyName: str
    budgetMin: float
    budgetMax: float
    bhk: str
    locality: str
    messageType: str # initial | followup_2day | followup_7day | visit_invite | offer_nudge | price_drop
    language: str # english | hindi | hinglish | tamil | telugu | marathi | kannada
    tone: str # formal | casual
    tenantTonePrefs: Optional[Dict] = None

class MessageResponse(BaseModel):
    message: str
    language: str
    characterCount: int
