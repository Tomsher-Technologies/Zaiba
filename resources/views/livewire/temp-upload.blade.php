<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">Upload Temp Images</h5>
            </div>

            <form class="form-horizontal" wire:submit.prevent="updatedFiles()" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{ translate('Images') }}</label>
                        <div class="col-sm-9">
                            <input type="file" wire:model="files" multiple placeholder="{{ translate('Images') }}"
                                id="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
