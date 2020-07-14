<?php

use App\Models\User;
use SleepingOwl\Admin\Navigation\Page;

// Default check access logic
// AdminNavigation::setAccessLogic(function(Page $page) {
// 	   return auth()->user()->isSuperAdmin();
// });
//
// AdminNavigation::addPage(\App\User::class)->setTitle('test')->setPages(function(Page $page) {
// 	  $page
//		  ->addPage()
//	  	  ->setTitle('Dashboard')
//		  ->setUrl(route('admin.dashboard'))
//		  ->setPriority(100);
//
//	  $page->addPage(\App\User::class);
// });
//
// // or
//
// AdminSection::addMenuPage(\App\User::class)

return [
    [
        'title' => 'Dashboard',
        'icon' => 'fa fa-tachometer-alt',
        'url' => route('admin.dashboard'),
        'priority' => 50,
//        'accessLogic' => function () {
//            return Auth::user()->isSuperAdmin() || Auth::user()->isAdmin();
//        }
    ],
    [
        'title' => __('admin.live.title'),
        'icon' => 'fab fa-twitch',
        'url' => url('/missioncontrol/live_broadcasts/1/edit'),
        'priority' => '117'
//        'accessLogic' => function () {
//            return Auth::user()->isSuperAdmin() || Auth::user()->isAdmin();
//        }
    ],
    (new Page(App\Models\WithdrawTransaction::class))
        ->setPriority(95),
    [
        'title' => __('admin.games.title'),
        'icon' => 'fa fa-gamepad',
        'priority' => 100,
        'pages' => [
            (new Page(\App\Models\Games\GamePUBG::class)),
            (new Page(\App\Models\Games\GameFreeFire::class)),
            (new Page(\App\Models\Games\GameCallOfDuty::class)),
        ]
    ],
    [
        'title' => __('admin.double_games.title'),
        'icon' => 'fa fa-chess',
        'priority' => 103,
        'pages' => [
            (new Page(\App\Models\DoubleGames\DoubleGamePUBG::class)),
            (new Page(\App\Models\DoubleGames\DoubleGameFreeFire::class)),
            (new Page(\App\Models\DoubleGames\DoubleGameCallOfDuty::class)),
        ]
    ],
//    (new Page(App\Models\Game::class))
//        ->setPriority(100),
//    (new Page(App\Models\DoubleGame::class))
//        ->setPriority(103),
//    (new Page(App\Models\KingGame::class))
//        ->setPriority(105),
    (new Page(App\Models\User::class))
        ->setPriority(110)
        ->addBadge(function () {
            return User::count();
        })->setAccessLogic(function () {
            return true; //Auth::user()->isSuperAdmin() || Auth::user()->isAdmin();
        }),
    (new Page(App\Models\Blogger::class))
        ->setPriority(115)
        ->addBadge(\App\Models\Blogger::new()->count())
        ->setAccessLogic(function () {
            return true;
        }),
    (new Page(App\Models\LiveBroadcast::class))
        ->setPriority(119)
        ->setAccessLogic(function () {
            return false;
        }),
    (new Page(App\Models\Mailing::class))
        ->setPriority(120)
        ->setAccessLogic(function () {
            return true; //Auth::user()->isSuperAdmin() || Auth::user()->isAdmin();
        }),
    (new Page(App\Models\Rank::class))
        ->setPriority(150)
        ->setAccessLogic(function () {
            return Auth::user()->isSuperAdmin();
        }),
    (new Page(App\Models\Content::class))
        ->setPriority(200)
        ->setAccessLogic(function () {
            return Auth::user()->isSuperAdmin() || Auth::user()->isAdmin();
        }),
    (new Page(App\Models\Admin::class))
        ->setPriority(250)
        ->setAccessLogic(function () {
            return Auth::user()->isSuperAdmin() || Auth::user()->isAdmin();
        }),

//        ->setUrl('users')
//        ->setAccessLogic(function (Page $page) {
//            return auth()->user()->isSuperAdmin();
//        }),

//    [
//        'title' => 'Information',
//        'icon'  => 'fa fa-exclamation-circle',
//        'url'   => route('admin.information'),
//    ],

    // Examples
    // [
    //    'title' => 'Content',
    //    'pages' => [
    //
    //        \App\User::class,
    //
    //        // or
    //
    //        (new Page(\App\User::class))
    //            ->setPriority(100)
    //            ->setIcon('fa fa-user')
    //            ->setUrl('users')
    //            ->setAccessLogic(function (Page $page) {
    //                return auth()->user()->isSuperAdmin();
    //            }),
    //
    //        // or
    //
    //        new Page([
    //            'title'    => 'News',
    //            'priority' => 200,
    //            'model'    => \App\News::class
    //        ]),
    //
    //        // or
    //        (new Page(/* ... */))->setPages(function (Page $page) {
    //            $page->addPage([
    //                'title'    => 'Blog',
    //                'priority' => 100,
    //                'model'    => \App\Blog::class
    //		      ));
    //
    //		      $page->addPage(\App\Blog::class);
    //	      }),
    //
    //        // or
    //
    //        [
    //            'title'       => 'News',
    //            'priority'    => 300,
    //            'accessLogic' => function ($page) {
    //                return $page->isActive();
    //		      },
    //            'pages'       => [
    //
    //                // ...
    //
    //            ]
    //        ]
    //    ]
    // ]
];
