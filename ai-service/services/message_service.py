from typing import Optional
from .groq_service import groq_ai

class MessageService:
    def __init__(self):
        self.ai = groq_ai

    def generate_whatsapp_message(self, context: dict) -> str:
        system_prompt = (
            "You are a professional real estate agent assistant in India. "
            "Write highly personalized, persuasive WhatsApp messages to leads. "
            "Keep it concise, use emojis sparingly, and ensure the tone is ultra-premium."
        )
        
        user_prompt = f"""
        Generate a WhatsApp message for a lead with the following details:
        - Lead Name: {context.get('leadName')}
        - Property: {context.get('propertyName')}
        - BHK: {context.get('bhk')}
        - Locality: {context.get('locality')}
        - Budget: {context.get('budgetMin')} - {context.get('budgetMax')}
        
        The message should focus on why this property is a perfect fit and include a clear call to action (CTA).
        """
        
        return self.ai.generate_completion(system_prompt, user_prompt)

message_service = MessageService()
