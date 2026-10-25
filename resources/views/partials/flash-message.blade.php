
{{-- Pesan sukses --}}
@if (session('success'))
    <div class="alert alert-success" role="status">
        {{ session('success') }}
    </div>
@endif

{{-- Pesan gagal --}}
@if (session('error'))
    <div class="alert alert-error" role="alert">
        {{ session('error') }}
    </div>
@endif

{{-- Ringkasan error validasi --}}
@if ($errors->any())
    <div class="alert alert-error" role="alert">
        <strong>Periksa kembali data yang dimasukkan:</strong>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
