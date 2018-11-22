<?php
return [
    'alipay' => [
        'app_id'         => '2016091900549704',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA5auFs4eS9S8nFw3IZU0nmw3K1TLw47H/z4UpunnNJxRUD94RVVEEZa8F6aOf7zMEDpqPLYa5rDQSeIufevuibhfeQJmPoJ98l6VBq5uJtaDqIDFTew9JdlPoN1vO2uZPcntn9Rrwai92Xy6QUzyg+vhnm/w4awawy4jxnikY0BWvOf9u19oAvGbhf/35R5XVJWhrMBlGD7CAIV5Xy6Ec+y+2dNtNKVs3GH0c6V5G+fk4N7QMmngDiAlXfqSMclVpZa1O1NMLusisIitz2LtMdC5jx187O5U689nn3ezhHP9Vo31rxcUQBn8Z9CM4Vv6MaozNg78BiSWyAPlTowEvKwIDAQAB',
        'private_key'    => 'MIIEogIBAAKCAQEAuhdcD+6fWA2YdOh9U6cBEvlDGAUJXaEgJruYPxxhjtsg2VmR37x/FUIq8ecLDDHGqVJKGmQqJlEqtzbZV/WrDLi/TEZYKsXMfV4fXPgVBM5pU2RRvmmx6Fz8whQbXMzhsw9CIWxbuOW276dHCBUpxdrBAy4C0aSvT8ShomkuVL/Zbq4g2CrsjjI0x3JQuRx5zeGHralJKNkMxBnVqIZBpbKSy+AEE9HJu9tT8QGV39wmAeMKgVtviPwqNjZALQRM4KG0+8c0KsGG8Qrd/8WpwmeI+EXlrHCL9KScd3TtQYKr85qpY5P2xXCdXyeIEUmJQKPDuoGNyx39DQP6O/ShlQIDAQABAoIBAGYyI+3f9wQtWzeA4irJgU6BMzaCtZUEwppi1YZ7OJxyfMTAatr7MrNjBiCY6LlGsLoWJfn+XiNy2cmKRqwGj2xKHUxvKUY8IEQNJ54v46MjFDam1jmC5CAeyPRwhGJfZeenDoM6ddjB1sggDmNC9BDjRLJRV1z4WwqsirOdncMOM5x/9n9NWC90BonMSOluC5NhyXcMs4ozSFV8kovx9rl0QiOnMyeyHQDEjEfHxh7RkTYFf4QILzQ7Q7YhoSiLnDRtYdiQfT9Ve655q3XjSDu/Bk1wFPZc6s32tUwmOcL64cjHcdMoClC9wbYi1nJiZzwX7Ext+GHtJwHa9WjbP4ECgYEA9Zf6Q5YbpNJMHXNGIFlwW8AXOg+80agIV5gIR7EZfXUIOYJGo4KAlb0y3f+BTURneRRC51CfaD+gsrSx2VLXW/LtZ0TGWkRMcZHsyEhL/Gie1OW0EYsSFdP45yeQDT9NUQPXpiKWaML0XGPpIZFoCORR8j8BlwZ0zE9rNBrLX3UCgYEAwfnxYGdTQwFebyVSPrcgKDNvqivEi4p3/fBK4lt0+En+mpWeiRG1xj5kihWw7BmTncmt1xX2tUdynNE8bKQRx14ejdEMYBmxg7T9KrXBfO7eSmoVem8DnxzpVu6iwuiIjm3fJaIgAZaukTOLpGiWU/cuKJk8p+/Rb4MFujA1FaECgYB5R/AC6GIGZwPHl2GOIBzoclgGCDeKoZxdBWsxXDfmSKEjJT6VCKFPUPuJvd+wtJMUq/jOGnZeGO3W0nzxgYNwmOq8EK6TbR8DBrkTvwAnSdMF99mQwEjSFrfYDyWIJTadR3R7SqFUYtIhvgUm2pOxsG73Xj3+wuVy79VVCdZc8QKBgHoRah66pyDxiZX0LI+e+jOSOuEh7evKVbyOLPHJkBB3sCtjoIKtjHCW7voesFnDtquDMUy7W6e0sKGS4q2TikSmLCAev+TyBk3V5cOs4XhXcbfujlmf5u2Iaib0waRaZSUMa3LpVa7qPizN/UCXOJLaaEfQNuWP+4JrN311vNJBAoGAD7PEVJWf+S4UTgMOzDhWsUxU1tp+fjvWm3sVCpi5oqANMvxZoUOHJP9aVJeZGZy+5ocLtJwzwdOOSAmPfXqdecRqyS9hk3g+D0nOWO46nHZzmQuK5oBO5nc+GwPahI0ndmJbO2bqIoFECQFiLmBYOkRrPfyJa9v2APabM8AcsSs=',
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];
