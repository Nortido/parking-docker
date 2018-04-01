<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
namespace App\Tests;


use App\DomainCounter;
use App\Email;
use App\Exceptions\DieException;
use App\FileReader;
use PHPUnit\Framework\TestCase;
use TypeError;

class Test extends TestCase
{
    /**
     * Test read exist file
     *
     * @throws DieException
     */
    function test_file_exist()
    {
        $result = FileReader::read_file("emails.txt" );

        $this->assertTrue( is_array( $result ) );
    }

    /**
     * Test read not exist file
     *
     * @throws DieException
     */
    function test_file_not_exist()
    {
        $this->expectException( DieException::class );
        $this->expectExceptionMessageRegExp( "/^Can't read file/" );

        $result = FileReader::read_file("emails2.txt" );
    }

    /**
     * Test read empty filename
     *
     * @throws DieException
     */
    function test_empty_filename()
    {
        $this->expectException( DieException::class );
        $this->expectExceptionMessage( "Enter file name" );

        $result = FileReader::read_file("");
    }

    /**
     * Test read empty file
     *
     * @throws DieException
     */
    function test_empty_file()
    {
        $this->expectException( DieException::class );
        $this->expectExceptionMessage( "File is empty" );

        $result = FileReader::read_file("empty.txt" );
    }

    /**
     * Test email validation
     */
    function test_email_valid()
    {
        $result = Email::email_validate("email@email.com" );

        $this->assertTrue( $result );
    }

    /**
     * Test short name email validation
     */
    function test_email_name_is_short()
    {
        $result = Email::email_validate("l@email.com" );

        $this->assertNotTrue( $result );
    }

    /**
     * Test special chars in name email validation
     */
    function test_email_name_not_valid()
    {
        $result = Email::email_validate("!#*(^$#(^!@email.com" );

        $this->assertNotTrue( $result );
    }

    /**
     * Test email without name validation
     */
    function test_email_name_is_empty()
    {
        $result = Email::email_validate("@email.com" );

        $this->assertNotTrue( $result );
    }

    /**
     * Test email without zone validation
     */
    function test_email_domain_without_zone()
    {
        $result = Email::email_validate("email@email." );

        $this->assertNotTrue( $result );

        $result = Email::email_validate("email@email" );

        $this->assertNotTrue( $result );
    }

    /**
     * Test email without @ char validation
     */
    function test_email_domain_without_special_character()
    {
        $result = Email::email_validate("emailemail.com" );

        $this->assertNotTrue( $result );

        $result = Email::email_validate("emailemailcom" );

        $this->assertNotTrue( $result );
    }

    /**
     * Test empty email validation
     */
    function test_email_empty()
    {
        $result = Email::email_validate("" );

        $this->assertNotTrue( $result );
    }

    /**
     * Test domain get
     */
    function test_domain()
    {
        $result = Email::get_domain("email@email.com" );

        $this->assertEquals("email.com", $result);
    }

    /**
     * Test short domain zone
     */
    function test_domain_zone_too_short()
    {
        $result = Email::get_domain("email@email.c" );

        $this->assertEquals("INVALID", $result);
    }

    /**
     * Test counting email domains array
     *
     * @throws DieException
     */
    function test_processing()
    {
        $result = ( new DomainCounter() )->process_all_emails( [
            'email@email.com',
            'email2@mail.ru',
        ] );

        $this->assertArrayHasKey("email.com", $result);
        $this->assertArrayHasKey("mail.ru", $result);
        $this->assertEquals( $result[ "mail.ru" ], 1);
        $this->assertEquals( $result[ "email.com" ], 1);
    }

    /**
     * Test counting email domains for string
     *
     * @throws DieException
     */
    function test_processing_not_array()
    {
        $this->expectException( TypeError::class );

        $result = ( new DomainCounter() )->process_all_emails( 'email@email.com' );
    }

    /**
     * Test counting email domains of empty array
     *
     * @throws DieException
     */
    function test_processing_empty_array()
    {
        $this->expectException( DieException::class );
        $this->expectExceptionMessage( "No emails to process" );

        $result = ( new DomainCounter() )->process_all_emails( [] );
    }

    /**
     * Test sorting email domains array
     *
     * @throws DieException
     */
    function test_sorting()
    {
        $result = ( new DomainCounter() )->process_all_emails( [
            'email@email.com',
            'email@vk.com',
            'email2@vk.com',
        ] );

        reset( $result );

        $this->assertTrue( key( $result )=== 'vk.com' );
    }

    /**
     * Test show email domains count message
     *
     * @throws DieException
     */
    function test_showing_emails_info_not_array()
    {
        $this->expectException( TypeError::class );

        $result = ( new DomainCounter() )->show_emails_info( 'vk.com' );
    }

    /**
     * Test show email domains count message for empty array
     *
     * @throws DieException
     */
    function test_showing_empty_array()
    {
        $this->expectException( DieException::class );
        $this->expectExceptionMessage( "No emails to process" );

        $result = ( new DomainCounter() )->show_emails_info( [] );
    }
}
