
<!-- crud_components/datetimepicker.blade.php -->
{{-- version 0.2 --}}


{{ Form::text($column_name, $column_value, ['class' => 'form-control' , 'style' => 'width: 180px;' , 'autocomplete' => 'off' ]) }}

<script src="{{ url('/assets/crud_components/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
<link href="{{ url('/assets/crud_components/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">

<script>
$(function(){
    jQuery.datetimepicker.setLocale('ja');
    $("[name='{{ $column_name }}']").datetimepicker({
		format:'Y-m-d H:i:00',
    });
});
</script>

<!-- / crud_components/datetimepicker.blade.php -->

