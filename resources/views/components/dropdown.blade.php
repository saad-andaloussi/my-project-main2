<div class="dropdown" x-data="{ open: false }" @click.outside="open = false">
    <div @click="open = !open">
        {{ $trigger }}
    </div>
    <div x-show="open" class="dropdown-content" style="display:none;">
        {{ $content }}
    </div>
</div>
