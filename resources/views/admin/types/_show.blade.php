<x-hito::Card>
    <div class="hito-admin__resource__wrapper">
        <x-hito::Form.Input title="Name" name="name" value="{{ $type->name }}" disabled />
        <x-hito::form.group>
            <x-hito::form.label>Symbol</x-hito::form.label>
            @include('hito.attendance::_type-pill', ['color' => $type->color, 'symbol' => $type->symbol])
        </x-hito::form.group>
        <x-hito::Form.Input title="Description" name="description" type="textarea" value="{{ $type->description }}" disabled />
        <x-hito::Form.Select title="Is unavailable (unable to work)" name="is_unavailable"
                             value="{{ $type->is_unavailable }}"
                             :items="[ ['value' => '0', 'label' => 'No'],
        ['value' => '1', 'label' => 'Yes'] ]" disabled />
        <x-hito::Form.Input title="Flow" name="flow" value="{{ $type->flow?->name }}" disabled />
    </div>
</x-hito::Card>
