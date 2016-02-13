<?php
/**
 * This file is part of the LdapTools package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\LdapTools\Ldif\Entry;

use LdapTools\Connection\LdapControl;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LdifEntryModRdnSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('dc=foo,dc=bar');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LdapTools\Ldif\Entry\LdifEntryModRdn');
    }

    function it_should_implement_LdifEntryInterface()
    {
        $this->shouldImplement('\LdapTools\Ldif\Entry\LdifEntryInterface');
    }

    function it_should_be_able_to_be_contructed_with_dn_changes()
    {
        $newRdn = 'cn=foobar';
        $newLocation = 'ou=foo,dc=foo,dc=bar';
        $deleteOldRdn = false;
        $this->beConstructedWith('dc=foo,dc=bar', $newLocation, $newRdn, $deleteOldRdn);

        $this->getNewLocation()->shouldBeEqualTo($newLocation);
        $this->getNewRdn()->shouldBeEqualTo($newRdn);
        $this->getDeleteOldRdn()->shouldBeEqualTo($deleteOldRdn);
    }

    function it_should_set_the_dn()
    {
        $dn = 'foo';
        $this->setDn($dn);
        $this->getDn()->shouldBeEqualTo($dn);
    }

    function it_should_add_a_control()
    {
        $control = new LdapControl('foo');
        $this->addControl($control);

        $this->getControls()->shouldBeEqualTo([$control]);
    }

    function it_should_get_a_rename_operation()
    {
        $newRdn = 'cn=foobar';
        $newLocation = 'ou=foo,dc=foo,dc=bar';
        $deleteOldRdn = false;
        $this->beConstructedWith('dc=foo,dc=bar', $newLocation, $newRdn, $deleteOldRdn);

        $this->toOperation()->shouldReturnAnInstanceOf('LdapTools\Operation\RenameOperation');
        $this->toOperation()->getNewRdn()->shouldBeEqualTo($newRdn);
        $this->toOperation()->getNewLocation()->shouldBeEqualTo($newLocation);
        $this->toOperation()->getDeleteOldRdn()->shouldBeEqualTo($deleteOldRdn);
    }

    function it_should_get_the_ldif_string_representation()
    {
        $newRdn = 'cn=foobar';
        $newLocation = 'ou=foo,dc=foo,dc=bar';
        $deleteOldRdn = true;
        $this->beConstructedWith('dc=foo,dc=bar', $newLocation, $newRdn, $deleteOldRdn);

        $ldif = "dn: dc=foo,dc=bar\r\nchangetype: modrdn\r\nnewrdn: cn=foobar\r\nnewsuperior: ou=foo,dc=foo,dc=bar\r\ndeleteoldrdn: 1\r\n";
        $this->toString()->shouldBeEqualTo($ldif);
    }

    function it_should_add_a_comment()
    {
        $this->addComment('test')->shouldReturnAnInstanceOf('LdapTools\Ldif\Entry\LdifEntryModRdn');
        $this->getComments()->shouldHaveCount(1);

        $this->addComment('foo', 'bar');
        $this->getComments()->shouldHaveCount(3);

        $this->getComments()->shouldBeEqualTo(['test', 'foo', 'bar']);
    }
}
