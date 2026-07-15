<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_a_contact_message_can_be_submitted(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Aïssatou',
            'email' => 'aissatou@example.test',
            'message' => 'Je souhaite une démonstration de FastCaisse.',
        ]);

        $response
            ->assertRedirect('/')
            ->assertSessionHas('success');
    }

    public function test_contact_submission_requires_valid_data(): void
    {
        $response = $this->from('/')
            ->post('/contact', [
                'name' => '',
                'email' => 'adresse-invalide',
                'message' => '',
            ]);

        $response
            ->assertRedirect('/')
            ->assertSessionHasErrors(['name', 'email', 'message']);
    }
}
