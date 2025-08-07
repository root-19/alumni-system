@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center text-black">
    <span class="text-4xl font-bold">{{ $title }}</span>
    <span class="text-lg text-gray-600">{{ $description }}</span>
</div>
