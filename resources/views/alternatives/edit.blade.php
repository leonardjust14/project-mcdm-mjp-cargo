@extends('layouts.app')

@section('title', 'Edit Alternative')

@section('content')
    <form method="post" action="{{ route('alternatives.update', $alternative) }}">
        @csrf
        @method('put')
        @include('alternatives.form')

        <div class="section-title">
            <div></div>
            <button class="button primary" type="submit">Update Alternative</button>
        </div>
    </form>
@endsection
