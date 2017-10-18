<?php

use Pug\Pug;

class PugExpressionLanguageTest extends PHPUnit_Framework_TestCase
{
    public function testJsExpression()
    {
        $pug = new Pug([
            'debug' => true,
            'expressionLanguage' => 'js',
        ]);

        $actual = trim($pug->render("- a = 2\n- b = 4\n- c = b * a\np=a * b\np=c"));
        self::assertSame('<p>8</p><p>8</p>', $actual);

        $actual = trim($pug->render("- a = 2\n- b = 4\np=a + b"));
        self::assertSame('<p>6</p>', $actual);

        $actual = trim($pug->render("- a = '2'\n- b = 4\np=a + b"));
        self::assertSame('<p>24</p>', $actual);

        $actual = trim($pug->render("mixin test\n  div&attributes(attributes)\nbody\n  +test()(class='test')"));
        self::assertSame('<body><div class="test"></div></body>', $actual);
    }

    public function testJsLanguageOptions()
    {
        $pug = new Pug([
            'debug' => true,
            'expressionLanguage' => 'js',
            'jsLanguage' => [
                'helpers' => [
                    'dot' => 'plus',
                ],
            ],
        ]);

        $actual = trim($pug->render('=a.ho', [
            'a' => 'hi '
        ]));
        self::assertSame('hi ho', $actual);
    }
}
