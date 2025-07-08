@props([
    'column',
    'record',
    'state',
    'tagMap',
    'renderedHtml',
])

<div {!! $attributes->class('prose') !!}>
    {!! $column->renderHtml() !!}
</div>
