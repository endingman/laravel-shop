<?php
return [
    'alipay' => [
        'app_id'         => '2016091900549704',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2tyBLOUpaJKMbptBh3m6Er9bTGqzpdZiMO7dg6cJ7pCYWO/4+QAskp4QiGh94h4QgCAVi4x7mammiITVV/0j0O9QnSPAdvtdjUFRPey/9gJ876+ftI2bo3pQYAFqU9ALnImAZKTuqricb8bFjKdMNoFULf9y6vTaCou52COfJ2E9hPqX64W48wgp8llHJb/76shykLhJ+768Vxd+W6EQTqvowLlFE9zREOuomJLaHybz6AT2QuQhgk/X98CL+AAHVGWCpf4Ha34rHDSrxKEHCEIrkkryuNjgBBuj/LOOTGFs1qCTk+59XFH+yHYx0DKrSzE09h7P+HLJMsXRVJ7jqwIDAQAB',
        'private_key'    => 'MIIEowIBAAKCAQEA2tyBLOUpaJKMbptBh3m6Er9bTGqzpdZiMO7dg6cJ7pCYWO/4+QAskp4QiGh94h4QgCAVi4x7mammiITVV/0j0O9QnSPAdvtdjUFRPey/9gJ876+ftI2bo3pQYAFqU9ALnImAZKTuqricb8bFjKdMNoFULf9y6vTaCou52COfJ2E9hPqX64W48wgp8llHJb/76shykLhJ+768Vxd+W6EQTqvowLlFE9zREOuomJLaHybz6AT2QuQhgk/X98CL+AAHVGWCpf4Ha34rHDSrxKEHCEIrkkryuNjgBBuj/LOOTGFs1qCTk+59XFH+yHYx0DKrSzE09h7P+HLJMsXRVJ7jqwIDAQABAoIBABmaoFYTPSPpuRobebASso0M6+5lsTyedwBIgYusWAIb0mbdNCBVP+GrMj3zE38Hi2Ch4ENiHPeeHEhuWdMgXzdLOAAaNaL1xz0GPi3ja5WpNtqHjkR+KI4YehyxLbI+bY1TnLypeu5oCI0fEO9ihwz9Vk7HSrdoC7YeIJDDohPmGeb1r06IicfFNHkHNUhN9d64hvKzoITEggqxNcP/Y6F3FsOulsrFK4cR1vlxz8JVxK1lt7nE17u4EhBbEYliwD+kUFCts+yXZq/oOcvl6H6Vx+Uex47o8tJvNHntLrgu2TyCX90+DrE1E+RT1+aHiSdDQYA8+T1LVc7Pu3yQitECgYEA+lYEsdHJZhEfuAkNywtywmkMPi8E1RFhl+tlFxVZjMkzO1JKGq4MVZFNWeyQBDrkew+kDhY0vKjrBbmVEI/EnheXbW3aqf4uTZ8kXjYxcZGI+FgYHtmUaQK5UTjJD+z6+rvtEAHogaI8Pj9R6LJsEGQojD+hmp8PKsw1OwvmdfkCgYEA39AuN/cVB7Yq8w4ziKxw4RFU6E/aA759GkwAYwbvAnl13XQh0aKvcy0qpLVEaAi8tj5fEP587NPKd5vKY1FiQXUDxnPwUzu3mSET2igsMKSxK6axa1bAt6s0NwORhYGBcbERmBWmFv9gaJLEUMiVbrN3WrKZm3JHXhY+gT0Q/8MCgYAY0iKRBv3zEsuUUcF+Wlo8kHHYUI0oGxsUFxDk4wcIAIMF5LjYQF2Utl13Bw+Ye8ZftUNM6kK8WftDjA0NoVObYTdwcC34IO2yYI8YkEMCwa1VAlcR5/zhFJYPSZQV8idIaQ2uQqItCEr02Q5kBhzU2zlx/nUlgZPdaHX2rs1g8QKBgEJeT3ZVFK1mvbG5olqDGC4Fn7IMC9BUanCj3PGAmtuW/Page27Y2UJEQIL7Jb/b52n7cOFvNchcvYtebVOqTIKwml/8/WURLk6AWoy0oPYDwG37Tl1QKnHMmP0/V6XJU4NNSLRapxfmvsYDz6dM89MR8PC6SYapCrTBz+MvIp1VAoGBALlG1uS1z5SCm5uIf/5SUpaWmgC1jLfM3jFdllGkJdmgrTSRpf+OpzrdTaLgFZ1Td0tyQm5+WL1NrWRoTdMFqg1uMD/GgfGGtaDbHbDpQYyIOqTw4jvq3QxI6Rl3GZ/NQuns97y8gAgjRImTKGwTJ1jkeb7UAuMkHQUgD1RGNfLA',
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
