<?php

namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;



class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function testRegistrationPage()
    {
        $response = $this->get('/registration');
        $response->assertStatus(200);
        $data = ['email' => 'test@email.com', 'password' => 'testing'];
        $response = $this->post('/registration', $data);
        $data['password'] = User::query()->value('password');
        $this->assertDatabaseHas('users', $data);
        $response->assertRedirect('/login');

    }

    public function testLoginPage()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $data = ['email' => 'test@email.com', 'password' => 'testing'];
        $user = User::factory()->create($data);
        $data['remember'] = 'on';
        $response = $this->actingAs($user)->post('/login', $data);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user, $guard = null);

    }

    public function testLogout()
    {
        $response = $this->get('/logout');
        $this->assertGuest($guard = null);
        $response->assertRedirect('/login');
    }

    public function testShowIndex()
    {

        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/');
        $response ->assertStatus(200);
    }

    public function testEdit()
    {
        $data = ['name' => 'John'];
        $user = User::factory()->create();
        $id = User::query()->value('id');
        $this->actingAs($user);
        $response = $this->get("/edit/{$id}");
        $response ->assertStatus(200);
        $response = $this->post("/edit/{$id}", $data);
        $response ->assertRedirect('/');

    }

        public function testSecurityPage()
    {
        $data = [
            'email' => 'test@mail.com',
            'password' => 'testing',
            'password_confirmation' => 'testing'
        ];

        $user = User::factory()->create();
        $id = User::query()->value('id');
        $this->actingAs($user);
        $response = $this->get("/security/{$id}");
        $response ->assertStatus(200);
        $response = $this->post("/security/{$id}", $data);
        unset($data['password_confirmation']);
        $data['password'] = User::query()->value('password');
        $this->assertDatabaseHas('users', $data);
        $response ->assertRedirect('/');
    }

        public function testStatusPage()
    {
        $data = ['online_status' => 'Не беспокоить'];
        $user = User::factory()->create();
        $id = User::query()->value('id');
        $this->actingAs($user);
        $response = $this->get("/status/{$id}");
        $response ->assertStatus(200);
        $response = $this->post("/status/{$id}", $data);
        $data['online_status'] = 'danger';
        $this->assertDatabaseHas('users', $data);
        $response ->assertRedirect('/');

    }

        public function testAvatarPage()
    {
        $user = User::factory()->create();
        $id = User::query()->value('id');
        $this->actingAs($user);
        $response = $this->get("/avatar/{$id}");
        $response ->assertStatus(200);
        Storage::fake('avatars');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->post('/avatar', [
            'file' => $file,
        ]);

        $path = User::query()->value('avatar_path');
        Storage::disk()->assertExists($path);

    }

        public function testProfilePage()
    {
        $user = User::factory()->create();
        $id = User::query()->value('id');
        $this->actingAs($user);
        $response = $this->get("/profile/{$id}");
        $response ->assertStatus(200);
    }

        public function testCreateUserPage()
    {
        $data = ['email' => 'test@email.com', 'password' => 'testing'];
        $user = User::factory()->create($data);
        $id = User::query()->value('id');
        $this->actingAs($user);
        $response = $this->get("/create_user/{$id}");
        $response ->assertStatus(200);
        $data['password'] = User::query()->value('password');
        $this->post("/create_user/{$id}", $data);
        $this->assertDatabaseHas('users', $data);

    }


}
