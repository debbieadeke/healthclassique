
<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name" class="col-md-2 control-label"><b>Name</b></label>
    <div class="col-md-10">
        <input class="form-control" name="name" type="text" id="name" value="{{ old('name', optional($location)->name) }}" minlength="1" maxlength="255" required="true" placeholder="Enter name here...">
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('territory_id') ? 'has-error' : '' }}">
    <label for="territory_id" class="col-md-2 control-label"><b>Territory</b></label>
    <div class="col-md-10">
        <select class="form-control" id="territory_id" name="territory_id" required="true">
        	    <option value="" style="display: none;" {{ old('territory_id', optional($location)->territory_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select territory</option>
        	@foreach ($Territories as $key => $Territory)
			    <option value="{{ $key }}" {{ old('territory_id', optional($location)->territory_id) == $key ? 'selected' : '' }}>
			    	{{ $Territory }}
			    </option>
			@endforeach
        </select>

        {!! $errors->first('territory_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

