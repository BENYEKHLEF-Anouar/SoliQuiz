@props(['items' => []])

<nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 animate-in fade-in slide-in-from-left duration-700">
    @foreach($items as $label => $url)
        @if(!$loop->first)
            <span class="size-1 rounded-full bg-slate-300"></span>
        @endif
        
        @if($url)
            <a href="{{ $url }}" class="hover:text-primary-500 transition-colors">{{ $label }}</a>
        @else
            <span class="text-slate-600">{{ $label }}</span>
        @endif
    @endforeach
</nav>
