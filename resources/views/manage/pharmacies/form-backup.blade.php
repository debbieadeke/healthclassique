
<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name" class="col-md-2 control-label">Name</label>
    <div class="col-md-10">
        <input class="form-control" name="name" type="text" id="name" value="{{ old('name', optional($location)->name) }}" minlength="1" maxlength="255" required="true" placeholder="Enter name here...">
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('longitude') ? 'has-error' : '' }}">
    <label for="longitude" class="col-md-2 control-label">Longitude</label>
    <div class="col-md-10">
        <input class="form-control" name="longitude" type="number" id="longitude" value="{{ old('longitude', optional($location)->longitude) }}" min="-999" max="999" placeholder="Enter longitude here..." step="any">
        {!! $errors->first('longitude', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('latitude') ? 'has-error' : '' }}">
    <label for="latitude" class="col-md-2 control-label">Latitude</label>
    <div class="col-md-10">
        <input class="form-control" name="latitude" type="number" id="latitude" value="{{ old('latitude', optional($location)->latitude) }}" min="-999" max="999" placeholder="Enter latitude here..." step="any">
        {!! $errors->first('latitude', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('territory_id') ? 'has-error' : '' }}">
    <label for="territory_id" class="col-md-2 control-label">Territory</label>
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

<div class="form-group {{ $errors->has('created_by') ? 'has-error' : '' }}">
    <label for="created_by" class="col-md-2 control-label">Created By</label>
    <div class="col-md-10">
        <select class="form-control" id="created_by" name="created_by">
        	    <option value="" style="display: none;" {{ old('created_by', optional($location)->created_by ?: '') == '' ? 'selected' : '' }} disabled selected>Select created by</option>
        	@foreach ($creators as $key => $creator)
			    <option value="{{ $key }}" {{ old('created_by', optional($location)->created_by) == $key ? 'selected' : '' }}>
			    	{{ $creator }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('created_by', '<p class="help-block">:message</p>') !!}
    </div>
</div>

