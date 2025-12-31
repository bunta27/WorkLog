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
        /** @var \Illuminate\Testing\TestResponse $this */
        $html = $this->getContent();

        // 画面に見えるテキスト（タグ除去）
        $text = strip_tags($html);

        // input の value="" もテキストとして拾う（勤怠画面がまさにこれ）
        preg_match_all('/\bvalue="([^"]*)"/u', $html, $m);
        if (!empty($m[1])) {
            $text .= "\n" . implode("\n", $m[1]);
        }

        \PHPUnit\Framework\Assert::assertMatchesRegularExpression($pattern, $text);

        return $this;
        });
    }
}
