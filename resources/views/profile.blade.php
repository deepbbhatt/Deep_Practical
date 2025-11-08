@extends('layouts.app')

@section('content')
<div class="card mx-auto" style="max-width: 600px;">
  <div class="card-header bg-primary text-white">
    <h5 class="mb-0">User Profile</h5>
  </div>
  <div class="card-body">
    <p><strong>First Name:</strong> {{ $user->first_name }}</p>
    <p><strong>Last Name:</strong> {{ $user->last_name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Back</a>
  </div>
</div>
@endsection
