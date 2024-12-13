<?php

use App\Models\Contact;
use Database\Seeders\UserSeeder;
use Database\Seeders\SearchSeeder;
use Database\Seeders\ContactSeeder;
use Illuminate\Support\Facades\Log;

describe("test contact", function () {
    it("create contact success", function () {

        $this->seed(UserSeeder::class);
        $this->post("/api/contacts", [
            "first_name" => "test",
            "last_name" => "test",
            "email" => "test@mail.com",
            "phone" => "123456789",
        ], [
            "Authorization" => "test"
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    "first_name" => "test",
                    "last_name" => "test",
                    "email" => "test@mail.com",
                    "phone" => "123456789",
                ]
            ]);
    });
});
describe("test contact", function () {
    it("create contact failed", function () {

        $this->seed(UserSeeder::class);
        $this->post("/api/contacts", [
            "first_name" => "",
            "last_name" => "test",
            "email" => "test",
            "phone" => "123456789",
        ], [
            "Authorization" => "test"
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "first_name" => ["The first name field is required."],

                    "email" => ["The email field must be a valid email address."],

                ]
            ]);
    });
});
describe("test contact", function () {
    it("create contact unauthorized", function () {

        $this->seed(UserSeeder::class);
        $this->post("/api/contacts", [
            "first_name" => "test",
            "last_name" => "test",
            "email" => "test@mail.com",
            "phone" => "123456789",
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => ["unauthorized"]

                ]
            ]);
    });
});


describe("test contact", function () {
    it("get contact success", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::where('first_name', 'test')->first();

        $this->get('/api/contacts/' . $contact->id, [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    'first_name' => 'test',
                    'last_name' => 'test',
                    'email' => 'test@mail.com',
                    'phone' => '123456789',
                ]
            ]);
    });
});

describe("test contact", function () {
    it("get contact not found", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id + 1, [
            "Authorization" => "test"
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    'message' => [
                        "not found"
                    ]
                ]
            ]);
    });
});

describe("test contact", function () {
    it("get other user contact", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id, [
            "Authorization" => "test2"
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    'message' => [
                        "not found"
                    ]
                ]
            ]);
    });
});


describe("test update contact", function () {
    it("update contact success", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->put('/api/contacts/' . $contact->id, [
            "first_name" => "baru",
            "last_name" => "baru",
            "email" => "baru@mail.com",
            "phone" => "987654321",
        ], [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "first_name" => "baru",
                    "last_name" => "baru",
                    "email" => "baru@mail.com",
                    "phone" => "987654321",
                ]
            ]);
    });
});
describe("test update contact", function () {
    it("update contact validation errors", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->put('/api/contacts/' . $contact->id, [
            "first_name" => "",
            "last_name" => "baru",
            "email" => "baru@mail.com",
            "phone" => "987654321",
        ], [
            "Authorization" => "test"
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "first_name" => ['The first name field is required.'],
                ]
            ]);
    });
});


describe("test remove contact", function () {
    it("remove contact success", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->delete('/api/contacts/' . $contact->id, [],  [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    true
                ]
            ]);
    });
});
describe("test remove contact", function () {
    it("remove contact not found", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->delete('/api/contacts/' . $contact->id + 1, [],  [
            "Authorization" => "test"
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    });
});


describe("test search contact", function () {
    it("search contact by first name", function () {

        $this->seed([UserSeeder::class, SearchSeeder::class]);

        $response = $this->get('/api/contacts?name=first',  [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->json();

        Log::info(json_encode($response));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    });
});


describe("test search contact", function () {
    it("search contact by last name", function () {

        $this->seed([UserSeeder::class, SearchSeeder::class]);

        $response = $this->get('/api/contacts?name=last',  [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->json();

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    });
});


describe("test search contact", function () {
    it("search contact by email", function () {

        $this->seed([UserSeeder::class, SearchSeeder::class]);

        $response = $this->get('/api/contacts?email=test',  [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->json();

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    });
});

describe("test search contact", function () {
    it("search contact by phone", function () {

        $this->seed([UserSeeder::class, SearchSeeder::class]);

        $response = $this->get('/api/contacts?phone=1234',  [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->json();

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    });
});


describe("test search contact", function () {
    it("search contact not found", function () {

        $this->seed([UserSeeder::class, SearchSeeder::class]);

        $response = $this->get('/api/contacts?name=salah',  [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->json();

        self::assertEquals(0, count($response['data']));
        self::assertEquals(0, $response['meta']['total']);
    });
});


describe("test search contact", function () {
    it("search contact page", function () {

        $this->seed([UserSeeder::class, SearchSeeder::class]);

        $response = $this->get('/api/contacts?size=5&page=2',  [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->json();

        self::assertEquals(5, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
        self::assertEquals(2, $response['meta']['current_page']);
    });
});
