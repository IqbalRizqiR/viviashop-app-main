<div class="modal fade" id="modal-supplier" tabindex="-1" role="dialog" aria-labelledby="modal-supplier">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Excel</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <a href="{{ route('admin.products.exportTemplate') }}" class="btn btn-primary">Download Template Excel ( terdapat dropdown pada category name )</a>
                <form enctype="multipart/form-data" action="{{ route('admin.products.imports') }}" method="post">
                    @csrf
                    <input type="file" name="excelFile" class="form-control" id="">

                    <button class="mt-5 btn btn-primary" type="submit">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
