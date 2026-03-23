(function() {
    const script = document.currentScript;
    const tenantId = script.getAttribute('data-tenant');
    const primaryColor = script.getAttribute('data-color') || '#2563EB';
    const apiUrl = 'https://api.wateringcrm.com/api/chatbot'; // Production URL

    let sessionId = Math.random().toString(36).substring(7);
    let history = [];
    let config = {};

    // Create Styles
    const style = document.createElement('style');
    style.innerHTML = `
        #watering-chatbot-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 10000;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        #watering-chat-button {
            width: 60px;
            height: 60px;
            border-radius: 30px;
            background: ${primaryColor};
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s;
        }
        #watering-chat-button:hover { transform: scale(1.1); }
        #watering-chat-panel {
            width: 350px;
            height: 500px;
            background: white;
            position: absolute;
            bottom: 80px;
            right: 0;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
            display: none;
            flex-direction: column;
            overflow: hidden;
        }
        #watering-chat-header {
            padding: 20px;
            background: ${primaryColor};
            color: white;
            font-weight: bold;
        }
        #watering-chat-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #F9FAFB;
        }
        .watering-msg {
            max-width: 80%;
            padding: 10px 14px;
            border-radius: 15px;
            margin-bottom: 10px;
            font-size: 13px;
            line-height: 1.4;
        }
        .watering-msg-ai { background: white; color: #1F2937; align-self: flex-start; border: 1px solid #E5E7EB; }
        .watering-msg-user { background: ${primaryColor}; color: white; align-self: flex-end; margin-left: auto; }
        #watering-chat-input-area {
            padding: 15px;
            border-top: 1px solid #E5E7EB;
            display: flex;
            gap: 10px;
        }
        #watering-chat-input {
            flex: 1;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 8px 12px;
            outline: none;
        }
        #watering-typing {
            font-size: 10px;
            color: #9CA3AF;
            margin-bottom: 5px;
            display: none;
        }
    `;
    document.head.appendChild(style);

    // Create UI
    const container = document.createElement('div');
    container.id = 'watering-chatbot-widget';
    container.innerHTML = `
        <div id="watering-chat-panel">
            <div id="watering-chat-header">Dream Home Assistant</div>
            <div id="watering-chat-messages">
                <div id="watering-typing">Watering AI is typing...</div>
            </div>
            <div id="watering-chat-input-area">
                <input type="text" id="watering-chat-input" placeholder="Ask anything...">
                <button id="watering-chat-send" style="background:${primaryColor}; color:white; border:none; border-radius:8px; padding:0 15px; cursor:pointer">Send</button>
            </div>
        </div>
        <div id="watering-chat-button">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
        </div>
    `;
    document.body.appendChild(container);

    const panel = document.getElementById('watering-chat-panel');
    const button = document.getElementById('watering-chat-button');
    const input = document.getElementById('watering-chat-input');
    const sendBtn = document.getElementById('watering-chat-send');
    const messages = document.getElementById('watering-chat-messages');
    const typing = document.getElementById('watering-typing');

    button.onclick = () => {
        const isVisible = panel.style.display === 'flex';
        panel.style.display = isVisible ? 'none' : 'flex';
        if (!isVisible && history.length === 0) startConversation();
    };

    async function startConversation() {
        // Fetch custom config
        try {
            const res = await fetch(`${apiUrl}/config/${tenantId}`);
            const data = await res.json();
            config = data.data;
            addMessage(config.greeting || 'Hi! How can I help you?', 'ai');
        } catch (e) {
            addMessage('Hello! How can I help you today?', 'ai');
        }
    }

    async function sendMessage() {
        const msg = input.value.trim();
        if (!msg) return;
        
        input.value = '';
        addMessage(msg, 'user');
        
        typing.style.display = 'block';
        messages.scrollTop = messages.scrollHeight;

        try {
            const res = await fetch(`${apiUrl}/message`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    tenantId,
                    sessionId,
                    message: msg,
                    history: history
                })
            });
            const data = await res.json();
            const reply = data.data.reply;
            
            typing.style.display = 'none';
            addMessage(reply, 'ai');

            if (data.data.capturedLead) {
                // Secondary check for lead capture logic (optional)
            }
        } catch (e) {
            typing.style.display = 'none';
            addMessage("I'm having trouble connecting right now. Please try again later.", 'ai');
        }
    }

    function addMessage(text, role) {
        const div = document.createElement('div');
        div.className = `watering-msg watering-msg-${role}`;
        div.innerText = text;
        messages.insertBefore(div, typing);
        messages.scrollTop = messages.scrollHeight;
        history.push({ role, content: text });
    }

    sendBtn.onclick = sendMessage;
    input.onkeypress = (e) => { if (e.key === 'Enter') sendMessage(); };

})();
