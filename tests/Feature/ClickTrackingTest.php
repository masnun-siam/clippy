<?php

namespace Tests\Feature;

use App\Models\Clip;
use App\Models\ClickEvent;
use App\Models\User;
use Database\Factories\ClipFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClickTrackingTest extends TestCase
{
    use RefreshDatabase;

    private const CHROME_UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    private function actingAsUser(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    /** @test */
    public function url_redirect_records_click(): void
    {
        $clip = Clip::factory()->create(['type' => 'url', 'password' => null]);

        $this->get("/{$clip->slug}", ['User-Agent' => self::CHROME_UA])
            ->assertRedirect($clip->url);

        $clip->refresh();
        $this->assertEquals(1, $clip->clicks_count);
        $this->assertEquals(1, ClickEvent::where('clip_id', $clip->id)->count());

        $event = ClickEvent::where('clip_id', $clip->id)->first();
        $this->assertFalse($event->passed_password);
        $this->assertEquals('Chrome', $event->browser);
        $this->assertEquals('Windows', $event->os);
        $this->assertEquals('desktop', $event->device_type);
    }

    /** @test */
    public function googlebot_ua_does_not_record_click(): void
    {
        $clip = Clip::factory()->create(['type' => 'url', 'password' => null]);

        $this->get("/{$clip->slug}", ['User-Agent' => 'Googlebot/2.1 (+http://www.google.com/bot.html)'])
            ->assertRedirect($clip->url);

        $clip->refresh();
        $this->assertEquals(0, $clip->clicks_count);
        $this->assertEquals(0, ClickEvent::where('clip_id', $clip->id)->count());
    }

    /** @test */
    public function curl_ua_does_not_record_click(): void
    {
        $clip = Clip::factory()->create(['type' => 'url', 'password' => null]);

        $this->get("/{$clip->slug}", ['User-Agent' => 'curl/8.1.2'])
            ->assertRedirect($clip->url);

        $clip->refresh();
        $this->assertEquals(0, $clip->clicks_count);
        $this->assertEquals(0, ClickEvent::where('clip_id', $clip->id)->count());
    }

    /** @test */
    public function empty_ua_does_not_record_click(): void
    {
        $clip = Clip::factory()->create(['type' => 'url', 'password' => null]);

        $this->get("/{$clip->slug}", ['User-Agent' => ''])
            ->assertRedirect($clip->url);

        $clip->refresh();
        $this->assertEquals(0, $clip->clicks_count);
        $this->assertEquals(0, ClickEvent::where('clip_id', $clip->id)->count());
    }

    /** @test */
    public function html_render_records_click(): void
    {
        $clip = Clip::factory()->html()->create(['password' => null]);

        $this->get("/{$clip->slug}", ['User-Agent' => self::CHROME_UA])
            ->assertOk();

        $clip->refresh();
        $this->assertEquals(1, $clip->clicks_count);
        $this->assertEquals(1, ClickEvent::where('clip_id', $clip->id)->count());

        $event = ClickEvent::where('clip_id', $clip->id)->first();
        $this->assertFalse($event->passed_password);
        $this->assertNotNull($event->browser);
        $this->assertNotNull($event->os);
        $this->assertNotNull($event->device_type);
    }

    /** @test */
    public function correct_password_unlock_records_click_with_passed_password(): void
    {
        $clip = Clip::factory()->create([
            'type' => 'url',
            'password' => bcrypt('secret'),
        ]);

        $this->post("/clip/{$clip->id}/verify-password", [
            'password' => 'secret',
        ], ['User-Agent' => self::CHROME_UA])
            ->assertRedirect($clip->url);

        $clip->refresh();
        $this->assertEquals(1, $clip->clicks_count);
        $this->assertEquals(1, ClickEvent::where('clip_id', $clip->id)->count());

        $event = ClickEvent::where('clip_id', $clip->id)->first();
        $this->assertTrue($event->passed_password);
    }

    /** @test */
    public function protected_html_not_double_counted(): void
    {
        $clip = Clip::factory()->html()->create([
            'password' => bcrypt('secret'),
        ]);

        // Unlock the password
        $this->post("/clip/{$clip->id}/verify-password", [
            'password' => 'secret',
        ], ['User-Agent' => self::CHROME_UA])
            ->assertRedirect(route('clip', $clip->slug));

        // Follow redirect to render (session should have unlocked flag)
        $this->get("/{$clip->slug}", ['User-Agent' => self::CHROME_UA])
            ->assertOk();

        $clip->refresh();
        $this->assertEquals(1, $clip->clicks_count);
        $this->assertEquals(1, ClickEvent::where('clip_id', $clip->id)->count());

        $event = ClickEvent::where('clip_id', $clip->id)->first();
        $this->assertTrue($event->passed_password);
    }

    /** @test */
    public function password_prompt_view_does_not_record_click(): void
    {
        $clip = Clip::factory()->create([
            'type' => 'url',
            'password' => bcrypt('secret'),
        ]);

        $this->get("/{$clip->slug}", ['User-Agent' => self::CHROME_UA])
            ->assertOk(); // password prompt view

        $clip->refresh();
        $this->assertEquals(0, $clip->clicks_count);
        $this->assertEquals(0, ClickEvent::where('clip_id', $clip->id)->count());
    }

    /** @test */
    public function expired_clip_returns_404_with_no_click(): void
    {
        $clip = Clip::factory()->expired()->create();

        $this->get("/{$clip->slug}", ['User-Agent' => self::CHROME_UA])
            ->assertNotFound();

        $clip->refresh();
        $this->assertEquals(0, $clip->clicks_count);
        $this->assertEquals(0, ClickEvent::where('clip_id', $clip->id)->count());
    }

    /** @test */
    public function reserved_slug_returns_404_with_no_click(): void
    {
        $this->get('/login', ['User-Agent' => self::CHROME_UA])
            ->assertNotFound();

        $this->assertEquals(0, ClickEvent::count());
    }

    /** @test */
    public function unique_calculation_distinct_ip_ua_pairs(): void
    {
        $clip = Clip::factory()->create(['type' => 'url', 'password' => null]);

        // 2 distinct (ip, ua) + 1 dup = unique 2, total 3
        ClickEvent::create([
            'clip_id' => $clip->id,
            'ip' => '1.1.1.1',
            'user_agent' => 'Agent-A',
            'device_type' => 'desktop',
            'browser' => 'Chrome',
            'os' => 'Windows',
        ]);

        ClickEvent::create([
            'clip_id' => $clip->id,
            'ip' => '1.1.1.1',
            'user_agent' => 'Agent-A',
            'device_type' => 'desktop',
            'browser' => 'Chrome',
            'os' => 'Windows',
        ]);

        ClickEvent::create([
            'clip_id' => $clip->id,
            'ip' => '2.2.2.2',
            'user_agent' => 'Agent-B',
            'device_type' => 'mobile',
            'browser' => 'Safari',
            'os' => 'iOS',
        ]);

        $clip->update(['clicks_count' => 3]);

        $uniqueClicks = ClickEvent::where('clip_id', $clip->id)
            ->select('ip', 'user_agent')
            ->distinct()
            ->count();

        $this->assertEquals(3, $clip->fresh()->clicks_count);
        $this->assertEquals(2, $uniqueClicks);
    }

    /** @test */
    public function utm_and_referer_captured(): void
    {
        $clip = Clip::factory()->create(['type' => 'url', 'password' => null]);

        $this->get("/{$clip->slug}?utm_source=twitter&utm_medium=social&utm_campaign=test", [
            'User-Agent' => self::CHROME_UA,
            'Referer' => 'https://twitter.com/someone/status/123',
        ])->assertRedirect($clip->url);

        $event = ClickEvent::where('clip_id', $clip->id)->first();
        $this->assertEquals('twitter', $event->utm_source);
        $this->assertEquals('social', $event->utm_medium);
        $this->assertEquals('test', $event->utm_campaign);
        $this->assertEquals('https://twitter.com/someone/status/123', $event->referer);
    }

    /** @test */
    public function csv_export_returns_rows(): void
    {
        $user = $this->actingAsUser();
        $clip = Clip::factory()->create();

        ClickEvent::create([
            'clip_id' => $clip->id,
            'ip' => '1.1.1.1',
            'user_agent' => 'TestAgent',
            'device_type' => 'desktop',
            'browser' => 'Chrome',
            'os' => 'Windows',
        ]);

        $response = $this->get("/clips/{$clip->id}/analytics/export");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeaderContains('Content-Disposition', 'clicks-');

        $content = $response->getContent();
        $this->assertStringContainsString('ID', $content);
        $this->assertStringContainsString('1.1.1.1', $content);
        $this->assertStringContainsString('TestAgent', $content);
    }

    /** @test */
    public function analytics_page_redirects_when_unauthenticated(): void
    {
        $clip = Clip::factory()->create();

        $this->get("/clips/{$clip->id}/analytics")
            ->assertRedirect('/login');
    }

    /** @test */
    public function analytics_page_loads_when_authenticated(): void
    {
        $this->actingAsUser();
        $clip = Clip::factory()->create();

        $this->get("/clips/{$clip->id}/analytics")
            ->assertOk()
            ->assertSee('Analytics')
            ->assertSee('Total Clicks')
            ->assertSee('Unique Visitors');
    }
}
