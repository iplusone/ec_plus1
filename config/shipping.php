<?php
return [
  'default_method' => 'flat',
  'methods' => [
    'flat' => [
      'name' => '通常配送（全国一律）',
      'base_fee' => 550,
      'free_threshold' => 5500, // 税込合計がこの金額以上で送料無料
      'active' => true,
    ],
    // 必要に応じて追加…
  ],
];
