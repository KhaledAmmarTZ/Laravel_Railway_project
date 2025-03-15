@extends('layout.apps')

@section('content')
<div class="container">
    <h2>Routes List</h2>
    <a href="{{ route('routes.create') }}" class="btn btn-primary mb-3">Add Route</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @php
        $groupedRoutes = $routes->groupBy('route_no');
    @endphp
    <table class="table">
        <thead>
            <tr>
                <th>Route No</th>
                <th>Stations</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedRoutes as $route_no => $routes)
            <tr>
                <td>{{ $route_no }}</td>
                <td>
                    @foreach($routes as $route)
                        {{ $route->source }} → 
                    @endforeach
                    {{ $routes->last()->destination }}
                </td>
                <td>
                    <a href="{{ route('routes.edit', $routes->first()->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('routes.destroy', $routes->first()->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
