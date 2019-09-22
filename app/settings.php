<?php
/**
 * MIT License
 *
 * Copyright (c) 2019 Filli Group (Einzelunternehmen)
 * Copyright (c) 2019 Filli IT (Einzelunternehmen)
 * Copyright (c) 2019 Ursin Filli
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

$dotenv = Dotenv\Dotenv::create(__DIR__ . '/../');
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);
$dotenv->required(['APP_GOOGLE_RECAPTCHA']);
$dotenv->required(['APP_PAGE_BRAND'])->notEmpty();
$dotenv->required(['APP_COMPANY_NAME', 'APP_COMPANY_EMAIL'])->notEmpty();
$dotenv->required(['APP_META_KEYWORDS', 'APP_META_DESCRIPTION'])->notEmpty();
$dotenv->required(['APP_FAVICON_APPLE_TOUCH_ICON', 'APP_FAVICON_ICON_32', 'APP_FAVICON_ICON_16', 'APP_FAVICON_MANIFEST',
    'APP_FAVICON_MASK_ICON', 'APP_FAVICON_SHORTCUT_ICON', 'APP_FAVICON_MSAPPLICATION_CONNFIG'])->notEmpty();

return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,

        // View Settings
        'view' => [
            'template_path' => __DIR__ . '/templates',
            'twig' => [
                'cache' => __DIR__ . '/../cache/twig',
                'auto_reload' => true,
            ],
        ],

        // Monolog Settings
        'logger' => [
            'name' => 'homepage',
            'path' => __DIR__ . '/../log/home.log',
        ],

        // Illuminate/database Configuration
        'db' => [
            'driver' => 'mysql',
            'host' => $_SERVER['DB_HOST'],
            'database' => $_SERVER['DB_NAME'],
            'username' => $_SERVER['DB_USER'],
            'password' => $_SERVER['DB_PASS'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],

        //Google reCaptcha
        'reCaptchaSecret' => $_SERVER['APP_GOOGLE_RECAPTCHA'],

        // Page Settings
        'page' => [
            'brand' => $_SERVER['APP_PAGE_BRAND'],
            'footer_company' => 'Filli Group (Einzelunternehmen)',
            'company_name' => $_SERVER['APP_COMPANY_NAME'],
            'company_email' => $_SERVER['APP_COMPANY_EMAIL'],
            'meta_keywords' => $_SERVER['APP_META_KEYWORDS'],
            'meta_description' => $_SERVER['APP_META_DESCRIPTION'],
            'favicon_apple_touch_icon' => $_SERVER['APP_FAVICON_APPLE_TOUCH_ICON'],
            'favicon_icon_32' => $_SERVER['APP_FAVICON_ICON_32'],
            'favicon_icon_16' => $_SERVER['APP_FAVICON_ICON_16'],
            'favicon_manifest' => $_SERVER['APP_FAVICON_MANIFEST'],
            'favicon_mask_icon' => $_SERVER['APP_FAVICON_MASK_ICON'],
            'favicon_shortcut_icon' => $_SERVER['APP_FAVICON_SHORTCUT_ICON'],
            'favicon_msapplication_config' => $_SERVER['APP_FAVICON_MSAPPLICATION_CONNFIG'],
        ],
    ],
];
