<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

class UserApiTest extends TestCase
{
    use WithFaker;

    protected $user, $isAdmin, $data;

    public function setUp() :void {

        parent::setUp();

        $this->user = factory(User::class)->create();
        Passport::actingAs($this->user);
        $this->user->is_admin ? $this->isAdmin = true : $this->isAdmin = false;
        $this->data = [
            'first_name' => $this->faker->name, 
            'last_name' => $this->faker->name, 
            'address' => $this->faker->address, 
            'postal_code' => $this->faker->randomDigit, 
            'contact_number' => $this->faker->phoneNumber, 
            'username' => $this->faker->userName, 
            'is_admin' => $this->faker->boolean,
            'email' => $this->faker->safeEmail, 
            'password' => Hash::make($this->faker->password),
        ];
    }

    /**
     * GET "api/users"
     * Should return 200 with data array
     *
     * @return void
     */
    public function testGetAllUser()
    {
        $response = $this->json('GET', "api/users");
        if($this->isAdmin) {
            $response
                ->assertStatus(200)
                ->assertJson([
                    'data' => true
                ]);
        } else {
            $response
                ->assertStatus(200)
                ->assertJson([
                     'error-message' => 'Unauthorized.'
                ]);
        }
        
    }

    /**
     * GET "api/user/{id}"
     * Should return 200 with data array
     *
     * @return void
     */
    public function testGetUserByID()
    {
        $response = $this->json('GET', "api/user/".$this->user->id);
        if($this->isAdmin) {
            $response
                ->assertStatus(200)
                ->assertJson([
                    'data' => true
                ]);
        } else {
            $response
                ->assertStatus(200)
                ->assertJson([
                     'error-message' => 'Unauthorized.'
                ]);
        }
    }

    /**
     * DELETE "api/user/{id}"
     * Should return 200 with data array
     *
     * @return void
     */
    public function testDeleteUserByID()
    {
        $response = $this->json('DELETE', "api/user/".$this->user->id);
        if($this->isAdmin) {
            $response
                ->assertStatus(200)
                ->assertJson([
                    'data' => true
                ]);
        } else {
            $response
                ->assertStatus(200)
                ->assertJson([
                     'error-message' => 'Unauthorized.'
                ]);
        }
    }

    /**
     * DELETE "api/user/{id}"
     * Should return 200 with data array
     *
     * @return void
     */
    public function testDeleteMultiple()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $ids = [$user1->id, $user2->id];

        $response = $this->json('POST', "api/users/delete-multiple", ['ids' => $ids]);
        if($this->isAdmin) {
            $response
                ->assertStatus(200)
                ->assertJson([
                    'data' => true
                ]);
        } else {
            $response
                ->assertStatus(200)
                ->assertJson([
                     'error-message' => 'Unauthorized.'
                ]);
        }
    }

    /**
     * POST "api/user"
     *
     * @return void
     */
    public function testCreate()
    {
        $response = $this->json('POST', "api/user", $this->data);
        if($this->isAdmin) {
            $response
                ->assertStatus(200)
                ->assertJson([
                    'data' => true
                ]);
        } else {
            $response
                ->assertStatus(200)
                ->assertJson([
                     'error-message' => 'Unauthorized.'
                ]);
        }
    }

    /**
     * PUT "api/user"
     *
     * @return void
     */
    public function testUpdate()
    {
        $user = factory(User::class)->create();
        $param = $this->data;
        $param['id'] = $user->id;
        $response = $this->json('PUT', "api/user", $param);
        if($this->isAdmin) {
            $response
                ->assertStatus(200)
                ->assertJson([
                    'data' => true
                ]);
        } else {
            $response
                ->assertStatus(200)
                ->assertJson([
                     'error-message' => 'Unauthorized.'
                ]);
        }
    }
}
