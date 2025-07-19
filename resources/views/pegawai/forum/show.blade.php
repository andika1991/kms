<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Forum Diskusi: {{ $grupChat->nama_grup }}
        </h2>
    </x-slot>

    <div class="w-full bg-[#eaf5ff] min-h-screen py-6">
        <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 flex flex-col h-[600px] border border-gray-200 dark:border-gray-700">

                {{-- Anggota Grup --}}
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-2">Anggota Grup</h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse($anggota as $user)
                            <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200 text-sm">
                                {{ $user->name }}
                            </span>
                        @empty
                            <span class="text-gray-500 dark:text-gray-400">Belum ada anggota.</span>
                        @endforelse
                    </div>
                </div>

                {{-- Chat Window --}}
                <div id="chat-messages"
                    class="flex-1 overflow-y-auto p-4 space-y-3 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700 mb-6">
                    @forelse($messages as $message)
                        <div class="flex {{ $message->pengguna_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs md:max-w-md px-4 py-2 rounded-2xl shadow 
                                {{ $message->pengguna_id === auth()->id() 
                                    ? 'bg-blue-600 text-white' 
                                    : 'bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-100' }}">
                                <div class="text-xs font-semibold mb-1 flex items-center gap-2">
                                    <span>{{ $message->pengguna?->name ?? 'User tidak ditemukan' }}</span>
                                    <span class="text-[10px] opacity-60">{{ $message->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <div class="text-base break-words">{{ $message->message }}</div>
                                @if($message->file)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $message->file) }}"
                                           class="underline text-white dark:text-gray-200 text-xs"
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
                      action="{{ route('pegawai.grupchat.pesan.store', $grupChat->id) }}"
                      enctype="multipart/form-data"
                      class="flex flex-col sm:flex-row items-end gap-2 mt-2">
                    @csrf

                    <div class="flex-1 flex flex-col sm:flex-row gap-2">
                        <div class="relative flex-1">
                            <input type="text" name="message"
                                class="w-full rounded-full border border-gray-300 dark:bg-gray-700 dark:text-gray-200 px-4 py-2 focus:ring-2 focus:ring-blue-500"
                                placeholder="Tulis pesan..." autocomplete="off">
                            <input type="file" name="file" class="hidden" id="file-input">
                            <button type="button"
                                onclick="document.getElementById('file-input').click()"
                                class="absolute top-1/2 right-4 -translate-y-1/2 text-gray-400 hover:text-blue-700 focus:outline-none">
                                📎
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1 w-full sm:w-auto">
                        <span id="file-name" class="text-xs text-gray-600 dark:text-gray-400 mb-1"></span>
                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-2 rounded-full bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition">
                            Kirim
                        </button>
                    </div>
                </form>
            </div>
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
