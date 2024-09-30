<?php
namespace App\Http\vendor\africastalking\africastalking\src\Tests;

use App\Http\vendor\africastalking\africastalking\src\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

class AfricasTalkingTest extends \PHPUnit\Framework\TestCase
{
	public function setup()
	{
		$this->username = Fixtures::$username;
		$this->apiKey 	= Fixtures::$apiKey;

		$this->client 	= new AfricasTalking($this->username, $this->apiKey);
	}

	public function testSMSClass()
	{
		$this->assertInstanceOf(\App\Http\vendor\africastalking\africastalking\src\SMS::class, $this->client->sms());
	}

	public function testContentClass()
	{
		$this->assertInstanceOf(\App\Http\vendor\africastalking\africastalking\src\Content::class, $this->client->content());
	}

	public function testAirtimeClass()
	{
		$this->assertInstanceOf(\App\Http\vendor\africastalking\africastalking\src\Airtime::class, $this->client->airtime());
	}

	public function testVoiceClass()
	{
		$this->assertInstanceOf(\App\Http\vendor\africastalking\africastalking\src\Voice::class, $this->client->voice());
	}

	public function testApplicationClass()
	{
		$this->assertInstanceOf(\App\Http\vendor\africastalking\africastalking\src\Application::class, $this->client->application());
	}

	public function testPaymentsClass()
	{
		$this->assertInstanceOf(\App\Http\vendor\africastalking\africastalking\src\Payments::class, $this->client->payments());
	}
}
