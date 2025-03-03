<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="rounded-lg p-6 shadow">
            <div class="text-2xl font-bold text-danger-600">
                {{ $this->getStats()['total_belum_disposisi'] }}
            </div>
            <div class="mt-2">
                <h3 class="text-lg font-semibold">Surat Belum Disposisi</h3>
                <p class="text-gray-500 text-sm">Menunggu tindakan Anda</p>
            </div>
        </div>

        <div class="rounded-lg p-6 shadow">
            <div class="text-2xl font-bold text-primary-600">
                {{ $this->getStats()['total_surat'] }}
            </div>
            <div class="mt-2">
                <h3 class="text-lg font-semibold">Total Surat Masuk</h3>
                <p class="text-gray-500 text-sm">Seluruh arsip surat</p>
            </div>
        </div>
    </div>

    {{ $this->table }}
</x-filament-panels::page>
