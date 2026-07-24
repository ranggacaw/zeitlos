<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PwaAssetsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_manifest_is_present_and_references_existing_icons(): void
    {
        $path = public_path('manifest.webmanifest');
        $this->assertFileExists($path);

        $manifest = json_decode(File::get($path), true);
        $this->assertSame('Zeitlos', $manifest['name']);
        $this->assertSame('Zeitlos', $manifest['short_name']);
        $this->assertSame('/', $manifest['start_url']);
        $this->assertSame('standalone', $manifest['display']);
        $this->assertNotEmpty($manifest['theme_color']);
        $this->assertNotEmpty($manifest['background_color']);

        $sizes = array_column($manifest['icons'], 'sizes');
        $this->assertContains('192x192', $sizes);
        $this->assertContains('512x512', $sizes);

        foreach ($manifest['icons'] as $icon) {
            $this->assertFileExists(public_path(ltrim($icon['src'], '/')));
        }
    }

    public function test_service_worker_file_is_present(): void
    {
        $contents = File::get(public_path('sw.js'));

        $this->assertStringContainsString('install', $contents);
        $this->assertStringContainsString('activate', $contents);
        $this->assertStringContainsString('fetch', $contents);
        $this->assertStringContainsString('/offline.html', $contents);
        $this->assertStringContainsString('zeitlos-v1', $contents);
    }

    public function test_offline_fallback_page_is_present(): void
    {
        $contents = File::get(public_path('offline.html'));

        $this->assertStringContainsString('offline', strtolower($contents));
        $this->assertStringContainsString('FC Zeitlos', $contents);
        $this->assertStringContainsString('/manifest.webmanifest', $contents);
    }

    public function test_pwa_icons_exist_on_disk(): void
    {
        $this->assertFileExists(public_path('icons/icon-192.png'));
        $this->assertFileExists(public_path('icons/icon-512.png'));
        $this->assertFileExists(public_path('icons/icon-maskable-512.png'));
        $this->assertFileExists(public_path('icons/apple-touch-icon.png'));

        $this->assertGreaterThan(0, filesize(public_path('icons/icon-512.png')));
    }

    public function test_app_shell_exposes_pwa_metadata(): void
    {
        $this->get(route('public.home'))
            ->assertOk()
            ->assertSee('<link rel="manifest" href="/manifest.webmanifest">', false)
            ->assertSee('<meta name="theme-color" content="#020617">', false)
            ->assertSee('<link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">', false)
            ->assertSee('<meta name="apple-mobile-web-app-capable" content="yes">', false)
            ->assertSee('viewport-fit=cover', false);
    }

    public function test_public_shell_exposes_mobile_navigation_and_install_prompt_markup(): void
    {
        $layout = File::get(resource_path('js/Layouts/PublicLayout.jsx'));

        $this->assertStringContainsString('data-public-nav', $layout);
        $this->assertStringContainsString('aria-label="Public navigation"', $layout);
        foreach (['Dashboard', 'Schedule', 'Roster', 'Leaderboard'] as $label) {
            $this->assertStringContainsString($label, $layout);
        }

        $prompt = File::get(resource_path('js/Components/InstallPrompt.jsx'));

        $this->assertStringContainsString('data-install-prompt', $prompt);
        $this->assertStringContainsString('bottom-28', $prompt);
        $this->assertStringContainsString('Install Zeitlos', $prompt);
    }
}
