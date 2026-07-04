<?php

namespace App\Support;

final class ModuleAccess
{
    /** Route-name prefix => label (employee / user permission matrix). */
    public const DEFINITIONS = [
        'inventory' => 'Inventory',
        'purchase' => 'Purchase',
        'pos' => 'POS Restaurant',
        'restaurant-pos' => 'Restaurant POS',
        'order-taker' => 'Order Taker',
        'kitchen' => 'Kitchen',
        'order-status' => 'Order Status',
        'employees' => 'Employees',
        'manufacturing' => 'Manufacturing',
        'maintenance' => 'Maintenance',
        'custom-forms' => 'Custom Forms',
        'expenses' => 'Expenses',
        'reports' => 'Reports',
        'analytics' => 'Analytics',
        'contacts' => 'Contacts',
        'credit-book' => 'Credit Book',
        'calendar' => 'Calendar',
    ];

    /**
     * @return list<string>
     */
    public static function moduleKeys(): array
    {
        return array_keys(self::DEFINITIONS);
    }

    /**
     * @return array<string, array{view: bool, create: bool, edit: bool, delete: bool, all: bool}>
     */
    public static function normalize(array $permissions): array
    {
        $out = [];
        foreach (self::DEFINITIONS as $m => $_label) {
            $allRaw = data_get($permissions, $m.'.all');
            $allOn = $allRaw === true || $allRaw === 1 || $allRaw === '1' || $allRaw === 'on';

            $out[$m] = [
                'view' => false,
                'create' => false,
                'edit' => false,
                'delete' => false,
                'all' => false,
            ];

            foreach (['view', 'create', 'edit', 'delete'] as $a) {
                $raw = data_get($permissions, $m.'.'.$a);
                $on = $allOn || $raw === true || $raw === 1 || $raw === '1' || $raw === 'on';
                $out[$m][$a] = $on;
            }

            if ($out[$m]['create'] || $out[$m]['edit'] || $out[$m]['delete']) {
                $out[$m]['view'] = true;
            }

            $out[$m]['all'] = $allOn
                || ($out[$m]['view'] && $out[$m]['create'] && $out[$m]['edit'] && $out[$m]['delete']);
        }

        return $out;
    }
}
