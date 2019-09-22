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

// DIC configuration

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new App\Views\CsrfExtension($c['csrf']));
    $view->getEnvironment()->addGlobal('flash', $c->get('flash'));

    // Add Variables
    $view->getEnvironment()->addGlobal('brand', $settings['page']['brand']);
    $view->getEnvironment()->addGlobal('footer_company', $settings['page']['footer_company']);
    $view->getEnvironment()->addGlobal('company_name', $settings['page']['company_name']);
    $view->getEnvironment()->addGlobal('company_email', $settings['page']['company_email']);
    $view->getEnvironment()->addGlobal('meta_keywords', $settings['page']['meta_keywords']);
    $view->getEnvironment()->addGlobal('meta_description', $settings['page']['meta_description']);
    $view->getEnvironment()->addGlobal('favicon_apple_touch_icon', $settings['page']['favicon_apple_touch_icon']);
    $view->getEnvironment()->addGlobal('favicon_icon_32', $settings['page']['favicon_icon_32']);
    $view->getEnvironment()->addGlobal('favicon_icon_16', $settings['page']['favicon_icon_16']);
    $view->getEnvironment()->addGlobal('favicon_manifest', $settings['page']['favicon_manifest']);
    $view->getEnvironment()->addGlobal('favicon_mask_icon', $settings['page']['favicon_mask_icon']);
    $view->getEnvironment()->addGlobal('favicon_shortcut_icon', $settings['page']['favicon_shortcut_icon']);
    $view->getEnvironment()->addGlobal('favicon_msapplication_config', $settings['page']['favicon_msapplication_config']);

    return $view;
};

// Flash messages
$container['flash'] = function ($c) {
    return new Slim\Flash\Messages;
};

// Database
$container['capsule'] = function ($c) {
    $capsule = new Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($c['settings']['db']);
    return $capsule;
};

// CSRF
$container['csrf'] = function ($container) {
    return new Slim\Csrf\Guard;
};

// Validator
$container['validator'] = function ($container) {
    return new App\Validation\Validator;
};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

$container[App\Action\HomeAction::class] = function ($c) {
    return new App\Action\HomeAction($c->get('view'), $c->get('logger'));
};

$container[App\Action\ImprintAction::class] = function ($c) {
    return new App\Action\ImprintAction($c->get('view'), $c->get('logger'));
};

$container[App\Action\PrivacyAction::class] = function ($c) {
    return new App\Action\PrivacyAction($c->get('view'), $c->get('logger'));
};

// -----------------------------------------------------------------------------
// Error Pages
// -----------------------------------------------------------------------------

// File Not Found
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container->view->render($response, 'error.twig', array(
            'title' => '404',
            'error' => '404',
            'css' => 'https://cdn.staticaly.com/gl/filli-group/css/raw/4b353dcb544cb9774c9e2f613dfc46d5b2e95b08/material-dashboard.min.css',
        ))->withStatus(404);
    };
};

// Method Not Allowed
$container['notAllowedHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container->view->render($response, 'error.twig', array(
            'title' => '405',
            'error' => '405',
            'css' => 'https://cdn.staticaly.com/gl/filli-group/css/raw/4b353dcb544cb9774c9e2f613dfc46d5b2e95b08/material-dashboard.min.css',
        ))->withStatus(405);
    };
};

// Internal Server Error
$container['errorHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container->view->render($response, 'error.twig', array(
            'title' => '500',
            'error' => '500',
            'css' => 'https://cdn.staticaly.com/gl/filli-group/css/raw/4b353dcb544cb9774c9e2f613dfc46d5b2e95b08/material-dashboard.min.css',
        ))->withStatus(500);
    };
};