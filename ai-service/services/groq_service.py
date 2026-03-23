import os
from groq import Groq
from dotenv import load_dotenv

load_dotenv()

class GroqService:
    def __init__(self):
        self.client = Groq(api_key=os.getenv("GROQ_API_KEY"))
        self.model = os.getenv("GROQ_MODEL", "llama-3.1-70b-versatile")

    def generate_completion(self, system_prompt: str, user_prompt: str, temperature: float = 0.7):
        completion = self.client.chat.completions.create(
            messages=[
                {"role": "system", "content": system_prompt},
                {"role": "user", "content": user_prompt}
            ],
            model=self.model,
            temperature=temperature,
        )
        return completion.choices[0].message.content

    def generate_chat_completion(self, messages: list, temperature: float = 0.7):
        completion = self.client.chat.completions.create(
            messages=messages,
            model=self.model,
            temperature=temperature,
        )
        return completion.choices[0].message.content

# Singleton instance
groq_ai = GroqService()
