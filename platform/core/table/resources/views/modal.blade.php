@include('core/table::partials.modal-item', [
    'type' => 'danger',
    'name' => 'modal-confirm-delete',
    'title' => trans('core/base::tables.confirm_delete'),
    'content' => trans('core/base::tables.confirm_delete_msg'),
    'action_name' => trans('core/base::tables.delete'),
    'action_button_attributes' => [
        'class' => 'delete-crud-entry',
    ],
])

@include('core/table::partials.modal-item', [
    'type' => 'danger',
    'name' => 'delete-many-modal',
    'title' => trans('core/base::tables.confirm_delete'),
    'content' => trans('core/base::tables.confirm_delete_many_msg'),
    'action_name' => trans('core/base::tables.delete'),
    'action_button_attributes' => [
        'class' => 'delete-many-entry-button',
    ],
])

@include('core/table::partials.modal-item', [
    'type' => 'info',
    'name' => 'modal-bulk-change-items',
    'title' => trans('core/base::tables.bulk_changes'),
    'content' => '<div class="modal-bulk-change-content"></div>',
    'action_name' => trans('core/base::tables.submit'),
    'action_button_attributes' => [
        'class' => 'confirm-bulk-change-button',
        'data-load-url' => route('tables.bulk-change.data'),
    ],
])

<div class="modal fade" tabindex="-1" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <form action="{{route('subcontractor.discount')}}" method="Post">
            @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="til_img"></i><strong></strong></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">

                </button>
            </div>
            <div class="modal-body with-padding">
{{--                <label>ID</label>--}}
{{--                <input type="text" name="sub_id" id="sub_id" class="form-control" readonly>--}}
                <label>Discount</label>
               <input type="text" name="subcontractor_dis" id="discount" class="form-control">
            </div>

            <div class="modal-footer">
                <button type="button" class="float-start btn btn-warning" data-bs-dismiss="modal">{{ trans('core/table::table.cancel') }}</button>
                <button class="float-end btn btn-success">Save</button>
            </div>
        </div>
        </form>
    </div>
</div>
<!-- end Modal -->
