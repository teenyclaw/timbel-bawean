<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\DiningTable;
use App\Models\LoyaltySetting;
use App\Models\Member;
use App\Models\MenuItem;
use App\Models\ModifierGroup;
use App\Models\ModifierOption;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $outlet = Outlet::firstOrCreate(
            ['slug' => 'nasi-timbel-bawean'],
            [
                'name' => 'Nasi Timbel Bawean',
                'phone' => '0224234567',
                'address' => 'Jl. Bawean No. 3, Merdeka, Sumur Bandung, Bandung 40113',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@qrpos.test'],
            [
                'name' => 'Admin NTB',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'kasir@qrpos.test'],
            [
                'name' => 'Kasir NTB',
                'password' => Hash::make('password'),
                'role' => 'cashier',
            ]
        );

        $kasir = User::where('email', 'kasir@qrpos.test')->first();
        $kasir?->outlets()->syncWithoutDetaching([$outlet->id]);

        LoyaltySetting::firstOrCreate(
            ['outlet_id' => $outlet->id],
            LoyaltySetting::defaultsFor($outlet->id)
        );

        Member::firstOrCreate(
            ['outlet_id' => $outlet->id, 'phone' => '081234567890'],
            ['name' => 'Pelanggan Setia', 'points' => 250]
        );

        $paket = Category::firstOrCreate(
            ['outlet_id' => $outlet->id, 'name' => 'Paket Nasi Timbel'],
            ['sort_order' => 1]
        );
        $nasi = Category::firstOrCreate(
            ['outlet_id' => $outlet->id, 'name' => 'Nasi'],
            ['sort_order' => 2]
        );
        $lauk = Category::firstOrCreate(
            ['outlet_id' => $outlet->id, 'name' => 'Lauk & Gorengan'],
            ['sort_order' => 3]
        );
        $pepes = Category::firstOrCreate(
            ['outlet_id' => $outlet->id, 'name' => 'Pepes & Tumis'],
            ['sort_order' => 4]
        );
        $minuman = Category::firstOrCreate(
            ['outlet_id' => $outlet->id, 'name' => 'Minuman'],
            ['sort_order' => 5]
        );

        foreach (['Meja 1', 'Meja 2', 'Meja 3', 'Meja 4', 'Meja 5', 'Meja 6', 'Meja 7', 'Meja 8'] as $i => $tableName) {
            DiningTable::firstOrCreate(
                ['outlet_id' => $outlet->id, 'name' => $tableName],
                ['sort_order' => $i + 1, 'is_active' => true]
            );
        }

        $menus = [
            ['category_id' => $paket->id, 'name' => 'Paket 1', 'description' => 'Nasi timbel + ayam goreng + sambal + lalab', 'price' => 25000],
            ['category_id' => $paket->id, 'name' => 'Paket 2', 'description' => 'Nasi timbel + ayam goreng + perkedel kentang + sambal + lalab', 'price' => 26000],
            ['category_id' => $paket->id, 'name' => 'Paket 3', 'description' => 'Nasi timbel + ayam goreng + ikan asin + sambal + lalab', 'price' => 26000],
            ['category_id' => $paket->id, 'name' => 'Paket 4', 'description' => 'Nasi timbel + ayam goreng + tahu tempe + sambal + lalab', 'price' => 28500],
            ['category_id' => $paket->id, 'name' => 'Paket 8', 'description' => 'Nasi timbel + ikan mas goreng + tahu tempe + sambal + lalab', 'price' => 27000],
            ['category_id' => $paket->id, 'name' => 'Paket 10', 'description' => 'Nasi timbel + pepes ikan mas + tahu tempe + sambal + lalab', 'price' => 27500],
            ['category_id' => $nasi->id, 'name' => 'Nasi Putih', 'description' => 'Porsi nasi putih', 'price' => 7000],
            ['category_id' => $nasi->id, 'name' => 'Nasi Merah', 'description' => 'Porsi nasi merah', 'price' => 7000],
            ['category_id' => $nasi->id, 'name' => 'Nasi Timbel', 'description' => 'Nasi dibungkus daun pisang', 'price' => 5000],
            ['category_id' => $lauk->id, 'name' => 'Ayam Goreng', 'description' => 'Ayam goreng renyah', 'price' => 17000],
            ['category_id' => $lauk->id, 'name' => 'Ayam Bakar', 'description' => 'Ayam bakar bumbu khas', 'price' => 17000],
            ['category_id' => $lauk->id, 'name' => 'Ikan Asin', 'description' => 'Ikan asin goreng', 'price' => 2000],
            ['category_id' => $lauk->id, 'name' => 'Ikan Mas Goreng', 'description' => 'Ikan mas goreng', 'price' => 16500],
            ['category_id' => $lauk->id, 'name' => 'Ikan Mujair Goreng', 'description' => 'Ikan mujair goreng', 'price' => 16500],
            ['category_id' => $lauk->id, 'name' => 'Tahu Goreng', 'description' => null, 'price' => 4000],
            ['category_id' => $lauk->id, 'name' => 'Tempe Goreng', 'description' => null, 'price' => 3000],
            ['category_id' => $lauk->id, 'name' => 'Perkedel Kentang', 'description' => 'Favorit pelanggan', 'price' => 2500],
            ['category_id' => $lauk->id, 'name' => 'Perkedel Jagung', 'description' => null, 'price' => 2500],
            ['category_id' => $lauk->id, 'name' => 'Ati Ampela', 'description' => null, 'price' => 8500],
            ['category_id' => $pepes->id, 'name' => 'Pepes Tahu', 'description' => null, 'price' => 4000],
            ['category_id' => $pepes->id, 'name' => 'Pepes Ayam', 'description' => null, 'price' => 17000],
            ['category_id' => $pepes->id, 'name' => 'Ulukuteuk Leunca', 'description' => null, 'price' => 4000],
            ['category_id' => $pepes->id, 'name' => 'Tumis Bunga Pepaya', 'description' => null, 'price' => 7000],
            ['category_id' => $minuman->id, 'name' => 'Es Teh Manis', 'description' => null, 'price' => 6500],
            ['category_id' => $minuman->id, 'name' => 'Es Jeruk', 'description' => null, 'price' => 7500],
            ['category_id' => $minuman->id, 'name' => 'Teh Hangat', 'description' => null, 'price' => 6500],
            ['category_id' => $minuman->id, 'name' => 'Jus Alpukat', 'description' => null, 'price' => 13000],
        ];

        $itemsByName = [];
        foreach ($menus as $i => $menu) {
            $itemsByName[$menu['name']] = MenuItem::firstOrCreate(
                ['outlet_id' => $outlet->id, 'name' => $menu['name']],
                array_merge($menu, [
                    'sort_order' => $i + 1,
                    'is_available' => true,
                ])
            );
        }

        $paket1 = $itemsByName['Paket 1'] ?? null;
        if ($paket1) {
            $nasiGroup = ModifierGroup::firstOrCreate(
                ['menu_item_id' => $paket1->id, 'name' => 'Pilihan Nasi'],
                ['selection_type' => 'single', 'min_select' => 1, 'max_select' => 1, 'sort_order' => 1]
            );
            foreach ([['Nasi Putih', 0, true], ['Nasi Merah', 0, false]] as $i => [$name, $price, $default]) {
                ModifierOption::firstOrCreate(
                    ['modifier_group_id' => $nasiGroup->id, 'name' => $name],
                    ['price_adjustment' => $price, 'is_default' => $default, 'sort_order' => $i + 1]
                );
            }
        }

        $ayamGoreng = $itemsByName['Ayam Goreng'] ?? null;
        if ($ayamGoreng) {
            $sambalGroup = ModifierGroup::firstOrCreate(
                ['menu_item_id' => $ayamGoreng->id, 'name' => 'Sambal'],
                ['selection_type' => 'single', 'min_select' => 1, 'max_select' => 1, 'sort_order' => 1]
            );
            foreach ([['Sambal Terasi', 0, true], ['Sambal Seuhah', 0, false]] as $i => [$name, $price, $default]) {
                ModifierOption::firstOrCreate(
                    ['modifier_group_id' => $sambalGroup->id, 'name' => $name],
                    ['price_adjustment' => $price, 'is_default' => $default, 'sort_order' => $i + 1]
                );
            }
        }

        $sampleOrders = [
            [
                'name' => 'Budi',
                'phone' => '0811111111',
                'status' => OrderStatus::Pending,
                'items' => [['Paket 1', 2], ['Es Teh Manis', 1]],
            ],
            [
                'name' => 'Siti',
                'phone' => '0822222222',
                'status' => OrderStatus::Confirmed,
                'items' => [['Nasi Timbel', 1], ['Ayam Goreng', 1], ['Perkedel Kentang', 2]],
            ],
            [
                'name' => 'Andi',
                'phone' => '0833333333',
                'status' => OrderStatus::Paid,
                'items' => [['Paket 4', 1], ['Jus Alpukat', 1]],
            ],
        ];

        foreach ($sampleOrders as $idx => $sample) {
            $total = 0;
            foreach ($sample['items'] as [$itemName, $qty]) {
                $total += $itemsByName[$itemName]->price * $qty;
            }

            $orderNumber = 'NTB-' . now()->format('Ymd') . '-' . str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT);

            $order = Order::firstOrCreate(
                ['order_number' => $orderNumber],
                [
                    'outlet_id' => $outlet->id,
                    'customer_name' => $sample['name'],
                    'customer_phone' => $sample['phone'],
                    'notes' => null,
                    'status' => $sample['status']->value,
                    'total' => $total,
                    'payment_method' => $sample['status'] === OrderStatus::Paid ? 'cash' : null,
                    'amount_paid' => $sample['status'] === OrderStatus::Paid ? $total : null,
                    'paid_at' => $sample['status'] === OrderStatus::Paid ? now() : null,
                ]
            );

            if (! $order->wasRecentlyCreated) {
                continue;
            }

            foreach ($sample['items'] as [$itemName, $qty]) {
                $item = $itemsByName[$itemName];
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item->id,
                    'item_name' => $item->name,
                    'qty' => $qty,
                    'price' => $item->price,
                    'is_paid' => $sample['status'] === OrderStatus::Paid,
                    'paid_at' => $sample['status'] === OrderStatus::Paid ? now() : null,
                ]);
            }
        }

        $this->command?->call('menu:seed-photos');
    }
}
