from fastapi import APIRouter, HTTPException
from models.message_models import MessageRequest, MessageResponse
from services.ai_service import ai_service

router = APIRouter()

@router.post("/generate-message", response_model=MessageResponse)
async def generate_message(request: MessageRequest):
    try:
        generated_text = await ai_service.generate_message(request)
        return MessageResponse(
            message=generated_text,
            language=request.language,
            characterCount=len(generated_text)
        )
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
