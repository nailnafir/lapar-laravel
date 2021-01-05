<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <?php $nomor = 0 ?>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="border px-6 py-4">Nomor</th>
                            <th class="border px-6 py-4">Nama Makanan</th>
                            <th class="border px-6 py-4">Nama Pengguna</th>
                            <th class="border px-6 py-4">Jumlah Beli</th>
                            <th class="border px-6 py-4">Total Harga</th>
                            <th class="border px-6 py-4">Status</th>
                            <th class="border px-6 py-4">Kelola</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $item)
                            <tr>
                                <td class="border px-6 py-4">{{ ++$nomor + ($transactions->currentPage()-1) * $transactions->perPage() }}</td>
                                <td class="border px-6 py-4">{{ $item->food->name }}</td>
                                <td class="border px-6 py-4">{{ $item->user->name }}</td>
                                <td class="border px-6 py-4">{{ $item->quantity }}</td>
                                <td class="border px-6 py-4">{{ number_format($item->total) }}</td>
                                <td class="border px-6 py-4">{{ $item->status }}</td>
                                <td class="border px-6 py-4 text-center">
                                    <a href=" {{ route('transactions.show', $item->id) }} " class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mx-2 rounded">
                                        Lihat
                                    </a>
                                    <form action=" {{ route('transactions.destroy', $item->id) }} " method="post" class="inline-block">
                                        {!! method_field('delete') . csrf_field() !!}
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 mx-2 rounded">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="border text-center p-5">Data tidak ditemukan!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-5">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
