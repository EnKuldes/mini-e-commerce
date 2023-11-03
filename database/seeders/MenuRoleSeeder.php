<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class MenuRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // list roles
        $roles = [
            ['id' => 1, 'role' => 'Admin', 'desc' => 'Admin untuk edit konten, app preference dan app theme.'],
            ['id' => 2, 'role' => 'User', 'desc' => 'User Normal.'],
            ['id' => 3, 'role' => 'Manager', 'desc' => 'User Manager'],
        ];
        // list dari menu atau request
        $access_lists = [
            ['id' => '1', 'type' => 'page', 'parent' => '0', 'order' => '99', 'icon' => 'fas fa-superscript', 'name' => 'Admin', 'link' => '#', 'child' => '1'],
            ['id' => '5', 'type' => 'page', 'parent' => '1', 'order' => '3', 'icon' => 'fas fa-tag', 'name' => 'Resource Management', 'link' => 'Page/Admin/Resource-management', 'child' => '0'],
            ['id' => '6', 'type' => 'request', 'parent' => '1', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'List User', 'link' => 'Request/Admin/List-user', 'child' => '0'],
            ['id' => '7', 'type' => 'request', 'parent' => '1', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'List Role', 'link' => 'Request/Admin/List-role', 'child' => '0'],
            ['id' => '8', 'type' => 'request', 'parent' => '1', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'Toggle Switch', 'link' => 'Request/Admin/Toggle-switch', 'child' => '0'],
            ['id' => '9', 'type' => 'request', 'parent' => '1', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'Save User', 'link' => 'Request/Admin/Save-user', 'child' => '0'],
            ['id' => '10', 'type' => 'request', 'parent' => '1', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'Info User', 'link' => 'Request/Admin/Info-user', 'child' => '0'],
            ['id' => '11', 'type' => 'request', 'parent' => '1', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'Save Role', 'link' => 'Request/Admin/Save-role', 'child' => '0'],
            ['id' => '12', 'type' => 'request', 'parent' => '1', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'Info Role', 'link' => 'Request/Admin/Info-role', 'child' => '0'],
            ['id' => '13', 'type' => 'request', 'parent' => '1', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'List User Role', 'link' => 'Request/Admin/List-user-roles', 'child' => '0'],
            ['id' => '14', 'type' => 'request', 'parent' => '1', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'Mapping User and Role', 'link' => 'Request/Admin/Map-user-role', 'child' => '0'],
            ['id' => '15', 'type' => 'request', 'parent' => '1', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'List Menu/Request', 'link' => 'Request/Admin/List-menu', 'child' => '0'],
            ['id' => '16', 'type' => 'request', 'parent' => '1', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'List Parent Menu/Request', 'link' => 'Request/Admin/List-parent-menus', 'child' => '0'],
            ['id' => '17', 'type' => 'request', 'parent' => '1', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'Save Menu/Request', 'link' => 'Request/Admin/Save-menu', 'child' => '0'],
            ['id' => '18', 'type' => 'request', 'parent' => '1', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'Info Menu/Request', 'link' => 'Request/Admin/Info-menu', 'child' => '0'],
            ['id' => '19', 'type' => 'request', 'parent' => '1', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'List Accessable Menu/Request', 'link' => 'Request/Admin/List-accessable-menu', 'child' => '0'],
            ['id' => '20', 'type' => 'request', 'parent' => '1', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'Save Accessable Menu/Request', 'link' => 'Request/Admin/Save-access-list-roles', 'child' => '0'],

            // Product
            ['id' => '31', 'type' => 'page', 'parent' => '0', 'order' => '2', 'icon' => 'fas fa-product-hunt', 'name' => 'Product', 'link' => 'Page/Product', 'child' => '1'],
            ['id' => '32', 'type' => 'request', 'parent' => '31', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'Get List Products', 'link' => 'Request/Product/List-products', 'child' => '0'],
            ['id' => '33', 'type' => 'request', 'parent' => '31', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'Get Single Products', 'link' => 'Request/Product/Product', 'child' => '0'],
            ['id' => '34', 'type' => 'request', 'parent' => '31', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'Save Products', 'link' => 'Request/Product/Save-product', 'child' => '0'],
            ['id' => '35', 'type' => 'request', 'parent' => '31', 'order' => '1', 'icon' => 'fas fa-tag', 'name' => 'Delete Products', 'link' => 'Request/Product/Delete-product', 'child' => '0'],

            // Order
            ['id' => '51', 'type' => 'page', 'parent' => '0', 'order' => '1', 'icon' => 'fas fa-shopping-basket', 'name' => 'Order', 'link' => '#', 'child' => '1'],
            ['id' => '52', 'type' => 'page', 'parent' => '51', 'order' => '1', 'icon' => 'fas fa-house-user', 'name' => 'Home', 'link' => 'Page/Order/Home', 'child' => '0'],
            ['id' => '53', 'type' => 'page', 'parent' => '51', 'order' => '3', 'icon' => 'fas fa-money-bill', 'name' => 'History', 'link' => 'Page/Order/History', 'child' => '0'],
            ['id' => '54', 'type' => 'page', 'parent' => '51', 'order' => '2', 'icon' => 'fas fa-shopping-cart', 'name' => 'Cart', 'link' => 'Page/Order/Cart', 'child' => '0'],
            ['id' => '55', 'type' => 'request', 'parent' => '51', 'order' => '9', 'icon' => 'fas fa-tag', 'name' => 'Get Page Product Information', 'link' => 'Request/Order/Information', 'child' => '0'],
            ['id' => '56', 'type' => 'request', 'parent' => '51', 'order' => '9', 'icon' => 'fas fa-tag', 'name' => 'Get List Orders', 'link' => 'Request/Order/List-orders', 'child' => '0'],
            ['id' => '57', 'type' => 'request', 'parent' => '51', 'order' => '9', 'icon' => 'fas fa-tag', 'name' => 'Get Cart Data', 'link' => 'Request/Order/Cart', 'child' => '0'],
            ['id' => '58', 'type' => 'request', 'parent' => '51', 'order' => '9', 'icon' => 'fas fa-tag', 'name' => 'Get Detail Order', 'link' => 'Request/Order/Detail', 'child' => '0'],
            ['id' => '59', 'type' => 'request', 'parent' => '51', 'order' => '9', 'icon' => 'fas fa-tag', 'name' => 'Post Add to Cart', 'link' => 'Request/Order/Add-cart', 'child' => '0'],
            ['id' => '60', 'type' => 'request', 'parent' => '51', 'order' => '9', 'icon' => 'fas fa-tag', 'name' => 'Post Remove from Cart', 'link' => 'Request/Order/Remove-cart', 'child' => '0'],
            ['id' => '61', 'type' => 'request', 'parent' => '51', 'order' => '9', 'icon' => 'fas fa-tag', 'name' => 'Post Update Qty Product', 'link' => 'Request/Order/Update-qty-product', 'child' => '0'],
            ['id' => '62', 'type' => 'request', 'parent' => '51', 'order' => '9', 'icon' => 'fas fa-tag', 'name' => 'Post Checkout', 'link' => 'Request/Order/Checkout', 'child' => '0'],


        ];
        // tabel penampung autorisasi role ke access(menu atau request)
        $access_role = [
            // Admin
            ['role_id' => '1', 'access_list_id' => '1'],
            ['role_id' => '1', 'access_list_id' => '5'],
            ['role_id' => '1', 'access_list_id' => '6'],
            ['role_id' => '1', 'access_list_id' => '7'],
            ['role_id' => '1', 'access_list_id' => '8'],
            ['role_id' => '1', 'access_list_id' => '9'],
            ['role_id' => '1', 'access_list_id' => '10'],
            ['role_id' => '1', 'access_list_id' => '11'],
            ['role_id' => '1', 'access_list_id' => '12'],
            ['role_id' => '1', 'access_list_id' => '13'],
            ['role_id' => '1', 'access_list_id' => '14'],
            ['role_id' => '1', 'access_list_id' => '15'],
            ['role_id' => '1', 'access_list_id' => '16'],
            ['role_id' => '1', 'access_list_id' => '17'],
            ['role_id' => '1', 'access_list_id' => '18'],
            ['role_id' => '1', 'access_list_id' => '19'],
            ['role_id' => '1', 'access_list_id' => '20'],
        ];
        // User
        for ($i=51; $i < 63; $i++) { 
            $access_role[] = ['role_id' => '2', 'access_list_id' => $i];
        }
        // Manager
        for ($i=31; $i < 36; $i++) { 
            $access_role[] = ['role_id' => '3', 'access_list_id' => $i];
        }

        // tabel penampung list user ke roles
        $role_user = [
            ['role_id' => '1', 'user_id' => '1'],
            ['role_id' => '2', 'user_id' => '2'],
            ['role_id' => '3', 'user_id' => '3']
        ];

        DB::table('roles')->insert($roles);
        DB::table('access_lists')->insert($access_lists);
        DB::table('access_list_roles')->insert($access_role);
        DB::table('role_users')->insert($role_user);
    }
}
