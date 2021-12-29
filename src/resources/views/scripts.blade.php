@foreach ($scripts as $link)
    <script src="{{ $link }}"></script>
@endforeach

{!! $this->codes !!}