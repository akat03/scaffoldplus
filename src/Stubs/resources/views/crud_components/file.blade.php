
{{--
<h1>file.blade</h1>
$column_name:{{$column_name}}<br>
$column_value:{{$column_value}}<br>
$class:{{$class}}<br>
<h1>file.blade</h1>
 --}}

<style type="text/css">
.attach_file_name {
	font-size: 14px;
	font-weight: bold;
}	
</style>

@if ( $column_value )
	@php
		$json = json_decode($column_value,true);
		// dump($json);
	@endphp
	<span class="attach_file_name">{{$json['file_name']}}</span>
	<span class="attach_file_size">( {{$json['original_name']}}
		@if(@$json['size_name'])
			/ {{@$json['size_name']}}ytes
		@elseif(@$json['size'])
			/ {{ number_format(@$json['size'])}} bytes
		@endif
	)</span><br>
	<label><input name="{{$column_name}}__edit_flag" type="radio" value="notedit" checked=""> @lang('excrud.component_file__attach_file_use')</label>
	<label class="text-danger ml15 mr15"><input name="{{$column_name}}__edit_flag" type="radio" value="delete"> @lang('excrud.component_file__attach_file_delete')</label>
@endif

<input type="file" name="{{$column_name}}__attach">

