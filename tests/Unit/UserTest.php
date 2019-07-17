<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserTest extends TestCase
{
	use WithFaker;

	protected $data;

    public function setUp() :void {

        parent::setUp();

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
     * Test create.
     *
     * @return void
     */
    public function testCreateUser()
    { 
        $userRepo = new UserRepository(new User);
        $user = $userRepo->create($this->data);

        $this->assertInstanceOf(User::class, $user);
        foreach ($this->data as $key => $value) {
        	$this->assertEquals($value, $user->$key);
        }
    }

    /**
     * Test get.
     *
     * @return void
     */
    public function testGetUser()
    {
        $user = factory(User::class)->create();
        $userRepo = new UserRepository(new User);
        $found = $userRepo->get($user->id);

        $this->assertInstanceOf(User::class, $found);
        foreach ($this->data as $key => $value) {
        	$this->assertEquals($found->$key, $user->$key);
        }
    }

    /**
     * Test update.
     *
     * @return void
     */
    public function testUpdateUser()
    {
        $user = factory(User::class)->create();
        
        $userRepo = new UserRepository(new User);
        $update = $userRepo->update($user->id, $this->data);
        $updatedUser = $userRepo->get($user->id);
        
        $this->assertTrue($update);
        foreach ($this->data as $key => $value) {
        	$this->assertEquals($value, $updatedUser->$key);
        }
    }

    /**
     * Test delete.
     *
     * @return void
     */
    public function testDeleteUser()
    {
        $user = factory(User::class)->create();
      
        $userRepo = new UserRepository($user);
        $delete = $userRepo->delete($user->id);
        
        $this->assertTrue($delete);
    }

    /**
     * Test delete multiple.
     *
     * @return void
     */
    public function testDeleteMultipleUser()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $ids = [$user1->id, $user2->id];
      
        $userRepo = new UserRepository(new User);
        $delete = $userRepo->deleteMultiple($ids);
        
        $this->assertEquals($delete, count($ids));
    }

}
