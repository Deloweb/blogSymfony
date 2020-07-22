<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class E2eTagTest extends PantherTestCase
{
    public function testTagExists(): void
    {
        $client = static::createPantherClient(['browser' => static::FIREFOX]);
        $client->request('GET', '/tag/');        
        
        // Use any PHPUnit assertion, including the ones provided by Symfony
        $this->assertPageTitleContains('Tag index');
        $this->assertSelectorTextContains('body', 'non dolorem mollitia');
    }    
    
    public function testCanCreateNewTag(): void
    {
        $tag = 'foo bar baz';
        $client = static::createPantherClient(['browser' => static::FIREFOX]);

        $client->request('GET', '/tag/'); 
        $client->clickLink('Create new'); 

        $client->submitForm('Save', [
            'tag[name]' => $tag,
            'tag[articles][]' => [1, 2],
        ]);        
        
        $client->request('GET', '/tag/');        
        
        // Use any PHPUnit assertion, including the ones provided by Symfony
        $this->assertPageTitleContains('Tag index');
        $this->assertSelectorTextContains('body', $tag);
    }
}