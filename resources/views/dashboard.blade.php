@extends('layouts.app')

@section('title', 'Decision Dashboard')

@section('header-action')
    <a class="button primary" href="{{ route('alternatives.create') }}">Add Truck</a>
@endsection

@section('content')
    <div class="hero">
        <div class="card">
            <h3>Current Recommendation</h3>
            @if ($top)
                <p><strong>{{ $top['alternative']->name }}</strong> with score {{ number_format($top['score'], 4) }}</p>
                <p>Rank #{{ $top['rank'] }}</p>
            @else
                <p>Add alternatives to see the recommendation.</p>
            @endif
        </div>
    </div>

    <div class="card-grid">
        <div class="card">
            <h3>Criteria</h3>
            <p>{{ $criteria->count() }} total</p>
        </div>
        <div class="card">
            <h3>Active Alternatives</h3>
            <p>{{ $alternatives->count() }} trucks</p>
        </div>
        <div class="card">
            <h3>Weights Ready</h3>
            <p>{{ $criteria->where('weight', '>', 0)->count() }} set</p>
        </div>
    </div>

    <div class="section-title">
        <h2>Quick View</h2>
        <a class="button" href="{{ route('results.index') }}">Open Results</a>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>Truck</th>
            <th>Status</th>
            <th>Code</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($alternatives as $alternative)
            <tr>
                <td>{{ $alternative->name }}</td>
                <td>{{ $alternative->is_active ? 'Active' : 'Inactive' }}</td>
                <td>{{ $alternative->code ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No alternatives yet.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
