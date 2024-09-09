
<!-- crud_components/calendar.blade.php -->

{{--
<h1>calendar</h1>
$column_name:{{$column_name}}<br>
$column_value:{{$column_value}}<br>
$class:{{$class}}<br>
 --}}

{{--  {{ Form::text($column_name, $column_value, ['class' => 'form-control calendar_ui' , 'style' => 'width: 120px;' , 'autocomplete' => 'off' ]) }}  --}}
{{ html()->text($column_name)->class(['form-control','calendar_ui'])->style('width: 120px;')->value($column_value) }}

{{-- <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"/>
<script src="https://g.0-oo.net/gcalendar-holidays.js"></script>
<style>
.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active, a.ui-button:active, .ui-button:active, .ui-button.ui-state-active:hover {
    color: white !important;
    background-color: rgb(0, 127, 255) !important;
}
.ui-datepicker {
    width: 300px;
}
</style>
<script>
$(function() {
    var months = ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"];
    $(".calendar_ui").datepicker({
        prevText: '前月',
        nextText: '次月',
        // changeYear: true,
        // changeMonth: true,
        yearSuffix: "年",
        showMonthAfterYear: true,
        monthNames: months,
        monthNamesShort: months,
        firstDay: 1,
        dayNamesMin: ["日", "月", "火", "水", "木", "金", "土"],
        showButtonPanel: true,
        currentText: '今日',
        closeText: '閉じる',
        dateFormat: "yy-mm-dd"
    });
});
</script>


{{-- z-index 1000 --}}
<style type="text/css">
    .ui-datepicker { z-index: 1000 !important; }
</style>

<!-- / crud_components/calendar.blade.php -->

