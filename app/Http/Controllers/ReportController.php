<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $validator = Validator::make($request->query(), [
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $dateFrom = $validator->fails()
            ? today()->toDateString()
            : $request->query('date_from', today()->toDateString());
        $dateTo = $validator->fails()
            ? today()->toDateString()
            : $request->query('date_to', today()->toDateString());

        $sessions = [];
        $summary = ['total_transactions' => 0, 'total_revenue' => 0];
        $error = null;

        try {
            $response = Http::timeout(5)->get(rtrim(config('services.internal_api.base_url'), '/').'/api/parking-sessions', [
                'status' => 'completed',
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'per_page' => 100,
            ]);

            if ($response->successful()) {
                $payload = $response->json();
                $sessions = $payload['data'] ?? [];
                $summary = $payload['summary'] ?? $summary;
            } else {
                $error = 'Laporan belum bisa dimuat dari API.';
            }
        } catch (ConnectionException) {
            $error = 'Server API internal tidak bisa dihubungi. Jalankan server kedua di port 8011.';
        }

        return view('reports.index', compact('sessions', 'summary', 'dateFrom', 'dateTo', 'error'));
    }
}
