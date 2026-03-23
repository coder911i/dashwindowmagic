from fastapi import APIRouter, HTTPException, Depends
from pydantic import BaseModel
import os
from typing import List, Optional
import anthropic

router = APIRouter()
client = anthropic.Anthropic(api_key=os.getenv("ANTHROPIC_API_KEY"))

class LeadContext(BaseModel):
    name: str
    project: str
    last_action: str
    status: str
    budget: str

class MessageRequest(BaseModel):
    lead: LeadContext
    tone: str = "professional" # professional, casual, urgent

@router.post("/generate-outreach")
async def generate_outreach(request: MessageRequest):
    prompt = f"""
    You are a professional real estate sales assistant in India. 
    Generate a WhatsApp outreach message for a lead with the following context:
    Name: {request.lead.name}
    Project Interest: {request.lead.project}
    Current Status: {request.lead.status}
    Last Action: {request.lead.last_action}
    Budget: {request.lead.budget}
    
    The tone should be {request.tone}. 
    Keep it concise, friendly, and include a clear call to action (like suggesting a site visit).
    Use Indian English nuances appropriately.
    """
    
    try:
        message = client.messages.create(
            model="claude-3-haiku-20240307",
            max_tokens=300,
            messages=[
                {"role": "user", "content": prompt}
            ]
        )
        return {"success": True, "suggestion": message.content[0].text}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.get("/score-lead")
async def score_lead(name: str, project: str, activity_count: int):
    # Mock AI scoring logic
    score = (activity_count * 2) + (10 if "luxury" in project.lower() else 5)
    normalized_score = min(max(score / 2, 1), 10)
    return {"success": True, "score": round(normalized_score, 1)}
