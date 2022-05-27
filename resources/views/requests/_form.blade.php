<x-hito::Form.Select title="Type" name="type" :required="true" value="{{ $request->type_id }}" placeholder="Select type"
    :items="$types" />
<x-hito::Form.Input title="Description" name="description" type="textarea" value="{{ $request->description }}" />
<x-hito::Form.DatePicker title="Start date" name="start_date" :required="true" value="{{ old('start_date') }}" />
<x-hito::Form.DatePicker title="End date" name="end_date" value="{{ old('end_date') }}" />

