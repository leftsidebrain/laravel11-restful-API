<?php

use App\Models\User;
use Database\Seeders\UserSeeder;
use GuzzleHttp\Psr7\Uri;

describe('User Registration Success', function () {
    it('can register a new user successfully', function () {
        $userData = [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'password' => '112233',
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => 'John Doe',
                    'username' => 'johndoe',
                ]
            ]);
    });
});


describe('User register fail', function () {
    it('cannot register with no data', function () {
        $userData = [
            'name' => '',
            'username' => '',
            'password' => '',
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => ['The name field is required.',],
                    'username' => ['The username field is required.'],
                    'password' => ["The password field is required."],
                ]
            ]);
    });
});


describe('User register fail', function () {
    it('cannot register with existing username', function () {
        $userData = [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'password' => '112233',
        ];

        $this->postJson('/api/users', $userData);
        $response = $this->postJson('/api/users', $userData);
        $response->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'username' => ['The username has already been taken.'],
                ]
            ]);
    });
});

describe('User Login Success', function () {
    it('can login a user successfully', function () {
        $this->seed(UserSeeder::class);

        $response = $this->postJson('/api/users/login', [
            'username' => 'user',
            'password' => '112233',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'user',
                    'username' => 'user',
                ]
            ]);

        $user = User::where('username', 'user')->first();
        self::assertNotNull($user->token);
    });
});
describe('User Login failed', function () {
    it('username not found', function () {


        $response = $this->postJson('/api/users/login', [
            'username' => 'user',
            'password' => '112233',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => ['The provided credentials are incorrect.'],
                ]
            ]);
    });
});
describe('User Login failed', function () {
    it('wrong password', function () {
        $this->seed(UserSeeder::class);

        $response = $this->postJson('/api/users/login', [
            'username' => 'user',
            'password' => '1122335',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => ['The provided credentials are incorrect.'],
                ]
            ]);
    });
});


describe('test Get user', function () {
    it('Success get current users', function () {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current', [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    "username" => "user",
                    "name" => "user"
                ]
            ]);
    });
});

describe('test Get user', function () {
    it('Unauthorized get current users', function () {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current')
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
            ]);
    });
});

describe('test Get user', function () {
    it('wrong token get current users', function () {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current', [
            'Authorization' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
            ]);
    });
});


describe('test update user', function () {
    it('update password success', function () {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('username', 'test')->first();

        $this->patch('/api/users/current', [
            'password' => 'baru'
        ], [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    "username" => "test",
                    "name" => "test"
                ]
            ]);
        $newUser = User::where('username', 'test')->first();
        self::assertNotEquals($oldUser->password, $newUser->password);
    });
});


describe('test update user', function () {
    it('update name success', function () {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('username', 'test')->first();

        $this->patch('/api/users/current', [
            'name' => 'testBaru'
        ], [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    "username" => "test",
                    "name" => "testBaru"
                ]
            ]);
        $newUser = User::where('username', 'test')->first();
        self::assertNotEquals($oldUser->name, $newUser->name);
    });
});


describe('test update user', function () {
    it('update failed', function () {
        $this->seed([UserSeeder::class]);

        $this->patch('/api/users/current',  [
            'Authorization' => 'test'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        "unauthorized"
                    ]
                ]
            ]);
    });
});



describe('test logout user', function () {
    it('logout success', function () {
        $this->seed([UserSeeder::class]);

        $this->delete(uri: '/api/users/logout', headers: [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);

        $user = User::where('username', 'test')->first();
        self::assertNull($user->token);
    });
});


describe('test logout user', function () {
    it('logout failed', function () {
        $this->seed([UserSeeder::class]);

        $this->delete(uri: '/api/users/logout', headers: [
            'Authorization' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
            ]);
    });
});
