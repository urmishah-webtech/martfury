<div class="onoffswitch">
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" name="{{ $name }}" class="onoffswitch-checkbox" id="{{ $name }}" value="1" @if ($value) checked @endif {!! Html::attributes($attributes) !!}>
    <label class="onoffswitch-label" for="{{ $name }}">
        <span class="onoffswitch-inner"></span>
        <span class="onoffswitch-switch"></span>
    </label>
</div>



{{--<div class="onoffswitch" style="float: inherit;">--}}
{{--    <input type="hidden" name="sub_contractor" value="0">--}}
{{--    <input type="checkbox" name="sub_contractor" class="onoffswitch-checkbox" id="sub_contractor" value="1" @if ($value) checked @endif {!! Html::attributes($attributes) !!}>--}}
{{--    <label class="onoffswitch-label" for="sub_contractor" >--}}
{{--        <span class="onoffswitch-inner"></span>--}}
{{--        <span class="onoffswitch-switch"></span>--}}
{{--    </label>--}}
{{--</div>--}}
