<?php

return [
    [
        'name' => 'Affiliate',
        'flag' => 'affiliate.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'affiliate.create',
        'parent_flag' => 'affiliate.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'affiliate.edit',
        'parent_flag' => 'affiliate.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'affiliate.destroy',
        'parent_flag' => 'affiliate.index',
    ],
    [
        'name' => 'Commissions',
        'flag' => 'affiliate.commissions.index',
        'parent_flag' => 'affiliate.index',
    ],
    [
        'name' => 'Withdrawals',
        'flag' => 'affiliate.withdrawals.index',
        'parent_flag' => 'affiliate.index',
    ],
    [
        'name' => 'Reports',
        'flag' => 'affiliate.reports',
        'parent_flag' => 'affiliate.index',
    ],
    [
        'name' => 'Coupons',
        'flag' => 'affiliate.coupons.index',
        'parent_flag' => 'affiliate.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'affiliate.coupons.create',
        'parent_flag' => 'affiliate.coupons.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'affiliate.coupons.destroy',
        'parent_flag' => 'affiliate.coupons.index',
    ],
    [
        'name' => 'Short Links',
        'flag' => 'affiliate.short-links.index',
        'parent_flag' => 'affiliate.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'affiliate.short-links.create',
        'parent_flag' => 'affiliate.short-links.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'affiliate.short-links.edit',
        'parent_flag' => 'affiliate.short-links.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'affiliate.short-links.destroy',
        'parent_flag' => 'affiliate.short-links.index',
    ],
    [
        'name' => 'Settings',
        'flag' => 'affiliate.settings',
        'parent_flag' => 'affiliate.index',
    ],
];
