<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPUnit\Framework\Assert;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        \Illuminate\Testing\TestResponse::macro('assertSeeTextMatches', function (string $pattern) {

        $html = $this->getContent();

        $text = strip_tags($html);

        preg_match_all('/\bvalue="([^"]*)"/u', $html, $m);
        if (!empty($m[1])) {
            $text .= "\n" . implode("\n", $m[1]);
        }

        \PHPUnit\Framework\Assert::assertMatchesRegularExpression($pattern, $text);

        return $this;
        });
    }
}
