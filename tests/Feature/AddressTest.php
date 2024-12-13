<?php

use App\Models\Address;
use App\Models\Contact;
use Database\Seeders\UserSeeder;
use Database\Seeders\AddressSeeder;
use Database\Seeders\ContactSeeder;

describe("test address", function () {
    it("ceate address success", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();


        $this->post('/api/contacts/' . $contact->id . '/addresses', [
            'street' => 'test',
            'city' => 'test',
            'province' => 'test',
            'country' => 'test',
            'postal_code' => '56352'

        ], [
            "Authorization" => 'test'
        ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'street' => 'test',
                    'city' => 'test',
                    'province' => 'test',
                    'country' => 'test',
                    'postal_code' => '56352'

                ]
            ]);
    });

    it("ceate address failed", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();


        $this->post('/api/contacts/' . $contact->id . '/addresses', [
            'street' => 'test',
            'city' => 'test',
            'province' => 'test',
            'country' => '',
            'postal_code' => '56352'

        ], [
            "Authorization" => 'test'
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "country" => [
                        "The country field is required."
                    ]
                ]
            ]);
    });


    it("ceate address not found", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();


        $this->post('/api/contacts/' . $contact->id + 1 . '/addresses', [
            'street' => 'test',
            'city' => 'test',
            'province' => 'test',
            'country' => 'test',
            'postal_code' => '56352'

        ], [
            "Authorization" => 'test'
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    });


    it("get address success", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();


        $this->get('/api/contacts/' . $address->contact_id . '/addresses/' . ($address->id + 1), [
            "Authorization" => 'test'
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    });

    it("update address success", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();


        $this->put('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id, [
            "street" => "test2",
            "city" => "test2",
            "province" => "test2",
            "country" => "test2",
            "postal_code" => "563522"
        ], [
            "Authorization" => 'test'
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "street" => "test2",
                    "city" => "test2",
                    "province" => "test2",
                    "country" => "test2",
                    "postal_code" => "563522"
                ]
            ]);
    });


    it("update address failed", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();


        $this->put('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id, [
            "street" => "test2",
            "city" => "test2",
            "province" => "test2",
            "country" => "",
            "postal_code" => "563522"
        ], [
            "Authorization" => 'test'
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [

                    "country" => ["The country field is required."],

                ]
            ]);
    });


    it("update address not found", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();


        $this->put('/api/contacts/' . $address->contact_id . '/addresses/' . ($address->id + 1), [
            "street" => "test2",
            "city" => "test2",
            "province" => "test2",
            "country" => "test",
            "postal_code" => "563522"
        ], [
            "Authorization" => 'test'
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [

                    "message" => [
                        "not found"
                    ]

                ]
            ]);
    });


    it("delete address success", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();


        $this->delete('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id, [], [
            "Authorization" => 'test'
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    true
                ]
            ]);
    });
    it("delete address not found", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();


        $this->delete('/api/contacts/' . $address->contact_id . '/addresses/' . ($address->id + 1), [], [
            "Authorization" => 'test'
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    });
    it("get list address success", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $contact = Contact::query()->limit(1)->first();


        $this->get('/api/contacts/' . $contact->id . '/addresses', [
            "Authorization" => 'test'
        ])->assertStatus(200)
            ->assertJson(
                [
                    "data" => [
                        [
                            'street' => 'test',
                            'city' => 'test',
                            'province' => 'test',
                            'country' => 'test',
                            'postal_code' => '56352'
                        ]
                    ]
                ]
            );
    });
    it("get list address not found", function () {

        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $contact = Contact::query()->limit(1)->first();


        $this->get('/api/contacts/' . $contact->id + 1 . '/addresses', [
            "Authorization" => 'test'
        ])->assertStatus(404)
            ->assertJson(
                [
                    "errors" => [
                        "message" => [
                            "not found"
                        ]
                    ]
                ]
            );
    });
});
