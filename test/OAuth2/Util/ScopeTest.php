<?php

class OAuth2_Util_ScopeTest extends PHPUnit_Framework_TestCase
{
    public function testCheckScope()
    {
        $scopeUtil = new OAuth2_Util_Scope();

        $this->assertFalse($scopeUtil->checkScope('invalid', 'list of scopes'));
        $this->assertFalse($scopeUtil->checkScope('invalid', array('list', 'of', 'scopes')));

        $this->assertTrue($scopeUtil->checkScope('valid', 'valid and-some other-scopes'));
        $this->assertTrue($scopeUtil->checkScope('valid', array('valid', 'and-some', 'other-scopes')));

        $this->assertTrue($scopeUtil->checkScope('valid another-valid', 'valid another-valid and-some other-scopes'));
        $this->assertTrue($scopeUtil->checkScope('valid another-valid', array('valid', 'another-valid', 'and-some', 'other-scopes')));

        // all scopes must match
        $this->assertFalse($scopeUtil->checkScope('valid invalid', 'valid and-some other-scopes'));
        $this->assertFalse($scopeUtil->checkScope('valid invalid', array('valid', 'and-some', 'other-scopes')));
        $this->assertFalse($scopeUtil->checkScope('valid valid2 invalid', 'valid valid2 and-some other-scopes'));
        $this->assertFalse($scopeUtil->checkScope('valid valid2 invalid', array('valid', 'valid2', 'and-some', 'other-scopes')));
    }

    public function testScopeStorage()
    {
        $scopeUtil = new OAuth2_Util_Scope();

        $this->assertEquals($scopeUtil->getDefaultScope(), 'all');
        $this->assertEquals($scopeUtil->getSupportedScopes(), array('all'));

        $scopeUtil = new OAuth2_Util_Scope(array(
            'default_scope' => 'default',
            'supported_scopes' => array('this', 'that', 'another'),
        ));

        $this->assertEquals($scopeUtil->getDefaultScope(), 'default');
        $this->assertEquals($scopeUtil->getSupportedScopes(), array('this', 'that', 'another'));

        $memoryStorage = new OAuth2_Storage_Memory(array(
            'default_scope' => 'base',
            'supported_scopes' => 'only-this-one',
        ));
        $scopeUtil = new OAuth2_Util_Scope($memoryStorage);

        $this->assertEquals($scopeUtil->getDefaultScope(), 'base');
        $this->assertEquals($scopeUtil->getSupportedScopes(), 'only-this-one');
    }
}
