<?php

namespace Botble\AffiliatePro\Http\Controllers;

use Botble\AffiliatePro\Forms\AffiliateForm;
use Botble\AffiliatePro\Http\Requests\AffiliateRequest;
use Botble\AffiliatePro\Models\Affiliate;
use Botble\AffiliatePro\Tables\AffiliateTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Breadcrumb;
use Exception;
use Illuminate\Http\Request;

class AffiliateController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/affiliate-pro::affiliate.all'), route('affiliate-pro.index'));
    }
    public function index(AffiliateTable $table)
    {
        $this->pageTitle(trans('plugins/affiliate-pro::affiliate.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        $this->pageTitle(trans('plugins/affiliate-pro::affiliate.create'));

        return $formBuilder->create(AffiliateForm::class)->renderForm();
    }

    public function store(AffiliateRequest $request, BaseHttpResponse $response)
    {
        $affiliate = Affiliate::query()->create($request->input());

        event(new CreatedContentEvent(AFFILIATE_PRO_MODULE_SCREEN_NAME, $request, $affiliate));

        return $response
            ->setPreviousUrl(route('affiliate-pro.index'))
            ->setNextUrl(route('affiliate-pro.edit', $affiliate))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(Affiliate $affiliate, FormBuilder $formBuilder)
    {
        $this->pageTitle(trans('plugins/affiliate-pro::affiliate.edit', ['name' => $affiliate->name]));

        return $formBuilder->create(AffiliateForm::class, ['model' => $affiliate])->renderForm();
    }

    public function update(Affiliate $affiliate, AffiliateRequest $request, BaseHttpResponse $response)
    {
        $affiliate->fill($request->input());
        $affiliate->save();

        event(new UpdatedContentEvent(AFFILIATE_PRO_MODULE_SCREEN_NAME, $request, $affiliate));

        return $response
            ->setPreviousUrl(route('affiliate-pro.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Affiliate $affiliate, Request $request, BaseHttpResponse $response)
    {
        try {
            $affiliate->delete();

            event(new DeletedContentEvent(AFFILIATE_PRO_MODULE_SCREEN_NAME, $request, $affiliate));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
}
