<div>

    @if ($template == 1)
        <div class="form-group mb-3">
            <label for="slug">Slug
                @if ($required)
                    <span class="text-danger" style="font-size: 20px;line-height: 1;">*</span>
                @endif
            </label>
            <input type="text" placeholder="Slug" name="slug" class="form-control" wire:model="slug"
                wire:change="isUnique()" {{ $required ? 'required' : '' }}>
            @error('slug')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    @else
        <div class="form-group row">
            <label class="col-md-3 col-form-label" for="slug">Slug
                @if ($required)
                    <span class="text-danger" style="font-size: 20px;line-height: 1;">*</span>
                @endif
            </label>
            <div class="col-md-9">
                <input type="text" placeholder="Slug" name="slug" class="form-control"
                    wire:model="slug" wire:change="isUnique()" {{ $required ? 'required' : '' }}>
                @error('slug')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    @endif

</div>
