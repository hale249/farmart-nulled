<?php

namespace Botble\AffiliatePro\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static WithdrawalStatusEnum PENDING()
 * @method static WithdrawalStatusEnum PROCESSING()
 * @method static WithdrawalStatusEnum APPROVED()
 * @method static WithdrawalStatusEnum REJECTED()
 * @method static WithdrawalStatusEnum CANCELED()
 */
class WithdrawalStatusEnum extends Enum
{
    public const PENDING = 'pending';

    public const PROCESSING = 'processing';

    public const APPROVED = 'approved';

    public const REJECTED = 'rejected';

    public const CANCELED = 'canceled';

    public static $langPath = 'plugins/affiliate-pro::withdrawal.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::PENDING => 'warning',
            self::PROCESSING => 'info',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            self::CANCELED => 'secondary',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
