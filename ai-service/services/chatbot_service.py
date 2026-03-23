from typing import List, Dict
from models.chatbot_models import ChatbotMessageRequest, ChatbotResponse, PropertyMatch
from .groq_service import groq_ai
from firebase_admin import firestore

class ChatbotService:
    def __init__(self):
        self.ai = groq_ai
        self.db = firestore.client()
        self.system_prompt = (
            "You are 'Watering AI', a helpful and expert real estate assistant for Indian home buyers. "
            "Your goal is to perform a 'Discovery Flow' to understand their needs before suggesting properties. "
            "Follow these steps in order across the conversation: "
            "1. Greet and ask for their approximate budget. "
            "2. Ask about their BHK preference (1, 2, 3, 4+ BHK). "
            "3. Ask for their preferred locality in the city. "
            "4. Ask for their name and phone number to connect them with a human specialist. "
            "IMPORTANT: Be concise, premium, and friendly. Do not reveal you are an AI unless asked."
        )

    def _fetch_properties(self, tenant_id: str) -> List[Dict]:
        props_ref = self.db.collection('tenants').document(tenant_id).collection('properties')
        docs = props_ref.stream()
        return [array_merge({"id": doc.id}, doc.to_dict()) for doc in docs]

    def generate_response(self, request: ChatbotMessageRequest) -> ChatbotResponse:
        # Prepare context
        messages = [{"role": "system", "content": self.system_prompt}]
        
        # Add history
        for msg in request.conversationHistory:
            role = "assistant" if msg["role"] == "ai" else "user"
            messages.append({"role": role, "content": msg["content"]})
            
        # Add current message
        messages.append({"role": "user", "content": request.message})

        # Generate response
        reply = self.ai.generate_chat_completion(messages)

        # Property matching logic
        suggested_properties = []
        if any(word in reply.lower() for word in ["budget", "bhk", "locality", "property", "suggest"]):
            # Fetch and match properties if we have context
            all_props = self._fetch_properties(request.tenantId)
            # Use Groq to select best 3 matches
            match_prompt = f"Given these properties: {all_props[:10]}, pick the best 3 for a user who said: {request.message}. Return ONLY a JSON list of property IDs."
            # (In production, use more robust ranking or embedding search)

        captured_lead = "phone" in reply.lower() or "mobile" in reply.lower() or "whatsapp" in reply.lower()

        return ChatbotResponse(
            reply=reply,
            capturedLead=captured_lead,
            suggestedProperties=[] # Simplified for now, but infrastructure is ready
        )

chatbot_service = ChatbotService()
