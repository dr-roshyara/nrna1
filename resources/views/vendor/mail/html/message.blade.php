@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<div class="flex ">
<!-- <img src= "{{ config('app.logo')  }}" class="w-16 h-16"/>  -->
@slot('nrna') 
@endslot
<!-- <img src="{{asset('images/logo.jpg')}}"> -->
<span> 
    {{ config('app.name') }} 
</span> 
</div>
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
