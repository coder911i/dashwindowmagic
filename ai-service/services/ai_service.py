import os
import anthropic
from models.message_models import MessageRequest

class AIService:
    def __init__(self):
        self.client = anthropic.Anthropic(api_key=os.getenv("ANTHROPIC_API_KEY"))
        self.model = "claude-3-5-sonnet-20240620" # Updated as per latest Anthropic availability

    async def generate_message(self, req: MessageRequest) -> str:
        system_prompt = f"""
        You are a professional but friendly Indian Real Estate Broker.
        CRITICAL RULES:
        1. Write in a natural, conversational tone. NOT corporate or robotic.
        2. Use Indian English/Hindi nuances (e.g., Lakhs/Crores instead of Millions).
        3. For 'hinglish': Use Roman script (English letters) for a blend of Hindi and English.
        4. Reference the lead's name ({req.leadName}), property ({req.propertyName}), and budgetspecifically.
        5. Tone: {req.tone.upper()}.
        6. Language: {req.language.upper()}.
        7. Max 120 words.
        8. WhatsApp Friendly: USE EMOJIS (🏠, 💰, 📞). No markdown, no asterisks, no bold text.
        9. Message Type: {req.messageType.replace('_', ' ').upper()}.
        """

        user_prompt = f"""
        Lead Name: {req.leadName}
        Property: {req.propertyName}
        Locality: {req.locality}
        Configuration: {req.bhk}
        Budget: ₹ {req.budgetMin/100000}L - {req.budgetMax/100000}L
        """

        try:
            message = self.client.messages.create(
                model=self.model,
                max_tokens=200,
                system=system_prompt,
                messages=[
                    {"role": "user", "content": user_prompt}
                ]
            )
            if not message.content:
                raise Exception("Anthropic returned an empty response.")
            return message.content[0].text
        except Exception as e:
            raise Exception(f"AI Service Error: {str(e)}")

ai_service = AIService()
