<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Contact;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contact = Contact::query()->limit(1)->first();
        Address::create([
            "contact_id" => $contact->id,
            "street" => "test",
            'province' => 'test',
            'country' => 'test',
            'city' => 'test',
            'postal_code' => '56352',
        ]);
    }
}
