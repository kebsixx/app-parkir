<div class="rounded-card border border-line bg-card shadow-sm">
    <div class="border-b border-line px-5 py-4">
        <h2 class="text-base font-semibold text-ink">Pembayaran</h2>
        <p class="mt-1 text-sm text-muted">Masukkan uang tunai yang diterima.</p>
    </div>

    <form wire:submit="checkout" class="space-y-5 p-5">
        <div class="rounded-control bg-surface p-4">
            <div class="flex items-center justify-between gap-4">
                <span class="text-sm text-muted">Total tagihan</span>
                <span class="text-xl font-semibold tabular-nums text-ink">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div>
            <label for="paid-amount" class="block text-sm font-medium text-ink-soft">Uang diterima</label>
            <input
                id="paid-amount"
                type="number"
                min="0"
                step="500"
                wire:model.live="paidAmount"
                class="mt-1.5 block w-full rounded-control border border-line bg-card px-3 py-3 text-lg font-semibold tabular-nums text-ink shadow-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"
            >
            @error('paidAmount')
                <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-control border border-line px-4 py-3">
            <div class="flex items-center justify-between gap-4">
                <span class="text-sm text-muted">Kembalian</span>
                <span class="text-lg font-semibold tabular-nums {{ $paid < $total ? 'text-danger' : 'text-success' }}">
                    Rp {{ number_format($change, 0, ',', '.') }}
                </span>
            </div>
            @if ($paid > 0 && $paid < $total)
                <p class="mt-1 text-xs text-danger">Uang diterima masih kurang Rp {{ number_format($total - $paid, 0, ',', '.') }}.</p>
            @endif
        </div>

        <x-ui.button type="submit" class="w-full" size="lg" :disabled="$paid < $total">
            <span wire:loading.remove wire:target="checkout">Konfirmasi Bayar</span>
            <span wire:loading wire:target="checkout">Memproses...</span>
        </x-ui.button>

        <p class="text-center text-xs text-muted">Transaksi dicatat satu kali. Pastikan nominal sudah benar.</p>
    </form>
</div>
