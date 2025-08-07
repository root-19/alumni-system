@props(['name'])

@if ($name === 'layout-dashboard')
    <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z"/>
    </svg>

@elseif ($name === 'calendar-event')
    <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>

@elseif ($name === 'chart-bar')
    <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 3v18h18M9 17v-6m4 6V9m4 6V5"/>
    </svg>

@elseif ($name === 'user-shield')
    <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 12c2.21 0 4-1.79 4-4S14.21 4 12 4 8 5.79 8 8s1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
    </svg>

@else
    <span class="text-red-500">Unknown icon: {{ $name }}</span>
@endif
