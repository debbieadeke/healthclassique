<?php

return [
    'roles' => [
        'super_admin' => 'Super Admin',
        'manager' => 'Manager',
        'user' => 'User',
    ],
    'permissions' => [
        'user' => [
            'create_user',
            'edit_user',
            'delete_user',
            'view_users',
            'import_user',
            'export_user',
        ],
        'sales_call' => [
            'create_calls',
            'edit_calls',
            'delete_calls',
            'view_calls',
        ],
		'sales_plan' => [
            'create_plans',
            'edit_plans',
            'delete_plans',
            'view_plans',
        ],
        'reports' => [
            'view_reports',
        ],
        'setup_items' => [
            'list',
            'edit',
            'add',
            'delete',
            'view'
        ],
        'production' => [
            'manage_inputs',
            'receive_batches',
        ],

    ],
    'expected_daily_call_rate' => 15,
    'enable_location_check' => 'On',
    'draft_production_orders' => 'Disabled',
    'sales_upload_module_toggle' => env('SALES_UPLOAD_MODULE', 'Off'), // Default to 'Off' if not set in .env
	'ft_duplicate_call_validator_toggle' => env('FEATURE_DUPLICATE_CALL_VALIDATOR', 'Off'),

];
