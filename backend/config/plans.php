<?php

return [
    'starter' => [
        'name' => 'Starter',
        'price' => 99900, // in paise
        'agents' => 1,
        'leads' => 100,
        'ai_messages' => 50,
        'features' => ['1 Agent', '100 Leads', '50 AI Follow-ups', 'Standard Support']
    ],
    'growth' => [
        'name' => 'Growth',
        'price' => 249900,
        'agents' => 5,
        'leads' => -1, // Unlimited
        'ai_messages' => -1,
        'features' => ['5 Agents', 'Unlimited Leads', 'Unlimited AI Follow-ups', 'AI Chatbot Widget', 'WhatsApp API Ready', 'Priority Support']
    ],
    'agency' => [
        'name' => 'Agency',
        'price' => 499900,
        'agents' => 20,
        'leads' => -1,
        'ai_messages' => -1,
        'features' => ['20 Agents', 'Unlimited Everything', 'Automated Commission Tracker', 'Custom Branding', 'dedicated Account Manager']
    ],
];
