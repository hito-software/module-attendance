<div>
    <div class="hito-admin__resource__index__item-title">{{ $flow->name }}</div>
    <div class="hito-admin__resource__index__item-pills">
        @if(!empty($flow->description))
        <span class="hito-admin__resource__index__pill"
              title="Description" data-tooltip>
            <span>{{ $flow->description }}</span>
        </span>
        @endif
    </div>
</div>
