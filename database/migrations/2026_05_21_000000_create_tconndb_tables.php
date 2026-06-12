<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop existing tables
        $tablesToDrop = [
            'categories',
            'suppliers',
            'customers',
            'products',
            'sales',
            'sale_items',
            'requisitions',
            'settings',
            'stores',
            'app_config',
            'app_logs',
            'app_role_settings',
            'app_staff_role_settings',
            'cart',
            'category',
            'departments',
            'itemname',
            'level',
            'modepay',
            'newlocation',
            'positions',
            'pos_employ',
            'pos_items',
            'pos_items_close_stock',
            'pos_items_que',
            'pos_items_session',
            'pos_people',
            'pos_receipt',
            'pos_sales_items',
            'pos_sales_items_session',
            'pos_sessions',
            'pos_store',
            'pos_store_requisition',
            'pos_store_shelve',
            'pos_users',
            'sales_return_in',
            'tconnpos_b_d',
            'tconnpos_b_d_supplier',
            'tconnpos_customers',
            'tconnpos_customers_bank',
            'tconnpos_customers_credited',
            'tconnpos_customer_goods',
            'tconnpos_customer_payment_transaction',
            'tconnpos_suppliers',
            'tconnpos_suppliers_payment',
            'temp_cart',
        ];

        Schema::disableForeignKeyConstraints();
        foreach ($tablesToDrop as $table) {
            Schema::dropIfExists($table);
        }
        Schema::enableForeignKeyConstraints();

        // Run the custom phpMyAdmin SQL Dump with timestamps
        DB::unprepared("
            CREATE TABLE `stores` (
              `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
              `name` varchar(255) NOT NULL,
              `host` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE `app_config` (
              `tkey` varchar(255) NOT NULL,
              `tvalues` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `app_logs` (
              `id` int(255) NOT NULL,
              `ipadress` text NOT NULL,
              `os` text NOT NULL,
              `date` text NOT NULL,
              `webpage` text NOT NULL,
              `browser` text NOT NULL,
              `user_id` text NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `app_role_settings` (
              `role_id` int(255) NOT NULL,
              `role_name` varchar(255) NOT NULL,
              `role_admin` varchar(255) NOT NULL,
              `role_operator` varchar(255) NOT NULL,
              `role_auditor` varchar(255) NOT NULL,
              `role_manager` varchar(255) NOT NULL,
              `role_accountant` varchar(255) NOT NULL,
              `role_storepersonnel` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `app_staff_role_settings` (
              `role_id` int(255) NOT NULL,
              `staff_id` varchar(255) NOT NULL,
              `role_position` varchar(255) NOT NULL,
              `role_contactus` varchar(255) NOT NULL,
              `role_store_config` varchar(255) NOT NULL,
              `role_employees` varchar(255) NOT NULL,
              `role_app_users` varchar(255) NOT NULL,
              `role_set_roles` varchar(255) NOT NULL,
              `role_products` varchar(255) NOT NULL,
              `role_store` varchar(255) NOT NULL,
              `role_mange_requisition` varchar(255) NOT NULL,
              `role_sales` varchar(255) NOT NULL,
              `role_suppliers` varchar(255) NOT NULL,
              `role_customers` varchar(255) NOT NULL,
              `role_returnin` varchar(255) NOT NULL,
              `role_reports` varchar(255) NOT NULL,
              `role_dbbackup` varchar(255) NOT NULL,
              `role_pkey` varchar(255) NOT NULL,
              `role_b_d` varchar(255) NOT NULL,
              `role_return_supplier` varchar(255) NOT NULL,
              `role_departments` varchar(255) NOT NULL,
              `role_positions` varchar(255) NOT NULL,
              `role_mode_pay` varchar(255) NOT NULL,
              `role_creator` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `cart` (
              `id` int(10) NOT NULL,
              `item_id` varchar(255) NOT NULL,
              `quantity` int(10) NOT NULL DEFAULT 0,
              `quantity_left` int(10) NOT NULL DEFAULT 0,
              `selling_price` double(15,2) NOT NULL,
              `cost_price` double(15,2) NOT NULL,
              `staff_id` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `category` (
              `id` int(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `branch` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `departments` (
              `id` int(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `branch` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `itemname` (
              `id` int(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `branch` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `level` (
              `id` int(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `modepay` (
              `id` int(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `branch` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `newlocation` (
              `id` int(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `branch` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `positions` (
              `id` int(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `branch` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `pos_employ` (
              `person_id` int(10) NOT NULL,
              `staff_id` varchar(255) NOT NULL,
              `position` varchar(255) NOT NULL,
              `department` varchar(255) NOT NULL,
              `no_hours_day` int(255) NOT NULL,
              `no_days` int(255) NOT NULL,
              `amount_day` double DEFAULT NULL,
              `date_employed` text NOT NULL,
              `staff` text NOT NULL,
              `branch` text NOT NULL,
              `status` text NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `pos_items` (
              `item_id` int(10) NOT NULL,
              `name` varchar(255) NOT NULL,
              `category` varchar(255) NOT NULL,
              `supplier` varchar(255) NOT NULL,
              `item_number` varchar(255) DEFAULT NULL,
              `description` varchar(255) NOT NULL,
              `cost_price` double(15,2) NOT NULL,
              `unit_price` double(15,2) NOT NULL,
              `quantity` float NOT NULL,
              `staff_id` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `date` timestamp NOT NULL DEFAULT current_timestamp(),
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `pos_items_close_stock` (
              `item_id` int(10) NOT NULL,
              `name` varchar(255) NOT NULL,
              `category` varchar(255) NOT NULL,
              `supplier` varchar(255) NOT NULL,
              `item_number` varchar(255) DEFAULT NULL,
              `description` varchar(255) NOT NULL,
              `cost_price` double(15,2) NOT NULL,
              `unit_price` double(15,2) NOT NULL,
              `quantity` float NOT NULL,
              `staff_id` varchar(255) NOT NULL,
              `branch` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `date` timestamp NOT NULL DEFAULT current_timestamp(),
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `pos_items_que` (
              `item_id` int(10) NOT NULL,
              `name` varchar(255) NOT NULL,
              `category` varchar(255) NOT NULL,
              `supplier` varchar(255) NOT NULL,
              `item_number` varchar(255) DEFAULT NULL,
              `description` varchar(255) NOT NULL,
              `cost_price` double(15,2) NOT NULL,
              `unit_price` double(15,2) NOT NULL,
              `quantity` float NOT NULL,
              `staff_id` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `date` timestamp NOT NULL DEFAULT current_timestamp(),
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `pos_items_session` (
              `item_id` int(10) NOT NULL,
              `name` varchar(255) NOT NULL,
              `category` varchar(255) NOT NULL,
              `supplier` varchar(255) NOT NULL,
              `item_number` varchar(255) DEFAULT NULL,
              `description` varchar(255) NOT NULL,
              `cost_price` double(15,2) NOT NULL,
              `unit_price` double(15,2) NOT NULL,
              `quantity` float NOT NULL,
              `staff_id` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `date` timestamp NOT NULL DEFAULT current_timestamp(),
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `pos_people` (
              `person_id` int(10) NOT NULL,
              `staff_id` varchar(255) NOT NULL,
              `title` varchar(255) NOT NULL,
              `first_name` varchar(255) NOT NULL,
              `last_name` varchar(255) NOT NULL,
              `sex` varchar(8) NOT NULL,
              `dob` varchar(255) NOT NULL,
              `mstatus` varchar(255) NOT NULL,
              `religion` varchar(255) NOT NULL,
              `phone_number` varchar(255) NOT NULL,
              `email` varchar(255) NOT NULL,
              `address` varchar(255) NOT NULL,
              `state` varchar(255) NOT NULL,
              `country` varchar(255) NOT NULL,
              `nok` varchar(255) NOT NULL,
              `nok_address` varchar(255) NOT NULL,
              `nok_contact` varchar(255) NOT NULL,
              `nok_email` varchar(255) NOT NULL,
              `nok_rela` varchar(255) NOT NULL,
              `comments` text NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `pos_receipt` (
              `id` int(255) NOT NULL,
              `rno` int(255) NOT NULL DEFAULT 1,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `pos_sales_items` (
              `id` int(255) NOT NULL,
              `recipt_number` varchar(255) NOT NULL,
              `item_id` varchar(255) NOT NULL,
              `category` varchar(255) NOT NULL,
              `supplier` varchar(255) NOT NULL,
              `quantity_purchased` int(255) NOT NULL DEFAULT 0,
              `quantity_left` int(255) NOT NULL DEFAULT 0,
              `item_cost_price` decimal(15,2) NOT NULL,
              `item_unit_price` double(15,2) NOT NULL,
              `total_amount` double(15,2) NOT NULL,
              `amount_paid` double(15,2) NOT NULL,
              `mode_payment` varchar(255) NOT NULL,
              `description` varchar(255) NOT NULL,
              `discount_percent` int(11) NOT NULL DEFAULT 0,
              `date` timestamp NOT NULL DEFAULT current_timestamp(),
              `staff_id` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `status_location` varchar(255) NOT NULL,
              `status_secound` varchar(255) NOT NULL,
              `customer` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `pos_sales_items_session` (
              `id` int(255) NOT NULL,
              `recipt_number` varchar(255) NOT NULL,
              `item_id` varchar(255) NOT NULL,
              `category` varchar(255) NOT NULL,
              `supplier` varchar(255) NOT NULL,
              `quantity_purchased` int(255) NOT NULL DEFAULT 0,
              `quantity_left` int(255) NOT NULL DEFAULT 0,
              `item_cost_price` decimal(15,2) NOT NULL,
              `item_unit_price` double(15,2) NOT NULL,
              `total_amount` double(15,2) NOT NULL,
              `amount_paid` double(15,2) NOT NULL,
              `mode_payment` varchar(255) NOT NULL,
              `description` varchar(255) NOT NULL,
              `discount_percent` int(11) NOT NULL DEFAULT 0,
              `date` text NOT NULL,
              `staff_id` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `status_location` varchar(255) NOT NULL,
              `customer` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `pos_sessions` (
              `id` int(255) NOT NULL,
              `ip_address` varchar(16) NOT NULL DEFAULT '0',
              `user_agent` varchar(255) NOT NULL,
              `recipt_number` varchar(255) NOT NULL,
              `itemname` varchar(255) NOT NULL,
              `qty` int(255) NOT NULL DEFAULT 0,
              `last_activity` varchar(255) NOT NULL,
              `date` timestamp NOT NULL DEFAULT current_timestamp(),
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `pos_store` (
              `item_id` int(10) NOT NULL,
              `name` varchar(255) NOT NULL,
              `category` varchar(255) NOT NULL,
              `supplier` varchar(255) NOT NULL,
              `item_number` varchar(255) DEFAULT NULL,
              `description` varchar(255) NOT NULL,
              `cost_price` double(15,2) NOT NULL,
              `quantity` float NOT NULL,
              `ty` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `status_location` varchar(255) NOT NULL,
              `staff_id` varchar(255) NOT NULL,
              `date` timestamp NOT NULL DEFAULT current_timestamp(),
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `pos_store_requisition` (
              `item_id` int(10) NOT NULL,
              `name` varchar(255) NOT NULL,
              `category` varchar(255) NOT NULL,
              `quantity` float NOT NULL,
              `collectedby` varchar(255) NOT NULL,
              `department` varchar(255) NOT NULL,
              `ty` varchar(255) NOT NULL,
              `staff_id` varchar(255) NOT NULL,
              `manager_approved` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `branch` varchar(255) NOT NULL,
              `datein` timestamp NOT NULL DEFAULT current_timestamp(),
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `pos_store_shelve` (
              `item_id` int(10) NOT NULL,
              `supplier` varchar(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `category` varchar(255) NOT NULL,
              `cost_price` double(15,2) NOT NULL,
              `quantity` float NOT NULL,
              `ty` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `status_location` varchar(255) NOT NULL,
              `staff_id` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `pos_users` (
              `person_id` int(10) NOT NULL,
              `staff_id` varchar(255) NOT NULL,
              `password` varchar(255) NOT NULL,
              `position` varchar(255) NOT NULL,
              `creator` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `sales_return_in` (
              `id` int(255) NOT NULL,
              `itemname` varchar(255) DEFAULT NULL,
              `quantity` int(255) DEFAULT NULL,
              `date` timestamp NOT NULL DEFAULT current_timestamp(),
              `staff_id` varchar(255) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `tconnpos_b_d` (
              `item_id` int(10) NOT NULL,
              `name` varchar(255) DEFAULT NULL,
              `qty` float NOT NULL,
              `from_dept` varchar(255) DEFAULT NULL,
              `description` varchar(255) DEFAULT NULL,
              `staff_id` varchar(255) DEFAULT NULL,
              `date` timestamp NOT NULL DEFAULT current_timestamp(),
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `tconnpos_b_d_supplier` (
              `item_id` int(10) NOT NULL,
              `name` varchar(255) DEFAULT NULL,
              `qty` float NOT NULL,
              `from_dept` varchar(255) DEFAULT NULL,
              `description` varchar(255) DEFAULT NULL,
              `supplier` varchar(255) DEFAULT NULL,
              `staff_id` varchar(255) DEFAULT NULL,
              `date` timestamp NOT NULL DEFAULT current_timestamp(),
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `tconnpos_customers` (
              `person_id` int(10) NOT NULL,
              `name` varchar(255) DEFAULT NULL,
              `address` varchar(255) DEFAULT NULL,
              `phone` varchar(255) DEFAULT NULL,
              `email` varchar(255) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `tconnpos_customers_bank` (
              `id` int(255) NOT NULL,
              `name` varchar(255) DEFAULT NULL,
              `amount_deposited` double(15,2) NOT NULL,
              `amount_left` double(15,2) NOT NULL,
              `date` timestamp NOT NULL DEFAULT current_timestamp(),
              `staff_id` varchar(255) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `tconnpos_customers_credited` (
              `id` int(255) NOT NULL,
              `name` varchar(255) DEFAULT NULL,
              `amount` double(15,2) NOT NULL,
              `teller_number` varchar(255) DEFAULT NULL,
              `date` timestamp NOT NULL DEFAULT current_timestamp(),
              `staff_id` varchar(255) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `tconnpos_customer_goods` (
              `id` int(255) NOT NULL,
              `customername` varchar(255) DEFAULT NULL,
              `receipt_no` varchar(255) DEFAULT NULL,
              `quantity` int(255) DEFAULT NULL,
              `amount_goods` double(15,2) NOT NULL,
              `amount_paid` double(15,2) NOT NULL,
              `mop` varchar(255) DEFAULT NULL,
              `date` timestamp NOT NULL DEFAULT current_timestamp(),
              `staff_id` varchar(255) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `tconnpos_customer_payment_transaction` (
              `id` int(255) NOT NULL,
              `customername` varchar(255) DEFAULT NULL,
              `receipt_no` varchar(255) DEFAULT NULL,
              `amount_paid` double(15,2) NOT NULL,
              `mop` varchar(255) DEFAULT NULL,
              `descp` varchar(255) DEFAULT NULL,
              `date` timestamp NOT NULL DEFAULT current_timestamp(),
              `staff_id` varchar(255) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `tconnpos_suppliers` (
              `person_id` int(10) NOT NULL,
              `company_name` varchar(255) DEFAULT NULL,
              `address` varchar(255) DEFAULT NULL,
              `phone` varchar(255) DEFAULT NULL,
              `email` varchar(255) DEFAULT NULL,
              `branch` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `tconnpos_suppliers_payment` (
              `person_id` int(10) NOT NULL,
              `quantity_goods` int(255) NOT NULL,
              `amount_topay` double(15,2) NOT NULL,
              `amount_paid` double(15,2) NOT NULL,
              `email` varchar(255) DEFAULT NULL,
              `date` timestamp NOT NULL DEFAULT current_timestamp(),
              `staff_id` varchar(255) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            CREATE TABLE `temp_cart` (
              `id` int(10) NOT NULL,
              `cartname` varchar(255) NOT NULL,
              `item_id` varchar(255) NOT NULL,
              `quantity` int(10) NOT NULL DEFAULT 0,
              `quantity_left` int(10) NOT NULL DEFAULT 0,
              `selling_price` double(15,2) NOT NULL,
              `staff_id` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

            -- --------------------------------------------------------
            -- Indexes & AUTO_INCREMENT configuration
            -- --------------------------------------------------------

            ALTER TABLE `app_config`
              ADD PRIMARY KEY (`tkey`);

            ALTER TABLE `app_logs`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `app_role_settings`
              ADD PRIMARY KEY (`role_id`);

            ALTER TABLE `app_staff_role_settings`
              ADD PRIMARY KEY (`role_id`);

            ALTER TABLE `cart`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `category`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `departments`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `itemname`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `level`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `modepay`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `newlocation`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `positions`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `pos_employ`
              ADD PRIMARY KEY (`person_id`);

            ALTER TABLE `pos_items`
              ADD PRIMARY KEY (`item_id`);

            ALTER TABLE `pos_items_close_stock`
              ADD PRIMARY KEY (`item_id`);

            ALTER TABLE `pos_items_que`
              ADD PRIMARY KEY (`item_id`);

            ALTER TABLE `pos_items_session`
              ADD PRIMARY KEY (`item_id`);

            ALTER TABLE `pos_people`
              ADD PRIMARY KEY (`person_id`);

            ALTER TABLE `pos_receipt`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `pos_sales_items`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `pos_sales_items_session`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `pos_sessions`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `pos_store`
              ADD PRIMARY KEY (`item_id`);

            ALTER TABLE `pos_store_requisition`
              ADD PRIMARY KEY (`item_id`);

            ALTER TABLE `pos_store_shelve`
              ADD PRIMARY KEY (`item_id`);

            ALTER TABLE `pos_users`
              ADD PRIMARY KEY (`person_id`);

            ALTER TABLE `sales_return_in`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `tconnpos_b_d`
              ADD PRIMARY KEY (`item_id`);

            ALTER TABLE `tconnpos_b_d_supplier`
              ADD PRIMARY KEY (`item_id`);

            ALTER TABLE `tconnpos_customers`
              ADD PRIMARY KEY (`person_id`);

            ALTER TABLE `tconnpos_customers_bank`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `tconnpos_customers_credited`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `tconnpos_customer_goods`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `tconnpos_customer_payment_transaction`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `tconnpos_suppliers`
              ADD PRIMARY KEY (`person_id`);

            ALTER TABLE `tconnpos_suppliers_payment`
              ADD PRIMARY KEY (`person_id`);

            ALTER TABLE `temp_cart`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `app_logs`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `app_role_settings`
              MODIFY `role_id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `app_staff_role_settings`
              MODIFY `role_id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `cart`
              MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `category`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `departments`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `itemname`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `level`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `modepay`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `newlocation`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `positions`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `pos_employ`
              MODIFY `person_id` int(10) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `pos_items`
              MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `pos_items_close_stock`
              MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `pos_items_que`
              MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `pos_people`
              MODIFY `person_id` int(10) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `pos_receipt`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `pos_sales_items`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `pos_sessions`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `pos_store`
              MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `pos_store_requisition`
              MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `pos_store_shelve`
              MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `pos_users`
              MODIFY `person_id` int(10) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `sales_return_in`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `tconnpos_b_d`
              MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `tconnpos_b_d_supplier`
              MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `tconnpos_customers`
              MODIFY `person_id` int(10) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `tconnpos_customers_bank`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `tconnpos_customers_credited`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `tconnpos_customer_goods`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `tconnpos_customer_payment_transaction`
              MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `tconnpos_suppliers`
              MODIFY `person_id` int(10) NOT NULL AUTO_INCREMENT;

            ALTER TABLE `temp_cart`
              MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Handled in up() via dropIfExists, so no custom code needed here.
    }
};
