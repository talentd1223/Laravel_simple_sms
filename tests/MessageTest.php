<?php
use Mockery as m;
use SimpleSoftwareIO\SMS\Message;

class MessageTest extends \PHPUnit_Framework_TestCase {

  public function tearDown()
  {
    m::close();
  }

  public function setUp()
  {
    $this->message = new Message(m::mock('\Illuminate\View\Factory'));
  }

  public function testMSMIsSetOnAttachImage()
  {
    $this->assertFalse($this->message->isMMS());

    $this->message->attachImage('foo.jpg');

    $this->assertTrue($this->message->isMMS());
  }

  public function testAddImage()
  {
    $images = $this->message->getAttachImages();

    $this->assertTrue(empty($images));

    $singleImage = [0 => 'foo.jpg'];
    $this->message->attachImage('foo.jpg');
    $images = $this->message->getAttachImages();

    $this->assertEquals($singleImage, $images);
  }

  public function testAddImages()
  {
    $mutiImage = [0 => 'foo.jpg', 1 => 'bar.jpg'];
    $this->message->attachImage(['foo.jpg', 'bar.jpg']);
    $images = $this->message->getAttachImages();

    $this->assertEquals($mutiImage, $images);
  }

  public function testAddToWithCarrier()
  {
    $this->assertTrue(empty($this->message->getTo()));

    $to = [0 => ['number' => '+15555555555', 'carrier' => 'att']];
    $this->message->to('+15555555555', 'att');

    $this->assertEquals($to, $this->message->getTo());

    $to = [
      0 => ['number' => '+15555555555', 'carrier' => 'att'],
      1 => ['number' => '+14444444444', 'carrier' => 'verizon']
    ];
    $this->message->to('+14444444444', 'verizon');
    $this->assertEquals($to, $this->message->getTo());
  }

  public function testAddToWithoutCarrier()
  {
    $to = [0 => ['number' => '+15555555555', 'carrier' => null]];
    $this->message->to('+15555555555');

    $this->assertEquals($to, $this->message->getTo());
  }

}