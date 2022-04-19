
@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        <div {!! $options['wrapperAttrs'] !!}>
    @endif
@endif

            @if ($showLabel && $options['label'] !== false && $options['label_show'])
                {!! Form::customLabel($name, $options['label'], $options['label_attr']) !!}
            @endif

            @if ($showField)
                {!! Form::onOff($name, $options['value'], $options['attr']) !!}
                @include('core/base::forms.partials.help-block')
            @endif
{{--            <div style="display: flex;--}}
{{--    justify-content: flex-start;--}}
{{--    position: absolute;--}}
{{--    margin-left: 3.5rem;--}}
{{--    margin-top: -19px;">--}}
{{--                @if($showLabel && $options['label'] !== false && $options['label_show'])--}}
{{--                    {!! Form::customLabel('Sub Contractor', 'Sub Contractor ?', $options['label_attr']) !!}--}}
{{--                @endif--}}
{{--            </div>--}}

@include('core/base::forms.partials.errors')

@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        </div>
    @endif
@endif
