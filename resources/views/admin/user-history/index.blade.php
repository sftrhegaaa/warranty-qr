@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="usersTable" class="table table-bordered table-striped justify-content-center text-align-center">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Email</th>  
                            <th class="text-center">Tanggal Lahir</th>
                            <th class="text-center">Gender</th>
                            <th class="text-center">Address Information</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($warranties as $p)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}.</td>
                            <td class="text-center">{{ $p->nama }}</td>
                            <td class="text-center">{{ $p->email }}</td>
                            <td class="text-center">{{ $p->tanggal_lahir }}</td>
                            <td class="text-center">{{ $p->gender }}</td>
                            <td class="text-center">{!! $p->alamat_admin !!}</td>


                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-muted">
                                Data belum ada
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>
$(document).ready(function () {
    $('#usersTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthMenu: [5, 10, 25, 50],
        pageLength: 10,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "›",
                previous: "‹"
            },
            zeroRecords: "Data tidak ditemukan"
        }
    });
});
</script>
@endpush




