<div x-data="{ open: false }">

    <!-- Trigger -->
    <button @click="open = true" class="bg-blue-500 text-white px-4 py-2 rounded">
        Buka Dialog
    </button>

    <!-- Modal -->
    <div x-show="open" class="fixed inset-0 bg-black/50 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow">

            <h2 class="text-lg font-semibold">Konfirmasi</h2>
            <p class="text-sm text-gray-500">Apakah kamu yakin?</p>

            <div class="mt-4 flex gap-2">
                <button @click="open = false" class="px-3 py-1 border rounded">
                    Batal
                </button>
                <button class="px-3 py-1 bg-red-500 text-white rounded">
                    Ya
                </button>
            </div>

        </div>
    </div>

</div>
