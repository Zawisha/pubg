<?php

return array (
  'admins' => 
  array (
    'email' => 'E-Mail',
    'password' => 'Password',
    'name' => 'Name',
    'role' => 'Role',
    'telegram_id' => 'Telegram ID',
    'title' => 'Administrators',
    'title_create' => 'Add user',
    'title_edit' => 'Edit user',
  ),
  'bloggers' => 
  array (
    'contact' => 'Contact',
    'created' => 'Recieved',
    'email' => 'E-Mail',
    'method' => 'Communication method',
    'name' => 'Name',
    'new' => 'New',
    'processed' => 'Processed',
    'status' => 'status',
    'status_checked' => 'Processed',
    'status_unchecked' => 'Not processed',
    'title' => 'Applications from bloggers',
    'title_edit' => 'Edit application',
  ),
  'contents' => 
  array (
    'content' => 'Content',
    'name' => 'Name',
    'title' => 'Content',
    'title_create' => 'Add',
    'title_edit' => 'Edit',
    'content_en' => 'Содержимое (на английском)',
  ),
  'dashboard' => 
  array (
    'balance' => 'User account balance',
    'bots' => 'Activated in Telegram bot',
    'income' => 'Received today',
    'online' => 'Tabs with the site on-line',
    'ref_payments' => 'Cashback from payments',
    'welcome' => 'Welcome to the PUBG Battles control panel',
  ),
  'games' => 
  array (
    'buttons' => 
    array (
      'cancel' => 'Close',
      'delete' => 'Cancel and delete',
      'save' => 'Save',
      'save_and_close' => 'Save and close',
    ),
    'comment' => 'Comment for admin',
    'cover' => 'Cover',
    'created_at' => 'Creation date',
    'face' => 'Face',
    'faces' => 
    array (
      'first' => 'First person',
      'third' => 'Third person',
    ),
    'finished' => 'Finished',
    'image' => 'Image',
    'image_current' => '(current)',
    'image_used' => '(used)',
    'king_maps' => 'Erangel, Miramar, Vikendi',
    'king_planned_at' => 'Scheduled at (first match and start of tournament)',
    'king_planned_at2' => 'Start of second match',
    'king_planned_at3' => 'Start of second match',
    'king_title' => 'Royal Tournaments',
    'king_title_create' => 'Add royal tournament',
    'king_title_edit' => 'Edit royal tournament',
    'login' => 'Game lobby login (ID)',
    'login1' => 'Game lobby login (ID) (match 1)',
    'login2' => 'Game lobby login (ID) (match 2)',
    'login3' => 'Game lobby login (ID) (match 3)',
    'map_name' => 'Map name',
    'max_players' => 'Number of players',
    'members' => 'Participants',
    'mode' => 'Mode',
    'name' => 'Name',
    'password' => 'Game lobby password',
    'password1' => 'Game lobby password (match 1)',
    'password2' => 'Game lobby password (match 2)',
    'password3' => 'Game lobby password (match 3)',
    'planned' => 'Planned',
    'planned_at' => 'Planned at',
    'price' => 'Cost of participation',
    'status' => 'Status',
    'statuses' => 
    array (
      'finished' => 'Finished',
      'new' => 'Planned',
      'started' => 'Started',
    ),
    'tabs' => 
    array (
      'general' => 'Basic settings',
      'members' => 'Participants',
    ),
    'title' => 'Tournaments',
    'title_create' => 'Add tournament',
    'title_edit' => 'Edit tournament',
    'top1_bonus' => 'Bonus for TOP1',
    'top2_bonus' => 'Bonus for TOP2',
    'top3_bonus' => 'Bonus for TOP3',
    'total_payed' => 'Total paid out for the tournament',
    'type' => 'Type',
    'types' => 
    array (
      'duo' => 'Duo',
      'solo' => 'Solo',
      'squad' => 'Squad',
    ),
    'use_max_kill' => 'Use the 0.75 coefficient per kill for all',
    'view' => 
    array (
      'buttons' => 
      array (
        'fill_teams' => 'Fill teams',
        'publish' => 'Publish',
        'reset' => 'Reset',
        'save' => 'Save',
      ),
      'loading' => 'Loading...',
      'msg_published' => 'The results of the tournament are published. Notifications to users will be sent out soon',
      'msg_results' => 'The publication of the tournament results is available only after its completion (the status of the tournament is completed)',
      'msg_results_published' => 'The results of the tournament have already been published and data changes are no longer available',
      'msg_saved' => 'The changes are saved',
      'rows' => 
      array (
        'frags' => 'Frags',
        'frags1' => 'Match 1',
        'frags2' => 'Match 2',
        'frags3' => 'Match 3',
        'id' => 'ID',
        'nick' => 'NICKNAME',
        'team' => 'Team',
        'total_frags' => 'Total',
        'visit' => 'Participation',
      ),
      'visited' => 'Participated',
    ),
  ),
  'live' => 
  array (
    'title' => 'Live stream',
  ),
  'mailing' => 
  array (
    'buttons' => 
    array (
      'cancel' => 'Cancel',
      'save' => 'Save',
      'save_and_send' => 'Save and send',
    ),
    'inputs' => 
    array (
      'created_from' => 'Registered after the specified date:',
      'created_to' => 'Registered before the specified date',
      'games' => 'Only participants of the specified tournaments (search by line - month - day, for example 1-28)',
      'image' => 'Image',
      'max_balance' => 'Maximum balance (less than or equal to, 0 - disabled)',
      'membership_type' => 'Tournament participation',
      'membership_types' => 
      array (
        'member' => 'Participated atleast in one',
        'no_metter' => 'Doesn\'t matter',
        'not_member' => 'Did not participate in tournaments',
      ),
      'message' => 'Text for bot and email',
      'message_hint' => 'Maximum 4000 characters',
      'min_balance' => 'Minimum balance (greater than or equal to, 0 - disabled)',
      'ranks' => 'Ranks',
      'short_message' => 'Text for user account',
      'to_bot' => 'Send to bot',
      'to_email' => 'Send to E-Mail',
      'to_lk' => 'Send to user account',
      'users' => 'Only specified users',
      'game_code' => 'Game',
    ),
    'rows' => 
    array (
      'id' => 'ID',
      'mailed_at' => 'The date of mailing',
      'name' => 'Name',
      'status' => 'Sent',
      'to_bot' => 'To BOT',
      'to_email' => 'By E-Mail',
      'to_lk' => 'To user account',
    ),
    'title' => 'Mailing list',
    'title_create' => 'Add a notification',
    'title_edit' => 'Edit a notification',
  ),
  'payments' => 
  array (
    'amount' => 'Amount',
    'comment' => 'Comment',
    'date' => 'Date',
    'status' => 'Status',
    'type' => 'Type',
    'warning' => 'Operations for the last 30 days',
  ),
  'ranks' => 
  array (
    'inputs' => 
    array (
      'cashback' => 'Cashback, %',
      'description' => 'Bonuses (text)',
      'frag_reward' => 'Frag reward, %',
      'frags' => 'Frags needed',
      'games' => 'Tournaments needed',
      'image' => 'Image',
      'name' => 'Name',
      'requirements' => 'Requirements (text)',
    ),
    'rows' => 
    array (
      'cashback' => 'Cashback %',
      'frag_reward' => 'For frag %',
      'frags' => 'Frags',
      'games' => 'Tournaments',
      'id' => 'ID',
      'name' => 'Name',
    ),
    'title' => 'User ranks',
    'title_create' => 'Add rank',
    'title_edit' => 'Edit rank',
  ),
  'stream' => 
  array (
    'save' => 'Save',
    'title' => 'Live stream',
    'title_edit' => 'Live stream',
    'url' => 'Live stream URL',
  ),
  'telegram' => 
  array (
    'blocked' => 'Blocked',
    'rows' => 
    array (
      'created_at' => 'Date',
      'id' => 'ID',
      'message' => 'Message',
      'status' => 'Status',
      'telegram_id' => 'Telegram ID',
    ),
    'send' => 'Sent',
    'title' => 'Telegram message history',
    'unknown_error' => 'Unknown error',
  ),
  'transactions' => 
  array (
    'cashback' => 'Cashback',
    'change' => 'Changed:',
    'statuses' => 
    array (
      'manual_refund' => 'Canceled by admin',
      'normal' => 'In process',
    ),
    'types' => 
    array (
      'admin' => 'Admin action',
      'game_payment' => 'Payment for participation',
      'game_return' => 'Refusal to participate',
      'kill' => 'By frags',
      'payment' => 'Deposit',
      'refund' => 'Cancellation of withdrawal (Admin)',
      'top1_payment' => 'Payment for TOP1',
      'top2_payment' => 'Payment for TOP2',
      'top3_payment' => 'Payment for TOP3',
      'withdraw' => 'Withdraw',
      'withdraw_paypal' => 'Withdraw PayPal',
    ),
  ),
  'users' => 
  array (
    'inputs' => 
    array (
      'active' => 'The user is active',
      'auth' => 'Log in for the user',
      'balance' => 'Balance',
      'bonus_games' => 'Bonus games left',
      'bonus_used' => '0 frag bonus used',
      'cbl' => 'Cashback limit % (0 - disabled)',
      'comment' => 'Comments',
      'created_at' => 'Registration date',
      'email' => 'E-mail',
      'games' => 'Tournaments',
      'kills' => 'Frags',
      'name' => 'PUBG NICKNAME',
      'no_mail' => 'Do not send notifications to E-Mail',
      'password' => 'Password',
      'password_hint' => 'If you do not want to change - leave the field empty',
      'pubg_id' => 'PUBG ID',
      'rank_id' => 'Rank',
      'referral_name' => 'Invited',
      'reflink' => 'Additional referral link',
      'telegram_ban' => 'Blocked Telegram bot',
      'telegram_id' => 'Telegram ID',
      'vk_link' => 'VK link',
    ),
    'rows' => 
    array (
      'balance' => 'Balance',
      'email' => 'E-mail',
      'games' => 'Tournaments',
      'id' => 'ID',
      'kills' => 'Frags',
      'name' => 'NICKNAME',
      'pubg_id' => 'PUBG ID',
      'rank_id' => 'Rank',
      'vk_link' => 'VK',
      'name_freefire' => 'Enter your nickname from Garena Free Fire',
    ),
    'tabs' => 
    array (
      'games' => 'Tournaments',
      'general' => 'General',
      'payments' => 'Payment transactions',
      'telegram' => 'Telegram history',
    ),
    'title' => 'Users',
    'title_create' => 'Add user',
    'title_edit' => 'Edit user',
  ),
  'double_games' => 
  array (
    'login1' => 'Логин (ID) игрового лобби (турнир 1)',
    'login2' => 'Логин (ID) игрового лобби (турнир 2)',
    'login3' => 'Логин (ID) игрового лобби (турнир 3)',
    'password1' => 'Пароль игрового лобби (турнир 1)',
    'password2' => 'Пароль игрового лобби (турнир 2)',
    'password3' => 'Пароль игрового лобби (турнир 3)',
    'title' => 'Двойные турниры',
    'title_create' => 'Добавление двойного турнира',
    'title_edit' => 'Редактирование двойного турнира',
    'mul' => 'Турниров параллельно',
  ),
);
