<?php

namespace Botble\AffiliatePro\Http\Controllers;

use Botble\AffiliatePro\Models\Affiliate;
use Botble\AffiliatePro\Tables\PendingAffiliateTable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Breadcrumb;
use Illuminate\Http\Request;

class PendingAffiliateController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/affiliate-pro::affiliate.pending_requests'), route('affiliate-pro.pending.index'));
    }
    public function index(PendingAffiliateTable $table)
    {
        $this->pageTitle(trans('plugins/affiliate-pro::affiliate.pending_requests'));

        return $table->renderTable();
    }

    public function approve(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $affiliate = Affiliate::query()->findOrFail($id);

        if ($affiliate->status != BaseStatusEnum::PENDING) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/affiliate-pro::affiliate.invalid_request'));
        }

        $affiliate->status = BaseStatusEnum::PUBLISHED;
        $affiliate->save();

        event(new UpdatedContentEvent(AFFILIATE_PRO_MODULE_SCREEN_NAME, $request, $affiliate));

        return $response
            ->setPreviousUrl(route('affiliate-pro.pending.index'))
            ->setMessage(trans('plugins/affiliate-pro::affiliate.approve_success'));
    }

    public function reject(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $affiliate = Affiliate::query()->findOrFail($id);

        if ($affiliate->status != BaseStatusEnum::PENDING) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/affiliate-pro::affiliate.invalid_request'));
        }

        $affiliate->status = BaseStatusEnum::DRAFT;
        $affiliate->save();

        event(new UpdatedContentEvent(AFFILIATE_PRO_MODULE_SCREEN_NAME, $request, $affiliate));

        return $response
            ->setPreviousUrl(route('affiliate-pro.pending.index'))
            ->setMessage(trans('plugins/affiliate-pro::affiliate.reject_success'));
    }

    public function show(int|string $id)
    {
        $affiliate = Affiliate::query()->with(['customer', 'customer.addresses'])->findOrFail($id);

        if ($affiliate->status != BaseStatusEnum::PENDING) {
            return redirect()->route('affiliate-pro.pending.index')
                ->with('error', trans('plugins/affiliate-pro::affiliate.invalid_request'));
        }

        $this->pageTitle(trans('plugins/affiliate-pro::affiliate.view_request', ['id' => $id]));

        return view('plugins/affiliate-pro::pending.show', compact('affiliate'));
    }
}
