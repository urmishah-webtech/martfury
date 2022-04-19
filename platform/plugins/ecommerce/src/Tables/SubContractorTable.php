<?php

namespace Botble\Ecommerce\Tables;

use BaseHelper;
use Botble\Ecommerce\Enums\CustomerStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Table\Abstracts\TableAbstract;
use EcommerceHelper;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\EloquentDataTable;

class SubContractorTable extends TableAbstract
{
    /**
     * @var bool
     */
    protected $hasActions = true;
    protected $hasOperations = false;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * CustomerTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param CustomerInterface $customerRepository
     */

    public function __construct(DataTables $table, UrlGenerator $urlGenerator, CustomerInterface $customerRepository)
    {
        parent::__construct($table, $urlGenerator);
        $this->repository = $customerRepository;

        if (!Auth::user()->hasAnyPermission(['customers.edit', 'customers.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }

    }

    /**
     * {@inheritDoc}
     */

    public function ajax()
    {

        $data = $this->table->eloquent($this->query())
            ->editColumn('avatar', function ($item) {
                if ($this->request()->input('action') == 'excel' ||
                    $this->request()->input('action') == 'csv') {
                    return $item->avatar_url;
                }

                return Html::tag('img', '', ['src' => $item->avatar_url, 'alt' => clean($item->name), 'width' => 50]);
            })

            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('customers.edit')) {
                    return clean($item->name);
                }

                return Html::link(route('customers.edit', $item->id), clean($item->name));
            })
            ->editColumn('email', function ($item) {
                return clean($item->email);
            })
            ->editColumn('subcontractor_dis', function ($item) {
                return $item->subcontractor_dis;
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })

            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return clean($item->status->toHtml());
            });

        if (EcommerceHelper::isEnableEmailVerification()) {
            $data = $data
                ->addColumn('confirmed_at', function ($item) {
                    return $item->confirmed_at ? Html::tag('span', trans('core/base::base.yes'),
                        ['class' => 'text-success']) : trans('core/base::base.no');
                });
        }

//        $data = $data
//            ->addColumn('operations', function ($item) {
//                return $this->getOperations('customers.edit', 'customers.destroy', $item);
//            });
        //
        $data = $data
            ->addColumn('operations', function ($item) {
               // return $this->getOperations('customers.edit', 'customers.destroy', $item);
                return "<button type='button' data-id='$item' onclick='test($(this))' class='btn btn-warning btn-sm' id='getActualizaId'>
    <i class='fas fa-fw fa-check-circle'></i>
    Discount
</button>";
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()->select([
            'id',
            'name',
            'email',
            'avatar',
            'created_at',
            'status',
            'subcontractor_dis',
            'confirmed_at',
        ])->where(['ranking' => 1]);

        return $this->applyScopes($query);
//        return  $query;
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {

        $columns = [
            'id'         => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-start',
            ],
            'avatar'      => [
                'title' => trans('plugins/ecommerce::customer.avatar'),
                'class' => 'text-center',
            ],
            'name'       => [
                'title' => trans('core/base::forms.name'),
                'class' => 'text-start',
            ],
            'email'      => [
                'title' => trans('plugins/ecommerce::customer.name'),
                'class' => 'text-start',
            ],
            'subcontractor_dis'      => [
                'title' => 'Discount',
                'class' => 'text-start',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-start',
            ],
            'status'     => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
//       dd($columns);
        if (EcommerceHelper::isEnableEmailVerification()) {
            $columns += [
                'confirmed_at' => [
                    'title' => trans('plugins/ecommerce::customer.email_verified'),
                    'width' => '100px',
                ],
            ];
        }

        return $columns;
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        $buttons =  $this->addCreateButton(route('customers.create'), 'customers.create');
        $buttons['import'] = [
//            'link' => route('ecommerce.bulk-import.index'),
            'text' => '<button type=\'button\'  onclick=\'test()\' class=\'btn-primary\' id=\'getActualizaId\'>
    <i class=\'fas fa-fw fa-check-circle\'></i>
    Discount
</button>',
        ];
//        return "<button type='button'  onclick='test()' class='btn btn-warning btn-sm' id='getActualizaId'>
//    <i class='fas fa-fw fa-check-circle'></i>
//    Discount
//</button>";
        return $buttons;
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('customers.deletes'), 'customers.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {

        return [
            'name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'email'      => [
                'title'    => trans('core/base::tables.email'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => CustomerStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', CustomerStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function renderTable($data = [], $mergeData = [])
    {

        if ($this->query()->count() === 0 &&
            $this->request()->input('filter_table_id') !== $this->getOption('id') && !$this->request()->ajax())
        {
            return view('plugins/ecommerce::customers.intro');
        }

        return parent::renderTable($data, $mergeData);
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultButtons(): array
    {
        return [
            'export',
            'reload',
        ];
    }
}
