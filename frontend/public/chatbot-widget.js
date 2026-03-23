(function() {
    const script = document.currentScript;
    const tenantId = new URL(script.src).searchParams.get('tenantId') || 'default';
    const baseUrl = 'https://ai-service-url.com'; // Change to actual AI service URL

    // Create Chatbot UI
    const container = document.createElement('div');
    container.id = 'watering-chatbot-widget';
    container.style.cssText = `
        position: fixed; bottom: 20px; right: 20px; z-index: 9999;
        font-family: 'Outfit', sans-serif;
    `;

    const button = document.createElement('button');
    button.style.cssText = `
        width: 60px; height: 60px; border-radius: 20px;
        background: #0F1117; color: white; border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2); cursor: pointer;
        display: flex; items-center; justify-content: center;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    `;
    button.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"></path></svg>';
    
    const panel = document.createElement('div');
    panel.style.cssText = `
        display: none; position: absolute; bottom: 80px; right: 0;
        width: 350px; height: 500px; background: white;
        border-radius: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        flex-direction: column; overflow: hidden; border: 1px solid #f0f0f0;
    `;

    panel.innerHTML = `
        <div style="background: #0F1117; color: white; padding: 20px; display: flex; align-items: center; gap: 12px;">
            <div style="width: 32px; height: 32px; background: #3b82f6; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </div>
            <div>
                <p style="font-size: 14px; font-weight: 900; margin: 0; text-transform: uppercase; letter-spacing: -0.02em;">Watering AI</p>
                <p style="font-size: 10px; font-weight: 700; color: #64748b; margin: 0; text-transform: uppercase;">Real Estate Assistant</p>
            </div>
        </div>
        <div id="watering-chat-history" style="flex: 1; padding: 20px; overflow-y: auto; background: #F9FAFB;"></div>
        <div style="padding: 15px; border-top: 1px solid #f0f0f0; display: flex; gap: 10px;">
            <input id="watering-chat-input" type="text" placeholder="Type message..." style="flex: 1; border: none; background: #f1f5f9; padding: 10px 15px; border-radius: 12px; font-size: 13px; outline: none;">
            <button id="watering-chat-send" style="background: #0F1117; color: white; border: none; width: 38px; height: 38px; border-radius: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"></path></svg>
            </button>
        </div>
    `;

    container.appendChild(panel);
    container.appendChild(button);
    document.body.appendChild(container);

    let history = [];

    const toggle = () => {
        const isVisible = panel.style.display === 'flex';
        panel.style.display = isVisible ? 'none' : 'flex';
        button.style.transform = isVisible ? 'rotate(0)' : 'rotate(90deg)';
        if (!isVisible && history.length === 0) {
            addMessage('ai', 'Hello! I am Watering AI. Looking for your dream home? What is your approximate budget?');
        }
    };

    const addMessage = (role, content) => {
        const chat = document.getElementById('watering-chat-history');
        const div = document.createElement('div');
        div.style.cssText = `
            margin-bottom: 15px; text-align: ${role === 'user' ? 'right' : 'left'};
        `;
        div.innerHTML = `
            <div style="display: inline-block; padding: 12px 16px; border-radius: 16px; font-size: 13px; max-width: 80%; 
                ${role === 'user' ? 'background: #3b82f6; color: white;' : 'background: white; border: 1px solid #e2e8f0; color: #1e293b; shadow: 0 4px 6px rgba(0,0,0,0.02);'}">
                ${content}
            </div>
        `;
        chat.appendChild(div);
        chat.scrollTop = chat.scrollHeight;
        history.push({ role, content });
    };

    const sendMessage = async () => {
        const input = document.getElementById('watering-chat-input');
        const content = input.value.trim();
        if (!content) return;

        input.value = '';
        addMessage('user', content);

        try {
            const resp = await fetch(`${baseUrl}/chatbot/message`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    tenantId,
                    message: content,
                    conversationHistory: history,
                    sessionId: 'user-' + Date.now()
                })
            });
            const data = await resp.json();
            addMessage('ai', data.reply);
        } catch (e) {
            addMessage('ai', 'Sorry, I am having trouble connecting. Please try again or leave your number.');
        }
    };

    button.onclick = toggle;
    document.getElementById('watering-chat-send').onclick = sendMessage;
    document.getElementById('watering-chat-input').onkeypress = (e) => e.key === 'Enter' && sendMessage();

    // Inject Fonts
    const link = document.createElement('link');
    link.href = 'https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap';
    link.rel = 'stylesheet';
    document.head.appendChild(link);
})();
