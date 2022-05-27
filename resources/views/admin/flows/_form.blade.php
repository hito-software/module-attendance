<x-hito::Form.Input title="Name" name="name" :required="true" value="{{ $flow->name }}"/>
<x-hito::Form.Input title="Description" name="description" type="textarea" value="{{ $flow->description }}"/>
<div>
    <attendance-approval-flow flow-id="{{ $flow->id }}" />
</div>
