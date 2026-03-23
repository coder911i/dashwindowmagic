<?php

namespace App\Services;

class RERAService
{
    private array $patterns = [
        'Maharashtra' => '/^P\d{11}$/', // MahaRERA: P followed by 11 digits
        'Karnataka' => '/^PRM\/KA\/RERA\/\d{4}\/\d{3}\/[A-Z]\/\d{6}$/',
        'Uttar Pradesh' => '/^UPRERAPRJ\d+$/',
        'Haryana' => '/^HRERA-PKL-\w+-\d+-\d+$/',
        'Rajasthan' => '/^RAJ\/P\/\d{4}\/\d+$/',
        'Gujarat' => '/^PR\/GJ\/\w+\/\w+\/\d+\/\d+$/',
    ];

    public function validate(string $state, string $reraNumber): array
    {
        $pattern = $this->patterns[$state] ?? null;
        
        if (!$pattern) {
            return [
                'isValid' => false,
                'message' => "Regex pattern not available for $state. Manual verification required.",
                'hint' => null
            ];
        }

        $isValid = (bool)preg_match($pattern, $reraNumber);

        return [
            'isValid' => $isValid,
            'message' => $isValid ? 'Format is valid for ' . $state : 'Invalid format for ' . $state,
            'hint' => $this->getHint($state)
        ];
    }

    private function getHint(string $state): ?string
    {
        $hints = [
            'Maharashtra' => 'P51800001234',
            'Karnataka' => 'PRM/KA/RERA/1232/446/PR/171014/002263',
            'Uttar Pradesh' => 'UPRERAPRJ1234',
        ];
        return $hints[$state] ?? null;
    }
}
