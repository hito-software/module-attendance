<x-hito::Card>
    <div class="hito-admin__resource__wrapper">
        <x-hito::Form.Input title="Name" name="name" value="{{ $flow->name }}" disabled />
        <x-hito::Form.Input title="Description" name="description" type="textarea" value="{{ $flow->description }}"
                            disabled />
        <div>
            <gsd-attendance-flow flow-id="{{ $flow->id }}" disabled />
        </div>
    </div>
</x-hito::Card>

@include('hito.attendance::admin._admin')
