@if($parents)
	<div class="field">
		<label for="title">Parent</label>
		<select name="parent_id" class="ui search dropdown">
			<option value="">No Parent</option>
			@foreach($parents as $item)
				@php
					if ($item->parent() == $root) {
						$indent = 0;
					}
					else {
						//Find the parent in the array of levels, log it, and set the indent appropriately
						foreach ($levels as $id => $value) {
							if ($id == $item->parent()) {
								$indent = $value + 1;
								break;
							}
						}
					}

					$levels[$item->id] = $indent;

					//Reenable selection of items after its been disabled
					if ($disabledFlag && $indent <= $disabledDepth) {
						$disabledFlag = false;
					}

					//Prevent selection of the current folder or its children
					if ($folder->id == $item->id) {
						$disabledFlag = true;
						$disabledDepth = $indent;
					}
				@endphp

				<option value="{{ $item->id }}"
					@if(isset($parent) && $parent->id == $item->id) selected="selected"@endif
					@if($disabledFlag) disabled @endif>{!! str_repeat('— ', $indent) !!}{{ $item->title }}</option>
			@endforeach
		</select>
	</div>
@else
	@if($folder->id)
		@section('page.head.menu')
		    <div class="ui secondary contextual menu">
		    	<div class="item">
		            <a class="ui icon button" href="{{route('document.folder.edit.parent', $folder->id)}}">
		                <i class="arrow circle outline right icon"></i>{{ ___('Move Folder') }}
		            </a>
	            </div>
		    </div>
		@endsection
	@endif

	<input type="hidden" name="parent_id" value="{{$parent ? $parent->id : ''}}">
	<div class="field">
		<label>Parent</label>
		<input type="text" disabled value="{{$parent ? $parent->title : 'No Parent'}}">
	</div>
@endif

<div class="field{{ $errors->has('title') ? ' error' : '' }}">
	<label for="title">{{ ___('Title') }}</label>
	<input type="text" id="title" name="title" placeholder="Title" value="{{ old('title', $folder->title) }}">
</div>

@section('head')
{{-- <link rel="stylesheet" type="text/css" href="/css/trix.css"> --}}
{{-- <script type="text/javascript" src="/js/trix.js"></script> --}}
@endsection