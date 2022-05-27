<x-hito::Form.Input title="Name" name="name" :required="true" value="{{ $type->name }}" />
<x-hito::Form.Input title="Symbol" name="symbol" :required="true" value="{{ $type->symbol }}" />
<x-hito::Form.Input title="Color" type="color" name="color" :required="true" value="{{ $type->color }}" />
<x-hito::Form.Input title="Description" name="description" :required="true" type="textarea" value="{{ $type->description }}" />
<x-hito::Form.Select title="Is unavailable (unable to work)" name="is_unavailable" :required="true" value="{{ $type->is_unavailable }}" :items="[ ['value' => '0', 'label' => 'No'],
        ['value' => '1', 'label' => 'Yes'] ]" />
<x-hito::Form.Select title="Flow" name="flow" value="{{ $type->attendance_flow_id }}" placeholder="Select flow"
    :items="$flows" />
