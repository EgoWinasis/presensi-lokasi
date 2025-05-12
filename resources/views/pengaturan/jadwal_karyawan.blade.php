@extends('adminlte::page')

@section('title', 'Jadwal Karyawan')

@section('content_header')
    <h1>Jadwal Karyawan</h1>
@stop

@section('content')

<div id="layoutSidenav">
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-12">
                        <h4>Download Users Data as Excel</h4>

                        <!-- Button to trigger Excel download -->
                        <button onclick="generateExcel()" class="btn btn-success">Download Users Data</button>
                    </div>
                </div>

                <hr>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h4>Users List</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

@stop

@section('footer')
    @include('footer')
@stop

@section('css')
@endsection

@section('js')

<!-- Include SheetJS (XLSX) library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>

<script>
    // Pass the users data from Laravel to JavaScript
    var users = @json($users);

    // Function to generate and download the Excel file
    function generateExcel() {
        // Prepare data for the Excel file
        var data = users.map(function(user) {
            return {
                'ID': user.id,
                'Name': user.name,
                'Email': user.email,
            };
        });

        // Create a workbook and add a worksheet with the users data
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.json_to_sheet(data);

        // Append the worksheet to the workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Users');

        // Generate Excel file and trigger download
        XLSX.writeFile(wb, 'users_data.xlsx');
    }
</script>

@stop
