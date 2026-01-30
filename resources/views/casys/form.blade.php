<form action="{{ $paymentUrl }}" method="post">
    @foreach ($payload as $name => $value)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
    @endforeach

    {{ $slot ?? '' }}
</form>
