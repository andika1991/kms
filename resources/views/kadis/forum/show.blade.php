<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Grup Chat: {{ $grupChat->nama_grup }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow flex flex-col h-[600px]">

            {{-- Anggota Grup --}}
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Anggota Grup:</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($anggota as $user)
                        <span class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 px-2 py-1 rounded text-sm">
                            {{ $user->name }}
                        </span>
                    @empty
                        <span class="text-gray-500 dark:text-gray-400">Belum ada anggota.</span>
                    @endforelse
                </div>
            </div>

            {{-- Chat Window --}}
            <div id="chat-messages"
                 class="flex-1 overflow-y-auto p-4 space-y-3 border border-gray-200 dark:border-gray-700 rounded bg-gray-50 dark:bg-gray-900">
                @forelse($messages as $message)
                    <div class="max-w-lg 
                        {{ $message->pengguna_id === auth()->id() ? 'ml-auto text-right' : 'mr-auto text-left' }}">
                        <div class="inline-block px-4 py-2 rounded 
                            {{ $message->pengguna_id === auth()->id() 
                                ? 'bg-blue-600 text-white' 
                                : 'bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-100' }}">
                            <p class="text-sm font-semibold mb-1">
                                {{ $message->pengguna?->name ?? 'User tidak ditemukan' }}
                                <span class="text-xs text-gray-200 dark:text-gray-400 block">
                                    {{ $message->created_at->format('d M Y H:i') }}
                                </span>
                            </p>
                            <p class="text-base">
                                {{ $message->decrypted_message  }}
                            </p>

                            @if($message->file)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $message->file) }}"
                                       class="underline text-white dark:text-gray-200"
                                       target="_blank">📎 Lihat Lampiran</a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400">Belum ada pesan di grup ini.</p>
                @endforelse
            </div>

            {{-- Chat Input --}}
            <form method="POST"
                  action="{{ route('kadis.grupchat.pesan.store', $grupChat->id) }}"
                  enctype="multipart/form-data"
                  class="mt-4 flex flex-col sm:flex-row gap-2">
                @csrf

                <div class="flex-1 flex flex-col sm:flex-row gap-2">
                    <input type="text" name="message"
                           class="flex-1 rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200"
                           placeholder="Tulis pesan...">

                    <input type="file" name="file" class="hidden" id="file-input">

                    <button type="button"
                            onclick="document.getElementById('file-input').click()"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded">
                        📎
                    </button>
                </div>

                {{-- Tampilkan nama file yang dipilih --}}
                <p id="file-name" class="text-sm text-gray-600 dark:text-gray-400 mt-1 ml-1"></p>

                <div class="mt-2 sm:mt-0">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded w-full sm:w-auto">
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let chatBox = document.getElementById('chat-messages');
            chatBox.scrollTop = chatBox.scrollHeight;

            const fileInput = document.getElementById('file-input');
            const fileNameDisplay = document.getElementById('file-name');

            fileInput.addEventListener('change', function () {
                if (this.files.length > 0) {
                    fileNameDisplay.textContent = "📎 " + this.files[0].name;
                } else {
                    fileNameDisplay.textContent = "";
                }
            });
        });
    </script>
</x-app-layout>
