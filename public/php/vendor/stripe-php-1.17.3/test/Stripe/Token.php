<?php

class card_tokenTest extends UnitTestCase
{
  public function testUrls()
  {
    $this->assertEqual(card_token::classUrl('card_token'), '/v1/tokens');
    $token = new card_token('abcd/efgh');
    $this->assertEqual($token->instanceUrl(), '/v1/tokens/abcd%2Fefgh');
  }
}