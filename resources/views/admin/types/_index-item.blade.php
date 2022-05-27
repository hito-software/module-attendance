<div>
    @include('hito.attendance::_type-pill', ['color' => $type->color, 'symbol' => $type->symbol])
</div>
<div>
    <div class="hito-admin__resource__index__item-title">{{ $type->name }}</div>
    <div class="hito-admin__resource__index__item-pills">
        @if(!empty($type->description))
        <span class="hito-admin__resource__index__pill"
              title="Description" data-tooltip>
            <span>{{ $type->description }}</span>
        </span>
        @endif
    </div>
</div>
