<?php

namespace Botble\AffiliatePro\Tables;

use Botble\AffiliatePro\Models\Affiliate;
use Botble\Base\Facades\Html;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class AffiliateTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Affiliate::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('affiliate-pro.create'))
            ->addActions([
                EditAction::make()->route('affiliate-pro.edit'),
                DeleteAction::make()->route('affiliate-pro.destroy'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('affiliate-pro.destroy'),
            ])
            ->addBulkChanges([
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->addColumns([
                IdColumn::make(),
                FormattedColumn::make('customer_id')
                    ->title(trans('plugins/affiliate-pro::affiliate.customer'))
                    ->searchable(false)
                    ->orderable(false)
                    ->getValueUsing(function (FormattedColumn $column) {
                        $customer = $column->getItem()->customer;
                        if (! $customer) {
                            return '&mdash;';
                        }

                        return Html::link(route('customers.edit', $customer->id), $customer->name);
                    }),
                Column::make('affiliate_code')
                    ->title(trans('plugins/affiliate-pro::affiliate.affiliate_code'))
                    ->searchable(),
                FormattedColumn::make('balance')
                    ->title(trans('plugins/affiliate-pro::affiliate.balance'))
                    ->searchable(false)
                    ->getValueUsing(function (FormattedColumn $column) {
                        return format_price($column->getItem()->balance);
                    }),
                FormattedColumn::make('total_commission')
                    ->title(trans('plugins/affiliate-pro::affiliate.total_commission'))
                    ->searchable(false)
                    ->getValueUsing(function (FormattedColumn $column) {
                        return format_price($column->getItem()->total_commission);
                    }),
                FormattedColumn::make('total_withdrawn')
                    ->title(trans('plugins/affiliate-pro::affiliate.total_withdrawn'))
                    ->searchable(false)
                    ->getValueUsing(function (FormattedColumn $column) {
                        return format_price($column->getItem()->total_withdrawn);
                    }),
                StatusColumn::make(),
                CreatedAtColumn::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'customer_id',
                        'affiliate_code',
                        'balance',
                        'total_commission',
                        'total_withdrawn',
                        'status',
                        'created_at',
                    ]);
            });
    }
}
