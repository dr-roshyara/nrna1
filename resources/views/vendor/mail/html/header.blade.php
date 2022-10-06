<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'NRNA')
{{-- <img src="https://nrna.org/wp-content/uploads/2020/08/logo-2.png" class="logo" alt="NRNA  Logo"> --}}
<!-- <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo"> -->
<img src=storage_path("images/logo-2.png") class="logo" alt="Laravel Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
