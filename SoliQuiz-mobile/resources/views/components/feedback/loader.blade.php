@props(['message' => null])

<div class="flex flex-col items-center justify-center p-8 text-center animate-in fade-in duration-500">
    <div class="relative flex items-center justify-center">
        <!-- outer spinning ring -->
        <div class="size-20 border-[3px] border-slate-100 border-t-primary-500 rounded-full animate-spin"></div>
        
        <!-- middle pulsing ring -->
        <div class="absolute size-14 border-[2px] border-primary-500/10 rounded-full animate-pulse"></div>
        
        <!-- internal pulsing core -->
        <div class="absolute size-5 bg-primary-500 rounded-full shadow-[0_0_20px_rgba(59,130,246,0.3)] animate-pulse"></div>
    </div>
    
    @if($message)
        <p class="mt-8 text-[11px] font-black uppercase tracking-[0.3em] text-slate-400 italic">
            {{ $message }}
        </p>
    @endif
</div>
