<?php

return [
    'name' => 'Withdrawals',
    'view' => 'View Withdrawal #:id',
    'affiliate' => 'Affiliate',
    'amount' => 'Amount',
    'status' => 'Status',
    'payment_method' => 'Payment Method',
    'payment_details' => 'Payment Details',
    'notes' => 'Notes',
    'created_at' => 'Created At',
    'approve' => 'Approve',
    'reject' => 'Reject',
    'approve_success' => 'Withdrawal approved successfully',
    'reject_success' => 'Withdrawal rejected successfully',
    'approve_withdrawal' => 'Approve Withdrawal',
    'reject_withdrawal' => 'Reject Withdrawal',
    'approve_withdrawal_confirmation' => 'Are you sure you want to approve withdrawal #:id? This action cannot be undone.',
    'reject_withdrawal_confirmation' => 'Are you sure you want to reject withdrawal #:id? This action cannot be undone.',
    'statuses' => [
        'pending' => 'Pending',
        'processing' => 'Processing',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'canceled' => 'Canceled',
    ],

    // Customer-facing translations
    'request' => 'Withdrawal Request',
    'history' => 'Withdrawal History',
    'no_withdrawals' => 'No withdrawals found.',
    'withdrawal_id' => 'Withdrawal ID',
    'date' => 'Date',
    'amount_required' => 'The withdrawal amount is required.',
    'amount_numeric' => 'The withdrawal amount must be a number.',
    'amount_min' => 'The withdrawal amount must be at least 0.',
    'payment_method_required' => 'The payment method is required.',
    'payment_details_required' => 'The payment details are required.',
    'account_not_approved' => 'Your affiliate account is not approved yet.',
    'minimum_amount' => 'The minimum withdrawal amount is :amount.',
    'insufficient_balance' => 'You do not have enough balance for this withdrawal.',
    'request_submitted' => 'Your withdrawal request has been submitted successfully.',
    'submit_request' => 'Submit Withdrawal Request',
    'available_balance' => 'Available Balance',
    'enter_amount' => 'Enter Amount',
    'select_payment_method' => 'Select Payment Method',
    'payment_details_placeholder' => 'Enter your payment details (e.g., PayPal email, bank account details)',

    // Additional translations for PayoutPaymentMethodsEnum
    'bank_transfer' => 'Bank Transfer',
    'paypal' => 'PayPal',
    'stripe' => 'Stripe',
    'bank_information' => 'Bank information',
    'paypal_id' => 'PayPal ID',

    // Payout payment methods
    'payout_payment_methods' => [
        'bank_transfer' => 'Bank Transfer',
        'paypal' => 'PayPal',
        'stripe' => 'Stripe',
    ],
];
