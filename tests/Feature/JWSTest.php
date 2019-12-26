<?php

namespace Pleets\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Pleets\Jose\JWS;
use Pleets\Jose\Unicode\Helper;

class JWSTest extends TestCase
{
    private $header;
    private $payload;

    public function setUp(): void
    {
        parent::setUp();
        $this->seedData();
    }

    /** @test */
    public function it_creates_a_jws_object_with_header_and_payload()
    {
        $jws = new JWS($this->header, $this->payload);

        $this->assertSame($this->header, $jws->getHeader());
        $this->assertSame($this->payload, $jws->getPayload());
    }

    /** @test */
    public function it_encodes_the_header()
    {
        $jws = new JWS($this->header, $this->payload);

        $this->assertSame('eyJ0eXAiOiJKV1QiLA0KICJhbGciOiJIUzI1NiJ9', $jws->getEncodedHeader());
    }

    /** @test */
    public function it_encodes_the_payload()
    {
        $jws = new JWS($this->header, $this->payload);

        $this->assertSame(
            'eyJpc3MiOiJqb2UiLA0KICJleHAiOjEzMDA4MTkzODAsDQogImh0dHA6Ly9leGFtcGxlLmNvbS9pc19yb290Ijp0cnVlfQ',
            $jws->getEncodedPayload()
        );
    }

    /** @test */
    public function it_generates_the_signing_input_value()
    {
        $jws = new JWS($this->header, $this->payload);

        $this->assertSame(
            'eyJ0eXAiOiJKV1QiLA0KICJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqb2UiLA0KICJleHAiOjEzMDA4MTkzODAsDQogImh0dHA6Ly9leGFtcGxlLmNvbS9pc19yb290Ijp0cnVlfQ',
            $jws->getSigningInputValue()
        );
    }

    /** @test */
    public function it_generates_the_jws_with_compact_serialization()
    {
        $jws = new JWS($this->header, $this->payload);
        $jwk = '{"kty":"oct","k":"AyM1SysPpbyDfgZld3umj1qzKObwVMkoqQ-EstJQLr_T-1qS0gZH75aKtMN3Yj0iPS4hcgUuTwjAzZr1Z9CAow"}';

        $this->assertSame(
            'eyJ0eXAiOiJKV1QiLA0KICJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqb2UiLA0KICJleHAiOjEzMDA4MTkzODAsDQogImh0dHA6Ly9leGFtcGxlLmNvbS9pc19yb290Ijp0cnVlfQ.dBjftJeZ4CVP-mB92K27uhbUJU1p1r_wW1gFWFOEjXk',
            $jws->build($jwk)
        );
    }

    private function seedData()
    {
        /**
         * According to
         * https://tools.ietf.org/html/rfc7515#appendix-A.1
         */

        $this->header = Helper::octetArrayToString([
            123, 34, 116, 121, 112, 34, 58, 34, 74, 87, 84, 34, 44, 13, 10, 32,
            34, 97, 108, 103, 34, 58, 34, 72, 83, 50, 53, 54, 34, 125
        ]);

        $this->payload = Helper::octetArrayToString([
            123, 34, 105, 115, 115, 34, 58, 34, 106, 111, 101, 34, 44, 13, 10,
            32, 34, 101, 120, 112, 34, 58, 49, 51, 48, 48, 56, 49, 57, 51, 56,
            48, 44, 13, 10, 32, 34, 104, 116, 116, 112, 58, 47, 47, 101, 120, 97,
            109, 112, 108, 101, 46, 99, 111, 109, 47, 105, 115, 95, 114, 111,
            111, 116, 34, 58, 116, 114, 117, 101, 125
        ]);
    }
}