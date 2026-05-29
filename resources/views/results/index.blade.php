@extends('layouts.app')

@section('title', 'TOPSIS Results')

@section('content')
    @if (!$result)
        <div class="card">
            <h3>No data yet</h3>
            <p>Add criteria and alternatives to see results.</p>
        </div>
    @else
        <div class="section-title">
            <h2>Decision Matrix</h2>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Alternative</th>
                @foreach ($criteria as $criterion)
                    <th>{{ $criterion->code }} ({{ $criterion->unit ?? '-' }})</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach ($alternatives as $alternative)
                <tr>
                    <td>{{ $alternative->name }}</td>
                    @foreach ($criteria as $criterion)
                        <td>{{ number_format($result['decisionMatrix'][$alternative->id][$criterion->id], 2) }}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="section-title">
            <h2>Normalized Matrix</h2>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Alternative</th>
                @foreach ($criteria as $criterion)
                    <th>{{ $criterion->code }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach ($alternatives as $alternative)
                <tr>
                    <td>{{ $alternative->name }}</td>
                    @foreach ($criteria as $criterion)
                        <td>{{ number_format($result['normalizedMatrix'][$alternative->id][$criterion->id], 4) }}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="section-title">
            <h2>Weighted Matrix</h2>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Alternative</th>
                @foreach ($criteria as $criterion)
                    <th>{{ $criterion->code }} (w={{ number_format($criterion->weight, 3) }})</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach ($alternatives as $alternative)
                <tr>
                    <td>{{ $alternative->name }}</td>
                    @foreach ($criteria as $criterion)
                        <td>{{ number_format($result['weightedMatrix'][$alternative->id][$criterion->id], 4) }}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="section-title">
            <h2>Ideal Solutions</h2>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Type</th>
                @foreach ($criteria as $criterion)
                    <th>{{ $criterion->code }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Ideal Positive (A+)</td>
                @foreach ($criteria as $criterion)
                    <td>{{ number_format($result['idealPositive'][$criterion->id], 4) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Ideal Negative (A-)</td>
                @foreach ($criteria as $criterion)
                    <td>{{ number_format($result['idealNegative'][$criterion->id], 4) }}</td>
                @endforeach
            </tr>
            </tbody>
        </table>

        <div class="section-title">
            <h2>Distances</h2>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Alternative</th>
                <th>D+</th>
                <th>D-</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($alternatives as $alternative)
                <tr>
                    <td>{{ $alternative->name }}</td>
                    <td>{{ number_format($result['distances'][$alternative->id]['positive'], 4) }}</td>
                    <td>{{ number_format($result['distances'][$alternative->id]['negative'], 4) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="section-title">
            <h2>Preference Scores</h2>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Rank</th>
                <th>Alternative</th>
                <th>Score (Ci)</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($result['ranking'] as $row)
                <tr>
                    <td>#{{ $row['rank'] }}</td>
                    <td>{{ $row['alternative']->name }}</td>
                    <td>{{ number_format($row['score'], 6) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection
