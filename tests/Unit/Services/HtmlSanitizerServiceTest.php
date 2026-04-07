<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\HtmlSanitizerService;

class HtmlSanitizerServiceTest extends TestCase
{
    protected HtmlSanitizerService $sanitizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sanitizer = app(HtmlSanitizerService::class);
    }

    /**
     * Test sanitizer removes script tags
     */
    public function test_sanitizer_removes_script_tags(): void
    {
        $html = '<p>Safe content</p><script>alert("xss")</script>';

        $sanitized = $this->sanitizer->sanitize($html);

        $this->assertStringNotContainsString('<script>', $sanitized);
        $this->assertStringNotContainsString('alert', $sanitized);
        $this->assertStringContainsString('<p>Safe content</p>', $sanitized);
    }

    /**
     * Test sanitizer removes iframe tags
     */
    public function test_sanitizer_removes_iframe_tags(): void
    {
        $html = '<p>Content</p><iframe src="malicious.com"></iframe>';

        $sanitized = $this->sanitizer->sanitize($html);

        $this->assertStringNotContainsString('<iframe>', $sanitized);
        $this->assertStringNotContainsString('malicious.com', $sanitized);
    }

    /**
     * Test sanitizer removes event handlers
     */
    public function test_sanitizer_removes_event_handlers(): void
    {
        $html = '<p onclick="alert(\"xss\")">Click me</p>';

        $sanitized = $this->sanitizer->sanitize($html);

        $this->assertStringNotContainsString('onclick', $sanitized);
        $this->assertStringContainsString('Click me', $sanitized);
    }

    /**
     * Test sanitizer removes javascript protocol
     */
    public function test_sanitizer_removes_javascript_protocol(): void
    {
        $html = '<a href="javascript:alert(\"xss\")">Link</a>';

        $sanitized = $this->sanitizer->sanitize($html);

        $this->assertStringNotContainsString('javascript:', $sanitized);
        $this->assertStringNotContainsString('alert', $sanitized);
        // Text masih ada setelah href dihapus
        $this->assertStringContainsString('Link', $sanitized);
    }

    /**
     * Test sanitizer preserves safe HTML
     */
    public function test_sanitizer_preserves_safe_html(): void
    {
        $html = '<p>Paragraph</p><strong>Bold</strong><em>Italic</em><ul><li>Item</li></ul>';

        $sanitized = $this->sanitizer->sanitize($html);

        $this->assertStringContainsString('<p>Paragraph</p>', $sanitized);
        $this->assertStringContainsString('<strong>Bold</strong>', $sanitized);
        $this->assertStringContainsString('<em>Italic</em>', $sanitized);
        $this->assertStringContainsString('<ul>', $sanitized);
        $this->assertStringContainsString('<li>Item</li>', $sanitized);
    }

    /**
     * Test sanitizer handles null input
     */
    public function test_sanitizer_handles_null_input(): void
    {
        $sanitized = $this->sanitizer->sanitize(null);

        $this->assertNull($sanitized);
    }

    /**
     * Test sanitizer handles empty string
     */
    public function test_sanitizer_handles_empty_string(): void
    {
        $sanitized = $this->sanitizer->sanitize('');

        $this->assertEquals('', $sanitized);
    }

    /**
     * Test sanitizer removes inline styles
     */
    public function test_sanitizer_removes_inline_styles(): void
    {
        $html = '<p style="color: red;">Styled text</p>';

        $sanitized = $this->sanitizer->sanitize($html);

        $this->assertStringNotContainsString('style=', $sanitized);
        $this->assertStringContainsString('Styled text', $sanitized);
    }

    /**
     * Test sanitizer preserves links with safe protocols
     */
    public function test_sanitizer_preserves_safe_links(): void
    {
        $html = '<a href="https://example.com">Safe Link</a>';

        $sanitized = $this->sanitizer->sanitize($html);

        $this->assertStringContainsString('href="https://example.com"', $sanitized);
        $this->assertStringContainsString('Safe Link', $sanitized);
    }

    /**
     * Test sanitizer adds rel attributes to external links with target blank
     */
    public function test_sanitizer_adds_rel_to_external_links(): void
    {
        $html = '<a href="https://external.com" target="_blank">External</a>';

        $sanitized = $this->sanitizer->sanitize($html);

        $this->assertStringContainsString('rel="noopener noreferrer"', $sanitized);
        $this->assertStringContainsString('target="_blank"', $sanitized);
    }
}
