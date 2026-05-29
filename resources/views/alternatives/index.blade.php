@extends('layouts.app')

@section('title', 'Alternatives')

@section('header-action')
    <a class="button primary" href="{{ route('alternatives.create') }}">Add Alternative</a>
@endsection

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Code</th>
            <th>Status</th>
            @foreach ($criteria as $criterion)
                <th>{{ $criterion->code }}</th>
            @endforeach
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($alternatives as $alternative)
            <tr>
                <td>{{ $alternative->name }}</td>
                <td>{{ $alternative->code ?? '-' }}</td>
                <td>{{ $alternative->is_active ? 'Active' : 'Inactive' }}</td>
                @foreach ($criteria as $criterion)
                    @php
                        $value = $alternative->values->firstWhere('criteria_id', $criterion->id);
                    @endphp
                    <td>{{ $value ? number_format($value->value, 2) : '-' }}</td>
                @endforeach
                <td>
                    <div class="actions">
                        <a class="button" href="{{ route('alternatives.edit', $alternative) }}">Edit</a>
                        <form method="post" action="{{ route('alternatives.destroy', $alternative) }}" onsubmit="return confirm('Delete this alternative?')">
                            @csrf
                            @method('delete')
                            <button class="button danger" type="submit">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ 4 + $criteria->count() }}">No alternatives found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
