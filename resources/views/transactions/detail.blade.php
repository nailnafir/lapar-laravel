<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transaksi &raquo; {{ $item->food->name }} oleh {{ $item->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full rounded overflow-hidden shadow-lg px-6 py-6 bg-white">
                <div class="flex flex-wrap -mx-4 -mb-4 md:mb-0">
                    <div class="w-full md:w-1/6 px-4 mb-4 md:mb-0">
                        <img src="{{ $item->food->picturePath }}" alt="" class="w-full mb-8 rounded">
                            <div class="text-sm">Status Saat Ini :</div>
                            <div class="text-xl font-bold mb-6">{{ $item->status }}</div>
                            <div class="text-sm mb-2">Ubah Status :</div>
                            <a href="{{ route('transactions.changeStatus', ['id' => $item->id, 'status' => 'Sedang Dikirim']) }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 py-3 rounded block text-center w-full mb-3"
                                onclick="Swal.fire({
                                    icon: 'success',
                                    title: 'Yuhuuu',
                                    text: 'Kamu berhasil ubah status pengiriman!',
                                    showCancelButton: false,
                                    showConfirmButton: false
                                })">
                                Sedang Dikirim
                            </a>
                            <a href="{{ route('transactions.changeStatus', ['id' => $item->id, 'status' => 'Terkirim']) }}"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold px-2 py-3 rounded block text-center w-full mb-3"
                                onclick="Swal.fire({
                                    icon: 'success',
                                    title: 'Yuhuuu',
                                    text: 'Kamu berhasil ubah status pengiriman!',
                                    showCancelButton: false,
                                    showConfirmButton: false
                                })">
                                Terkirim
                            </a>
                            <a href="{{ route('transactions.changeStatus', ['id' => $item->id, 'status' => 'Dibatalkan']) }}"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold px-2 py-3 rounded block text-center w-full mb-3"
                                onclick="Swal.fire({
                                    icon: 'success',
                                    title: 'Yuhuuu',
                                    text: 'Kamu berhasil ubah status pengiriman!',
                                    showCancelButton: false,
                                    showConfirmButton: false
                                })">
                                Dibatalkan
                            </a>
                    </div>
                    <div class="w-full md:w-5/6 px-4 mb-4 md:mb-0">
                        <div class="flex flex-wrap mb-4">
                            <div class="w-2/6">
                                <div class="text-sm">Nama Produk</div>
                                <div class="text-xl font-bold">{{ $item->food->name }}</div>
                            </div>
                            <div class="w-1/6">
                                <div class="text-sm">Jumlah Beli</div>
                                <div class="text-xl font-bold">{{ number_format($item->quantity) }}</div>
                            </div>
                            <div class="w-1/6">
                                <div class="text-sm">Diskon</div>
                                <div class="text-xl font-bold">{{ number_format($item->food->discount) }}%</div>
                            </div>
                            <div class="w-2/6">
                                <div class="text-sm">Total Harga</div>
                                <div class="text-xl font-bold">IDR {{ number_format($item->total) }}</div>
                            </div>
                        </div>
                        <div class="flex flex-wrap mb-4">
                            <div class="w-2/6">
                                <div class="text-sm">Penilaian Makanan</div>
                                <div class="text-xl font-bold">{{ $item->food->rate }} / 5.0 &starf; </div>
                            </div>
                            <div class="w-4/6">
                                <div class="text-sm">Lokasi Penjual</div>
                                <div class="text-xl font-bold">{{ $item->food->location }}</div>
                            </div>
                        </div>
                        <hr class="mt-8 mb-8">
                        <div class="flex flex-wrap mb-3">
                            <div class="w-2/6">
                                <div class="text-sm">Nama Pembeli</div>
                                <div class="text-xl font-bold">{{ $item->user->name }}</div>
                            </div>
                            <div class="w-2/6">
                                <div class="text-sm">Email</div>
                                <div class="text-xl font-bold">{{ $item->user->email }}</div>
                            </div>
                            <div class="w-2/6">
                                <div class="text-sm">Jenis Kelamin</div>
                                <div class="text-xl font-bold">{{ $item->user->gender }}</div>
                            </div>
                        </div>
                        <div class="flex flex-wrap mb-4">
                            <div class="w-2/6">
                                <div class="text-sm">Alamat Rumah</div>
                                <div class="text-xl font-bold">{{ $item->user->address }}</div>
                            </div>
                            <div class="w-1/6">
                                <div class="text-sm">RT / RW</div>
                                <div class="text-xl font-bold">{{ $item->user->rtrw }}</div>
                            </div>
                            <div class="w-1/6">
                                <div class="text-sm">Nomor Rumah</div>
                                <div class="text-xl font-bold">{{ $item->user->houseNumber }}</div>
                            </div>
                            <div class="w-2/6">
                                <div class="text-sm">Nomor Handphone</div>
                                <div class="text-xl font-bold">{{ $item->user->phoneNumber }}</div>
                            </div>
                        </div>
                        <div class="flex flex-wrap mb-4">
                            <div class="w-2/6">
                                <div class="text-sm">Kabupaten / Kota</div>
                                <div class="text-xl font-bold">{{ $item->user->city }}</div>
                            </div>
                            <div class="w-1/6">
                                <div class="text-sm">Kelurahan</div>
                                <div class="text-xl font-bold">{{ $item->user->subDistrict }}</div>
                            </div>
                            <div class="w-1/6">
                                <div class="text-sm">Kecamatan</div>
                                <div class="text-xl font-bold">{{ $item->user->district }}</div>
                            </div>
                            <div class="w-2/6">
                                <div class="text-sm">Waktu Transaksi</div>
                                <div class="text-xl font-bold">{{ date('l, d/m/Y, H:i:s', substr($item->created_at, 0, 10)) }}</div>
                                {{-- <div class="text-xl font-bold">{{ $item->created_at }}</div> --}}
                            </div>
                        </div>
                        <hr class="mt-8 mb-8">
                        <div class="flex flex-wrap mb-4">
                            <div class="w-6/6">
                                <div class="text-sm">URL Pembayaran</div>
                                <div class="text-lg font-bold hover:text-blue-500">
                                    <a href="{{ $item->payment_url }}">{{ $item->payment_url }}</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>