@extends('layout.admin')

@section('title', 'Select Train to Edit')

@section('content')  
    <div class="card text-center" style="width: 100%; background-color: #f8f9fa; border: 1px solid #ccc;">
        <div class="card-header text-white" style="background-color: #005F56">
                <h2>Select a Train to Edit</h2>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Train Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trains as $train)
                        <tr>
                            <td>{{ $train->trainname }}</td>
                            <td>
                                <a href="{{ route('train.edit', $train->trainid) }}" class="btn btn-primary">Edit</a>
                            </td>
                        </tr>
                    @endforeach    
                </tbody>
            </table>
        </div>
    </div>
@endsection
