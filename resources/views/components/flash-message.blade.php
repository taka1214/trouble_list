@props(['status' => 'info'])

@php
if(session('status') === 'info'){$bgColor = 'bg-blue-300';}
if(session('status') === 'alert'){$bgColor = 'bg-alert';}
@endphp

@if(session('message'))
  <div 
      class="{{ $bgColor }} lg:w-1/2 mx-auto p-2 my-4 text-white sm:w-full" 
      x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
  >
    {!! nl2br(e(session('message'))) !!}
  </div>
@endif