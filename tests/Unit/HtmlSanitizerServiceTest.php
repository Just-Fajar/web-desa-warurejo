<?php

namespace Tests\Unit;

use App\Services\HtmlSanitizerService;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerServiceTest extends TestCase
{
    protected $sanitizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sanitizer = new HtmlSanitizerService();
    }

    /**
     * Test removes script tags.
     */
    public function test_removes_script_tags(): void
    {
        $html = '<p>Hello</p><script>alert("xss")</script><p>World</p>';
        $result = $this->sanitizer->sanitize($html);

        $this->assertStringNotContainsString('<script>', $result);
        $this->assertStringContainsString('<p>Hello</p>', $result);
    }

    /**
     * Test removes event handlers.
     */
    public function test_removes_event_handlers(): void
    {
        $html = '<p onclick="alert(\"xss\")">Click me</p>';
        $result = $this->sanitizer->sanitize($html);

        $this->assertStringNotContainsString('onclick', $result);
        $this->assertStringContainsString('Click me', $result);
    }

    /**
     * Test removes javascript protocol.
     */
    public function test_removes_javascript_protocol(): void
    {
        $html = '<a href="javascript:alert(\'xss\')">Link</a>';
        $result = $this->sanitizer->sanitize($html);

        $this->assertStringNotContainsString('javascript:', $result);
    }

    /**
     * Test removes data protocol.
     */
    public function test_removes_data_protocol(): void
    {
        $html = '<a href="data:text/html,<script>alert(\'xss\')</script>">Link</a>';
        $result = $this->sanitizer->sanitize($html);

        $this->assertStringNotContainsString('data:', $result);
    }

    /**
     * Test removes iframe tags.
     */
    public function test_removes_iframe_tags(): void
    {
        $html = '<p>Content</p><iframe src="https://evil.com"></iframe>';
        $result = $this->sanitizer->sanitize($html);

        $this->assertStringNotContainsString('<iframe', $result);
        $this->assertStringContainsString('<p>Content</p>', $result);
    }

    /**
     * Test removes style attribute.
     */
    public function test_removes_style_attribute(): void
    {
        $html = '<p style="color: red;">Styled text</p>';
        $result = $this->sanitizer->sanitize($html);

        $this->assertStringNotContainsString('style=', $result);
        $this->assertStringContainsString('Styled text', $result);
    }

    /**
     * Test allows safe HTML tags.
     */
    public function test_allows_safe_tags(): void
    {
        $html = '<p>Paragraph</p><strong>Bold</strong><em>Italic</em><ul><li>Item</li></ul>';
        $result = $this->sanitizer->sanitize($html);

        $this->assertStringContainsString('<p>Paragraph</p>', $result);
        $this->assertStringContainsString('<strong>Bold</strong>', $result);
        $this->assertStringContainsString('<em>Italic</em>', $result);
        $this->assertStringContainsString('<ul>', $result);
        $this->assertStringContainsString('<li>Item</li>', $result);
    }

    /**
     * Test adds rel attribute to external links with target blank.
     */
    public function test_adds_rel_to_external_links_with_target_blank(): void
    {
        $html = '<a href="https://example.com" target="_blank">External Link</a>';
        $result = $this->sanitizer->sanitize($html);

        $this->assertStringContainsString('rel="noopener noreferrer"', $result);
    }

    /**
     * Test handles null input.
     */
    public function test_handles_null_input(): void
    {
        $result = $this->sanitizer->sanitize(null);
        $this->assertNull($result);
    }

    /**
     * Test handles empty string.
     */
    public function test_handles_empty_string(): void
    {
        $result = $this->sanitizer->sanitize('');
        $this->assertEmpty($result);
    }

    /**
     * Test sanitize for preview removes all tags.
     */
    public function test_sanitize_for_preview_removes_all_tags(): void
    {
        $html = '<p>This is <strong>bold</strong> text</p>';
        $result = $this->sanitizer->sanitizeForPreview($html);

        $this->assertStringNotContainsString('<p>', $result);
        $this->assertStringNotContainsString('<strong>', $result);
        $this->assertStringContainsString('This is bold text', $result);
    }

    /**
     * Test sanitize for preview limits length.
     */
    public function test_sanitize_for_preview_limits_length(): void
    {
        $html = '<p>' . str_repeat('A very long text. ', 20) . '</p>';
        $result = $this->sanitizer->sanitizeForPreview($html, 50);

        $this->assertLessThanOrEqual(53, strlen($result)); // 50 + '...'
        $this->assertStringEndsWith('...', $result);
    }

    /**
     * Test isDangerous detects script tags.
     */
    public function test_is_dangerous_detects_script_tags(): void
    {
        $html = '<p>Safe</p><script>alert("xss")</script>';
        $this->assertTrue($this->sanitizer->isDangerous($html));
    }

    /**
     * Test isDangerous detects event handlers.
     */
    public function test_is_dangerous_detects_event_handlers(): void
    {
        $html = '<div onclick="alert(\'xss\')">Click</div>';
        $this->assertTrue($this->sanitizer->isDangerous($html));
    }

    /**
     * Test isDangerous detects javascript protocol.
     */
    public function test_is_dangerous_detects_javascript_protocol(): void
    {
        $html = '<a href="javascript:void(0)">Link</a>';
        $this->assertTrue($this->sanitizer->isDangerous($html));
    }

    /**
     * Test isDangerous detects iframe.
     */
    public function test_is_dangerous_detects_iframe(): void
    {
        $html = '<iframe src="evil.com"></iframe>';
        $this->assertTrue($this->sanitizer->isDangerous($html));
    }

    /**
     * Test isDangerous returns false for safe content.
     */
    public function test_is_dangerous_returns_false_for_safe_content(): void
    {
        $html = '<p>Safe <strong>content</strong> here</p>';
        $this->assertFalse($this->sanitizer->isDangerous($html));
    }

    /**
     * Test removes form elements.
     */
    public function test_removes_form_elements(): void
    {
        $html = '<p>Before</p><form><input type="text"><button>Submit</button></form><p>After</p>';
        $result = $this->sanitizer->sanitize($html);

        $this->assertStringNotContainsString('<form>', $result);
        $this->assertStringNotContainsString('<input', $result);
        $this->assertStringNotContainsString('<button>', $result);
        $this->assertStringContainsString('<p>Before</p>', $result);
        $this->assertStringContainsString('<p>After</p>', $result);
    }
}
