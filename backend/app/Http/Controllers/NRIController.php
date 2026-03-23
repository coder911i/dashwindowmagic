<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Kreait\Laravel\Firebase\Facades\Firebase;

class NRIController extends Controller
{
    use ApiResponse;

    public function markAsNRI(Request $request, string $id)
    {
        $tenantId = $request->attributes->get('tenantId');
        
        Firebase::firestore()->database()
            ->collection('tenants')
            ->document($tenantId)
            ->collection('leads')
            ->document($id)
            ->set([
                'isNRI' => true,
                'nri' => [
                    'country' => $request->input('country'),
                    'timezone' => $request->input('timezone'),
                    'currency' => $request->input('currency'),
                    'updatedAt' => new \DateTime()
                ]
            ], ['merge' => true]);

        return $this->success(null, 'Lead marked as NRI.');
    }

    public function timezones()
    {
        return $this->success([
            ['id' => 'Asia/Dubai', 'name' => 'UAE (GST)'],
            ['id' => 'Europe/London', 'name' => 'UK (GMT)'],
            ['id' => 'America/New_York', 'name' => 'USA (EST)'],
            ['id' => 'Asia/Singapore', 'name' => 'Singapore (SGT)'],
            ['id' => 'Australia/Sydney', 'name' => 'Australia (AEST)'],
        ]);
    }

    public function currencyRates()
    {
        // Mocking rates to avoid API key requirement for demo
        return $this->success([
            'USD' => 83.50,
            'AED' => 22.74,
            'GBP' => 105.20,
            'SGD' => 61.80
        ]);
    }
}
