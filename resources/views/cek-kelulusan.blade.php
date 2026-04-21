<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Kelulusan Siswa Kelas XII</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .confetti {
            animation: fall 3s ease-in-out infinite;
        }

        @keyframes fall {
            0% {
                transform: translateY(-10px) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">

    {{-- Header --}}
    <header class="bg-gradient-to-r from-blue-700 to-indigo-800 text-white shadow-lg">
        <div class="max-w-4xl mx-auto py-8 px-4 text-center">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-yellow-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path
                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zM12 14v7" />
                </svg>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Pengumuman Kelulusan</h1>
            <p class="text-lg text-blue-100">Siswa Kelas XII SMA / SMK</p>
            @if (isset($pengumuman) && $pengumuman)
                <p class="mt-2 text-yellow-200 font-semibold">Tahun Ajaran {{ $pengumuman->tahun_ajaran }}</p>
            @endif
        </div>
    </header>

    <main class="max-w-4xl mx-auto py-10 px-4">

        {{-- Countdown --}}
        @if (!empty($showCountdown) && isset($pengumuman) && $pengumuman)
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 text-center" id="countdown-section">
                <div class="text-5xl mb-4">⏳</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Pengumuman Akan Dibuka Dalam</h2>
                <p class="text-gray-500 mb-6">{{ $pengumuman->judul }}</p>

                <div class="flex justify-center gap-4 md:gap-6" id="countdown">
                    <div
                        class="bg-gradient-to-b from-blue-600 to-indigo-700 text-white rounded-2xl p-4 md:p-6 min-w-[80px] shadow-lg">
                        <div class="text-3xl md:text-5xl font-extrabold" id="cd-days">--</div>
                        <div class="text-xs md:text-sm text-blue-200 mt-1">Hari</div>
                    </div>
                    <div
                        class="bg-gradient-to-b from-blue-600 to-indigo-700 text-white rounded-2xl p-4 md:p-6 min-w-[80px] shadow-lg">
                        <div class="text-3xl md:text-5xl font-extrabold" id="cd-hours">--</div>
                        <div class="text-xs md:text-sm text-blue-200 mt-1">Jam</div>
                    </div>
                    <div
                        class="bg-gradient-to-b from-blue-600 to-indigo-700 text-white rounded-2xl p-4 md:p-6 min-w-[80px] shadow-lg">
                        <div class="text-3xl md:text-5xl font-extrabold" id="cd-minutes">--</div>
                        <div class="text-xs md:text-sm text-blue-200 mt-1">Menit</div>
                    </div>
                    <div
                        class="bg-gradient-to-b from-blue-600 to-indigo-700 text-white rounded-2xl p-4 md:p-6 min-w-[80px] shadow-lg">
                        <div class="text-3xl md:text-5xl font-extrabold" id="cd-seconds">--</div>
                        <div class="text-xs md:text-sm text-blue-200 mt-1">Detik</div>
                    </div>
                </div>

                <p class="mt-6 text-sm text-gray-400">
                    Pengumuman dibuka pada: {{ $pengumuman->tanggal_pengumuman->format('d F Y, H:i') }} WITA
                </p>
            </div>

            <script>
                (function() {
                    const targetDate = new Date("{{ $pengumuman->tanggal_pengumuman->toIso8601String() }}").getTime();

                    function updateCountdown() {
                        const now = new Date().getTime();
                        const diff = targetDate - now;

                        if (diff <= 0) {
                            window.location.reload();
                            return;
                        }

                        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                        document.getElementById('cd-days').textContent = String(days).padStart(2, '0');
                        document.getElementById('cd-hours').textContent = String(hours).padStart(2, '0');
                        document.getElementById('cd-minutes').textContent = String(minutes).padStart(2, '0');
                        document.getElementById('cd-seconds').textContent = String(seconds).padStart(2, '0');
                    }

                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                })();
            </script>
        @else
            {{-- Form Pencarian --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4 text-center">Cek Status Kelulusan</h2>
                <p class="text-gray-500 text-center mb-6">Masukkan NISN (Nomor Induk Siswa Nasional) untuk mengecek
                    status
                    kelulusan</p>

                <form action="{{ route('cek-kelulusan.cari') }}" method="POST" class="max-w-md mx-auto">
                    @csrf
                    <div class="flex gap-3">
                        <input type="text" name="nisn" placeholder="Masukkan NISN..."
                            value="{{ old('nisn', request('nisn')) }}" required maxlength="20"
                            class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-lg" />
                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg">
                            Cek
                        </button>
                    </div>
                    @error('nisn')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </form>
            </div>

            {{-- Hasil Pencarian --}}
            @if (isset($siswa) && isset($kelulusan) && $kelulusan)
                @if ($kelulusan->status === 'lulus')
                    {{-- LULUS --}}
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-green-400">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white py-6 px-8 text-center">
                            <div class="text-5xl mb-2">🎓</div>
                            <h3 class="text-3xl font-extrabold">SELAMAT!</h3>
                            <p class="text-green-100 text-lg">Anda dinyatakan</p>
                            <div
                                class="inline-block mt-2 px-6 py-2 bg-white text-green-700 rounded-full text-2xl font-extrabold shadow">
                                L U L U S
                            </div>
                        </div>
                        <div class="p-8">
                            <table class="w-full text-left">
                                <tbody class="divide-y divide-gray-100">
                                    <tr>
                                        <td class="py-3 text-gray-500 font-medium w-40">NISN</td>
                                        <td class="py-3 font-semibold text-gray-800">{{ $siswa->nisn }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 text-gray-500 font-medium">Nama</td>
                                        <td class="py-3 font-semibold text-gray-800">{{ $siswa->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 text-gray-500 font-medium">Sekolah</td>
                                        <td class="py-3 font-semibold text-gray-800">{{ $siswa->sekolah->nama ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 text-gray-500 font-medium">Kelas</td>
                                        <td class="py-3 font-semibold text-gray-800">{{ $siswa->kelas }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 text-gray-500 font-medium">Jurusan</td>
                                        <td class="py-3 font-semibold text-gray-800">{{ $siswa->jurusan ?? '-' }}</td>
                                    </tr>
                                    @if ($kelulusan->nilai_rata_rata)
                                        <tr>
                                            <td class="py-3 text-gray-500 font-medium">Nilai Rata-rata</td>
                                            <td class="py-3 font-semibold text-gray-800">
                                                {{ number_format($kelulusan->nilai_rata_rata, 2) }}</td>
                                        </tr>
                                    @endif
                                    @if ($kelulusan->keterangan)
                                        <tr>
                                            <td class="py-3 text-gray-500 font-medium">Keterangan</td>
                                            <td class="py-3 font-semibold text-green-600">{{ $kelulusan->keterangan }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            @if ($kelulusan->skl_path)
                                <div class="mt-6 text-center">
                                    <a href="{{ route('skl.download', $kelulusan) }}"
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Download SKL (Surat Keterangan Lulus)
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    {{-- TIDAK LULUS --}}
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-red-400">
                        <div class="bg-gradient-to-r from-red-500 to-rose-600 text-white py-6 px-8 text-center">
                            <div class="text-5xl mb-2">📋</div>
                            <h3 class="text-2xl font-bold">Hasil Kelulusan</h3>
                            <div
                                class="inline-block mt-2 px-6 py-2 bg-white text-red-700 rounded-full text-xl font-extrabold shadow">
                                TIDAK LULUS
                            </div>
                        </div>
                        <div class="p-8">
                            <table class="w-full text-left">
                                <tbody class="divide-y divide-gray-100">
                                    <tr>
                                        <td class="py-3 text-gray-500 font-medium w-40">NISN</td>
                                        <td class="py-3 font-semibold text-gray-800">{{ $siswa->nisn }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 text-gray-500 font-medium">Nama</td>
                                        <td class="py-3 font-semibold text-gray-800">{{ $siswa->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 text-gray-500 font-medium">Sekolah</td>
                                        <td class="py-3 font-semibold text-gray-800">
                                            {{ $siswa->sekolah->nama ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 text-gray-500 font-medium">Kelas</td>
                                        <td class="py-3 font-semibold text-gray-800">{{ $siswa->kelas }}</td>
                                    </tr>
                                    @if ($kelulusan->keterangan)
                                        <tr>
                                            <td class="py-3 text-gray-500 font-medium">Keterangan</td>
                                            <td class="py-3 font-semibold text-red-600">{{ $kelulusan->keterangan }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <div
                                class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-yellow-800 text-sm">
                                Tetap semangat! Hubungi pihak sekolah untuk informasi lebih lanjut.
                            </div>
                        </div>
                    </div>
                @endif
            @elseif(isset($siswa) && !isset($kelulusan))
                {{-- Siswa ditemukan tapi belum ada data kelulusan --}}
                <div class="bg-white rounded-2xl shadow-xl p-8 text-center border-2 border-yellow-400">
                    <div class="text-5xl mb-4">⏳</div>
                    <h3 class="text-xl font-bold text-yellow-700 mb-2">Data Kelulusan Belum Tersedia</h3>
                    <p class="text-gray-500">Data siswa dengan NISN <strong>{{ $siswa->nisn }}</strong>
                        ({{ $siswa->nama }}) ditemukan, namun pengumuman kelulusan belum dipublikasikan.</p>
                </div>
            @elseif(isset($siswa) && $siswa === null)
                {{-- NISN tidak ditemukan --}}
                <div class="bg-white rounded-2xl shadow-xl p-8 text-center border-2 border-gray-300">
                    <div class="text-5xl mb-4">🔍</div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-gray-500">Siswa dengan NISN yang dimasukkan tidak ditemukan dalam database. Pastikan
                        NISN
                        yang dimasukkan sudah benar.</p>
                </div>
            @endif

            {{-- Info Pengumuman --}}
            @if (isset($pengumuman) && $pengumuman && $pengumuman->deskripsi)
                <div class="mt-8 bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">{{ $pengumuman->judul }}</h3>
                    <div class="prose prose-sm text-gray-600 max-w-none">
                        {!! $pengumuman->deskripsi !!}
                    </div>
                    <p class="mt-4 text-sm text-gray-400">Tanggal Pengumuman:
                        {{ $pengumuman->tanggal_pengumuman->format('d F Y, H:i') }} WITA</p>
                </div>
            @endif
        @endif
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-gray-400 text-center py-6 mt-10">
        <p>&copy; {{ date('Y') }} Sistem Informasi Pengumuman Kelulusan</p>
        <p class="text-sm mt-1">
            <a href="{{ url('/admin') }}" class="text-blue-400 hover:text-blue-300">Login Admin</a>
        </p>
    </footer>

</body>

</html>
