<?php

namespace App\Livewire;

use App\Models\ParkingSession;
use App\Services\ParkingService;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CheckoutPanel extends Component
{
    public ParkingSession $session;

    public string $paidAmount = '';

    public function mount(ParkingSession $session): void
    {
        $this->session = $session;
        $this->paidAmount = (string) $session->calculatedFee();
    }

    public function checkout(ParkingService $service)
    {
        $this->validate([
            'paidAmount' => ['required', 'integer', 'min:0'],
        ]);

        try {
            $service->checkout($this->session, auth()->user(), (int) $this->paidAmount);
        } catch (ValidationException $exception) {
            foreach ($exception->errors() as $field => $messages) {
                $this->addError($field === 'paid_amount' ? 'paidAmount' : $field, $messages[0]);
            }

            return null;
        }

        session()->flash('success', 'Pembayaran berhasil. Kendaraan boleh keluar.');

        return redirect()->route('parking.success', $this->session->id);
    }

    public function render()
    {
        $total = $this->session->calculatedFee();
        $paid = (int) $this->paidAmount;
        $change = max(0, $paid - $total);

        return view('livewire.checkout-panel', compact('total', 'paid', 'change'));
    }
}
