<?php

namespace Botble\AffiliatePro\Forms;

use Botble\AffiliatePro\Http\Requests\AffiliateRequest;
use Botble\AffiliatePro\Models\Affiliate;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Models\Customer;
use Illuminate\Support\Str;

class AffiliateForm extends FormAbstract
{
    public function buildForm(): void
    {
        $customers = Customer::query()
            ->whereNotIn('id', function ($query) {
                $query->select('customer_id')
                    ->from('affiliates')
                    ->where('id', '!=', $this->getModel() ? $this->getModel()->id : 0);
            })
            ->pluck('name', 'id')
            ->toArray();

        $this
            ->model(Affiliate::class)
            ->setValidatorClass(AffiliateRequest::class)
            ->withCustomFields()
            ->add('customer_id', 'customSelect', [
                'label' => trans('plugins/affiliate-pro::affiliate.customer'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-search-full',
                ],
                'choices' => $customers,
            ])
            ->add('affiliate_code', 'text', [
                'label' => trans('plugins/affiliate-pro::affiliate.affiliate_code'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('plugins/affiliate-pro::affiliate.affiliate_code'),
                    'data-counter' => 100,
                ],
                'default_value' => $this->getModel()->id ? $this->getModel()->affiliate_code : Str::random(10),
            ])
            ->add('balance', 'number', [
                'label' => trans('plugins/affiliate-pro::affiliate.balance'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('plugins/affiliate-pro::affiliate.balance'),
                    'step' => 0.01,
                ],
                'default_value' => 0,
            ])
            ->add('total_commission', 'number', [
                'label' => trans('plugins/affiliate-pro::affiliate.total_commission'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('plugins/affiliate-pro::affiliate.total_commission'),
                    'step' => 0.01,
                ],
                'default_value' => 0,
            ])
            ->add('total_withdrawn', 'number', [
                'label' => trans('plugins/affiliate-pro::affiliate.total_withdrawn'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('plugins/affiliate-pro::affiliate.total_withdrawn'),
                    'step' => 0.01,
                ],
                'default_value' => 0,
            ])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
