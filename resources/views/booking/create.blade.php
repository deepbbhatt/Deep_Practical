@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Create Booking</h3>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
        <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
            @csrf

            <!-- Customer Name -->
            <div class="mb-3">
                <label>Customer Name</label>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="form-control @error('customer_name') is-invalid @enderror">
                @error('customer_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Customer Email -->
            <div class="mb-3">
                <label>Customer Email</label>
                <input type="email" name="customer_email" value="{{ old('customer_email') }}" class="form-control @error('customer_email') is-invalid @enderror">
                @error('customer_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Booking Date -->
            <div class="mb-3">
                <label>Booking Date</label>
                <input type="date" name="booking_date" value="{{ old('booking_date') }}" class="form-control @error('booking_date') is-invalid @enderror">
                @error('booking_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Booking Type -->
            <div class="mb-3">
                <label>Booking Type</label>
                <select name="booking_type" id="bookingType" class="form-control @error('booking_type') is-invalid @enderror">
                    <option value="">-- Select --</option>
                    <option value="full_day" {{ old('booking_type') == 'full_day' ? 'selected' : '' }}>Full Day</option>
                    <option value="half_day" {{ old('booking_type') == 'half_day' ? 'selected' : '' }}>Half Day</option>
                    <option value="custom" {{ old('booking_type') == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
                @error('booking_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Booking Slot -->
            <div class="mb-3" id="slotDiv" style="display:none;">
                <label>Booking Slot</label>
                <select name="booking_slot" class="form-control @error('booking_slot') is-invalid @enderror">
                    <option value="">-- Select Slot --</option>
                    <option value="first_half" {{ old('booking_slot') == 'first_half' ? 'selected' : '' }}>First Half</option>
                    <option value="second_half" {{ old('booking_slot') == 'second_half' ? 'selected' : '' }}>Second Half</option>
                </select>
                @error('booking_slot')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Custom Time -->
            <div class="row" id="timeDiv" style="display:none;">
                <div class="col-md-6 mb-3">
                    <label>From Time</label>
                    <input type="time" name="booking_from" value="{{ old('booking_from') }}" class="form-control @error('booking_from') is-invalid @enderror">
                    @error('booking_from')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>To Time</label>
                    <input type="time" name="booking_to" value="{{ old('booking_to') }}" class="form-control @error('booking_to') is-invalid @enderror">
                    @error('booking_to')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Book</button>
        </form>

</div>

<script>
function toggleFields() {
    let bookingType = document.getElementById('bookingType').value;
    document.getElementById('slotDiv').style.display = bookingType === 'half_day' ? 'block' : 'none';
    document.getElementById('timeDiv').style.display = bookingType === 'custom' ? 'flex' : 'none';
}

document.getElementById('bookingType').addEventListener('change', toggleFields);

// Keep visibility after validation error
window.addEventListener('load', toggleFields);
</script>

@endsection
