@extends('layouts.app')

@section('title', 'Add Alternative')

@section('content')
    <form method="post" action="{{ route('alternatives.store') }}">
        @csrf
        @include('alternatives.form', ['alternative' => new \App\Models\Alternative()])

        <div class="section-title">
            <div></div>
            <button class="button primary" type="submit">Save Alternative</button>
        </div>
    </form>
@endsection
