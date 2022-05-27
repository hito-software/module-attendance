<div class="text-center flex items-center space-x-1">
    <div class="hito-attendance__pill" style="background-color: {{ $color }}">{{ $symbol }}</div>
    @if(!empty($name))
        <div>{{ $name }}</div>
    @endif
</div>
