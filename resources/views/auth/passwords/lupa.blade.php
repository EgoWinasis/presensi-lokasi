<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> {{-- Include your CSS --}}
</head>
<body>
    <div class="container mt-5">
        <h2>Lupa Password</h2>

        {{-- Show success message --}}
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        {{-- Show validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Reset Form --}}
        <form method="POST" action="{{ route('ubah.kirim') }}">
            @csrf

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" name="email" class="form-control" required autofocus placeholder="Masukkan email anda">
            </div>

            <button type="submit" class="btn btn-primary">Kirim Permintaan Reset</button>
        </form>
    </div>
</body>
</html>
