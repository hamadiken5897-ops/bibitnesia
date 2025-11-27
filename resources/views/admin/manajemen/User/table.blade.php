<table class="table table-striped" id="table1">
    <thead>
        <tr>
            <th>ID User</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id_user }}</td>
                <td>{{ $user->nama }}</td>
                <td>{{ $user->email }}</td>
                {{--   <td>{{ $user->no_telepon ?? '-' }}</td> 
                <td>{{ $user->alamat ?? '-' }}</td>   --}}
                <td>{{ $user->role }}</td>
                <td>
                    @if ($user->status_akun === 'AKTIF')
                        <span class="badge bg-success">Aktif</span>
                    @elseif ($user->status_akun === 'NONAKTIF')
                        <span class="badge bg-warning">Nonaktif</span>
                    @else
                        <span class="badge bg-danger">Banned</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.users.show', $user->id_user) }}" >
                        Detail
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
