@component('mail::message')
# Finance Infomormation {{$type}} Sheet.
<p style="font-weight: bold;">  Sent By: {{$user['name']}} </p>
<div>
     <?php
         $keys =array_keys($finance);
        //  $values=
         echo '<ol>';
         for($i=0; $i<sizeof($keys); $i++){
             echo '<li style="tab-size:4 ">';
              echo '<span style="font-weight:bold; padding-right:2px; margin-right:4px;">';
              echo  $keys[$i]. ':  </span>';
              echo '<span> '. $finance[$keys[$i]]. '</span>';
              echo   '</li>';
        }
        echo '<ol>';
     ?>

</div>


{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
